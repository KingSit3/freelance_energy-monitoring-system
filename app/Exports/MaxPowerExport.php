<?php

namespace App\Exports;

use App\Models\Dpm;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MaxPowerExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public $id = null;
    public $startDate = null;
    public $endDate = null;


    public function __construct($id, $startDate, $endDate)
    {
        $this->id = $id;
        $this->startDate = $startDate . " 00:00:00";
        $this->endDate = $endDate . " 23:59:59";
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $getData = Dpm::query();

        if ($this->id) {
            $paddedId = str_pad($this->id, 2, 0, STR_PAD_LEFT);
            $getData->select([
                "payload->" . $paddedId . "kWh as max_power",
                "payload->_terminalTime as terminal_time",
                "created_at"
            ]);
            return collect($getData->whereBetween("created_at", [$this->startDate, $this->endDate])->get())->map(function ($row) {
                return [
                    $row["max_power"] ?? 0,
                    Carbon::parse($row["terminal_time"])->format('Y-m-d H:i:s') ?? "-",
                    $row["created_at"] ?? "-",
                ];
            });
        } else {
            $getData->select([
                "payload->01kWh as 01kWh",
                "payload->02kWh as 02kWh",
                "payload->03kWh as 03kWh",
                "payload->04kWh as 04kWh",
                "payload->05kWh as 05kWh",
                "payload->06kWh as 06kWh",
                "payload->07kWh as 07kWh",
                "payload->08kWh as 08kWh",
                "payload->09kWh as 09kWh",
                "payload->10kWh as 10kWh",
                "payload->11kWh as 11kWh",
                "payload->_terminalTime as terminal_time",
                "created_at"
            ]);
            return collect($getData->whereBetween("created_at", [$this->startDate, $this->endDate])->get())->map(function ($row) {
                return [
                    $row["01kWh"] ?? 0,
                    $row["02kWh"] ?? 0,
                    $row["03kWh"] ?? 0,
                    $row["04kWh"] ?? 0,
                    $row["05kWh"] ?? 0,
                    $row["06kWh"] ?? 0,
                    $row["07kWh"] ?? 0,
                    $row["08kWh"] ?? 0,
                    $row["09kWh"] ?? 0,
                    $row["10kWh"] ?? 0,
                    $row["11kWh"] ?? 0,
                    Carbon::parse($row["terminal_time"])->format('Y-m-d H:i:s') ?? "-",
                    $row["created_at"] ?? "-",
                ];
            });
        }
    }

    public function headings(): array
    {
        if ($this->id) {
            return [
                'DPM',
                'Terminal Time',
                'Asia/Jakarta Time',
            ];
        } else {
            $arrayData = [
                'Terminal Time',
                'Asia/Jakarta Time',
            ];

            for ($i = 11; $i > 0; $i--) {
                array_unshift($arrayData, "DPM $i");
            }

            return $arrayData;
        }
    }
}
