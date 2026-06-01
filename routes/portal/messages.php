<?php

use App\Http\Controllers\Portal\MessageController;
use Illuminate\Support\Facades\Route;

Route::get('/messages',              [MessageController::class, 'index'])->name('messages.index');
Route::get('/messages/unread',       [MessageController::class, 'unreadCount'])->name('messages.unread');
Route::get('/messages/{parentId}',   [MessageController::class, 'conversation'])->name('messages.conversation');
Route::post('/messages/{parentId}',  [MessageController::class, 'send'])->name('messages.send');
