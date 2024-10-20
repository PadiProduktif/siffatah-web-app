<?php

namespace App\Models\MasterData;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataNonKaryawan extends Model
{
    use HasFactory;

    protected $table = 'table_non_karyawan';
    protected $primaryKey = 'id_non_karyawan';
    protected $fillable = [
        'id_non_karyawan',
        'nama',
        'jenis_kelamin',
        'hubungan_keluarga',
        'tempat_lahir',
        'tanggal_lahir',
        'pendidikan',
        'alamat',
        'agama',
        'status_pernikahan',
        'pekerjaan',
        'nik',
        'kewarganegaraan',
        'url_foto_diri',
        'id_karyawan_terkait'
    ];
}
