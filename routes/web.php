<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VolunteerManagementController;

use App\Http\Controllers\DogsController;

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

/*

Route::get('/dogs', [DogsController::class, 'index']);
Route::get('/dogs/create', [DogsController::class, 'create']);
Route::post('/dogs', [DogsController::class, 'store']);
Route::get('/dogs/{id}', [DogsController::class, 'show']);
Route::get('/dogs/{id}/edit', [DogsController::class, 'edit']);
Route::put('/dogs/{id}', [DogsController::class, 'update']);
Route::get('/dogs/{id}/walks', [DogsController::class, 'walks']);


*/
Route::prefix('worker')->name('worker.')->group(function () {
    Route::resource('volunteers', VolunteerManagementController::class);
    Route::patch('volunteers/{volunteer}/approve', [VolunteerManagementController::class, 'approve'])->name('volunteers.approve');
    Route::patch('volunteers/{volunteer}/block', [VolunteerManagementController::class, 'block'])->name('volunteers.block');
    Route::patch('volunteers/{id}/reset-password', [VolunteerManagementController::class, 'resetPassword'])->name('volunteers.reset-password');
    Route::post('volunteers/{id}/rate', [VolunteerManagementController::class, 'storeRating'])->name('volunteers.rate');
    Route::patch('schedules/{id}/rate', [VolunteerManagementController::class, 'rateSchedule'])->name('schedules.rate');
});
