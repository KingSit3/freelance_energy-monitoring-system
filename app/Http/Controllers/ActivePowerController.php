<?php

namespace App\Http\Controllers;

use App\Exports\ActivePowerExport;
use App\Models\Dpm;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;

class ActivePowerController extends Controller
{
    public function index()
    {
        // Chart Data
        $getActivePower = Dpm::select([
            "payload->01P_tot as 01P_tot",
            "payload->02P_tot as 02P_tot",
            "payload->03P_tot as 03P_tot",
            "payload->04P_tot as 04P_tot",
            "payload->05P_tot as 05P_tot",
            "payload->06P_tot as 06P_tot",
            "payload->07P_tot as 07P_tot",
            "payload->08P_tot as 08P_tot",
            "payload->09P_tot as 09P_tot",
            "payload->10P_tot as 10P_tot",
            "payload->11P_tot as 11P_tot",
        ])->latest()->first();
        // End Chart Data

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
            "title" => "Active Power",
            "data" => collect($getActivePower),
            "sensor_colors" => $sensorColors
        ];

        return view("active-power", $data);
    }

    public function getActivePower()
    {
        $getActivePower = Dpm::select([
            "payload->01P_tot as 01P_tot",
            "payload->02P_tot as 02P_tot",
            "payload->03P_tot as 03P_tot",
            "payload->04P_tot as 04P_tot",
            "payload->05P_tot as 05P_tot",
            "payload->06P_tot as 06P_tot",
            "payload->07P_tot as 07P_tot",
            "payload->08P_tot as 08P_tot",
            "payload->09P_tot as 09P_tot",
            "payload->10P_tot as 10P_tot",
            "payload->11P_tot as 11P_tot",
        ])->latest()->first();

        return response([
            "data" => collect($getActivePower)->map(fn($item) => abs($item))->flatten()
        ]);
    }

    public function getTableDataOfActivePower()
    {
        return DataTables::eloquent(Dpm::query()->select([
            "id",
            "payload->01P_tot as 01P_tot",
            "payload->02P_tot as 02P_tot",
            "payload->03P_tot as 03P_tot",
            "payload->04P_tot as 04P_tot",
            "payload->05P_tot as 05P_tot",
            "payload->06P_tot as 06P_tot",
            "payload->07P_tot as 07P_tot",
            "payload->08P_tot as 08P_tot",
            "payload->09P_tot as 09P_tot",
            "payload->10P_tot as 10P_tot",
            "payload->11P_tot as 11P_tot",
            "payload->_terminalTime as terminal_time",
            "created_at",
            "updated_at",
        ]))
            ->editColumn("01P_tot", fn($row) => abs($row["01P_tot"]))
            ->editColumn("02P_tot", fn($row) => abs($row["02P_tot"]))
            ->editColumn("03P_tot", fn($row) => abs($row["03P_tot"]))
            ->editColumn("04P_tot", fn($row) => abs($row["04P_tot"]))
            ->editColumn("05P_tot", fn($row) => abs($row["05P_tot"]))
            ->editColumn("06P_tot", fn($row) => abs($row["06P_tot"]))
            ->editColumn("07P_tot", fn($row) => abs($row["07P_tot"]))
            ->editColumn("08P_tot", fn($row) => abs($row["08P_tot"]))
            ->editColumn("09P_tot", fn($row) => abs($row["09P_tot"]))
            ->editColumn("10P_tot", fn($row) => abs($row["10P_tot"]))
            ->editColumn("11P_tot", fn($row) => abs($row["11P_tot"]))
            ->editColumn("terminal_time", fn($row) => Carbon::parse($row["terminal_time"])->format('Y-m-d H:i:s'))
            ->toJson();
    }

    public function export(Request $req)
    {
        $startDate = $req->get("start_date");
        $endDate = $req->get("end_date");

        $filename = "active-power.csv";
        return Excel::download(new ActivePowerExport($startDate, $endDate),  $filename);
    }
}
