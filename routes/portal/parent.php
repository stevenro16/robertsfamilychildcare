<?php

use App\Http\Controllers\ParentPortal\ParentDashboardController;
use App\Http\Controllers\ParentPortal\ParentChangePasswordController;
use App\Http\Controllers\ParentPortal\ParentChildController;
use App\Http\Controllers\ParentPortal\ParentMessageController;
use Illuminate\Support\Facades\Route;

Route::get('/',                          [ParentDashboardController::class, 'index'])->name('dashboard');
Route::get('/change-password',           [ParentChangePasswordController::class, 'show'])->name('change-password');
Route::post('/change-password',          [ParentChangePasswordController::class, 'update'])->name('change-password.update');
Route::get('/children/{id}',             [ParentChildController::class, 'show'])->name('children.show');
Route::get('/children/{id}/contacts',    [ParentChildController::class, 'contacts'])->name('children.contacts');
Route::post('/children/{id}/contacts',   [ParentChildController::class, 'addContact'])->name('children.contacts.add');
Route::get('/children/{id}/documents',   [ParentChildController::class, 'documents'])->name('children.documents');
Route::post('/children/{id}/documents',  [ParentChildController::class, 'uploadDocument'])->name('children.documents.upload');
Route::get('/messages',                  [ParentMessageController::class, 'index'])->name('messages.index');
Route::post('/messages',                 [ParentMessageController::class, 'send'])->name('messages.send');
