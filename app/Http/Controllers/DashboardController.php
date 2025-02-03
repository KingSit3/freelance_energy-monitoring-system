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
        ])->latest()->limit(60)->get();
        $resultActivePowers = collect($getActivePowers)->map(function ($item) {
            return [
                "id" => $item["id"],
                "data" => [
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
                ],
                "total" => array_sum([
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
