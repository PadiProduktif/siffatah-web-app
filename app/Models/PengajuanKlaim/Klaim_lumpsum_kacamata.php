<?php

namespace App\Models\PengajuanKlaim;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class klaim_lumpsum_kacamata extends Model
{
    use HasFactory;
    protected $table = 'table_lumpsum_kacamata';
    protected $primaryKey = 'id_lumpsum_kacamata';
    protected $fillable = [
        'id_lumpsum_kacamata',
        'id_badge',
        'nama_karyawan',
        'unit_kerja',
        'rs_klinik',
        'deskripsi',
        'nama_pasien',
        'hubungan',
        'tanggal_pengajuan',
        'nominal',
        'filename',
        'file_url',
        
    ];
}
