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

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $sensorId = $this->id;
        return ActivePower::when($sensorId, function ($query) use ($sensorId) {
            $query->select(["active_power_$sensorId as active_power", "terminal_time", "created_at"]);
        })->get()->makeHidden(["id", "updated_at"]);
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

            for ($i = 13; $i > 0; $i--) {
                array_unshift($arrayData, "Active Power $i");
            }

            return $arrayData;
        }
    }
}
