<?php

namespace App\Models\PengajuanKlaim;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class klaim_kematian extends Model
{
    use HasFactory;
    protected $table = 'table_klaim_kematian';
    protected $primaryKey = 'id_klaim_kematian';
    protected $fillable = [
        'id_klaim_kematian',
        'id_badge',
        'nama_karyawan',
        'unit_kerja',
        'nama_asuransi',
        'rs_klinik',
        'tanggal_wafat',
        'nama_keluarga',
        'hubungan_keluarga',
        'no_polis',
        'filename',
        'file_url'
    ];
}
