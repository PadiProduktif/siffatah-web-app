<?php

namespace App\Models\BerkasPengobatan;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BerkasPengobatan extends Model
{
    use HasFactory;

    protected $table = 'table_berkas_pengobatan';
    protected $primaryKey = 'id_berkas_pengobatan';
    protected $fillable = ['id_berkas_pengobatan','id_badge','nama_karyawan','jabatan_karyawan','nama_anggota_keluarga','hubungan_keluarga','deskripsi','nominal','rs_klinik','urgensi','no_surat_rs','tanggal_pengobatan','status','keterangan','filename','file_url'];

}