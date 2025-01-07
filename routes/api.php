<?php

use App\Http\Controllers\ActivePowerController;
use App\Http\Controllers\CurrentLoadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/active-powers', [ActivePowerController::class, 'getActivePower'])->name("active_power");
Route::get('/active-powers/export', [ActivePowerController::class, 'export'])->name("active_power.export");
Route::get('/active-powers/{id}', [ActivePowerController::class, 'getOneActivePower'])->name("one_active_power");


Route::get('/datatable/active-powers', [ActivePowerController::class, 'getTableDataOfActivePower'])->name("datatable.active_power");
Route::get('/datatable/active-powers/{id}', [ActivePowerController::class, 'getTableDataOfOneActivePower'])->name("datatable.one_active_power");

Route::get('/latest-current-load', [CurrentLoadController::class, 'getLatestCurrentLoad'])->name("latest_current_load");
Route::get('/current-load/export', [CurrentLoadController::class, 'export'])->name("current_load.export");
