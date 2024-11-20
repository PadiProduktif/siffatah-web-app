<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterData\MasterDataKaryawanController;
use App\Http\Controllers\BerkasPengobatan\BerkasPengobatanController;
use App\Http\Controllers\Ekses\EksesController;
use App\Http\Controllers\KelengkapanKerja\KelengkapanKerjaController;
use App\Http\Controllers\MasterData\MasterDataNonKaryawanController;
use App\Http\Controllers\PengajuanKlaim\KlaimKecelakaanController;
use App\Http\Controllers\PengajuanKlaim\KlaimKematianController;
use App\Http\Controllers\PengajuanKlaim\KlaimLumpsumKacamataController;
use App\Http\Controllers\PengajuanKlaim\KlaimLumpsumKelahiranController;
use App\Http\Controllers\PengajuanKlaim\KlaimPengobatanController;
use App\Http\Controllers\PengajuanKlaim\KlaimPurnaJabatanController;
use App\Http\Controllers\RestitusiKaryawan\RestitusiKaryawanController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/', function () {
    return view('landing-page');
});
Route::get('/home', function () {
    return view('home');
});

// EXTRAS
Route::get('/master-data-karyawan', function () {
    return view('extras/master-data-karyawan');
});
Route::get('/set-profil', function () {
    return view('extras/set-profil');
});

// FITUR UTAMA
Route::get('/restitusi-karyawan', function () {
    return view('dashboard/restitusi-karyawan');
});
Route::get('/pengajuan-klaim', function () {
    return view('dashboard/pengajuan-klaim');
});
Route::get('/kepesertaan-anggota', function () {
    return view('dashboard/kepesertaan-anggota');
});
Route::get('/ekses', function () {
    return view('dashboard/ekses');
});
Route::get('/berkas-pengobatan', function () {
    return view('dashboard/berkas-pengobatan');
});

Route::get('/wearpack', function () {
    return view('dashboard/wearpack');
});
Route::get('/sepatu', function () {
    return view('dashboard/sepatu');
});



Route::post('/register', [AuthController::class, 'register']);
Route::get('/roles', [AuthController::class, 'get_role']);
Route::post('/action_login', [AuthController::class, 'actionLogin'])->name('actionLogin');
Route::get('/login', [AuthController::class, 'login'])->name('login');

// Route::get('admin/dashboard', [DashboardController::class, 'index']);



// Route::middleware('auth:sanctum')->group(function () {

//     //Admin dashboard
//     Route::get('admin/dashboard', [DashboardController::class, 'index']);
//     //Master Data Karyawan
//     Route::get('/master_data_karyawan', [MasterDataKaryawanController::class, 'index']);
//     Route::post('/master_data_karyawan/tambah', [MasterDataKaryawanController::class, 'store']);
//     Route::get('/master_data_karyawan/edit/{id}', [MasterDataKaryawanController::class, 'edit']);
//     Route::post('/master_data_karyawan/update/{id}', [MasterDataKaryawanController::class, 'update']);
//     Route::get('/master_data_karyawan/delete/{id}', [MasterDataKaryawanController::class, 'destroy']);
 
//     //Master Data Non Karyawan
//     Route::get('/master_data_non_karyawan', [MasterDataNonKaryawanController::class, 'index']);
//     Route::post('/master_data_non_karyawan/tambah', [MasterDataNonKaryawanController::class, 'store']);
//     Route::get('/master_data_non_karyawan/edit/{id}', [MasterDataNonKaryawanController::class, 'edit']);
//     Route::post('/master_data_non_karyawan/update/{id}', [MasterDataNonKaryawanController::class, 'update']);
//     Route::get('/master_data_non_karyawan/delete/{id}', [MasterDataNonKaryawanController::class, 'destroy']);

//     //Berkas Pengobatan
//     Route::get('/berkas_pengobatan', [BerkasPengobatanController::class, 'index']);
//     Route::post('/berkas_pengobatan/tambah', [BerkasPengobatanController::class, 'store']);
//     Route::get('/berkas_pengobatan/edit/{id}', [BerkasPengobatanController::class, 'edit']);
//     Route::post('/berkas_pengobatan/update/{id}', [BerkasPengobatanController::class, 'update']);
//     Route::get('/berkas_pengobatan/delete/{id}', [BerkasPengobatanController::class, 'destroy']);
    
//     //Ekses
//     Route::get('/ekses', [EksesController::class, 'index']);
//     Route::post('/ekses/tambah', [EksesController::class, 'store']);
//     Route::get('/ekses/edit/{id}', [EksesController::class, 'edit']);
//     Route::post('/ekses/update/{id}', [EksesController::class, 'update']);
//     Route::get('/ekses/delete/{id}', [EksesController::class, 'destroy']);
    
//     //Kelengkapan Kerja
//     Route::get('/kelengkapan_kerja', [KelengkapanKerjaController::class, 'index']);
//     Route::post('/kelengkapan_kerja/tambah', [KelengkapanKerjaController::class, 'store']);
//     Route::get('/kelengkapan_kerja/edit/{id}', [KelengkapanKerjaController::class, 'edit']);
//     Route::post('/kelengkapan_kerja/update/{id}', [KelengkapanKerjaController::class, 'update']);
//     Route::get('/kelengkapan_kerja/delete/{id}', [KelengkapanKerjaController::class, 'destroy']);
    
//     //Klaim Kecelakaan
//     Route::get('/klaim_kecelakaan', [KlaimKecelakaanController::class, 'index']);
//     Route::post('/klaim_kecelakaan/tambah', [KlaimKecelakaanController::class, 'store']);
//     Route::get('/klaim_kecelakaan/edit/{id}', [KlaimKecelakaanController::class, 'edit']);
//     Route::post('/klaim_kecelakaan/update/{id}', [KlaimKecelakaanController::class, 'update']);
//     Route::get('/klaim_kecelakaan/delete/{id}', [KlaimKecelakaanController::class, 'destroy']);
    
//     //Klaim Kematian
//     Route::get('/klaim_kematian', [KlaimKematianController::class, 'index']);
//     Route::post('/klaim_kematian/tambah', [KlaimKematianController::class, 'store']);
//     Route::get('/klaim_kematian/edit/{id}', [KlaimKematianController::class, 'edit']);
//     Route::post('/klaim_kematian/update/{id}', [KlaimKematianController::class, 'update']);
//     Route::get('/klaim_kematian/delete/{id}', [KlaimKematianController::class, 'destroy']);
    
//     //Klaim Lumpsum Kacamata
//     Route::get('/klaim_lumpsum_kacamata', [KlaimLumpsumKacamataController::class, 'index']);
//     Route::post('/klaim_lumpsum_kacamata/tambah', [KlaimLumpsumKacamataController::class, 'store']);
//     Route::get('/klaim_lumpsum_kacamata/edit/{id}', [KlaimLumpsumKacamataController::class, 'edit']);
//     Route::post('/klaim_lumpsum_kacamata/update/{id}', [KlaimLumpsumKacamataController::class, 'update']);
//     Route::get('/klaim_lumpsum_kacamata/delete/{id}', [KlaimLumpsumKacamataController::class, 'destroy']);

//     //Klaim Lumpsum Kelahiran
//     Route::get('/klaim_lumpsum_kelahiran', [KlaimLumpsumKelahiranController::class, 'index']);
//     Route::post('/klaim_lumpsum_kelahiran/tambah', [KlaimLumpsumKelahiranController::class, 'store']);
//     Route::get('/klaim_lumpsum_kelahiran/edit/{id}', [KlaimLumpsumKelahiranController::class, 'edit']);
//     Route::post('/klaim_lumpsum_kelahiran/update/{id}', [KlaimLumpsumKelahiranController::class, 'update']);
//     Route::get('/klaim_lumpsum_kelahiran/delete/{id}', [KlaimLumpsumKelahiranController::class, 'destroy']);

//     //Klaim Pengobatan
//     Route::get('/klaim_pengobatan', [KlaimPengobatanController::class, 'index']);
//     Route::post('/klaim_pengobatan/tambah', [KlaimPengobatanController::class, 'store']);
//     Route::get('/klaim_pengobatan/edit/{id}', [KlaimPengobatanController::class, 'edit']);
//     Route::post('/klaim_pengobatan/update/{id}', [KlaimPengobatanController::class, 'update']);
//     Route::get('/klaim_pengobatan/delete/{id}', [KlaimPengobatanController::class, 'destroy']);

//     //Klaim Purnajabatan
//     Route::get('/klaim_purnajabatan', [KlaimPurnaJabatanController::class, 'index']);
//     Route::post('/klaim_purnajabatan/tambah', [KlaimPurnaJabatanController::class, 'store']);
//     Route::get('/klaim_purnajabatan/edit/{id}', [KlaimPurnaJabatanController::class, 'edit']);
//     Route::post('/klaim_purnajabatan/update/{id}', [KlaimPurnaJabatanController::class, 'update']);
//     Route::get('/klaim_purnajabatan/delete/{id}', [KlaimPurnaJabatanController::class, 'destroy']);

//     //Klaim Restitusi Karyawan / Pengajuana Reimburse
//     Route::get('/restitusi_karyawan', [RestitusiKaryawanController::class, 'index']);
//     Route::post('/restitusi_karyawan/tambah', [RestitusiKaryawanController::class, 'store']);
//     Route::get('/restitusi_karyawan/edit/{id}', [RestitusiKaryawanController::class, 'edit']);
//     Route::post('/restitusi_karyawan/update/{id}', [RestitusiKaryawanController::class, 'update']);
//     Route::get('/restitusi_karyawan/delete/{id}', [RestitusiKaryawanController::class, 'destroy']);
//     //Logout
//     Route::post('/logout', [AuthController::class, 'logout']);
// });