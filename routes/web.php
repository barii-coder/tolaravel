<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use \App\Livewire\Admin\Home\Index;

Route::get('/',[Index::class, 'render']);

// Login
Route::get('/login', fn() => view('login'))->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);

// Register
Route::get('/register', fn() => view('register'))->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Dashboard
Route::get('/dashboard', fn() => 'Welcome!')->middleware('auth');

