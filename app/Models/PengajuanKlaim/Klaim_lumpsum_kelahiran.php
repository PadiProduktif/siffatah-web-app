<?php

namespace App\Models\PengajuanKlaim;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class klaim_lumpsum_kelahiran extends Model
{
    use HasFactory;
    protected $table = 'table_lumpsum_kelahiran';
    protected $primaryKey = 'id_lumpsum_kelahiran';
    protected $fillable = [
        'id_lumpsum_kelahiran',
        'id_badge',
        'nama_karyawan',
        'unit_kerja',
        'nama_pasangan',
        'anak_ke',
        'rumah_sakit',
        'tanggal_pengajuan',
        'tanggal_approve',
        'nominal',
        'deskripsi',
        'filename',
        'file_url'

    ];
}
