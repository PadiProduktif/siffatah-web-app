<?php

namespace App\Models\Ekses;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ekses extends Model
{
    use HasFactory;

    protected $table = 'ekses';
    protected $primaryKey = 'id_ekses';
    // Menghapus duplikasi dan memastikan hanya field yang diperlukan
    protected $fillable = [
        'id_member',
        'id_badge',
        'nama_karyawan',
        'unit_kerja',
        'nama_pasien',
        'deskripsi',
        'tanggal_pengajuan',
        'jumlah_ekses',
        'filename',
        'file_url'
    ];
}
