<?php

use App\Http\Controllers\Portal\ChildController;
use Illuminate\Support\Facades\Route;

Route::get('/children',                       [ChildController::class, 'index'])->name('children.index');
Route::get('/children/new',                   [ChildController::class, 'create'])->name('children.create');
Route::post('/children',                      [ChildController::class, 'store'])->name('children.store');
Route::get('/children/{id}',                  [ChildController::class, 'show'])->name('children.show');
Route::patch('/children/{id}',                [ChildController::class, 'update'])->name('children.update');
Route::get('/children/{id}/notes',            [ChildController::class, 'notes'])->name('children.notes');
Route::post('/children/{id}/notes',           [ChildController::class, 'addNote'])->name('children.notes.add');
Route::patch('/children/{id}/notes/{noteId}', [ChildController::class, 'updateNote'])->name('children.notes.update');
Route::get('/children/{id}/documents',        [ChildController::class, 'documents'])->name('children.documents');
Route::post('/children/{id}/documents',       [ChildController::class, 'uploadDocument'])->name('children.documents.upload');
Route::delete('/children/{id}/documents/{docId}', [ChildController::class, 'deleteDocument'])->name('children.documents.delete');
Route::get('/children/{id}/contacts',         [ChildController::class, 'contacts'])->name('children.contacts');
Route::post('/children/{id}/contacts',        [ChildController::class, 'addContact'])->name('children.contacts.add');
Route::patch('/children/{id}/contacts/{ccId}',[ChildController::class, 'updateContact'])->name('children.contacts.update');
Route::post('/children/{id}/photo',           [ChildController::class, 'uploadPhoto'])->name('children.photo');
Route::delete('/children/{id}/photo',        [ChildController::class, 'clearPhoto'])->name('children.photo.clear');
