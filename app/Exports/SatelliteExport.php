<?php

namespace App\Exports;

use App\Models\Satellite;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SatelliteExport implements FromCollection, WithHeadings, WithStyles
{
    public function collection()
    {
        return Satellite::with('groundStation')->get()->map(function ($s) {
            return [
                'ID' => $s->id,
                'Nama' => $s->name,
                'Negara' => $s->country,
                'Tanggal Peluncuran' => $s->launch_date ? $s->launch_date->format('d/m/Y') : '-',
                'Orbit' => $s->orbit_type,
                'Status' => $s->is_active ? 'Aktif' : 'Nonaktif',
                'Ground Station' => $s->groundStation ? $s->groundStation->name : '-',
                'Deskripsi' => $s->description ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return ['ID', 'Nama Satelit', 'Negara', 'Tanggal Peluncuran', 'Orbit Type', 'Status', 'Ground Station', 'Deskripsi'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}