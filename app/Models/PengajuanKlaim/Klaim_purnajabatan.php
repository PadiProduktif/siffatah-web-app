<?php

namespace App\Models\PengajuanKlaim;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class klaim_purnajabatan extends Model
{
    use HasFactory;
    protected $table = 'table_klaim_purnajabatan';
    protected $primaryKey = 'id_klaim_purnajabatan';
    protected $fillable = [
        'id_klaim_purnajabatan',
        'nama',
        'jabatan',
        'tanggal_lahir',
        'mulai_asuransi',
        'akhir_asuransi',
        'nama_asuransi',
        'no_polis',
        'premi_tahunan',
        'uang_tertanggung',
        'deskripsi',
        'filename',
        'file_url'
        
    ];
}
