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
    public $totalPower = 0;

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

    public function getLatestMaxPower()
    {
        $getLatestMaxPower = Dpm::select([
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
        ])->latest()->first();

        return response(["data" => collect($getLatestMaxPower)->flatten()]);
    }

    public function getChartDataOfMaxPower()
    {
        $getKwhData = Dpm::select([
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
        ])
            ->latest()
            ->whereBetween("created_at", [
                Carbon::now()->startOfMonth()->format("Y-m-d H:i:s"),
                Carbon::now()->endOfMonth()->format("Y-m-d H:i:s")
            ])
            ->get();

        $chartLabels = [];
        for ($i = 1; $i <= Carbon::now()->endOfMonth()->format("d"); $i++) {
            array_push($chartLabels, str_pad($i, 2, 0, STR_PAD_LEFT));
        }

        // Get Data Per Month
        $getMaxPowerGroupByDay = collect($getKwhData)
            ->groupBy(fn($item) => Carbon::parse($item->created_at)->format("d"))
            ->map(function ($dataPerDay) {
                $resultPerDay = [
                    "01kWh" => 0,
                    "02kWh" => 0,
                    "03kWh" => 0,
                    "04kWh" => 0,
                    "05kWh" => 0,
                    "06kWh" => 0,
                    "07kWh" => 0,
                    "08kWh" => 0,
                    "09kWh" => 0,
                    "10kWh" => 0,
                    "11kWh" => 0,
                ];

                foreach ($dataPerDay as $item) {
                    $resultPerDay["01kWh"] += $item["01kWh"];
                    $resultPerDay["02kWh"] += $item["02kWh"];
                    $resultPerDay["03kWh"] += $item["03kWh"];
                    $resultPerDay["04kWh"] += $item["04kWh"];
                    $resultPerDay["05kWh"] += $item["05kWh"];
                    $resultPerDay["06kWh"] += $item["06kWh"];
                    $resultPerDay["07kWh"] += $item["07kWh"];
                    $resultPerDay["08kWh"] += $item["08kWh"];
                    $resultPerDay["09kWh"] += $item["09kWh"];
                    $resultPerDay["10kWh"] += $item["10kWh"];
                    $resultPerDay["11kWh"] += $item["11kWh"];
                }

                $this->totalPower += array_sum($resultPerDay);

                return $resultPerDay;
            });

        $result = [
            "01kWh" => [],
            "02kWh" => [],
            "03kWh" => [],
            "04kWh" => [],
            "05kWh" => [],
            "06kWh" => [],
            "07kWh" => [],
            "08kWh" => [],
            "09kWh" => [],
            "10kWh" => [],
            "11kWh" => [],
        ];
        foreach ($chartLabels as $label) {
            array_push($result["01kWh"], $getMaxPowerGroupByDay[$label]["01kWh"] ?? 0);
            array_push($result["02kWh"], $getMaxPowerGroupByDay[$label]["02kWh"] ?? 0);
            array_push($result["03kWh"], $getMaxPowerGroupByDay[$label]["03kWh"] ?? 0);
            array_push($result["04kWh"], $getMaxPowerGroupByDay[$label]["04kWh"] ?? 0);
            array_push($result["05kWh"], $getMaxPowerGroupByDay[$label]["05kWh"] ?? 0);
            array_push($result["06kWh"], $getMaxPowerGroupByDay[$label]["06kWh"] ?? 0);
            array_push($result["07kWh"], $getMaxPowerGroupByDay[$label]["07kWh"] ?? 0);
            array_push($result["08kWh"], $getMaxPowerGroupByDay[$label]["08kWh"] ?? 0);
            array_push($result["09kWh"], $getMaxPowerGroupByDay[$label]["09kWh"] ?? 0);
            array_push($result["10kWh"], $getMaxPowerGroupByDay[$label]["10kWh"] ?? 0);
            array_push($result["11kWh"], $getMaxPowerGroupByDay[$label]["11kWh"] ?? 0);
        }

        return response([
            "data" => array_values($result),
            "labels" => $chartLabels,
            "total_power" => $this->totalPower,
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
