<?php

use App\Http\Controllers\ActivePowerController;
use App\Http\Controllers\CurrentLoadController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MaxPowerController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name("dashboard");
Route::get('/max-power/{id}', [MaxPowerController::class, 'show'])->name("show.max_power");
Route::get('/active-power', [ActivePowerController::class, 'index'])->name("active_power");
Route::get('/current-load', [CurrentLoadController::class, 'index'])->name("current_load");
