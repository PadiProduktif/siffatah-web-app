<?php

namespace App\Models\Ekses;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekses extends Model
{
    use HasFactory;

    protected $table = 'ekses';
    protected $primaryKey = 'id_ekses';
    protected $fillable = ['id_ekses','id_member','id_badge','nama_karyawan','unit_kerja','nama_karyawan','unit_kerja','nama_pasien','deskripsi','tanggal_pengajuan','jumlah_ekses','filename','file_url'];
}
