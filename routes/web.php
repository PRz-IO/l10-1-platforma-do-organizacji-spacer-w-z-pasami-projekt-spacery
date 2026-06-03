<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VolunteerManagementController;
use App\Http\Controllers\WalksController;

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
    Route::patch('volunteers/{id}/reset-password', [VolunteerManagementController::class, 'resetPassword'])->name('volunteers.reset-password');
    Route::post('volunteers/{id}/rate', [VolunteerManagementController::class, 'storeRating'])->name('volunteers.rate');
    Route::patch('schedules/{id}/rate', [VolunteerManagementController::class, 'rateSchedule'])->name('schedules.rate');
});

Route::get('/walks-panel', function () {
    return view('walks');
});

Route::get('/psy/{id}', [WalksController::class, 'show'])->name('dogs.show');

Route::post('/psy/{id}/rezerwuj', [WalksController::class, 'reserve'])->name('walks.reserve');

Route::get('/psy/{id}/zajete-godziny', [WalksController::class, 'getBookedTimes']);

Route::post('/psy/{id}/ulubione', [WalksController::class, 'toggleFavorite'])->name('walks.favorite');

Route::get('/spacery', [WalksController::class, 'index'])->name('walks.index');

Route::post('/spacery/{schedule_id}/notatka', [WalksController::class, 'addNote'])->name('walks.addNote');

Route::post('/spacery/{schedule_id}/ocena', [WalksController::class, 'addGrade'])->name('walks.addGrade');

Route::delete('/spacery/{schedule_id}/anuluj', [WalksController::class, 'cancelWalk'])->name('walks.cancel');