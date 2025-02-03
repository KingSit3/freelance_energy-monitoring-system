<?php

namespace App\Http\Controllers;

use App\Exports\MaxPowerExport;
use App\Models\Dpm;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;

class MaxPowerController extends Controller
{
    public function show($id)
    {
        $paddedId = str_pad($id, 2, 0, STR_PAD_LEFT);

        $getMaxPowers = Dpm::select([
            "id",
            "payload->" . $paddedId . "kWh as data",
            "payload->_terminalTime as terminal_time",
            "created_at",
            "updated_at",
        ])->latest()->limit(60)->get();
        $chartLabels = collect($getMaxPowers)->pluck("created_at")->map(fn($item) => Carbon::parse($item)->format('H:i:s'));
        $chartData = collect($getMaxPowers)->pluck("data");

        $data = [
            "data" => $getMaxPowers,
            "title" => "DPM $id",
            "chart_labels" => $chartLabels,
            "chart_data" => $chartData,
            "sensor_id" => $id,
        ];

        return view("max-power", $data);
    }

    public function getMaxPower()
    {
        $getMaxPower = Dpm::select([
            "id",
            "payload->01kWh as 01kWh",
            "payload->02kWh as 02kWh",
            "payload->03kWh as 03kWh",
            "payload->04kWh as 04kWh",
            "payload->05kWh as 05kWh",
            "payload->06kWh as 06kWh",
            "payload->07kWh as 07kWh",
            "payload->08kWh as 08kWh",
            "payload->09kWh as 09kWh",
            "payload->10kWh as 10kWh",
            "payload->11kWh as 11kWh",
            "payload->_terminalTime as terminal_time",
            "created_at",
            "updated_at",
        ])->latest()->first();
        $resultMaxPowers =  [
            "id" => $getMaxPower["id"],
            "data" => [
                $getMaxPower["01kWh"],
                $getMaxPower["02kWh"],
                $getMaxPower["03kWh"],
                $getMaxPower["04kWh"],
                $getMaxPower["05kWh"],
                $getMaxPower["06kWh"],
                $getMaxPower["07kWh"],
                $getMaxPower["08kWh"],
                $getMaxPower["09kWh"],
                $getMaxPower["10kWh"],
                $getMaxPower["11kWh"],
            ],
            "total" => array_sum([
                $getMaxPower["01kWh"],
                $getMaxPower["02kWh"],
                $getMaxPower["03kWh"],
                $getMaxPower["04kWh"],
                $getMaxPower["05kWh"],
                $getMaxPower["06kWh"],
                $getMaxPower["07kWh"],
                $getMaxPower["08kWh"],
                $getMaxPower["09kWh"],
                $getMaxPower["10kWh"],
                $getMaxPower["11kWh"],
            ]),
            "terminal_time" => Carbon::parse($getMaxPower["terminal_time"])->format('Y-m-d H:i:s'),
            "created_at" => $getMaxPower["created_at"],
            "updated_at" => $getMaxPower["updated_at"],
        ];

        return response([
            "max_power" => $resultMaxPowers,
            "chart_labels" => Carbon::parse($getMaxPower["created_at"])->format('H:i:s')
        ]);
    }

    public function getTableDataOfMaxPower()
    {
        return DataTables::eloquent(Dpm::query()->select([
            "id",
            "payload->01kWh as 01kWh",
            "payload->02kWh as 02kWh",
            "payload->03kWh as 03kWh",
            "payload->04kWh as 04kWh",
            "payload->05kWh as 05kWh",
            "payload->06kWh as 06kWh",
            "payload->07kWh as 07kWh",
            "payload->08kWh as 08kWh",
            "payload->09kWh as 09kWh",
            "payload->10kWh as 10kWh",
            "payload->11kWh as 11kWh",
            "payload->_terminalTime as terminal_time",
            "created_at",
            "updated_at",
        ]))->toJson();
    }

    public function getOneMaxPower($id)
    {
        $paddedId = str_pad($id, 2, 0, STR_PAD_LEFT);

        $getMaxPower = Dpm::select([
            "id",
            "payload->" . $paddedId . "kWh as data",
            "payload->_terminalTime as terminal_time",
            "created_at",
            "updated_at",
        ])->latest()->first();

        return response([
            "max_power" => $getMaxPower,
            "chart_labels" => Carbon::parse($getMaxPower["created_at"])->format('H:i:s')
        ]);
    }

    public function getTableDataOfOneMaxPower($id)
    {
        $paddedId = str_pad($id, 2, 0, STR_PAD_LEFT);

        $model = Dpm::query()->select([
            "id",
            "payload->" . $paddedId . "kWh as data",
            "payload->_terminalTime as terminal_time",
            "created_at",
            "updated_at",
        ]);

        return DataTables::eloquent($model)
            // ->editColumn("created_at", fn($row) => Carbon::parse($row["created_at"])->format('Y-m-d H:i:s'))
            ->toJson();
    }

    public function getHistoryOfOneMaxPower($id)
    {
        $getMaxPower = Dpm::select(["id", "terminal_time", "created_at", "updated_at", "max_power_$id as max_power"])->latest();
        $resultMaxPowers = [
            "id" => $getMaxPower["id"],
            "max_power" => $getMaxPower["max_power"],
            "terminal_time" => $getMaxPower["terminal_time"],
            "created_at" => Carbon::parse($getMaxPower["created_at"])->format('Y-m-d H:i:s'),
            "updated_at" => Carbon::parse($getMaxPower["updated_at"])->format('Y-m-d H:i:s'),
        ];

        return response([
            "max_power" => $resultMaxPowers,
            "chart_labels" => Carbon::parse($getMaxPower["created_at"])->format('H:i:s')
        ]);
    }

    public function export(Request $req)
    {
        $sensorId = $req->get("id", null);
        $startDate = $req->get("start_date");
        $endDate = $req->get("end_date");

        $filename = $sensorId ? "max-power_$sensorId.csv" : "max-power.csv";
        return Excel::download(new MaxPowerExport($sensorId, $startDate, $endDate),  $filename);
    }
}
