<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (\Auth::check()) {
        return redirect()->route('home');
    }

    return view('main');
});

Route::get('/login', function () {
    return view('main');
})->name('login');

Route::get('/home', function () {
    return view('app');
})->middleware(['auth'])->name('home');
