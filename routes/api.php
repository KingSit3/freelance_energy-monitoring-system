<?php

use App\Http\Controllers\ActivePowerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/active-powers', [ActivePowerController::class, 'getActivePower'])->name("active_power");
