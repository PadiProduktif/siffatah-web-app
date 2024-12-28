<?php

namespace App\Models\BerkasPengobatan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasPengobatan extends Model
{
    use HasFactory;

    // Tentukan tabel yang digunakan
    protected $table = 'table_berkas_pengobatan';

    // Tentukan primary key
    protected $primaryKey = 'id_berkas_pengobatan';

    // Jika tidak menggunakan created_at & updated_at
    public $timestamps = false;

    // Kolom yang bisa diisi (whitelist)
    protected $fillable = [
        // 'id_berkas_pengobatan',
        'id_badge',
        'nama_karyawan',
        'jabatan_karyawan',
        'nama_anggota_keluarga',
        'hubungan_keluarga',
        'deskripsi',
        'nominal',
        'rs_klinik',
        'urgensi',
        'no_surat_rs',
        'tanggal_pengobatan',
        'status',
        'keterangan',
        'filename',
        'file_url',
        
        'updated_at',
        'updated_by',
        'created_at',
        'created_by'
    ];
}
