<?php

use App\Http\Controllers\Portal\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/settings',          [SettingsController::class, 'index'])->name('settings.index');
Route::get('/settings/backup',   [SettingsController::class, 'backup'])->name('settings.backup')->middleware('admin');
Route::post('/settings/sql',     [SettingsController::class, 'sql'])->name('settings.sql')->middleware('admin');
Route::get('/accounts',          [SettingsController::class, 'accounts'])->name('accounts.index')->middleware('admin');
Route::post('/accounts',         [SettingsController::class, 'createAccount'])->name('accounts.store')->middleware('admin');
Route::patch('/accounts/{id}',   [SettingsController::class, 'updateAccount'])->name('accounts.update')->middleware('admin');
