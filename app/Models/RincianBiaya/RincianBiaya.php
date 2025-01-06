<?php

namespace App\Models\RincianBiaya;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class RincianBiaya extends Model
{
    use HasFactory;
    protected $table = 'table_rincian_biaya';
    protected $primaryKey = 'id_rincian_biaya';
    protected $fillable = [
        'id_rincian_biaya',
        'id_badge',
        'kategori',
        'id_kategori',
        'rumah_sakit',
        'no_surat_rs',
        'deskripsi_biaya',
        'nominal_pengajuan',
        'nominal_dokter',
        'nominal_akhir',
        'status_rincian_biaya',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
