<?php

namespace App\Http\Controllers;

use App\Models\ActivePower;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivePowerController extends Controller
{
    public function show($id)
    {
        $getActivePower = ActivePower::select(["id", "terminal_time", "created_at", "updated_at", "active_power_$id as active_power"])->latest()->limit(24)->get();

        // Chart Data
        $chartLabels = collect($getActivePower)->pluck("created_at")->map(fn($item) => Carbon::parse($item)->format('H:i:s'));
        $chartData = collect($getActivePower)->pluck("active_power");
        // End Chart Data

        $data = [
            "active_power" => $getActivePower,
            "title" => "sensor $id",
            "chart_labels" => $chartLabels,
            "chart_data" => $chartData,
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
            "created_at" => Carbon::parse($getActivePower["created_at"])->format('Y-m-d H:i:s'),
            "updated_at" => Carbon::parse($getActivePower["updated_at"])->format('Y-m-d H:i:s'),
        ];

        return response([
            "active_power" => $resultActivePowers,
            "chart_labels" => Carbon::parse($getActivePower["created_at"])->format('H:i:s')
        ]);
    }

    public function getOneActivePower($id)
    {
        $getActivePower = ActivePower::select(["id", "terminal_time", "created_at", "updated_at", "active_power_$id as active_power"])->latest()->first();
        return response([
            "active_power" => $getActivePower,
            "chart_labels" => Carbon::parse($getActivePower["created_at"])->format('H:i:s')
        ]);
    }
}
