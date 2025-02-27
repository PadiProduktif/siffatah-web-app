<?php


use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterData\MasterDataKaryawanController;
use App\Http\Controllers\BerkasPengobatan\BerkasPengobatanController;
use App\Http\Controllers\Ekses\EksesController;
use App\Http\Controllers\KelengkapanKerja\KelengkapanKerjaController;
use App\Http\Controllers\MasterData\MasterDataNonKaryawanController;
use App\Http\Controllers\MasterData\MasterDataCostCenter;
use App\Http\Controllers\PengajuanKlaim\KlaimKecelakaanController;
use App\Http\Controllers\PengajuanKlaim\KlaimKematianController;
use App\Http\Controllers\PengajuanKlaim\KlaimLumpsumKacamataController;
use App\Http\Controllers\PengajuanKlaim\KlaimLumpsumKelahiranController;
use App\Http\Controllers\PengajuanKlaim\KlaimPengobatanController;
use App\Http\Controllers\PengajuanKlaim\KlaimPurnaJabatanController;
use App\Http\Controllers\RestitusiKaryawan\RestitusiKaryawanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PesertaBPJS\PesertaBPJSKesehatanController;
use App\Http\Controllers\PesertaBPJS\PesertaBPJSKetenagakerjaanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Jika user sudah login
    if (Auth::check()) {
        // Logout user dan hapus session
        Auth::logout();
        Session::flush();

        // Opsional: Kirim pesan flash (jika diperlukan)
        Session::flash('info', 'Anda telah logout untuk mengakses halaman ini.');
    }

    // Tampilkan halaman landing-page
    return view('landing-page');
});


// Route::post('/register', [AuthController:    :class, 'register']);
Route::get('/roles', [AuthController::class, 'get_role']);
Route::post('/action_login', [AuthController::class, 'actionLogin']);
// Route::post('/action_login', [AuthController::class, 'actionLogin'])->name('actionLogin');
Route::get('/login', [AuthController::class, 'login'])->name('login');


Route::middleware('auth:sanctum')->group(function () {

    // tko viewer
    Route::get('generate-code', [AuthController::class, 'generate']);
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::get('ekses', [EksesController::class, 'index']);
    Route::get('berkas-pengobatan', [BerkasPengobatanController::class, 'index']);
    Route::get('restitusi_karyawan', [RestitusiKaryawanController::class, 'index']);
    Route::post('restitusi_karyawan/tambah', [RestitusiKaryawanController::class, 'store']);
    Route::post('restitusi_karyawan/update/{id}', [RestitusiKaryawanController::class, 'update']);
    Route::delete('restitusi_karyawan/delete/{id}', [RestitusiKaryawanController::class, 'destroy']);

    
    Route::get('/klaim-pengobatan', [KlaimPengobatanController::class, 'index']);
    Route::get('/klaim-kecelakaan', [KlaimKecelakaanController::class, 'index']);
    Route::get('/klaim-kematian', [KlaimKematianController::class, 'index']);
    Route::get('/klaim-purna-jabatan', [KlaimPurnaJabatanController::class, 'index']);
    Route::get('/klaim-lumpsum-kacamata', [KlaimLumpsumKacamataController::class, 'index']);
    Route::get('/klaim-lumpsum-lahiran', [KlaimLumpsumKelahiranController::class, 'index']);





    //Admin dashboard
    Route::get('admin/dashboard', [DashboardController::class, 'index']);
    //Master Data Karyawan
    Route::get('/admin/master_data_karyawan', [MasterDataKaryawanController::class, 'index']);
    Route::post('/admin/master_data_karyawan/tambah', [MasterDataKaryawanController::class, 'store']);
    Route::get('/admin/master_data_karyawan/detail/{id}', [MasterDataKaryawanController::class, 'detail'])->name('karyawan.detail');
    Route::get('/admin/master_data_karyawan/edit/{id}', [MasterDataKaryawanController::class, 'edit']);
    Route::post('/admin/master_data_karyawan/update/{id}', [MasterDataKaryawanController::class, 'update']);
    Route::delete('/admin/master_data_karyawan/delete/{id}', [MasterDataKaryawanController::class, 'destroy']);
    Route::post('/admin/master_data_karyawan/upload', [MasterDataKaryawanController::class, 'uploadExcel'])->name('master-data-karyawan.upload');
    Route::post('/master_data_karyawan/upload-temp', [MasterDataKaryawanController::class, 'uploadTemp'])->name('master_data_Karyawan_upload.temp');
    Route::post('/master_data_karyawan/delete-temp', [MasterDataKaryawanController::class, 'deleteTemp'])->name('master_data_Karyawan_delete.temp');
    Route::post('/admin/master_data_karyawan/detail/update_berkas/{id}', [MasterDataKaryawanController::class, 'updateBerkas'])->name('master_data_karyawan.update_berkas');
    
    Route::post('/admin/master_data_karyawan/reset-password/{id}', [AuthController::class, 'resetPassword'])->name('karyawan.resetPassword');

    
    Route::post('/admin/master_data_karyawan/update-kelengkapan-kerja/{id}', [MasterDataKaryawanController::class, 'updateKelengkapanKerja'])->name('karyawan.update.kelengkapan');  

    
    //Master Data Non Karyawan
    Route::get('/admin/master_data_non_karyawan', [MasterDataNonKaryawanController::class, 'index']);
    Route::post('/admin/master_data_non_karyawan/tambah', [MasterDataNonKaryawanController::class, 'store']);
    Route::get('/admin/master_data_non_karyawan/edit/{id}', [MasterDataNonKaryawanController::class, 'edit']);
    Route::post('/admin/master_data_non_karyawan/update/{id}', [MasterDataNonKaryawanController::class, 'update']);
    Route::get('/admin/master_data_non_karyawan/delete/{id}', [MasterDataNonKaryawanController::class, 'destroy']);


    //Master Data Cost Center
    Route::get('/admin/master_data_cost_center', [MasterDataCostCenter::class,'index']);
    Route::post('/admin/master_data_cost_center/tambah', [MasterDataCostCenter::class, 'store'])->name('cost_center.store');
    Route::post('/admin/master_data_cost_center/update/{id}', [MasterDataCostCenter::class, 'update']);
    Route::post('/admin/master_data_cost_center/upload', [MasterDataCostCenter::class, 'uploadExcel'])->name('cost_center.upload');
    Route::get('/admin/master_data_cost_center/delete/{id}', [MasterDataCostCenter::class, 'destroy']);
    Route::post('/admin/master_data_cost_center/delete-multiple', [MasterDataCostCenter::class, 'deleteMultiple'])->name('cost_center.delete-multiple');
    
    //Berkas Pengobatan
    Route::get('/admin/berkas-pengobatan', [BerkasPengobatanController::class, 'index'])->name('berkas-pengobatan.index');
    Route::post('/admin/berkas-pengobatan/tambah', [BerkasPengobatanController::class, 'store'])->name('berkas-pengobatan.store');
    Route::get('/admin/berkas-pengobatan/edit/{id}', [BerkasPengobatanController::class, 'edit'])->name('berkas-pengobatan.edit');
    Route::post('/admin/berkas-pengobatan/update/{id}', [BerkasPengobatanController::class, 'update'])->name('berkas-pengobatan.update');
    Route::delete('/admin/berkas-pengobatan/delete/{id}', [BerkasPengobatanController::class, 'destroy'])->name('berkas-pengobatan.destroy');
    Route::post('/berkas-pengobatan/upload-temp', [BerkasPengobatanController::class, 'uploadTemp'])->name('berkas-pengobatan-upload.temp');
    Route::post('/berkas-pengobatan/delete-temp', [BerkasPengobatanController::class, 'deleteTemp'])->name('berkas-pengobatan-delete.temp');
    
    
    Route::post('/admin/tagihan-baru', [BerkasPengobatanController::class, 'store_invoice'])->name('tagihan-baru.store');
    


    //Ekses
    Route::get('/admin/ekses', [EksesController::class, 'index']);
    Route::post('/admin/ekses/tambah', [EksesController::class, 'store'])->name('ekses.store');
    Route::post('/admin/ekses/upload', [EksesController::class, 'uploadExcel'])->name('ekses.upload');
    Route::get('/admin/ekses/edit/{id}', [EksesController::class, 'edit']);
    Route::post('/admin/ekses/update/{id}', [EksesController::class, 'update'])->name('ekses.update');
    Route::get('/admin/ekses/delete/{id}', [EksesController::class, 'destroy'])->name('ekses.destroy');
    Route::post('/admin/ekses/delete-multiple', [EksesController::class, 'deleteMultiple'])->name('ekses.delete-multiple');
    Route::post('/upload-temp', [EksesController::class, 'uploadTemp'])->name('upload.temp');
    Route::post('/delete-temp', [EksesController::class, 'deleteTemp'])->name('delete.temp');

    //Kelengkapan Kerja
    Route::get('/admin/kelengkapan_kerja/', [KelengkapanKerjaController::class, 'index']);
    Route::post('/admin/kelengkapan_kerja/upload', [KelengkapanKerjaController::class, 'uploadExcel'])->name('kelengkapan-kerja.upload');
    Route::post('/admin/kelengkapan_kerja/store', [KelengkapanKerjaController::class, 'store'])->name('kelengkapan-kerja.store');
    Route::post('/admin/kelengkapan_kerja/update/{id}', [KelengkapanKerjaController::class, 'update'])->name('kelengkapan-kerja.update');
    Route::get('/admin/kelengkapan_kerja/delete/{id}', [KelengkapanKerjaController::class, 'destroy'])->name('kelengkapan-kerja.destroy');
    Route::post('/admin/kelengkapan_kerja/delete-multiple', [KelengkapanKerjaController::class, 'deleteMultiple'])->name('kelengkapan-kerja.delete-multiple');

    
    //Klaim Kecelakaan
    Route::get('/admin/klaim_kecelakaan', [KlaimKecelakaanController::class, 'index']);
    Route::post('/admin/klaim_kecelakaan/tambah', [KlaimKecelakaanController::class, 'store'])->name('pengajuan-klaim.store');
    Route::post('/admin/klaim_kecelakaan/upload', [KlaimKecelakaanController::class, 'uploadExcel'])->name('pengajuan-klaim.upload');
    Route::get('/admin/klaim_kecelakaan/edit/{id}', [KlaimKecelakaanController::class, 'edit']);
    Route::post('/admin/klaim_kecelakaan/update/{id}', [KlaimKecelakaanController::class, 'update'])->name('pengajuan-klaim.update');
    Route::get('/admin/klaim_kecelakaan/delete/{id}', [KlaimKecelakaanController::class, 'destroy']);
    Route::post('/admin/klaim_kecelakaan/delete-multiple', [KlaimKecelakaanController::class, 'deleteMultiple'])->name('pengajuan-klaim.delete-multiple');
    Route::post('/pengajuan-klaim-kecelakaan/upload-temp', [KlaimKecelakaanController::class, 'uploadTemp'])->name('upload.temp');
    Route::post('/pengajuan-klaim-kecelakaan/delete-temp', [KlaimKecelakaanController::class, 'deleteTemp'])->name('delete.temp');
     
    //Klaim Kematian
    Route::get('/admin/klaim_kematian', [KlaimKematianController::class, 'index']);
    Route::post('/admin/klaim_kematian/tambah', [KlaimKematianController::class, 'store'])->name('pengajuan-klaim-kematian.store');
    Route::post('/admin/klaim_kematian/upload', [KlaimKematianController::class, 'uploadExcel'])->name('pengajuan-klaim-kematian.upload');
    Route::get('/admin/klaim_kematian/edit/{id}', [KlaimKematianController::class, 'edit']);
    Route::post('/admin/klaim_kematian/update/{id}', [KlaimKematianController::class, 'update']);
    Route::get('/admin/klaim_kematian/delete/{id}', [KlaimKematianController::class, 'destroy']);
    Route::post('/admin/klaim_kematian/delete-multiple', [KlaimKematianController::class, 'deleteMultiple'])->name('pengajuan-klaim.delete-multiple');
    Route::post('/pengajuan-klaim-kematian/upload-temp', [KlaimKematianController::class, 'uploadTemp'])->name('klaim-kematian-upload.temp');
    Route::post('/pengajuan-klaim-kematian/delete-temp', [KlaimKematianController::class, 'deleteTemp'])->name('klaim-kematian-delete.temp');
    
    //Klaim Lumpsum Kacamata
    Route::get('/admin/klaim_lumpsum-kacamata', [KlaimLumpsumKacamataController::class, 'index']);
    Route::post('/admin/klaim_lumpsum-kacamata/tambah', [KlaimLumpsumKacamataController::class, 'store'])->name('pengajuan-klaim-lumpsum-kacamata.store');
    Route::post('/admin/klaim_lumpsum-kacamata/upload', [KlaimLumpsumKacamataController::class, 'uploadExcel'])->name('pengajuan-klaim-lumpsum-kacamata.upload');
    Route::post('/admin/klaim_lumpsum-kacamata/update/{id}', [KlaimLumpsumKacamataController::class, 'update']);
    Route::get('/admin/klaim_lumpsum-kacamata/delete/{id}', [KlaimLumpsumKacamataController::class, 'destroy']);
    Route::post('/admin/klaim_lumpsum-kacamata/delete-multiple', [KlaimLumpsumKacamataController::class, 'deleteMultiple'])->name('pengajuan-klaim-lumpsum-kacamata.delete-multiple');
    Route::post('/pengajuan-klaim-lumpsum-kacamata/upload-temp', [KlaimLumpsumKacamataController::class, 'uploadTemp'])->name('klaim-lumpsum-kacamata-upload.temp');
    Route::post('/pengajuan-klaim-lumpsum-kacamata/delete-temp', [KlaimLumpsumKacamataController::class, 'deleteTemp'])->name('klaim-lumpsum-kacamata-delete.temp');

    //Klaim Lumpsum Kelahiran
    Route::get('/admin/klaim_lumpsum-lahiran', [KlaimLumpsumKelahiranController::class, 'index']);
    Route::post('/admin/klaim_lumpsum-lahiran/tambah', [KlaimLumpsumKelahiranController::class, 'store'])->name('pengajuan-klaim-lumpsum-kelahiran.store');
    Route::post('/admin/klaim_lumpsum-lahiran/upload', [KlaimLumpsumKelahiranController::class, 'uploadExcel'])->name('pengajuan-klaim-lumpsum-kelahiran.upload');
    Route::get('/admin/klaim_lumpsum-lahiran/edit/{id}', [KlaimLumpsumKelahiranController::class, 'edit']);
    Route::post('/admin/klaim_lumpsum-lahiran/update/{id}', [KlaimLumpsumKelahiranController::class, 'update']);
    Route::get('/admin/klaim_lumpsum-lahiran/delete/{id}', [KlaimLumpsumKelahiranController::class, 'destroy']);
    Route::post('/admin/klaim_lumpsum-lahiran/delete-multiple', [KlaimLumpsumKelahiranController::class, 'deleteMultiple'])->name('pengajuan-klaim-lumpsum-kelahiran.delete-multiple');
    Route::post('/pengajuan-klaim-lumpsum-kelahiran/upload-temp', [KlaimLumpsumKelahiranController::class, 'uploadTemp'])->name('klaim-lumpsum-kelahiran-upload.temp');
    Route::post('/pengajuan-klaim-lumpsum-kelahiran/delete-temp', [KlaimLumpsumKelahiranController::class, 'deleteTemp'])->name('klaim-lumpsum-kelahiran-delete.temp');

    //Klaim Pengobatan
    Route::get('/admin/klaim_pengobatan', [KlaimPengobatanController::class, 'index']);
    Route::post('/admin/klaim_pengobatan/upload', [KlaimPengobatanController::class, 'uploadExcel'])->name('pengajuan-klaim-pengobatan.upload');
    Route::post('/admin/klaim_pengobatan/tambah', [KlaimPengobatanController::class, 'store'])->name('pengajuan-klaim-pengobatan.store');
    Route::get('/admin/klaim_pengobatan/edit/{id}', [KlaimPengobatanController::class, 'edit']);
    Route::post('/admin/klaim_pengobatan/update/{id}', [KlaimPengobatanController::class, 'update']);
    Route::get('/admin/klaim_pengobatan/delete/{id}', [KlaimPengobatanController::class, 'destroy']);
    Route::post('/admin/klaim_pengobatan/delete-multiple', [KlaimPengobatanController::class, 'deleteMultiple'])->name('pengajuan-klaim.delete-multiple');
    Route::post('/pengajuan-klaim-pengobatan/upload-temp', [KlaimPengobatanController::class, 'uploadTemp'])->name('klaim-pengobatan-upload.temp');
    Route::post('/pengajuan-klaim-pengobatan/delete-temp', [KlaimPengobatanController::class, 'deleteTemp'])->name('klaim-pengobatan-delete.temp');

    //Klaim Purnajabatan
    Route::get('/admin/klaim_purna-jabatan', [KlaimPurnaJabatanController::class, 'index']);
    Route::post('/admin/klaim_purna-jabatan/upload', [KlaimPurnaJabatanController::class, 'uploadExcel'])->name('pengajuan-klaim-purna-jabatan.upload');
    Route::post('/admin/klaim_purna-jabatan/tambah', [KlaimPurnaJabatanController::class, 'store'])->name('pengajuan-klaim-purnajabatan.store');
    Route::get('/admin/klaim_purna-jabatan/edit/{id}', [KlaimPurnaJabatanController::class, 'edit']);
    Route::post('/admin/klaim_purna-jabatan/update/{id}', [KlaimPurnaJabatanController::class, 'update']);
    Route::get('/admin/klaim_purna-jabatan/delete/{id}', [KlaimPurnaJabatanController::class, 'destroy']);
    Route::post('/admin/klaim_purna-jabatan/delete-multiple', [KlaimPurnaJabatanController::class, 'deleteMultiple'])->name('pengajuan-klaim.delete-multiple');
    Route::post('/pengajuan-klaim-purna-jabatan/upload-temp', [KlaimPurnaJabatanController::class, 'uploadTemp'])->name('klaim-pengobatan-upload.temp');
    Route::post('/pengajuan-klaim-purna-jabatan/delete-temp', [KlaimPurnaJabatanController::class, 'deleteTemp'])->name('klaim-pengobatan-delete.temp');

    //Klaim Restitusi Karyawan / Pengajuana Reimburse
    Route::get('/admin/restitusi_karyawan', [RestitusiKaryawanController::class, 'index']);
    Route::get('/admin/set_user_list', [RestitusiKaryawanController::class, 'set_user_list'])->name('set_user_list');
    Route::post('/admin/set_user_submit', [RestitusiKaryawanController::class, 'submitListUser'])->name('set_user_submit');
    Route::post('/admin/remove-user', [RestitusiKaryawanController::class, 'removeUser']);
    Route::post('/admin/restitusi_karyawan/tambah', [RestitusiKaryawanController::class, 'store']);
    Route::get('/admin/restitusi_karyawan/edit/{id}', [RestitusiKaryawanController::class, 'edit']);
    Route::post('/admin/restitusi_karyawan/update/{id}', [RestitusiKaryawanController::class, 'update']);
    Route::delete('/admin/restitusi_karyawan/delete/{id}', [RestitusiKaryawanController::class, 'destroy']);
    // Route::patch('/restitusi-karyawan/approval-dr/{id}', [RestitusiKaryawanController::class, 'approval_dr']);
    // Route::patch('/restitusi-karyawan/approval-vp/{id}', [RestitusiKaryawanController::class, 'approval_vp']);
    Route::put('/restitusi-karyawan/approval-screening/{id}', [RestitusiKaryawanController::class, 'approval_screening'])->name('approval-screening');
    Route::put('/restitusi-karyawan/reject-screening/{id}', [RestitusiKaryawanController::class, 'reject_screening'])->name('reject-screening');
    Route::put('/restitusi-karyawan/approval-dr/{id}', [RestitusiKaryawanController::class, 'approval_dr'])->name('approval-dr');
    Route::put('/restitusi-karyawan/reject-dr/{id}', [RestitusiKaryawanController::class, 'reject_dr'])->name('reject-dr');
    Route::put('/restitusi-karyawan/approval-vp/{id}', [RestitusiKaryawanController::class, 'approval_vp'])->name('approval-vp');
    Route::put('/restitusi-karyawan/reject-vp/{id}', [RestitusiKaryawanController::class, 'reject_vp'])->name('reject-vp');
    Route::get('/restitusi_karyawan/view_edit_nominal_pengajuan/{id}', [RestitusiKaryawanController::class, 'view_edit_nominal_pengajuan'])->name('nominal_pengajuan.edit');
    Route::get('/restitusi_karyawan/rincian/{id}', [RestitusiKaryawanController::class, 'getRincianBiaya'])->name('restitusi_karyawan.rincian');
    Route::post('/restitusi_karyawan/upload-temp', [RestitusiKaryawanController::class, 'uploadTemp'])->name('restitusi_karyawan_upload.temp');
    Route::post('/restitusi_karyawan/delete-temp', [RestitusiKaryawanController::class, 'deleteTemp'])->name('restitusi_karyawan_delete.temp');
    Route::post('/restitusi_karyawan/get-non-karyawan', [RestitusiKaryawanController::class, 'getNonKaryawan'])->name('restitusi_karyawan_delete.get_non_karyawan');
    Route::post('/restitusi_karyawan/get-detail-pasien', [RestitusiKaryawanController::class, 'getDetailPasien'])->name('getDetailPasien');
    Route::post('/download-restitusi', [RestitusiKaryawanController::class, 'downloadPDF'])->name('print-restitusi');
    Route::post('/download-restitusi-printout', [RestitusiKaryawanController::class, 'downloadPrintOutRK'])->name('print-restitusi-printout');
    //Kepesertaan BPJS  Kesehatan
    Route::get('/admin/bpjs/bpjs-kesehatan', [PesertaBPJSKesehatanController::class, 'index']);
    Route::post('/admin/bpjs/bpjs-kesehatan/upload', [PesertaBPJSKesehatanController::class, 'uploadExcel'])->name('bpjs-kesehatan.upload');
    Route::post('/admin/bpjs/bpjs-kesehatan/tambah', [PesertaBPJSKesehatanController::class, 'store'])->name('bpjs-kesehatan.store');
    Route::post('/admin/bpjs/bpjs-kesehatan/update/{id}', [PesertaBPJSKesehatanController::class, 'update']);
    Route::get('/admin/bpjs/bpjs-kesehatan/delete/{id}', [PesertaBPJSKesehatanController::class, 'destroy']);
    Route::post('/admin/bpjs/bpjs-kesehatan/delete-multiple', [PesertaBPJSKesehatanController::class, 'deleteMultiple'])->name('bpjs-kesehatan.delete-multiple');
    Route::post('/bpjs-kesehatan/upload-temp', [PesertaBPJSKesehatanController::class, 'uploadTemp'])->name('bpjs-kesehatan-upload.temp');
    Route::post('/bpjs-kesehatan/delete-temp', [PesertaBPJSKesehatanController::class, 'deleteTemp'])->name('bpjs-kesehatan-delete.temp');

    //Kepesertaan BPJS KetenagaKerjaan
    Route::get('/admin/bpjs/bpjs-ketenagakerjaan', [PesertaBPJSKetenagakerjaanController::class, 'index']);
    Route::post('/admin/bpjs/bpjs-ketenagakerjaan/upload', [PesertaBPJSKetenagakerjaanController::class, 'uploadExcel'])->name('bpjs-ketenagakerjaan.upload');
    Route::post('/admin/bpjs/bpjs-ketenagakerjaan/tambah', [PesertaBPJSKetenagakerjaanController::class, 'store'])->name('bpjs-ketenagakerjaan.store');
    Route::post('/admin/bpjs/bpjs-ketenagakerjaan/update/{id}', [PesertaBPJSKetenagakerjaanController::class, 'update']);
    Route::get('/admin/bpjs/bpjs-ketenagakerjaan/delete/{id}', [PesertaBPJSKetenagakerjaanController::class, 'destroy']);
    Route::post('/admin/bpjs/bpjs-ketenagakerjaan/delete-multiple', [PesertaBPJSKetenagakerjaanController::class, 'deleteMultiple'])->name('bpjs-ketenagakerjaan.delete-multiple');
    Route::post('/bpjs-ketenagakerjaan/upload-temp', [PesertaBPJSKetenagakerjaanController::class, 'uploadTemp'])->name('bpjs-ketenagakerjaan-upload.temp');
    Route::post('/bpjs-ketenagakerjaan/delete-temp', [PesertaBPJSKetenagakerjaanController::class, 'deleteTemp'])->name('bpjs-ketenagakerjaan-delete.temp');

    Route::post('/admin/restitusi_karyawan/upload', [RestitusiKaryawanController::class, 'uploadExcel'])->name('restitusi-karyawan.upload');
    
    Route::get('/keluarga', [MasterDataKaryawanController::class, 'keluarga']);
    // Route::get('set-profil', function () {
    //     return view('extras/set-profil');
    // });
    
    Route::get('/set-profil', [MasterDataKaryawanController::class, 'set_profil']);
    
    Route::post('/get-keluarga', [MasterDataNonKaryawanController::class, 'get_keluarga_from_badge']);

    Route::get('kelengkapan-kerja/export/', [KelengkapanKerjaController::class, 'export']);


    
    Route::delete('/admin/kelengkapan_kerja/delete-kelengkapan/{periode}', [KelengkapanKerjaController::class, 'deleteKelengkapan'])->name('delete.kelengkapan');

    
    Route::get('/admin/berkas-tagihan-rumah-sakit/{id}', [BerkasPengobatanController::class, 'show'])->name('berkas-tagihan-rumah-sakit.show');
    
    //Logout
    // use App\Http\Controllers\UserController;

    // Route::get('/update-password', [UserController::class, 'showUpdatePasswordForm'])->name('password.update.form');
    Route::post('/update-password', [AuthController::class, 'updatePassword'])->name('password.update');

    Route::get('/logout', [AuthController::class, 'logout']);
});



// });

//point
