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

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::get('/', function () {
    return view('landing-page');
});

// Route::post('/register', [AuthController:    :class, 'register']);
Route::get('/roles', [AuthController::class, 'get_role']);
Route::post('/action_login', [AuthController::class, 'actionLogin']);
// Route::post('/action_login', [AuthController::class, 'actionLogin'])->name('actionLogin');
Route::get('/login', [AuthController::class, 'login'])->name('login');


Route::middleware('auth:sanctum')->group(function () {

    //Admin dashboard
    Route::get('admin/dashboard', [DashboardController::class, 'index']);
    //Master Data Karyawan
    Route::get('/admin/master_data_karyawan', [MasterDataKaryawanController::class, 'index']);
    Route::post('/admin/master_data_karyawan/tambah', [MasterDataKaryawanController::class, 'store']);
    Route::get('/admin/master_data_karyawan/detail/{id}', [MasterDataKaryawanController::class, 'detail']);
    Route::get('/admin/master_data_karyawan/edit/{id}', [MasterDataKaryawanController::class, 'edit']);
    Route::post('/admin/master_data_karyawan/update/{id}', [MasterDataKaryawanController::class, 'update']);
    Route::delete('/admin/master_data_karyawan/delete/{id}', [MasterDataKaryawanController::class, 'destroy']);
 
    //Master Data Non Karyawan
    Route::get('/admin/master_data_non_karyawan', [MasterDataNonKaryawanController::class, 'index']);
    Route::post('/admin/master_data_non_karyawan/tambah', [MasterDataNonKaryawanController::class, 'store']);
    Route::get('/admin/master_data_non_karyawan/edit/{id}', [MasterDataNonKaryawanController::class, 'edit']);
    Route::post('/admin/master_data_non_karyawan/update/{id}', [MasterDataNonKaryawanController::class, 'update']);
    Route::get('/admin/master_data_non_karyawan/delete/{id}', [MasterDataNonKaryawanController::class, 'destroy']);

    //Berkas Pengobatan
    Route::get('/admin/berkas_pengobatan', [BerkasPengobatanController::class, 'index']);
    Route::post('/admin/berkas_pengobatan/tambah', [BerkasPengobatanController::class, 'store']);
    Route::get('/admin/berkas_pengobatan/edit/{id}', [BerkasPengobatanController::class, 'edit']);
    Route::post('/admin/berkas_pengobatan/update/{id}', [BerkasPengobatanController::class, 'update']);
    Route::get('/admin/berkas_pengobatan/delete/{id}', [BerkasPengobatanController::class, 'destroy']);
    
    //Ekses
    Route::get('/admin/ekses', [EksesController::class, 'index']);
    Route::post('/admin/ekses/tambah', [EksesController::class, 'store']);
    Route::get('/admin/ekses/edit/{id}', [EksesController::class, 'edit']);
    Route::post('/admin/ekses/update/{id}', [EksesController::class, 'update']);
    Route::get('/admin/ekses/delete/{id}', [EksesController::class, 'destroy']);
    
    //Kelengkapan Kerja
    Route::get('/admin/kelengkapan_kerja/', [KelengkapanKerjaController::class, 'index']);
    Route::post('/admin/kelengkapan_kerja/wearpack/tambah', [KelengkapanKerjaController::class, 'store']);
    Route::get('/admin/kelengkapan_kerja/wearpack/edit/{id}', [KelengkapanKerjaController::class, 'edit']);
    Route::post('/admin/kelengkapan_kerja/wearpack/update/{id}', [KelengkapanKerjaController::class, 'update']);
    Route::get('/admin/kelengkapan_kerja/wearpack/delete/{id}', [KelengkapanKerjaController::class, 'destroy']);
    Route::post('/admin/kelengkapan_kerja/upload', [KelengkapanKerjaController::class, 'uploadExcel'])->name('kelengkapan-kerja.upload');
    //Kelengkapan Kerja Wearpack
    Route::get('/admin/kelengkapan_kerja/wearpack', [KelengkapanKerjaController::class, 'index_wearpack']);
    Route::post('/admin/kelengkapan_kerja/wearpack/tambah', [KelengkapanKerjaController::class, 'store']);
    Route::get('/admin/kelengkapan_kerja/wearpack/edit/{id}', [KelengkapanKerjaController::class, 'edit']);
    Route::post('/admin/kelengkapan_kerja/wearpack/update/{id}', [KelengkapanKerjaController::class, 'update']);
    Route::get('/admin/kelengkapan_kerja/wearpack/delete/{id}', [KelengkapanKerjaController::class, 'destroy']);

    //Kelengkapan Kerja Sepatu
    Route::get('/admin/kelengkapan_kerja/sepatu', [KelengkapanKerjaController::class, 'index_sepatu']);
    Route::post('/admin/kelengkapan_kerja/sepatu/tambah', [KelengkapanKerjaController::class, 'store']);
    Route::get('/admin/kelengkapan_kerja/sepatu/edit/{id}', [KelengkapanKerjaController::class, 'edit']);
    Route::post('/admin/kelengkapan_kerja/sepatu/update/{id}', [KelengkapanKerjaController::class, 'update']);
    Route::get('/admin/kelengkapan_kerja/sepatu/delete/{id}', [KelengkapanKerjaController::class, 'destroy']);
    
    //Klaim Kecelakaan
    Route::get('/admin/klaim_kecelakaan', [KlaimKecelakaanController::class, 'index']);
    Route::post('/admin/klaim_kecelakaan/tambah', [KlaimKecelakaanController::class, 'store']);
    Route::get('/admin/klaim_kecelakaan/edit/{id}', [KlaimKecelakaanController::class, 'edit']);
    Route::post('/admin/klaim_kecelakaan/update/{id}', [KlaimKecelakaanController::class, 'update']);
    Route::get('/admin/klaim_kecelakaan/delete/{id}', [KlaimKecelakaanController::class, 'destroy']);
    
    //Klaim Kematian
    Route::get('/admin/klaim_kematian', [KlaimKematianController::class, 'index']);
    Route::post('/admin/klaim_kematian/tambah', [KlaimKematianController::class, 'store']);
    Route::get('/admin/klaim_kematian/edit/{id}', [KlaimKematianController::class, 'edit']);
    Route::post('/admin/klaim_kematian/update/{id}', [KlaimKematianController::class, 'update']);
    Route::get('/admin/klaim_kematian/delete/{id}', [KlaimKematianController::class, 'destroy']);
    
    //Klaim Lumpsum Kacamata
    Route::get('/admin/klaim_lumpsum_kacamata', [KlaimLumpsumKacamataController::class, 'index']);
    Route::post('/admin/klaim_lumpsum_kacamata/tambah', [KlaimLumpsumKacamataController::class, 'store']);
    Route::get('/admin/klaim_lumpsum_kacamata/edit/{id}', [KlaimLumpsumKacamataController::class, 'edit']);
    Route::post('/admin/klaim_lumpsum_kacamata/update/{id}', [KlaimLumpsumKacamataController::class, 'update']);
    Route::get('/admin/klaim_lumpsum_kacamata/delete/{id}', [KlaimLumpsumKacamataController::class, 'destroy']);

    //Klaim Lumpsum Kelahiran
    Route::get('/admin/klaim_lumpsum_kelahiran', [KlaimLumpsumKelahiranController::class, 'index']);
    Route::post('/admin/klaim_lumpsum_kelahiran/tambah', [KlaimLumpsumKelahiranController::class, 'store']);
    Route::get('/admin/klaim_lumpsum_kelahiran/edit/{id}', [KlaimLumpsumKelahiranController::class, 'edit']);
    Route::post('/admin/klaim_lumpsum_kelahiran/update/{id}', [KlaimLumpsumKelahiranController::class, 'update']);
    Route::get('/admin/klaim_lumpsum_kelahiran/delete/{id}', [KlaimLumpsumKelahiranController::class, 'destroy']);

    //Klaim Pengobatan
    Route::get('/admin/klaim_pengobatan', [KlaimPengobatanController::class, 'index']);
    Route::post('/admin/klaim_pengobatan/tambah', [KlaimPengobatanController::class, 'store']);
    Route::get('/admin/klaim_pengobatan/edit/{id}', [KlaimPengobatanController::class, 'edit']);
    Route::post('/admin/klaim_pengobatan/update/{id}', [KlaimPengobatanController::class, 'update']);
    Route::get('/admin/klaim_pengobatan/delete/{id}', [KlaimPengobatanController::class, 'destroy']);

    //Klaim Purnajabatan
    Route::get('/admin/klaim_purnajabatan', [KlaimPurnaJabatanController::class, 'index']);
    Route::post('/admin/klaim_purnajabatan/tambah', [KlaimPurnaJabatanController::class, 'store']);
    Route::get('/admin/klaim_purnajabatan/edit/{id}', [KlaimPurnaJabatanController::class, 'edit']);
    Route::post('/admin/klaim_purnajabatan/update/{id}', [KlaimPurnaJabatanController::class, 'update']);
    Route::get('/admin/klaim_purnajabatan/delete/{id}', [KlaimPurnaJabatanController::class, 'destroy']);

    //Klaim Restitusi Karyawan / Pengajuana Reimburse
    Route::get('/admin/restitusi_karyawan', [RestitusiKaryawanController::class, 'index']);
    Route::post('/admin/restitusi_karyawan/tambah', [RestitusiKaryawanController::class, 'store']);
    Route::get('/admin/restitusi_karyawan/edit/{id}', [RestitusiKaryawanController::class, 'edit']);
    Route::post('/admin/restitusi_karyawan/update/{id}', [RestitusiKaryawanController::class, 'update']);
    Route::get('/admin/restitusi_karyawan/delete/{id}', [RestitusiKaryawanController::class, 'destroy']);
    //Logout
    Route::get('/logout', [AuthController::class, 'logout']);
});


// Route::get('admin/dashboard', [DashboardController::class, 'index']);

// Route::middleware('auth:sanctum')->group(function () {
//     //Home Dashboard admin
//     // Admin dashboard
//     Route::get('admin/dashboard', [DashboardController::class, 'index']);
//     // Route::get('/home', function () {
//     //     return view('home');
//     // });
//     // FITUR UTAMA
//     Route::get('/restitusi-karyawan', function () {
//         return view('dashboard/restitusi-karyawan');
//     });
//     Route::get('/pengajuan-klaim', function () {
//         return view('dashboard/pengajuan-klaim');
//     });
//     Route::get('/kepesertaan-anggota', function () {
//         return view('dashboard/kepesertaan-anggota');
//     });
//     Route::get('/ekses', function () {
//         return view('dashboard/ekses');
//     });
//     Route::get('/berkas-pengobatan', function () {
//         return view('dashboard/berkas-pengobatan');
//     });

//     Route::get('/wearpack', function () {
//         return view('dashboard/wearpack');
//     });
//     Route::get('/sepatu', function () {
//         return view('dashboard/sepatu');
//     });
//     Route::get('/logout', [AuthController::class, 'logout']);

//     // EXTRAS
//     Route::get('/master-data-karyawan', function () {
//         return view('extras/master-data-karyawan');
//     });
    Route::get('set-profil', function () {
        return view('extras/set-profil');
    });
// });
