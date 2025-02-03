<?php

namespace App\Exports;

use App\Models\Dpm;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OtherPowerExport implements FromCollection, WithHeadings, ShouldAutoSize
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
        ])
            ->whereBetween("created_at", [$this->startDate, $this->endDate])
            ->get();

        return collect($getData)->map(function ($row) {
            return [
                $row["01V1N"] ?? 0,
                $row["01V2N"] ?? 0,
                $row["01V3N"] ?? 0,
                $row["09V12"] ?? 0,
                $row["01V23"] ?? 0,
                $row["01V31"] ?? 0,
                $row["01PF"] ?? 0,
                $row["01FREQ"] ?? 0,
                Carbon::parse($row["terminal_time"])->format('Y-m-d H:i:s') ?? "-",
                $row["created_at"] ?? "-",
            ];
        });
    }

    public function headings(): array
    {
        $arrayData = [
            '01V1N',
            '01V2N',
            '01V3N',
            '09V12',
            '01V23',
            '01PF',
            '01FREQ',
            'Terminal Time',
            'Asia/Jakarta Time',
        ];

        return $arrayData;
    }
}
