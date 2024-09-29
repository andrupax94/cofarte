<?php

use \App\Http\Controllers\eventosController;
use \App\Http\Controllers\UserController;
use \Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
 */

Route::post('/getToken', [UserController::class, 'getToken'])->middleware(['token']);
Route::post('/getUser', [UserController::class, 'getUser'])->middleware(['token']);
Route::post('/procesingDelete', [UserController::class, 'procesingDelete']);
Route::post('/logIn', [UserController::class, 'logIn']);
Route::post('/logOut', [UserController::class, 'logOut']);

Route::get('/', function () {
    return 'hola';
});

$mEventos = ['procesing', 'token'];

Route::post('/getEventos', [eventosController::class, 'getEventos'])->middleware($mEventos);
