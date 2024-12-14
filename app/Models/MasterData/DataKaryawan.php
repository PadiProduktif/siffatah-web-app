<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataKaryawan extends Model
{
    use HasFactory;

    protected $table = 'table_karyawan'; // Pastikan nama tabel benar
    protected $primaryKey = 'id_karyawan'; // Pastikan primary key ada di tabel
    protected $fillable = [
        'id_badge',
        'nama_karyawan',
        'gelar_depan',
        'nama_lengkap',
        'gelar_belakang',
        'pendidikan',
        'alamat',
        'agama',
        'status_pernikahan',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        // 'keluarga',
        // 'url_foto_diri',
        // 'url_file_ktp',
        // 'url_file_kk',
        // 'url_file_buku_nikah',
        // 'url_file_akta_kelahiran',
        // 'url_npwp',
        // 'url_lamaran_pekerjaan',
        
        'cost_center',
        'files',
        'updated_at',
        'updated_by',
        'created_at',
        'created_by',
    ];
}
