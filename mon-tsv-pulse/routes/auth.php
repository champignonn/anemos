<?php

use Illuminate\Support\Facades\Route;

Route::post('/logout', function () {
    auth()->logout();

    return redirect('/');

})->name('logout');
