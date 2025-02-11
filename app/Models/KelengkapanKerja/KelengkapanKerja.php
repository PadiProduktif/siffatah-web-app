<?php  
  
namespace App\Models\KelengkapanKerja;  
  
use Illuminate\Database\Eloquent\Factories\HasFactory;  
use Illuminate\Database\Eloquent\Model;  
  
class KelengkapanKerja extends Model  
{  
    use HasFactory;  
  
    protected $table = 'table_kelengkapan_kerja';  
    protected $primaryKey = 'id_kelengkapan_kerja';  
  
    // Menggunakan fillable untuk mengizinkan mass assignment  
    protected $fillable = [  
        'id_badge',  
        'nama_karyawan',  
        'cost_center',  
        'unit_kerja',  
        'grade',  
        'sepatu_kantor',  
        'sepatu_safety',  
        'wearpack_cover_all',  
        'jaket_shift',  
        'seragam_olahraga',  
        'jaket_casual',  
        'seragam_dinas_harian',  
        
        'created_at',  
        'updated_at',  
    ];  
  
    // Jika id_kelengkapan_kerja bukan auto-increment, tambahkan ini  
    public $incrementing = true;  
  
    // Jika primary key bukan integer, tambahkan ini  
    protected $keyType = 'int'; // atau 'string' jika menggunakan string  
  
    // Jika ada timestamp, aktifkan ini  
    public $timestamps = true; // jika tabel tidak memiliki created_at dan updated_at, set false  
}  
