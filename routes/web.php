<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DogsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return view('test');
});



Route::get('/dogs', [DogsController::class, 'index']);
Route::get('/dogs/create', [DogsController::class, 'create']);
Route::post('/dogs', [DogsController::class, 'store']);
Route::get('/dogs/{id}', [DogsController::class, 'show']);
Route::get('/dogs/{id}/edit', [DogsController::class, 'edit']);
Route::put('/dogs/{id}', [DogsController::class, 'update']);
Route::get('/dogs/{id}/walks', [DogsController::class, 'walks']);