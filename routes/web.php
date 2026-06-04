<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkersController;

Route::view('/layout-test', 'components.layout');


Route::resource('workers', WorkersController::class);
Route::patch(
    '/workers/{worker}/block',
    [WorkersController::class, 'block']
)->name('workers.block');

Route::patch(
    '/workers/{worker}/unblock',
    [WorkersController::class, 'unblock']
)->name('workers.unblock');
Route::post(
    '/workers/{worker}/reset-password',
    [WorkersController::class, 'resetPassword']
)->name('workers.reset-password');



use App\Http\Controllers\VolunteerManagementController;

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

Route::prefix('worker')->name('worker.')->group(function () {
    Route::resource('volunteers', VolunteerManagementController::class);
    Route::patch('volunteers/{volunteer}/approve', [VolunteerManagementController::class, 'approve'])->name('volunteers.approve');
    Route::patch('volunteers/{volunteer}/block', [VolunteerManagementController::class, 'block'])->name('volunteers.block');
});