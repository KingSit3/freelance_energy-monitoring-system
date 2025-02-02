<?php

use App\Http\Controllers\ActivePowerController;
use App\Http\Controllers\CurrentLoadController;
use App\Http\Controllers\MaxPowerController;
use App\Http\Controllers\OtherPowerController;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/max-powers', [MaxPowerController::class, 'getMaxPower'])->name("max_power");
Route::get('/max-powers/export', [MaxPowerController::class, 'export'])->name("max_power.export");
Route::get('/max-powers/{id}', [MaxPowerController::class, 'getOneMaxPower'])->name("one_max_power");
Route::get('/datatable/max-powers', [MaxPowerController::class, 'getTableDataOfMaxPower'])->name("datatable.max_power");
Route::get('/datatable/max-powers/{id}', [MaxPowerController::class, 'getTableDataOfOneMaxPower'])->name("datatable.one_max_power");

Route::get('/latest-active-powers', [ActivePowerController::class, 'getActivePower'])->name("latest_active_power");
Route::get('/active-powers/export', [ActivePowerController::class, 'export'])->name("active_power.export");
Route::get('/datatable/active-powers', [ActivePowerController::class, 'getTableDataOfActivePower'])->name("datatable.active_power");

Route::get('/latest-current-load', [CurrentLoadController::class, 'getLatestCurrentLoad'])->name("latest_current_load");
Route::get('/datatable/current-load', [CurrentLoadController::class, 'getDatatableCurrentLoad'])->name("datatable.one_current_load");
Route::get('/current-load/export', [CurrentLoadController::class, 'export'])->name("current_load.export");

Route::get('/latest-other-power', [OtherPowerController::class, 'getLatestOtherPower'])->name("latest_other_power");
Route::get('/datatable/other-power', [OtherPowerController::class, 'getDatatableData'])->name("datatable.other_power");
Route::get('/other-power/export', [OtherPowerController::class, 'export'])->name("other_power.export");
