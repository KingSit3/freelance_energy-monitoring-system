<?php

namespace App\Exports;

use App\Models\ActivePower;
use App\Models\CurrentLoad;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CurrentLoadExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public $id = null;
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
        return CurrentLoad::whereBetween("created_at", [$this->startDate, $this->endDate])
            ->get()
            ->makeHidden(["id", "updated_at"]);
    }

    public function headings(): array
    {
        $arrayData = [
            'Terminal Time',
            'Asia/Jakarta Time',
        ];

        for ($i = 11; $i > 0; $i--) {
            for ($j = 3; $j > 0; $j--) {
                array_unshift($arrayData, str_pad($i, 2, "0", STR_PAD_LEFT) . " - " . $j);
            }
        }

        return $arrayData;
    }

    public function map($invoice): array
    {
        return [
            $invoice->invoice_number,
            $invoice->user->name,
            Date::dateTimeToExcel($invoice->created_at),
        ];
    }
}
