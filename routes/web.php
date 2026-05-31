<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VolunteerManagementController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('test');
});

Route::prefix('worker')->name('worker.')->group(function () {
    Route::resource('volunteers', VolunteerManagementController::class);
    Route::patch('volunteers/{id}/approve', [VolunteerManagementController::class, 'approve'])->name('volunteers.approve');
    Route::patch('volunteers/{id}/block', [VolunteerManagementController::class, 'block'])->name('volunteers.block');
});