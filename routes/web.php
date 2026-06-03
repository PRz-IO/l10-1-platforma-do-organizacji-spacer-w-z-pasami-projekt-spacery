<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\SignupController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VolunteerManagementController;

use App\Http\Controllers\DogsController;
use App\Http\Controllers\FavDogsController;

Route::get('/', function () {
    return redirect("/test");
    //return view('welcome');
});


Route::controller(LoginController::class)->group(function () {
    Route::get("/login", 'index')->name('login.index');
    Route::post("/login", 'Login')->name('login.login');
    Route::get("/logout", 'Logout')->name('login.logout');
});

Route::controller(SignupController::class)->group(function () {
    Route::get("/signup", 'index')->name('signup.index');
    Route::post("/signup", 'SignUp')->name('signup.signup');
});


Route::get('/test', function () {
    return view('test');
})->name('test');


Route::get('/dogs', [DogsController::class, 'index'])->name('dogs.index');
Route::get('/dogs/create', [DogsController::class, 'create']);
Route::post('/dogs', [DogsController::class, 'store']);
Route::get('/dogs/{id}', [DogsController::class, 'show']);
Route::get('/dogs/{id}/edit', [DogsController::class, 'edit']);
Route::put('/dogs/{id}', [DogsController::class, 'update']);
Route::get('/dogs/{id}/walks', [DogsController::class, 'walks']);

Route::post('/dogs/{id}/favorite', [DogsController::class, 'toggleFavorite']);


Route::prefix('worker')->name('worker.')->group(function () {
    Route::resource('volunteers', VolunteerManagementController::class);
    Route::patch('volunteers/{volunteer}/approve', [VolunteerManagementController::class, 'approve'])->name('volunteers.approve');
    Route::patch('volunteers/{volunteer}/block', [VolunteerManagementController::class, 'block'])->name('volunteers.block');
    Route::patch('volunteers/{id}/reset-password', [VolunteerManagementController::class, 'resetPassword'])->name('volunteers.reset-password');
    Route::post('volunteers/{id}/rate', [VolunteerManagementController::class, 'storeRating'])->name('volunteers.rate');
    Route::patch('schedules/{id}/rate', [VolunteerManagementController::class, 'rateSchedule'])->name('schedules.rate');
});
