<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterData\MasterDataKaryawanController;
use App\Http\Controllers\BerkasPengobatan\BerkasPengobatanController;
use App\Http\Controllers\Ekses\EksesController;
use App\Http\Controllers\KelengkapanKerja\KelengkapanKerjaController;
use App\Http\Controllers\MasterData\MasterDataNonKaryawanController;
use App\Http\Controllers\PengajuanKlaim\KlaimKecelakaanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');


Route::middleware('auth:sanctum')->group(function () {

    //Master Data Karyawan
    Route::get('/master_data_karyawan', [MasterDataKaryawanController::class, 'index']);
    Route::get('/master_data_karyawan/tambah', [MasterDataKaryawanController::class, 'store']);
    Route::get('/master_data_karyawan/edit/{id}', [MasterDataKaryawanController::class, 'edit']);
    Route::post('/master_data_karyawan/update/{id}', [MasterDataKaryawanController::class, 'update']);
    Route::get('/master_data_karyawan/delete/{id}', [MasterDataKaryawanController::class, 'destroy']);
 
    //Master Data Non Karyawan
    Route::get('/master_data_non_karyawan', [MasterDataNonKaryawanController::class, 'index']);
    Route::get('/master_data_non_karyawan/tambah', [MasterDataNonKaryawanController::class, 'store']);
    Route::get('/master_data_non_karyawan/edit/{id}', [MasterDataNonKaryawanController::class, 'edit']);
    Route::post('/master_data_non_karyawan/update/{id}', [MasterDataNonKaryawanController::class, 'update']);
    Route::get('/master_data_non_karyawan/delete/{id}', [MasterDataNonKaryawanController::class, 'destroy']);

    //Berkas Pengobatan
    Route::get('/berkas_pengobatan', [BerkasPengobatanController::class, 'index']);
    Route::get('/berkas_pengobatan/tambah', [BerkasPengobatanController::class, 'store']);
    Route::get('/berkas_pengobatan/edit/{id}', [BerkasPengobatanController::class, 'edit']);
    Route::post('/berkas_pengobatan/update/{id}', [BerkasPengobatanController::class, 'update']);
    Route::get('/berkas_pengobatan/delete/{id}', [BerkasPengobatanController::class, 'destroy']);
    
    //Ekses
    Route::get('/ekses', [EksesController::class, 'index']);
    Route::get('/ekses/tambah', [EksesController::class, 'store']);
    Route::get('/ekses/edit/{id}', [EksesController::class, 'edit']);
    Route::post('/ekses/update/{id}', [EksesController::class, 'update']);
    Route::get('/ekses/delete/{id}', [EksesController::class, 'destroy']);
    
    //Kelengkapan Kerja
    Route::get('/kelengkapan_kerja', [KelengkapanKerjaController::class, 'index']);
    Route::get('/kelengkapan_kerja/tambah', [KelengkapanKerjaController::class, 'store']);
    Route::get('/kelengkapan_kerja/edit/{id}', [KelengkapanKerjaController::class, 'edit']);
    Route::post('/kelengkapan_kerja/update/{id}', [KelengkapanKerjaController::class, 'update']);
    Route::get('/kelengkapan_kerja/delete/{id}', [KelengkapanKerjaController::class, 'destroy']);
    
    //Kelengkapan Kerja
    Route::get('/klaim_kecelakaan', [KlaimKecelakaanController::class, 'index']);
    Route::get('/klaim_kecelakaan/tambah', [KlaimKecelakaanController::class, 'store']);
    Route::get('/klaim_kecelakaan/edit/{id}', [KlaimKecelakaanController::class, 'edit']);
    Route::post('/klaim_kecelakaan/update/{id}', [KlaimKecelakaanController::class, 'update']);
    Route::get('/klaim_kecelakaan/delete/{id}', [KlaimKecelakaanController::class, 'destroy']);

    
    //Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});