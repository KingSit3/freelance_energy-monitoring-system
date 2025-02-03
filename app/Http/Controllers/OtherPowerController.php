<?php

namespace App\Http\Controllers;

use App\Exports\OtherPowerExport;
use App\Models\Dpm;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;

class OtherPowerController extends Controller
{
    public function index()
    {
        $getOtherPower = Dpm::select([
            "payload->01V1N as 01V1N",
            "payload->01V2N as 01V2N",
            "payload->01V3N as 01V3N",
            "payload->09V12 as 09V12",
            "payload->01V23 as 01V23",
            "payload->01V31 as 01V31",
            "payload->01PF as 01PF",
            "payload->01FREQ as 01FREQ",
        ])->latest()->first();

        $data = [
            "title" => "Other Power",
            "data" => $getOtherPower,
        ];

        return view("other-power", $data);
    }

    public function getLatestOtherPower()
    {
        $getOtherPower = Dpm::select([
            "payload->01V1N as 01V1N",
            "payload->01V2N as 01V2N",
            "payload->01V3N as 01V3N",
            "payload->09V12 as 09V12",
            "payload->01V23 as 01V23",
            "payload->01V31 as 01V31",
            "payload->01PF as 01PF",
            "payload->01FREQ as 01FREQ",
        ])->latest()->first();

        return response([
            "data" => $getOtherPower
        ]);
    }

    public function getDatatableData()
    {
        return DataTables::eloquent(Dpm::query()->select([
            "id",
            "payload->01V1N as 01V1N",
            "payload->01V2N as 01V2N",
            "payload->01V3N as 01V3N",
            "payload->09V12 as 09V12",
            "payload->01V23 as 01V23",
            "payload->01V31 as 01V31",
            "payload->01PF as 01PF",
            "payload->01FREQ as 01FREQ",
            "payload->_terminalTime as terminal_time",
            "created_at",
            "updated_at",
        ]))
            ->editColumn("01V1N", fn($row) => abs($row["01V1N"]))
            ->editColumn("01V2N", fn($row) => abs($row["01V2N"]))
            ->editColumn("01V3N", fn($row) => abs($row["01V3N"]))
            ->editColumn("09V12", fn($row) => abs($row["09V12"]))
            ->editColumn("01V23", fn($row) => abs($row["01V23"]))
            ->editColumn("01V31", fn($row) => abs($row["01V31"]))
            ->editColumn("01PF", fn($row) => abs($row["01PF"]))
            ->editColumn("01FREQ", fn($row) => abs($row["01FREQ"]))
            ->editColumn("terminal_time", fn($row) => Carbon::parse($row["terminal_time"])->format('Y-m-d H:i:s'))
            ->toJson();
    }

    public function export(Request $req)
    {
        $startDate = $req->get("start_date");
        $endDate = $req->get("end_date");

        $filename = "other-power.csv";
        return Excel::download(new OtherPowerExport($startDate, $endDate),  $filename);
    }
}
