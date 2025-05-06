<?php

use App\Http\Controllers\UploadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');
Route::get('/horizon', function () {
    return redirect('/horizon/dashboard');
});

Route::get('/upload/', [UploadController::class, 'index'])->name('uploadPage');
Route::get('/upload/status', [UploadController::class, 'status'])->name('uploadStatus');
Route::post('/upload/', [UploadController::class, 'upload'])->name('uploadStore');
