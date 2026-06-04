<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\SignupController;
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
use App\Http\Controllers\WalksController;

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
