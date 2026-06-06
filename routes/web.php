<?php

use App\Http\Controllers\Auth\ParentAuthController;
use App\Http\Controllers\Auth\StaffAuthController;
use App\Http\Controllers\Auth\UnifiedLoginController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\ChangePasswordController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\AboutController;
use App\Http\Controllers\Public\ContactController;
use App\Http\Controllers\Public\GalleryController;
use App\Http\Controllers\Public\LocationController;
use App\Http\Controllers\Public\ProgramsController;
use App\Http\Controllers\Public\StaffController;
use App\Http\Controllers\Public\TestimonialsController;
use App\Http\Controllers\Public\TestimonialSubmitController;
use Illuminate\Support\Facades\Route;

// ── Public routes ─────────────────────────────────────────────────────────────
Route::get('/',                [HomeController::class, 'index'])->name('home');
Route::get('/about',           [AboutController::class, 'index'])->name('about');
Route::get('/location',        [LocationController::class, 'index'])->name('location');
Route::get('/programs',        [ProgramsController::class, 'index'])->name('programs');
Route::get('/staff',           [StaffController::class, 'index'])->name('staff');
Route::get('/gallery',         [GalleryController::class, 'index'])->name('gallery');
Route::get('/gallery/{id}',    [GalleryController::class, 'serve'])->name('gallery.image');
Route::get('/testimonials',    [TestimonialsController::class, 'index'])->name('testimonials');
Route::get('/contact',         [ContactController::class, 'index'])->name('contact');
Route::post('/contact',        [ContactController::class, 'store'])->name('contact.store');
Route::get('/testimonial/{token}',  [TestimonialSubmitController::class, 'show'])->name('testimonial.show');
Route::post('/testimonial/{token}', [TestimonialSubmitController::class, 'store'])->name('testimonial.store');

// ── Storage file passthrough (GoDaddy can't symlink; serve via Laravel) ───────
Route::get('/storage/{path}', function (string $path) {
    foreach ([storage_path('app/public'), '/home/eyuabkafn4mp/public_html/storage/app/public'] as $base) {
        $resolved = realpath($base);
        if (!$resolved) continue;
        $file = realpath($resolved . '/' . $path);
        if ($file && str_starts_with($file, $resolved) && is_file($file)) {
            return response()->file($file);
        }
    }
    abort(404);
})->where('path', '.+');

// ── Unified login ─────────────────────────────────────────────────────────────
Route::post('/login',  [UnifiedLoginController::class, 'login'])->name('unified.login');
Route::post('/logout', [StaffAuthController::class, 'logout'])->name('staff.logout');

// ── Login redirect — sends unauthenticated users to home page modal ───────────
Route::get('/login', fn() => redirect()->route('home'))->name('login');

// ── Staff portal ──────────────────────────────────────────────────────────────
Route::middleware(['auth', 'password.change'])->prefix('portal')->name('portal.')->group(function () {
    Route::get('/',                [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/change-password', [ChangePasswordController::class, 'show'])->name('change-password');
    Route::post('/change-password',[ChangePasswordController::class, 'update'])->name('change-password.update');

    require __DIR__.'/portal/inquiries.php';
    require __DIR__.'/portal/children.php';
    require __DIR__.'/portal/contacts.php';
    require __DIR__.'/portal/staff.php';
    require __DIR__.'/portal/gallery.php';
    require __DIR__.'/portal/messages.php';
    require __DIR__.'/portal/testimonials.php';
    require __DIR__.'/portal/parent-portals.php';
    require __DIR__.'/portal/settings.php';
    require __DIR__.'/portal/littlelog.php';
});

// ── Parent auth ───────────────────────────────────────────────────────────────
Route::get('/parent/login',  [ParentAuthController::class, 'showLogin'])->name('parent.login');
Route::post('/parent/login', [ParentAuthController::class, 'login']);
Route::post('/parent/logout',[ParentAuthController::class, 'logout'])->name('parent.logout');

// ── Parent portal ─────────────────────────────────────────────────────────────
Route::middleware(['auth:parent', 'password.change:parent'])->prefix('parent')->name('parent.')->group(function () {
    require __DIR__.'/portal/parent.php';
});
