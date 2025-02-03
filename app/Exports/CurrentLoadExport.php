<?php

namespace App\Exports;

use App\Models\Dpm;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CurrentLoadExport implements FromCollection, WithHeadings, ShouldAutoSize
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
        ])
            ->whereBetween("created_at", [$this->startDate, $this->endDate])
            ->get();

        return collect($getData)->map(function ($row) {
            return [
                $row["01I1"] ?? 0,
                $row["01I2"] ?? 0,
                $row["01I3"] ?? 0,
                $row["02I1"] ?? 0,
                $row["02I2"] ?? 0,
                $row["02I3"] ?? 0,
                $row["03I1"] ?? 0,
                $row["03I2"] ?? 0,
                $row["03I3"] ?? 0,
                $row["04I1"] ?? 0,
                $row["04I2"] ?? 0,
                $row["04I3"] ?? 0,
                $row["05I1"] ?? 0,
                $row["05I2"] ?? 0,
                $row["05I3"] ?? 0,
                $row["06I1"] ?? 0,
                $row["06I2"] ?? 0,
                $row["06I3"] ?? 0,
                $row["07I1"] ?? 0,
                $row["07I2"] ?? 0,
                $row["07I3"] ?? 0,
                $row["08I1"] ?? 0,
                $row["08I2"] ?? 0,
                $row["08I3"] ?? 0,
                $row["09I1"] ?? 0,
                $row["09I2"] ?? 0,
                $row["09I3"] ?? 0,
                $row["10I1"] ?? 0,
                $row["10I2"] ?? 0,
                $row["10I3"] ?? 0,
                $row["11I1"] ?? 0,
                $row["11I2"] ?? 0,
                $row["11I3"] ?? 0,
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
            for ($j = 3; $j > 0; $j--) {
                array_unshift($arrayData, str_pad($i, 2, "0", STR_PAD_LEFT) . "I" . $j);
            }
        }

        return $arrayData;
    }
}
