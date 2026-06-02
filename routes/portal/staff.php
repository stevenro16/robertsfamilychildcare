<?php

use App\Http\Controllers\Portal\StaffController;
use Illuminate\Support\Facades\Route;

Route::get('/staff',                           [StaffController::class, 'index'])->name('staff.index');
Route::post('/staff/reorder',                  [StaffController::class, 'reorder'])->name('staff.reorder');
Route::post('/staff',                          [StaffController::class, 'store'])->name('staff.store');
Route::get('/staff/{id}',                      [StaffController::class, 'show'])->name('staff.show');
Route::patch('/staff/{id}',                    [StaffController::class, 'update'])->name('staff.update');
Route::post('/staff/{id}/photo',               [StaffController::class, 'uploadPhoto'])->name('staff.photo');
Route::delete('/staff/{id}/photo',             [StaffController::class, 'clearPhoto'])->name('staff.photo.clear');
Route::get('/staff/{id}/notes',                [StaffController::class, 'notes'])->name('staff.notes');
Route::post('/staff/{id}/notes',               [StaffController::class, 'addNote'])->name('staff.notes.add');
Route::patch('/staff/{id}/notes/{noteId}',     [StaffController::class, 'updateNote'])->name('staff.notes.update');
