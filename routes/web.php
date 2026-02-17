<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\UI\Http\Controllers\MessageController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [MessageController::class, 'index'])->name('dashboard');
Route::get('/messages/create', [MessageController::class, 'create'])->name('messages.create');
Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
