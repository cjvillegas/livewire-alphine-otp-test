<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (\Auth::check()) {
        return redirect()->route('home');
    }

    return view('main');
})->middleware(['throttle:10,1']);

Route::get('/login', function () {
    return view('main');
})
    ->middleware(['throttle:10,1'])
    ->name('login');

Route::get('/home', function () {
    return view('app');
})
    ->middleware(['auth', 'throttle:20,1'])
    ->name('home');
