<?php

namespace App\Models\PesertaBPJS;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PesertaBPJSKetenagakerjaan extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'table_peserta_bpjs_ketenagakerjaan';

    // Primary key
    protected $primaryKey = 'id_peserta_bpjs_ketenagakerjaan';

    // Kolom yang dapat diisi
    protected $fillable = [
        'id_peserta_bpjs_ketenagakerjaan',
        'id_badge',
        'nik',
        'tgl_lahir',
        'alamat',
        'nama_karyawan',
        'no_bpjs',
        'file_url',
    ];
}
