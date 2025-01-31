<?php

namespace App\Http\Controllers;

use App\Models\ActivePower;
use App\Models\Dpm;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $getActivePowers = Dpm::select([
            "id",
            "payload->01kWh as 01kWH",
            "payload->02kWh as 02kWH",
            "payload->03kWh as 03kWH",
            "payload->04kWh as 04kWH",
            "payload->05kWh as 05kWH",
            "payload->06kWh as 06kWH",
            "payload->07kWh as 07kWH",
            "payload->08kWh as 08kWH",
            "payload->09kWh as 09kWH",
            "payload->10kWh as 10kWH",
            "payload->11kWh as 11kWH",
            "payload->_terminalTime as terminal_time",
            "created_at",
            "updated_at",
        ])->latest()->limit(60)->get();
        $resultActivePowers = collect($getActivePowers)->map(function ($item) {
            return [
                "id" => $item["id"],
                "data" => [
                    $item["01kWH"],
                    $item["02kWH"],
                    $item["03kWH"],
                    $item["04kWH"],
                    $item["05kWH"],
                    $item["06kWH"],
                    $item["07kWH"],
                    $item["08kWH"],
                    $item["09kWH"],
                    $item["10kWH"],
                    $item["11kWH"],
                ],
                "total" => array_sum([
                    $item["01kWH"],
                    $item["02kWH"],
                    $item["03kWH"],
                    $item["04kWH"],
                    $item["05kWH"],
                    $item["06kWH"],
                    $item["07kWH"],
                    $item["08kWH"],
                    $item["09kWH"],
                    $item["10kWH"],
                    $item["11kWH"],
                ]),
                "terminal_time" => Carbon::parse($item["terminal_time"])->format('Y-m-d H:i:s'),
                "created_at" => $item["created_at"],
                "updated_at" => $item["updated_at"],
            ];
        });

        // Chart Data
        $chartLabels = collect($getActivePowers)->pluck("created_at")->map(fn($item) => Carbon::parse($item)->format('H:i:s'));

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
            "data" => $resultActivePowers,
            "title" => "dashboard",
            "chart_labels" => $chartLabels,
            "sensor_colors" => $sensorColors
        ];

        return view("dashboard", $data);
    }
}
