<?php

namespace App\Models\PengajuanKlaim;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class klaim_pengobatan extends Model
{
    use HasFactory;
    protected $table = 'table_klaim_pengobatan';
    protected $primaryKey = 'id_klaim_pengobatan';
    protected $fillable = [
        'id_klaim_pengobatan',
        'id_badge',
        'nama_karyawan',
        'unit_kerja',
        'nama_asuransi',
        'rs_klinik',
        'tanggal_pengajuan',
        'nominal',
        'deskripsi',
        'filename',
        'file_url'
    ];
}
