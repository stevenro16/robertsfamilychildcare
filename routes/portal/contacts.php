<?php

use App\Http\Controllers\Portal\ContactController;
use Illuminate\Support\Facades\Route;

Route::get('/contacts',                          [ContactController::class, 'index'])->name('contacts.index');
Route::post('/contacts',                         [ContactController::class, 'store'])->name('contacts.store');
Route::get('/contacts/{id}',                     [ContactController::class, 'show'])->name('contacts.show');
Route::patch('/contacts/{id}',                   [ContactController::class, 'update'])->name('contacts.update');
Route::get('/contacts/{id}/notes',               [ContactController::class, 'notes'])->name('contacts.notes');
Route::post('/contacts/{id}/notes',              [ContactController::class, 'addNote'])->name('contacts.notes.add');
Route::patch('/contacts/{id}/notes/{noteId}',    [ContactController::class, 'updateNote'])->name('contacts.notes.update');
Route::post('/contacts/{id}/parent-user',        [ContactController::class, 'createParentUser'])->name('contacts.parent-user.create');
