<?php

namespace App\Models\PesertaBPJS;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesertaBPJSKesehatan extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'table_peserta_bpjs_kesehatan';

    // Primary key
    protected $primaryKey = 'id_peserta_bpjs';

    // Kolom yang dapat diisi
    protected $fillable = [
        'id_peserta_bpjs',
        'id_badge',
        'nik',
        'tgl_lahir',
        'faskes_tingkat_1',
        'kelas_rawat',
        'alamat',
        'nama_karyawan',
        'hubungan_keluarga',
        'no_bpjs',
        'file_url',
    ];
}
