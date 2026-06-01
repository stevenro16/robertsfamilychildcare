<?php

use App\Http\Controllers\Portal\InquiryController;
use Illuminate\Support\Facades\Route;

Route::get('/inquiries',               [InquiryController::class, 'index'])->name('inquiries.index');
Route::get('/inquiries/snoozed',       [InquiryController::class, 'snoozed'])->name('inquiries.snoozed');
Route::get('/inquiries/room',          [InquiryController::class, 'room'])->name('inquiries.room');
Route::get('/inquiries/completed',     [InquiryController::class, 'completed'])->name('inquiries.completed');
Route::get('/inquiries/{id}',          [InquiryController::class, 'show'])->name('inquiries.show');
Route::patch('/inquiries/{id}',        [InquiryController::class, 'update'])->name('inquiries.update');
Route::post('/inquiries/{id}/snooze',  [InquiryController::class, 'snooze'])->name('inquiries.snooze');
Route::get('/inquiries/{id}/notes',    [InquiryController::class, 'notes'])->name('inquiries.notes');
Route::post('/inquiries/{id}/notes',   [InquiryController::class, 'addNote'])->name('inquiries.notes.add');
