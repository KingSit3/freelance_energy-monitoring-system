<?php

namespace App\Http\Controllers;

use App\Models\CurrentLoad;

class CurrentLoadController extends Controller
{
    public function index()
    {
        $data = [
            "title" => "Current Load",
        ];

        return view("current-load", $data);
    }

    public function getLatestCurrentLoad()
    {
        $getCurrentLoads = CurrentLoad::latest()->first();

        $data = collect($getCurrentLoads);

        $id = $data->pull("id");
        $terminalTime = $data->pull("terminal_time");
        $createdAt = $data->pull("created_at");
        $updatedAt = $data->pull("updated_at");

        return response([
            "id" => $id,
            "terminal_time" => $terminalTime,
            "dpm_list" => collect($data->all())->split(11),
            "created_at" => $createdAt,
            "updated_at" => $updatedAt,
        ]);
    }
}
