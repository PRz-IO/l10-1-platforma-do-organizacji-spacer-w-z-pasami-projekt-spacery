<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VolunteerController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/volunteers', [VolunteerController::class, 'index'])->name('volunteers.index');
Route::post('/volunteers', [VolunteerController::class, 'store'])->name('volunteers.store');