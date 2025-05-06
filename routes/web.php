<?php

use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/upload/', [UploadController::class, 'index'])->name('uploadPage'); // Generate participants
