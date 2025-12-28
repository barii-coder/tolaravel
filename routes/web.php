<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\Admin\Home\Index::class)->name('home');

Route::get('/submit', \App\Livewire\Admin\Support\Index::class);
