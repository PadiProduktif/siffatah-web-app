<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterData\MasterDataKaryawanController;
use App\Http\Controllers\BerkasPengobatan\BerkasPengobatanController;
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
    //Berkas Pengobatan
    Route::get('/berkas_pengobatan', [BerkasPengobatanController::class, 'index']);
    Route::get('/berkas_pengobatan/tambah', [BerkasPengobatanController::class, 'store']);
    Route::get('/berkas_pengobatan/edit/{id}', [BerkasPengobatanController::class, 'edit']);
    Route::post('/berkas_pengobatan/update/{id}', [BerkasPengobatanController::class, 'update']);
    Route::get('/berkas_pengobatan/delete/{id}', [BerkasPengobatanController::class, 'destroy']);
    //Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});