<?php

use App\Http\Controllers\Portal\TestimonialController;
use Illuminate\Support\Facades\Route;

Route::get('/testimonials',               [TestimonialController::class, 'index'])->name('testimonials.index');
Route::patch('/testimonials/{id}',        [TestimonialController::class, 'update'])->name('testimonials.update');
Route::get('/testimonials/links',         [TestimonialController::class, 'links'])->name('testimonials.links');
Route::post('/testimonials/links',        [TestimonialController::class, 'createLink'])->name('testimonials.links.create');
