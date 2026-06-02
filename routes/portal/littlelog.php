<?php

use App\Http\Controllers\Portal\LittleLogController;
use Illuminate\Support\Facades\Route;

Route::get('/littlelog',              [LittleLogController::class, 'index'])->name('littlelog');
Route::post('/littlelog/checkin/{id}',  [LittleLogController::class, 'checkin'])->name('littlelog.checkin');
Route::post('/littlelog/checkout/{id}', [LittleLogController::class, 'checkout'])->name('littlelog.checkout');
