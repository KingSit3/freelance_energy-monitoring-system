<?php

namespace App\Exports;

use App\Models\Dpm;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ActivePowerExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public $startDate = null;
    public $endDate = null;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate . " 00:00:00";
        $this->endDate = $endDate . " 23:59:59";
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $getData = Dpm::select([
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
        ])
            ->whereBetween("created_at", [$this->startDate, $this->endDate])
            ->get();

        return collect($getData)->map(function ($row) {
            return [
                abs($row["01P_tot"]) ?? 0,
                abs($row["02P_tot"]) ?? 0,
                abs($row["03P_tot"]) ?? 0,
                abs($row["04P_tot"]) ?? 0,
                abs($row["05P_tot"]) ?? 0,
                abs($row["06P_tot"]) ?? 0,
                abs($row["07P_tot"]) ?? 0,
                abs($row["08P_tot"]) ?? 0,
                abs($row["09P_tot"]) ?? 0,
                abs($row["10P_tot"]) ?? 0,
                abs($row["11P_tot"]) ?? 0,
                Carbon::parse($row["terminal_time"])->format('Y-m-d H:i:s') ?? "-",
                $row["created_at"] ?? "-",
            ];
        });
    }

    public function headings(): array
    {
        $arrayData = [
            'Terminal Time',
            'Asia/Jakarta Time',
        ];

        for ($i = 11; $i > 0; $i--) {
            array_unshift($arrayData, "Active Power $i");
        }

        return $arrayData;
    }
}
