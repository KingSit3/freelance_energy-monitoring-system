<?php

namespace App\Http\Controllers;

use App\Exports\CurrentLoadExport;
use App\Models\CurrentLoad;
use App\Models\Dpm;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use DataTables;

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
        $getCurrentLoads = Dpm::select([
            "id",
            "payload->01I1 as 01I1",
            "payload->01I2 as 01I2",
            "payload->01I3 as 01I3",
            "payload->02I1 as 02I1",
            "payload->02I2 as 02I2",
            "payload->02I3 as 02I3",
            "payload->03I1 as 03I1",
            "payload->03I2 as 03I2",
            "payload->03I3 as 03I3",
            "payload->04I1 as 04I1",
            "payload->04I2 as 04I2",
            "payload->04I3 as 04I3",
            "payload->05I1 as 05I1",
            "payload->05I2 as 05I2",
            "payload->05I3 as 05I3",
            "payload->06I1 as 06I1",
            "payload->06I2 as 06I2",
            "payload->06I3 as 06I3",
            "payload->07I1 as 07I1",
            "payload->07I2 as 07I2",
            "payload->07I3 as 07I3",
            "payload->08I1 as 08I1",
            "payload->08I2 as 08I2",
            "payload->08I3 as 08I3",
            "payload->09I1 as 09I1",
            "payload->09I2 as 09I2",
            "payload->09I3 as 09I3",
            "payload->10I1 as 10I1",
            "payload->10I2 as 10I2",
            "payload->10I3 as 10I3",
            "payload->11I1 as 11I1",
            "payload->11I2 as 11I2",
            "payload->11I3 as 11I3",
            "payload->_terminalTime as terminal_time",
            "created_at",
            "updated_at",
        ])->latest()->first();

        return response([
            "dpm_list" => collect($getCurrentLoads)->except(["id", "terminal_time", "created_at", "updated_at"])->split(11),
            "terminal_time" => $getCurrentLoads["terminal_time"],
            "created_at" => $getCurrentLoads["created_at"],
            "updated_at" => $getCurrentLoads["updated_at"],
        ]);
    }

    public function getDatatableCurrentLoad()
    {
        $model = Dpm::query()->select([
            "id",
            "payload->01I1 as 01I1",
            "payload->01I2 as 01I2",
            "payload->01I3 as 01I3",
            "payload->02I1 as 02I1",
            "payload->02I2 as 02I2",
            "payload->02I3 as 02I3",
            "payload->03I1 as 03I1",
            "payload->03I2 as 03I2",
            "payload->03I3 as 03I3",
            "payload->04I1 as 04I1",
            "payload->04I2 as 04I2",
            "payload->04I3 as 04I3",
            "payload->05I1 as 05I1",
            "payload->05I2 as 05I2",
            "payload->05I3 as 05I3",
            "payload->06I1 as 06I1",
            "payload->06I2 as 06I2",
            "payload->06I3 as 06I3",
            "payload->07I1 as 07I1",
            "payload->07I2 as 07I2",
            "payload->07I3 as 07I3",
            "payload->08I1 as 08I1",
            "payload->08I2 as 08I2",
            "payload->08I3 as 08I3",
            "payload->09I1 as 09I1",
            "payload->09I2 as 09I2",
            "payload->09I3 as 09I3",
            "payload->10I1 as 10I1",
            "payload->10I2 as 10I2",
            "payload->10I3 as 10I3",
            "payload->11I1 as 11I1",
            "payload->11I2 as 11I2",
            "payload->11I3 as 11I3",
            "payload->_terminalTime as terminal_time",
            "created_at",
        ]);

        return DataTables::eloquent($model)
            // ->editColumn("created_at", fn($row) => Carbon::parse($row["created_at"])->format('Y-m-d H:i:s'))
            ->toJson();
    }

    public function export(Request $req)
    {
        $startDate = $req->get("start_date");
        $endDate = $req->get("end_date");

        $filename = "current_load.csv";
        return Excel::download(new CurrentLoadExport($startDate, $endDate),  $filename);
    }
}
