<?php

namespace App\Models\KelengkapanKerja;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelengkapanKerja extends Model
{
    use HasFactory;
    protected $table = 'table_kelengkapan_kerja';
    protected $primaryKey = 'id_kelengkapan_kerja';
    protected $fillable = ['id_kelengkapan_kerja','id_badge','nama_karyawan','cost_center','jabatan_karyawan','grade','jenis_pakaian','jenis_kelengkapan_kantor','tahun','warna','gender','ukuran'];
}
