<?php

namespace App\Exports;

use App\Models\ActivePower;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ActivePowerExport implements FromCollection, WithHeadings, ShouldAutoSize
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
        $sensorId = $this->id;
        return ActivePower::when($sensorId, function ($query) use ($sensorId) {
            $query->select(["active_power_$sensorId as active_power", "terminal_time", "created_at"]);
        })
            ->whereBetween("created_at", [$this->startDate, $this->endDate])
            ->get()
            ->makeHidden(["id", "updated_at"]);
    }

    public function headings(): array
    {
        if ($this->id) {
            return [
                'Active Power',
                'Terminal Time',
                'Asia/Jakarta Time',
            ];
        } else {
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
}
