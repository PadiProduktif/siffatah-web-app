<?php

namespace App\Models\RestitusiKaryawan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestitusiKaryawan extends Model
{
    use HasFactory;
    protected $table = 'table_pengajuan_reimburse';
    protected $primaryKey = 'id_pengajuan';
    protected $fillable = ['id_pengajuan','id_badge','nama_karyawan','jabatan_karyawan','nama_anggota_keluarga','hubungan_karyawan','deskripsi','nominal','rumah_sakit','urgensi','no_surat_rs','tanggal_pengobatan','keterangan_pengajuan','filename','url_file','status_pengajuan'];
}
