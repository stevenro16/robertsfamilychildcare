<?php

use App\Http\Controllers\Portal\ParentPortalController;
use Illuminate\Support\Facades\Route;

Route::get('/parent-portals',         [ParentPortalController::class, 'index'])->name('parent-portals.index');
Route::post('/parent-portals',        [ParentPortalController::class, 'store'])->name('parent-portals.store');
Route::patch('/parent-portals/{id}',  [ParentPortalController::class, 'update'])->name('parent-portals.update');
