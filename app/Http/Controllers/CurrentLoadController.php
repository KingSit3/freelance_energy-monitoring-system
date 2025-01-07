<?php

namespace App\Http\Controllers;

use App\Exports\CurrentLoadExport;
use App\Models\CurrentLoad;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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

    public function export(Request $req)
    {
        $startDate = $req->get("start_date");
        $endDate = $req->get("end_date");

        $filename = "current_load.csv";
        return Excel::download(new CurrentLoadExport($startDate, $endDate),  $filename);
    }
}
