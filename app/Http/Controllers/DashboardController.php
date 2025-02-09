<?php

namespace App\Http\Controllers;

use App\Models\Dpm;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public $total = 0;

    public function index()
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
                Carbon::now()->subMonth(1)->startOfMonth()->format("Y-m-d H:i:s"),
                Carbon::now()->subMonth(1)->endOfMonth()->format("Y-m-d H:i:s")
            ])
            ->get();

        $chartLabels = [];
        for ($i = 1; $i <= Carbon::now()->endOfMonth()->format("d"); $i++) {
            array_push($chartLabels, $i);
        }

        // Get Data Per Month
        $resultKwh  = collect($getKwhData)
            ->groupBy(fn($item) => Carbon::parse($item->created_at)->format("d"))
            ->map(function ($dataPerMonth) {
                $resultPerMonth = [
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

                foreach ($dataPerMonth as $item) {
                    $resultPerMonth["01kWh"] += $item["01kWh"];
                    $resultPerMonth["02kWh"] += $item["02kWh"];
                    $resultPerMonth["03kWh"] += $item["03kWh"];
                    $resultPerMonth["04kWh"] += $item["04kWh"];
                    $resultPerMonth["05kWh"] += $item["05kWh"];
                    $resultPerMonth["06kWh"] += $item["06kWh"];
                    $resultPerMonth["07kWh"] += $item["07kWh"];
                    $resultPerMonth["08kWh"] += $item["08kWh"];
                    $resultPerMonth["09kWh"] += $item["09kWh"];
                    $resultPerMonth["10kWh"] += $item["10kWh"];
                    $resultPerMonth["11kWh"] += $item["11kWh"];
                }

                $this->total += array_sum([
                    $item["01kWh"],
                    $item["02kWh"],
                    $item["03kWh"],
                    $item["04kWh"],
                    $item["05kWh"],
                    $item["06kWh"],
                    $item["07kWh"],
                    $item["08kWh"],
                    $item["09kWh"],
                    $item["10kWh"],
                    $item["11kWh"],
                ]);

                return $resultPerMonth;
            });


        $sensorColors = [
            "#ef4444",
            "#f97316",
            "#f59e0b",
            "#eab308",
            "#84cc16",
            "#10b981",
            "#14b8a6",
            "#3b82f6",
            "#8b5cf6",
            "#d946ef",
            "#f43f5e",
            "#3730a3",
            "#92400e",
        ];

        $data = [
            "total_power" => $this->total,
            "data" => $resultKwh,
            "title" => "dashboard",
            "chart_labels" => $chartLabels,
            "sensor_colors" => $sensorColors
        ];

        dd($data);

        return view("dashboard", $data);
    }
}
