<?php

use App\Http\Controllers\ActivePowerController;
use App\Http\Controllers\CurrentLoadController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name("dashboard");
Route::get('/active-power/{id}', [ActivePowerController::class, 'show'])->name("show.active.power");
Route::get('/current-load', [CurrentLoadController::class, 'index'])->name("current-load");
