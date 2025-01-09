<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagihanRumahSakitModel extends Model
{
    // Tentukan tabel yang digunakan
    protected $table = 'table_tagihan_rumah_sakit';

    // Tentukan primary key
    protected $primaryKey = 'id_tagihan';

    // Jika tidak menggunakan created_at & updated_at
    public $timestamps = false;

    // Kolom yang bisa diisi (whitelist)
    protected $fillable = [
        'deskripsi',
        'no_surat',
        'keterangan',
        'rs_klinik',
        'tanggal_invoice',
        'status',
        'urgensi',

        'updated_at',
        'updated_by',
        'created_at',
        'created_by'
    ];
}
