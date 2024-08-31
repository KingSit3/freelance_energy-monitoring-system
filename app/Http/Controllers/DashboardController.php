<?php

namespace App\Http\Controllers;

use App\Models\ActivePower;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $getActivePowers = ActivePower::latest()->limit(24)->get();
        $resultActivePowers = collect($getActivePowers)->map(function ($item) {
            return [
                "id" => $item["id"],
                "data" => [
                    $item["active_power_1"],
                    $item["active_power_2"],
                    $item["active_power_3"],
                    $item["active_power_4"],
                    $item["active_power_5"],
                    $item["active_power_6"],
                    $item["active_power_7"],
                    $item["active_power_8"],
                    $item["active_power_9"],
                    $item["active_power_10"],
                    $item["active_power_11"],
                    $item["active_power_12"],
                    $item["active_power_13"],
                ],
                "total_active_power" => array_sum([
                    $item["active_power_1"],
                    $item["active_power_2"],
                    $item["active_power_3"],
                    $item["active_power_4"],
                    $item["active_power_5"],
                    $item["active_power_6"],
                    $item["active_power_7"],
                    $item["active_power_8"],
                    $item["active_power_9"],
                    $item["active_power_10"],
                    $item["active_power_11"],
                    $item["active_power_12"],
                    $item["active_power_13"],
                ]),
                "terminal_time" => $item["terminal_time"],
                "created_at" => Carbon::parse($item["created_at"])->format('Y-m-d H:i:s'),
                "updated_at" => Carbon::parse($item["updated_at"])->format('Y-m-d H:i:s'),
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
            "active_powers" => $resultActivePowers,
            "title" => "dashboard",
            "chart_labels" => $chartLabels,
            "sensor_colors" => $sensorColors
        ];

        return view("dashboard", $data);
    }
}
