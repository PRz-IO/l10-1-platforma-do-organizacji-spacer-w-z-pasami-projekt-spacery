<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect("/login");
    //return view('welcome');
});


Route::controller(LoginController::class)->group(function () {
    Route::get("/login", 'index')->name('login.index');
    Route::post("/login", 'Login')->name('login.login');
    Route::get("/logout", 'Logout')->name('login.logout');
});


Route::get('/test', function () {
    return view('test');
});