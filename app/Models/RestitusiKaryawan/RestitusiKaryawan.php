<?php

namespace App\Models\RestitusiKaryawan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestitusiKaryawan extends Model
{
    use HasFactory;
    protected $table = 'table_pengajuan_reimburse';
    protected $primaryKey = 'id_pengajuan';
    protected $fillable = [
        'id_badge',
        'id_pengajuan',
        'nama_karyawan',
        'jabatan_karyawan',
        'nama_anggota_keluarga',
        'hubungan_karyawan',
        'deskripsi',
        'nominal',
        'rumah_sakit',
        'urgensi',
        'no_surat_rs',
        'tanggal_pengobatan',
        'keterangan_pengajuan',
        'filename',
        'url_file',
        'url_file_dr',
        'status_pengajuan',
        'reject_notes',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
