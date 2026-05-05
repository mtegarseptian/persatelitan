<?php

namespace App\Exports;

use App\Models\GroundStation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class GroundStationExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return GroundStation::withCount('satellites')->get()->map(function ($gs) {
            return [
                'ID' => $gs->id,
                'Nama' => $gs->name,
                'Lokasi' => $gs->location,
                'Negara' => $gs->country,
                'Latitude' => $gs->latitude,
                'Longitude' => $gs->longitude,
                'Total Satelit' => $gs->satellites_count,
                'Status' => $gs->is_active ? 'Aktif' : 'Nonaktif',
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Nama Stasiun', 'Lokasi', 'Negara', 'Latitude', 'Longitude', 'Total Satelit', 'Status'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}