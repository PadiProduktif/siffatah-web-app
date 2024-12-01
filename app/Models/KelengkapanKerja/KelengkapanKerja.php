<?php

namespace App\Models\KelengkapanKerja;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelengkapanKerja extends Model
{
    use HasFactory;
    protected $table = 'table_kelengkapan_kerja';
    protected $primaryKey = 'id_kelengkapan_kerja';
    protected $fillable = [
        'id_kelengkapan_kerja',
        'id_badge',
        'nama_karyawan',
        'cost_center',
        'jabatan_karyawan',
        'grade',
        'sepatu_kantor',
        'sepatu_safety',
        'wearpack_cover_all',
        'jaket_shift',
        'seragam_olahraga',
        'jaket_casual',
        'seragam_dinas_harian',
    ];
}
