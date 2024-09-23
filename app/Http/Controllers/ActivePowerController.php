<?php

namespace App\Http\Controllers;

use App\Exports\ActivePowerExport;
use App\Models\ActivePower;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;

class ActivePowerController extends Controller
{
    public function show($id)
    {
        // Chart Data
        $getLimitedActivePower = ActivePower::select(["id", "terminal_time", "created_at", "updated_at", "active_power_$id as active_power"])->latest()->limit(24)->get();
        $chartLabels = collect($getLimitedActivePower)->pluck("created_at")->map(fn($item) => Carbon::parse($item)->format('H:i:s'));
        $chartData = collect($getLimitedActivePower)->pluck("active_power");
        // End Chart Data

        $data = [
            "limited_active_power" => $getLimitedActivePower,
            "title" => "sensor $id",
            "chart_labels" => $chartLabels,
            "chart_data" => $chartData,
            "sensor_id" => $id,
        ];

        return view("active-power", $data);
    }

    public function getActivePower()
    {
        $getActivePower = ActivePower::latest()->first();
        $resultActivePowers =  [
            "id" => $getActivePower["id"],
            "data" => [
                $getActivePower["active_power_1"],
                $getActivePower["active_power_2"],
                $getActivePower["active_power_3"],
                $getActivePower["active_power_4"],
                $getActivePower["active_power_5"],
                $getActivePower["active_power_6"],
                $getActivePower["active_power_7"],
                $getActivePower["active_power_8"],
                $getActivePower["active_power_9"],
                $getActivePower["active_power_10"],
                $getActivePower["active_power_11"],
                $getActivePower["active_power_12"],
                $getActivePower["active_power_13"],
            ],
            "total_active_power" => array_sum([
                $getActivePower["active_power_1"],
                $getActivePower["active_power_2"],
                $getActivePower["active_power_3"],
                $getActivePower["active_power_4"],
                $getActivePower["active_power_5"],
                $getActivePower["active_power_6"],
                $getActivePower["active_power_7"],
                $getActivePower["active_power_8"],
                $getActivePower["active_power_9"],
                $getActivePower["active_power_10"],
                $getActivePower["active_power_11"],
                $getActivePower["active_power_12"],
                $getActivePower["active_power_13"],
            ]),
            "terminal_time" => $getActivePower["terminal_time"],
            "created_at" => Carbon::parse($getActivePower["created_at"]),
            "updated_at" => Carbon::parse($getActivePower["updated_at"]),
        ];

        return response([
            "active_power" => $resultActivePowers,
            "chart_labels" => Carbon::parse($getActivePower["created_at"])->format('H:i:s')
        ]);
    }

    public function getTableDataOfActivePower()
    {
        return DataTables::eloquent(ActivePower::query())->toJson();
    }

    public function getOneActivePower($id)
    {
        $getActivePower = ActivePower::select(["id", "terminal_time", "created_at", "updated_at", "active_power_$id as active_power"])->latest()->first();
        $resultActivePowers = [
            "id" => $getActivePower["id"],
            "active_power" => $getActivePower["active_power"],
            "terminal_time" => $getActivePower["terminal_time"],
            "created_at" => Carbon::parse($getActivePower["created_at"]),
            "updated_at" => Carbon::parse($getActivePower["updated_at"]),
        ];

        return response([
            "active_power" => $resultActivePowers,
            "chart_labels" => Carbon::parse($getActivePower["created_at"])->format('H:i:s')
        ]);
    }

    public function getTableDataOfOneActivePower($id)
    {
        $model = ActivePower::query()->select(["id", "terminal_time", "created_at", "active_power_$id as active_power"]);

        return DataTables::eloquent($model)
            ->editColumn("created_at", fn($row) => Carbon::parse($row["created_at"])->format('Y-m-d H:i:s'))
            ->toJson();
    }

    public function getHistoryOfOneActivePower($id)
    {
        $getActivePower = ActivePower::select(["id", "terminal_time", "created_at", "updated_at", "active_power_$id as active_power"])->latest();
        $resultActivePowers = [
            "id" => $getActivePower["id"],
            "active_power" => $getActivePower["active_power"],
            "terminal_time" => $getActivePower["terminal_time"],
            "created_at" => Carbon::parse($getActivePower["created_at"])->format('Y-m-d H:i:s'),
            "updated_at" => Carbon::parse($getActivePower["updated_at"])->format('Y-m-d H:i:s'),
        ];

        return response([
            "active_power" => $resultActivePowers,
            "chart_labels" => Carbon::parse($getActivePower["created_at"])->format('H:i:s')
        ]);
    }

    public function export(Request $req)
    {
        $sensorId = $req->get("id");
        $filename = $sensorId ? "Sensor_$sensorId.csv" : "Sensor.csv";
        return Excel::download(new ActivePowerExport($sensorId),  $filename);
    }
}
