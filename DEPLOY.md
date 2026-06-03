# Deployment Guide — Roberts Family ChildCare

## Server Architecture (GoDaddy cPanel Shared Hosting)

This setup is non-standard because GoDaddy locks the primary domain document root to `public_html/` and will not allow it to be changed.

```
/home/eyuabkafn4mp/
├── public_html/              ← web root (document root, cannot change)
│   ├── index.php             ← custom Laravel bootstrap (NOT Laravel's default)
│   └── build/
│       └── assets/           ← Vite CSS/JS must be uploaded here manually
└── rfc/                      ← Laravel app lives here
    ├── app/
    ├── bootstrap/
    ├── public/
    │   └── build/
    │       └── manifest.json ← Laravel reads manifest from here (filesystem)
    └── storage/
```

The `public_html/index.php` bootstraps Laravel from `../rfc/`:
```php
require __DIR__.'/../rfc/vendor/autoload.php';
$app = require_once __DIR__.'/../rfc/bootstrap/app.php';
$app->handleRequest(Request::capture());
```

**Symlinks do not work** — GoDaddy disables `FollowSymLinks` on the primary domain at the server level; `.htaccess` cannot override this.

---

## PHP Version

GoDaddy hosting runs PHP 8.3. Set via cPanel → MultiPHP Manager → select PHP 8.3 for the domain. Confirm with:
```bash
php -v
```

---

## First-Time Setup

### 1. Upload the app via FTP

Upload the entire `rfc/` folder to `/home/eyuabkafn4mp/rfc/`. Exclude:
- `node_modules/`
- `.env`
- `storage/logs/`
- `public/build/` (handled separately — see Assets below)

### 2. Upload the custom `public_html/index.php`

This file is NOT part of the repo. It lives only on the server at `/home/eyuabkafn4mp/public_html/index.php`. Do not overwrite it during deploys. Its contents:

```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../rfc/vendor/autoload.php';

/** @var Application $app */
$app = require_once __DIR__.'/../rfc/bootstrap/app.php';

$app->handleRequest(Request::capture());
```

### 3. Install Composer dependencies (SSH/cPanel Terminal)

```bash
cd ~/rfc
composer install --no-dev --optimize-autoloader
```

### 4. Create `.env`

Copy `.env.example` to `~/rfc/.env` and fill in all values. Required production settings:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://robertsfamilychildcare.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=robertsfamilychildcare
DB_USERNAME=robertsfamilychildcare_db
DB_PASSWORD=<password>

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
SESSION_DOMAIN=robertsfamilychildcare.com

CACHE_STORE=database
QUEUE_CONNECTION=database
```

> `TRUSTED_PROXIES` does **not** go in `.env` — it is configured in `bootstrap/app.php` via `$middleware->trustProxies(at: '*')`, which is already committed to the repo.

### 5. Generate app key

```bash
php ~/rfc/artisan key:generate
```

### 6. Set directory permissions

```bash
chmod -R 755 ~/rfc/storage/
chmod -R 755 ~/rfc/bootstrap/cache/
```

### 7. Run artisan setup commands

```bash
php ~/rfc/artisan storage:link
php ~/rfc/artisan config:cache
php ~/rfc/artisan route:cache
php ~/rfc/artisan view:cache
```

> `storage:link` creates `rfc/public/storage → rfc/storage/app/public`. Note: uploaded files (gallery, staff photos, etc.) are served via this path, which works because requests route through Laravel — not as direct static files.

---

## Deploying Updates

### PHP / Blade / Config changes only

1. Upload changed files via FTP to `~/rfc/` (same exclusions as above)
2. Via SSH:
```bash
php ~/rfc/artisan config:cache
php ~/rfc/artisan route:cache
php ~/rfc/artisan view:clear
```

### CSS / JS changes (after running `npm run build` locally)

The Vite build output must go to **two locations** on the server because the document root is `public_html/` but Laravel reads the manifest from `rfc/public/`:

| What | From (local) | To (server) |
|---|---|---|
| Manifest + assets (Laravel reads) | `public/build/` | `~/rfc/public/build/` |
| Assets only (browser downloads) | `public/build/assets/` | `~/public_html/build/assets/` |

**Step by step:**
1. Run locally: `npm run build`
2. FTP upload `public/build/` → `~/rfc/public/build/` (replace existing)
3. FTP upload `public/build/assets/` → `~/public_html/build/assets/` (replace existing)
4. Clear view cache: `php ~/rfc/artisan view:clear`

> The asset filenames are content-hashed (e.g., `app-DRPs0cBi.css`). Old files in `public_html/build/assets/` from previous builds can be deleted safely.

---

## Cache Clear Commands (Quick Reference)

```bash
php ~/rfc/artisan config:clear
php ~/rfc/artisan route:clear
php ~/rfc/artisan view:clear
php ~/rfc/artisan cache:clear
```

To re-cache everything after a deploy:
```bash
php ~/rfc/artisan config:cache && php ~/rfc/artisan route:cache && php ~/rfc/artisan view:cache
```

---

## Local Development

Local uses SQLite (no MySQL required) and file-based sessions. The local `.env` should have:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:6767

DB_CONNECTION=sqlite

SESSION_DRIVER=file
SESSION_SECURE_COOKIE=false
SESSION_DOMAIN=

CACHE_STORE=file
```

Start local server:
```bash
php artisan serve --port=6767   # in one terminal
npm run dev                      # in another terminal
```

> There are no Laravel migrations — the database schema was created by the original Next.js app. Local SQLite will be empty; public pages work but login/portal requires production data.

---

## Troubleshooting

| Symptom | Cause | Fix |
|---|---|---|
| `ViteManifestNotFoundException` | `public/build/manifest.json` missing on server | Upload `public/build/` to `~/rfc/public/build/` |
| CSS/JS 404 | Assets not in `public_html/build/assets/` | Upload `public/build/assets/` to `~/public_html/build/assets/` |
| `419 Page Expired` | Proxy not trusted or session config wrong | Ensure `trustProxies(at: '*')` is in `bootstrap/app.php`; check `SESSION_DOMAIN` has no duplicate `null` entry in `.env` |
| `Route [x] not defined` | Stale view cache | Run `php ~/rfc/artisan view:clear` |
| `500` on all pages | Config/route cache out of sync | Run `php ~/rfc/artisan config:clear && php ~/rfc/artisan route:clear` |
| Storage uploads 404 | `storage:link` not run | Run `php ~/rfc/artisan storage:link` |
