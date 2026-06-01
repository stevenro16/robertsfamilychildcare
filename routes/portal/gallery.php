<?php

use App\Http\Controllers\Portal\GalleryController;
use Illuminate\Support\Facades\Route;

Route::get('/gallery',                    [GalleryController::class, 'index'])->name('gallery.index');
Route::post('/gallery',                   [GalleryController::class, 'store'])->name('gallery.store');
Route::patch('/gallery/{id}',             [GalleryController::class, 'update'])->name('gallery.update');
Route::delete('/gallery/{id}',            [GalleryController::class, 'destroy'])->name('gallery.destroy');
Route::post('/gallery/instagram-import',  [GalleryController::class, 'instagramImport'])->name('gallery.instagram-import');
