<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public $total = 0;

    public function index()
    {
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
            "#f43f5e"
        ];

        $data = [
            "total_power" => $this->total,
            "title" => "dashboard",
            "sensor_colors" => $sensorColors
        ];

        return view("dashboard", $data);
    }
}
