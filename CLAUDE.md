# CLAUDE.md

This file provides guidance to Claude Code when working with the Roberts Family ChildCare Laravel application.

## Commands

```bash
php artisan serve --port=6767   # local dev server (http://127.0.0.1:6767)
npm run dev                      # Vite asset watcher (run alongside artisan serve)
php artisan view:clear           # clear compiled Blade views
php artisan view:cache           # pre-compile all Blade views (catches template errors)
php artisan route:list           # list all routes
php artisan tinker               # interactive REPL (avoid on Windows — BOM issue; use --execute instead)
```

> **Windows note:** `php artisan tinker` adds a BOM to PHP files when writing output. Use `php artisan tinker --execute="..."` for one-off DB queries instead.

There are no automated tests.

## Deployment (GoDaddy cPanel PHP Hosting)

See **[DEPLOY.md](DEPLOY.md)** for the full deployment guide, including the non-standard server architecture, asset upload workflow, and troubleshooting reference.

**Key facts:**
- Document root is locked to `public_html/` — cannot be changed
- Laravel app lives at `~/rfc/`, bootstrapped via a custom `public_html/index.php`
- Vite build assets must be uploaded to **two** locations (see DEPLOY.md)
- Symlinks do not work on this host — do not use `ln -s` for assets
- `TRUSTED_PROXIES` is set in `bootstrap/app.php`, not `.env`

## Architecture

### Route Groups

| Prefix | Middleware | Description |
|---|---|---|
| `/` | none | 9 public-facing pages |
| `/portal/*` | `auth`, `password.change` | Staff portal (Employee guard) |
| `/parent/*` | `auth:parent`, `password.change:parent` | Parent portal (ParentUser guard) |

### Auth

**Two guards** configured in `config/auth.php`:
- `web` — `Employee` model, logs in with `email` field
- `parent` — `ParentUser` model, logs in with `username` field

**Unified login**: single Alpine.js modal on all public pages → `POST /login` → `UnifiedLoginController`. Tries staff guard first, then parent guard.

**`RequirePasswordChange` middleware** (`app/Http/Middleware/`): checks `mustChangePassword` on the authenticated user and redirects to the change-password page on first login.

### Database

Same MySQL database as the original Next.js app. No schema changes during migration.

**All 18 models** in `app/Models/`:

| Model | Table | Notes |
|---|---|---|
| `Employee` | `employee` | Staff logins, `STAFF`/`ADMIN` role, bcrypt password |
| `ParentUser` | `ParentUser` | Parent logins, `mustChangePassword` flag |
| `Contact` | `Contact` | Single `name` column (not firstName/lastName) |
| `ContactNote` | `ContactNote` | Notes on contacts |
| `Child` | `Child` | `firstName`/`lastName` columns, `schedule` is JSON stored as TEXT |
| `ChildNote` | `ChildNote` | Notes on children |
| `ChildDocument` | `ChildDocument` | Uploaded files, `fileUrl` stores `/storage/uploads/...` path |
| `ChildContact` | `ChildContact` | Junction: child ↔ contact with `relationship`, `isPrimary`, `addedByParent` |
| `Inquiry` | `Inquiry` | Contact form submissions, `status` + `isSnoozed`/`snoozeUntil` |
| `InquiryNote` | `InquiryNote` | Staff notes on inquiries |
| `InquiryStatusHistory` | `InquiryStatusHistory` | Audit trail of status changes |
| `Message` | `Message` | Staff↔parent messages, `senderRole` (`STAFF`/`PARENT`), `readAt` |
| `StaffMember` | `StaffMember` | Public staff profiles (bio, photo, sort order) |
| `StaffNote` | `StaffNote` | Internal notes on staff members |
| `GalleryImage` | `GalleryImage` | `filename` stores `/storage/uploads/gallery/{id}.ext`; no `updatedAt` |
| `TestimonialLink` | `TestimonialLink` | One-time tokens for testimonial submissions |
| `Testimonial` | `Testimonial` | Parent reviews, `status` `PENDING`/`APPROVED`/`REJECTED` |
| `SiteContent` | `SiteContent` | Key-value CMS pairs for editable site text |

**Key conventions:**
- All PKs are 32-char hex UUIDs — `HasUuidKey` trait in `app/Traits/` auto-generates on create
- All models use camelCase column names for timestamps: `createdAt`, `updatedAt`
- `const CREATED_AT = 'createdAt'`, `const UPDATED_AT = 'updatedAt'` (or `null` if no updatedAt)
- Tables with `updatedAt` require it to be set manually in update calls

### File Storage

Laravel Storage disk `public` → `storage/app/public/`. Symlinked to `public/storage` via `storage:link`.

| Asset type | Storage path | DB column value |
|---|---|---|
| Child photos | `storage/app/public/uploads/children/{id}.jpg` | `/storage/uploads/children/{id}.jpg` |
| Child documents | `storage/app/public/uploads/children/docs/{childId}/{filename}` | `/storage/uploads/children/docs/...` |
| Staff photos | `storage/app/public/uploads/staff/{filename}` | `/storage/uploads/staff/{filename}` |
| Contact photos | `storage/app/public/uploads/contacts/{filename}` | `/storage/uploads/contacts/{filename}` |
| Gallery images | `storage/app/public/uploads/gallery/{id}.{ext}` | `/storage/uploads/gallery/{id}.{ext}` |

### Views

```
resources/views/
  layouts/
    public.blade.php    # public site layout — navbar, login modal, footer
    portal.blade.php    # staff portal — sidebar + Alpine portalShell()
    parent.blade.php    # parent portal — simple top nav
  public/               # home, about, gallery, staff, testimonials, location, programs, contact
  portal/               # inquiries, children, contacts, staff, gallery, messages, testimonials,
                        #   parent-portals, settings, dashboard
  parent/               # dashboard, change-password, children/{contacts,documents}, messages
  auth/                 # login (legacy direct-access page)
  components/
    icon.blade.php      # Heroicons wrapper — <x-icon name="..." class="..." />
```

**Tailwind component classes** (defined in `resources/css/app.css`):

| Class | Usage |
|---|---|
| `.btn-primary` | Sky-blue primary action button |
| `.btn-accent` | Amber/orange accent button |
| `.btn-ghost` | Ghost/outline button |
| `.card` | White rounded panel with shadow |
| `.input` | Standard text input |
| `.label` | Form field label |
| `.wide` | Max-width layout container |

### Alpine.js Patterns

- **Login modal**: `x-data` wrapper is the outermost `<div>` in `public.blade.php`. Modal is a sibling of `<header>` (not a child) to avoid `backdrop-filter` CSS containing-block trapping `position: fixed`.
- **`$persist`**: used in login form to remember username across page loads (`rfcc_remembered_username` localStorage key)
- **Portal shell**: `portalShell()` Alpine component in `resources/js/app.js` — handles sidebar toggle and message badge polling (every 30s via `setInterval`)

### Controllers

**Public** (`app/Http/Controllers/Public/`):
`HomeController`, `AboutController`, `LocationController`, `ProgramsController`, `StaffController`, `GalleryController`, `TestimonialsController`, `ContactController`, `TestimonialSubmitController`

**Auth** (`app/Http/Controllers/Auth/`):
`UnifiedLoginController`, `StaffAuthController`, `ParentAuthController`

**Portal** (`app/Http/Controllers/Portal/`):
`DashboardController`, `InquiryController`, `ChildController`, `ContactController`, `StaffController`, `GalleryController`, `MessageController`, `TestimonialController`, `ParentPortalController`, `SettingsController`, `ChangePasswordController`

**Parent** (`app/Http/Controllers/Parent/`):
`DashboardController`, `ChangePasswordController`, `ChildrenController`, `MessageController`

### Email

`NewInquiryMail` mailable fires on contact form submission. Configured via `.env`:
```
MAIL_MAILER=smtp
MAIL_HOST=...
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS=...
NOTIFICATION_EMAIL=...   # recipient for new inquiry alerts
```

### Key Files

- `routes/web.php` — all top-level routes; portal/parent sub-routes in `routes/portal/`
- `config/auth.php` — two-guard setup (`web`/`parent`)
- `app/Traits/HasUuidKey.php` — auto UUID generation for all models
- `resources/js/app.js` — Alpine.js registration + `portalShell()` component
- `resources/css/app.css` — Tailwind + custom component classes
- `app/Http/Middleware/RequirePasswordChange.php` — force password change middleware
