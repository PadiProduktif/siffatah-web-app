<?php

namespace App\Models\PengajuanKlaim;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class klaim_kecelakaan extends Model
{
    use HasFactory;

    protected $table = 'table_klaim_kecelakaan';
    protected $primaryKey = 'id_klaim_kecelakaan';
    protected $fillable = [
        'id_badge',
        'nama_karyawan',
        'unit_kerja',
        'nama_asuransi',
        'rs_klinik',
        'tanggal_kejadian',
        'nama_keluarga',
        'hubungan_keluarga',
        'deskripsi',
        'filename',
        'file_url'
    ];

}
