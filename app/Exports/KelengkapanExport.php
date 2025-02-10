<?php

namespace App\Exports;

use App\Models\KelengkapanKerja\KelengkapanKerja;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class KelengkapanExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths
{
    public function collection()
    {
        $checkCreatedAt = KelengkapanKerja::orderByDesc('created_at')->first();

        
        $dataExport = KelengkapanKerja::where('table_kelengkapan_kerja.created_at', $checkCreatedAt->created_at)
        ->join('table_karyawan', 'table_karyawan.id_badge', '=', 'table_kelengkapan_kerja.id_badge')
        ->select(
            'table_kelengkapan_kerja.*',
            'table_karyawan.nama_karyawan',
            'table_karyawan.cost_center',
        )

        ->orderByRaw("
            (CASE WHEN table_kelengkapan_kerja.sepatu_kantor IS NOT NULL THEN 1 ELSE 0 END) +
            (CASE WHEN table_kelengkapan_kerja.sepatu_safety IS NOT NULL THEN 1 ELSE 0 END) +
            (CASE WHEN table_kelengkapan_kerja.wearpack_cover_all IS NOT NULL THEN 1 ELSE 0 END) +
            (CASE WHEN table_kelengkapan_kerja.jaket_shift IS NOT NULL THEN 1 ELSE 0 END) +
            (CASE WHEN table_kelengkapan_kerja.seragam_olahraga IS NOT NULL THEN 1 ELSE 0 END) +
            (CASE WHEN table_kelengkapan_kerja.jaket_casual IS NOT NULL THEN 1 ELSE 0 END) +
            (CASE WHEN table_kelengkapan_kerja.seragam_dinas_harian IS NOT NULL THEN 1 ELSE 0 END) DESC
        ")

        ->get();
        return $dataExport;
    }

    public function headings(): array
    {
        return [
            'No', 'ID Badge', 'Nama Karyawan', 'Cost Center', 'Unit Kerja', 'Grade',
            'Sepatu Kantor', 'Sepatu Safety', 'Wearpack Cover All', 'Jaket Shift',
            'Seragam Olahraga', 'Jaket Casual', 'Seragam Dinas Harian',
            // 'Created At', 'Updated At'
        ];
    }

    public function map($item): array
    {
        static $no = 1;
        return [
            $no++, // Menambahkan nomor urut
            $item->id_badge,
            $item->nama_karyawan,
            $item->cost_center,
            $item->unit_kerja,
            $item->grade,
            $item->sepatu_kantor,
            $item->sepatu_safety,
            $item->wearpack_cover_all,
            $item->jaket_shift,
            $item->seragam_olahraga,
            $item->jaket_casual,
            $item->seragam_dinas_harian,
            // $item->created_at,
            // $item->updated_at,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 15,  // ID Badge
            'C' => 25,  // Nama Karyawan
            'D' => 20,  // Cost Center
            'E' => 20,  // Unit Kerja
            'F' => 10,  // Grade
            'G' => 15,  // Sepatu Kantor
            'H' => 15,  // Sepatu Safety
            'I' => 20,  // Wearpack Cover All
            'J' => 15,  // Jaket Shift
            'K' => 20,  // Seragam Olahraga
            'L' => 15,  // Jaket Casual
            'M' => 25,  // Seragam Dinas Harian
            'N' => 20,  // Created At
            'O' => 20,  // Updated At
        ];
    }
}
