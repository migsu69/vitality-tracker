<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\Exportable;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class VitalsExport implements FromArray, WithColumnFormatting, ShouldAutoSize
{
    use Exportable;

    protected $records;

    public function __construct($records)
    {
        $this->records = $records;
    }

    public function array(): array
    {
        return $this->records;
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
            'B' => NumberFormat::FORMAT_TEXT,
            // Add other columns as needed
        ];
    }

    public function sortByMonth()
    {
        return collect($this->records)
            ->sortBy([
                ['month', 'desc'],
            ])->values()->all();
    }

}
