<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoomController;

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

Route::get('/', [RoomController::class, 'index']);
Route::post('/rooms/{id}/book', [RoomController::class, 'bookRoom']);
Route::get('/rooms/{id}', [RoomController::class, 'getRoomById']);
Route::post('/rooms', [RoomController::class, 'store']);
Route::get('/rooms', [RoomController::class, 'type']);
Route::get('rooms/{id}/availability', [RoomController::class, 'availability']);





