<?php

namespace App\Http\Controllers\RestitusiKaryawan;

use App\Http\Controllers\Controller;
use App\Models\MasterData\DataKaryawan;
use App\Models\RestitusiKaryawan\RestitusiKaryawan;
use App\Models\RincianBiaya\RincianBiaya;
use App\Models\CostCenter;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade as PDF;
use File;
use TCPDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class RestitusiKaryawanController extends Controller
{
    public function index()
    {
        
        // Ambil username dan role dari session (atau metode lainnya)
        $username = auth()->user()->username; // Pastikan 'username' tersimpan di session
        $role = auth()->user()->role; // Pastikan 'role' tersimpan di session

        $query = RestitusiKaryawan::select(
            'table_pengajuan_reimburse.*',
            'table_karyawan.nama_karyawan' // Kolom dari tabel karyawan
        )
        ->leftJoin('table_karyawan', 'table_pengajuan_reimburse.id_badge', '=', 'table_karyawan.id_badge');
        
        // Tambahkan kondisi jika role adalah 'tko'
        if ($role === 'tko') {
            $query->where('table_pengajuan_reimburse.id_badge', $username);
            $karyawan = DataKaryawan::orderBy('nama_karyawan', 'asc')->where('id_badge', $username);
        } elseif ($role === 'adm_karyawan') {
            // Ambil list_karyawan dari user yang sedang login
            $listKaryawan = json_decode(auth()->user()->list_karyawan, true);
            // $query->where('table_pengajuan_reimburse.id_badge', $username);
            // Query data karyawan berdasarkan id_badge
            $karyawan = DataKaryawan::whereIn('cost_center', $listKaryawan);
            $filteredIds = $karyawan->pluck('id_badge')->toArray(); // Mengambil semua id_badge dari koleksi

            $query->whereIn('table_pengajuan_reimburse.id_badge', $filteredIds);
        }else {
            
            $karyawan = DataKaryawan::orderBy('nama_karyawan', 'asc');
        }
        // return response()->json([
        //     'status' => 'error',
        //     'message' => 'Data gagal successfully',
        //     'data' => $karyawan,
        // ], 200);

        if ($role === 'dr_hph') {
            $query->where('table_pengajuan_reimburse.status_pengajuan', 2);
        }
        if ($role === 'vp_osdm') {
            $query->where('table_pengajuan_reimburse.status_pengajuan', 3);
        }

        $karyawan = $karyawan->get();
        // Urutkan hasil secara descending
        $restitusi = $query->orderBy('table_pengajuan_reimburse.created_at', 'desc')->get();


        
        // Mengembalikan view dengan data yang diambil
        return view('dashboard/restitusi-karyawan', [
            'restitusi' => $restitusi,
            'karyawan' => $karyawan,
        ]);
    }
    
    public function set_user_list()
    {
        // Ambil username dan role dari session (atau metode lainnya)
        $username = auth()->user()->username;
        $role = auth()->user()->role;

        // Ambil semua data cost_center
        $costCenters = CostCenter::orderBy('nama_bagian', 'asc')->get();

        // Ambil daftar cost center yang sudah tersimpan dalam user
        $listCostCenter = json_decode(auth()->user()->list_karyawan, true) ?? [];

        // Ambil data cost center yang sudah terdaftar
        $registeredCostCenters = CostCenter::whereIn('cost_center', $listCostCenter)->get();

        return view('extras/set_list_user', [
            'costCenters' => $costCenters,
            'registeredCostCenters' => $registeredCostCenters
        ]);
    }

    public function submitListUser(Request $request)
    {
        // Decode JSON dari request
        $selectedCostCenters = json_decode($request->selected_cost_center, true);

        // Cek apakah data valid
        if (is_null($selectedCostCenters)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal decode JSON'
            ]);
        }

        // Ambil user yang sedang login
        $user = auth()->user();
        $existingCostCenters = json_decode($user->list_karyawan, true) ?? [];

        // Gabungkan data baru dengan yang lama, pastikan unik
        $mergedCostCenters = array_unique(array_merge($existingCostCenters, $selectedCostCenters));

        // Simpan ke database
        $user->list_karyawan = json_encode($mergedCostCenters);
        $saved = $user->save();

        if (!$saved) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan data'
            ]);
        }

        return back()->with('success', 'Cost Center berhasil disimpan!');
    }

    public function removeUser(Request $request)
    {
        $costCenterId = $request->cost_center_id;
        $user = auth()->user();
        $existingCostCenters = json_decode($user->list_karyawan, true) ?? [];

        // Hapus ID dari array
        $updatedCostCenters = array_filter($existingCostCenters, fn($id) => $id != $costCenterId);

        $user->list_karyawan = json_encode(array_values($updatedCostCenters));
        $user->save();

        return response()->json(['status' => 'success', 'message' => 'Cost Center berhasil dihapus!']);
    }


    public function store(Request $request)
    {
        $selectedPasien = $request->input('selected_pasien', []);
        $no_surat_rs = $this->generateNoSuratRs();
        if ($request->input('hide_pasien') === "1") {
            $selectedPasien = null;
        }else {
            $selectedPasien = $request->input('selected_pasien', []);
        }
        // Decode JSON jika diperlukan
        // $selectedPasien = $selectedPasien, true;
        // return response()->json([
        //     'status' => 'error',
        //     'message' => 'Data Request di ambil',
        //     'data' => $request->all(),
        //     'selectedPasien' => $selectedPasien,
        //     'generateNoRS' => $this->generateNoSuratRs()
        // ], 200);
        try {
            $id_restitusi = rand(10, 99999999);
            $validatedData = $request->validate([
                'id_badge' => 'required|string|max:255',
                'tanggal_pengobatan' => 'nullable|date',
                'urgensi' => 'nullable|string|in:Low,Medium,High',
                'deskripsi' => 'nullable|string',
                'rumah_sakit' => 'nullable|string|max:255',
                // 'no_surat_rs' => 'nullable|string|max:255',
                'status_pengajuan' => 'nullable|numeric',
                'nominal_pengajuan' => 'required|array',
                'nominal_pengajuan.*' => 'required|string',
                'deskripsi_pengajuan' => 'required|array',
                'deskripsi_pengajuan.*' => 'nullable|string',
            ]);

            $restitusi = RestitusiKaryawan::create([
                'id_pengajuan' => $id_restitusi,
                'id_badge' => $validatedData['id_badge'],
                'deskripsi' => $validatedData['deskripsi'],
                'rumah_sakit' => $validatedData['rumah_sakit'],
                'urgensi' => $validatedData['urgensi'],
                'no_surat_rs' => $no_surat_rs,
                'tanggal_pengobatan' => $validatedData['tanggal_pengobatan'],
                'url_file' => $request->uploaded_files,
                'jenis_perawatan' => $request->kategori_perawatan,
                'daftar_pasien' => $selectedPasien,
                'status_pengajuan' => '1',
            ]);

            foreach ($validatedData['nominal_pengajuan'] as $index => $nominal) {
                $deskripsiBiaya = $validatedData['deskripsi_pengajuan'][$index] ?? '';
                RincianBiaya::create([
                    'id_rincian_biaya' => rand(10, 99999999),
                    'id_badge' => $validatedData['id_badge'],
                    'kategori' => "restitusi",
                    'id_kategori' => $id_restitusi,
                    'rumah_sakit' => $validatedData['rumah_sakit'],
                    'no_surat_rs' => $no_surat_rs,
                    'deskripsi_biaya' => $deskripsiBiaya,
                    'nominal_pengajuan' => str_replace(['Rp', '.', ','], '', $nominal),
                    'status_rincian_biaya' => 1,
                    'created_by' => auth()->user()->id_user,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            return redirect('/admin/restitusi_karyawan')->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data gagal successfully',
                'data' => $th->getMessage(),
            ], 200);
        }
    }

    public function getDetailPasien(Request $request)
    {
        $pasienIds = $request->input('pasien_ids', []);
    
        // Jika pasien_ids bukan array, ubah menjadi array
        if (!is_array($pasienIds)) {
            $pasienIds = [$pasienIds];
        }
    
        Log::info('Pasien IDs diterima:', ['pasien_ids' => $pasienIds]);
    
        $dataPasien = DB::table('table_non_karyawan')
            ->whereIn('id_non_karyawan', $pasienIds)
            ->select('id_non_karyawan', 'nama_lengkap', 'hubungan_keluarga')
            ->get();
    
        if ($dataPasien->isEmpty()) {
            Log::error('Data pasien tidak ditemukan.', ['pasien_ids' => $pasienIds]);
            return response()->json(['status' => 'error', 'message' => 'Data pasien tidak ditemukan.']);
        }
    
        Log::info('Data pasien ditemukan:', ['data_pasien' => $dataPasien->toArray()]);
    
        return response()->json(['status' => 'success', 'data' => $dataPasien]);
    }

    public function generateNoSuratRs()
    {
        $baseFormat = '{no_surat}/RESTITUSI/{bulan}/{tahun}';
        $tahun = date('Y');
        $bulan = date('n'); // Bulan numerik
        $bulanRomawi = $this->convertToRoman($bulan);

        // Regex untuk memvalidasi format nomor surat
        $regexFormat = '/^(\d+)\/RESTITUSI\/' . $bulanRomawi . '\/' . $tahun . '$/';

        // Ambil nomor surat terbaru sesuai format
        $latestNoSurat = DB::table('table_pengajuan_reimburse')
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->orderBy('id_pengajuan', 'desc')
            ->pluck('no_surat_rs') // Ambil semua no_surat_rs
            ->filter(function ($value) use ($regexFormat) {
                return preg_match($regexFormat, $value); // Cek kecocokan dengan format
            })
            ->map(function ($value) {
                return (int) explode('/', $value)[0]; // Ekstrak nomor surat
            })
            ->sortDesc() // Urutkan dari terbesar ke terkecil
            ->first(); // Ambil nomor terbesar

        // Tentukan nomor berikutnya
        $nextNumber = $latestNoSurat ? $latestNoSurat + 1 : 1;

        return str_replace(
            ['{no_surat}', '{bulan}', '{tahun}'],
            [$nextNumber, $bulanRomawi, $tahun],
            $baseFormat
        );
    }

    // Fungsi untuk mengonversi bulan ke format angka Romawi
    function convertToRoman($number)
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V',
            6 => 'VI', 7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X',
            11 => 'XI', 12 => 'XII'
        ];
    
        return $map[$number] ?? $number;
    }

    public function getNonKaryawan(Request $request)
    {
        $idBadge = $request->input('id_badge'); // Ambil id_badge dari request
    
        // Debugging untuk memastikan ID diterima
        Log::info('ID Badge diterima: ' . $idBadge);
    
        // Ambil data dari table_non_karyawan berdasarkan badge_parent
        $data = DB::table('table_non_karyawan')
            ->where('badge_parent', $idBadge)
            ->select('id_non_karyawan', 'nama_lengkap', 'hubungan_keluarga')
            ->get();
    
        // Cek jika data tidak ditemukan
        if ($data->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data pasien tidak ditemukan.'
            ]);
        }
    
        // Jika data ditemukan, kembalikan response
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function view_edit_nominal_pengajuan($id)
    {
        try {
            // Ambil data restitusi berdasarkan ID
            $restitusi = RestitusiKaryawan::where('id_pengajuan', $id)->firstOrFail();

            // Ambil rincian biaya terkait
            $rincianBiaya = RincianBiaya::where('id_kategori', $id)->get();

            // Kembalikan data dalam bentuk JSON
            return response()->json([
                'status' => 'success',
                'data' => [
                    'restitusi' => $restitusi,
                    'rincian_biaya' => $rincianBiaya,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getRincianBiaya($id)
    {
        try {
            
            $rincianBiaya = RincianBiaya::where('id_kategori', $id)->get();
    
            return response()->json([
                'status' => 'success',
                'data' => $rincianBiaya
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function uploadTemp(Request $request)
    {

        // return response()->json(['error' => 'No file uploaded'], 400);
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/Restitusi_Karyawan'), $fileName);
        
            return response()->json([
                'fileName' => $fileName
            ]);
        }
        
        return response()->json(['error' => 'No file uploaded'], 400);
    }

    public function deleteTemp(Request $request)
    {
        $filename = $request->input('fileName');
        $filePath = storage_path("app/public/temp/{$filename}");

        if (file_exists($filePath)) {
            unlink($filePath);
            Log::info("File Terhapus dari Public Upload Klaim Purna Jabatan Temp: " . json_encode($filename));
            return response()->json(['success' => true]);
        }else{
            $filePath = public_path("uploads/Restitusi_Karyawan/{$filename}");
            unlink($filePath);
            Log::info("File Terhapus dari Public Upload Klaim PurnaJabatan: " . json_encode($filename));
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'File not found'], 404);
    }

    public function edit(string $id)
    {
        try {
            // Find the restitusi by ID or throw a 404 if not found
            $restitusi = RestitusiKaryawan::findOrFail($id);
    
            // Return success response with restitusi data
            return response()->json([
                'status' => 'success',
                'message' => 'Data retrieved successfully',
                'data' => $restitusi
            ], 200);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Return a 404 response if restitusi is not found
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found',
            ], 404);
        }
    }


    public function update(Request $request, string $id)
    {
        // return response()->json([
        //         'status' => 'Debugging',
        //         'message' => 'Data Response berhasil diproses.',
        //         'request' => $request->all(),
        //         // 'decoded_id_rincian_biaya' => count($idRincianBiaya),
        //         // 'decoded_nominal_pengajuan' => count($nominalPengajuan),
        //         // 'decoded_deskripsi_pengajuan' => count($deskripsiPengajuan),
        //         // 'data_restitusi' => $restitusi
        //     ]);

        try {
            Log::info('Updating Attachment Data Karyawan: ' . $id . ', Request Data: ', $request->all());
    
            // Decode uploaded_files dan removed_files
            $uploadedFiles = $request->input('uploaded_files', '[]');
            $uploadedFiles = json_decode($uploadedFiles, true) ?? [];
    
            $removedFiles = $request->input('removed_files', '[]');
            $removedFiles = json_decode($removedFiles, true) ?? [];
    
            Log::info('Decoded Uploaded Files:', $uploadedFiles);
            Log::info('Decoded Removed Files:', $removedFiles);
    
            // Pastikan uploadedFiles dan removedFiles adalah array
            $uploadedFiles = is_array($uploadedFiles) ? $uploadedFiles : [];
            $removedFiles = is_array($removedFiles) ? $removedFiles : [];
    
            // Ambil data klaim dari database
            $restitusi = RestitusiKaryawan::findOrFail($id);
            $currentFiles = json_decode($restitusi->url_file, true);
            if (!is_array($currentFiles)) {
                $currentFiles = [];
            }
    
            Log::info('Existing Files:', $currentFiles);
    
            // Hapus file dari database dan server jika ada dalam $removedFiles
            if (!empty($removedFiles)) {
                foreach ($removedFiles as $file) {
                    $filePath = public_path('uploads/Restitusi_Karyawan/' . $file);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                        Log::info('File removed from server:', ['file' => $filePath]);
                    }
                }
                $currentFiles = array_values(array_diff($currentFiles, $removedFiles));
            }
    
            // Tambahkan file baru ke array yang ada
            if (!empty($uploadedFiles)) {
                $currentFiles = array_merge($currentFiles, $uploadedFiles);
            }
    
            // Hilangkan duplikat file dan reset index array
            $finalFiles = array_values(array_unique($currentFiles));
    
            if (auth()->user()->role === "superadmin") {
                $restitusi->update([
                    'url_file' => json_encode($finalFiles),
                    'rumah_sakit' => $request->rumah_sakit,
                    'urgensi' => $request->urgensi,
                    
                    'tanggal_pengobatan' => $request->tanggal_pengobatan,
                    'keterangan_pengajuan' => $request->keterangan_pengajuan,
                    'status_pengajuan' => 1,
                    'reject_notes' => null,
                    'deskripsi' => $request->deskripsi,
                ]);
            }
            // Update data restitusi
            
    
            // Update rincian biaya
            // Ambil data dari request
            $idRincianBiayaRaw = $request->input('id_rincian_biaya', []);
            $nominalPengajuanRaw = $request->input('nominal_pengajuan', []);
            $deskripsiPengajuanRaw = $request->input('deskripsi_pengajuan', []);
            $removedRincianBiaya = $request->input('removed_rincian_biaya', []);
            if (!empty($removedRincianBiaya)) {
                $removedRincianBiaya = json_decode($removedRincianBiaya, true);
                RincianBiaya::whereIn('id_rincian_biaya', $removedRincianBiaya)->delete();
            }

            // Proses data menjadi array PHP
            $idRincianBiaya = !empty($idRincianBiayaRaw) ? json_decode($idRincianBiayaRaw[0], true) : [];
            $nominalPengajuan = !empty($nominalPengajuanRaw) ? json_decode($nominalPengajuanRaw[0], true) : [];
            $deskripsiPengajuan = !empty($deskripsiPengajuanRaw) ? json_decode($deskripsiPengajuanRaw[0], true) : [];

            if (count($idRincianBiaya) !== count($nominalPengajuan) || count($idRincianBiaya) !== count($deskripsiPengajuan)) {
                throw new \Exception('Panjang array id_rincian_biaya, nominal_pengajuan, dan deskripsi_pengajuan tidak sesuai.');
            }

            Log::info('Data ID Rincian Biaya:', $idRincianBiaya);
            Log::info('Data Nominal Pengajuan:', $nominalPengajuan);
            Log::info('Data Deskripsi Pengajuan:', $deskripsiPengajuan);

            foreach ($idRincianBiaya as $index => $idRincian) {
                if (empty($idRincian)) {
                    // Jika ID kosong/null, buat rincian baru
                    $rincian = RincianBiaya::create([
                        'id_badge' => $restitusi->id_badge,
                        'kategori' => "restitusi",
                        'id_kategori' => $restitusi->id_pengajuan,
                        'rumah_sakit' => $restitusi->rumah_sakit,
                        'no_surat_rs' => $restitusi->no_surat_rs,
                        'nominal_pengajuan' => str_replace(['Rp', '.', ','], '', $nominalPengajuan[$index]),
                        'deskripsi_biaya' => $deskripsiPengajuan[$index],
                        'created_at' => now(),
                        'created_by' => auth()->user()->id_user
                    ]);
                    Log::info('Rincian biaya baru dibuat:', ['data' => $rincian]);
                } else {
                    // Jika ID ada, update rincian yang ada
                    $updated = RincianBiaya::where('id_rincian_biaya', $idRincian)->update([
                        'nominal_pengajuan' => str_replace(['Rp', '.', ','], '', $nominalPengajuan[$index]),
                        'deskripsi_biaya' => $deskripsiPengajuan[$index],
                        'updated_at' => now(),
                        'updated_by' => auth()->user()->id_user
                    ]);
                    Log::info('Rincian biaya diupdate:', ['id' => $idRincian, 'updated' => $updated]);
                }
            }

            // return response()->json([
            //     'status' => 'Debugging',
            //     'message' => 'Data Response berhasil diproses.',
            //     'request' => $request->all(),
            //     'decoded_id_rincian_biaya' => count($idRincianBiaya),
            //     'decoded_nominal_pengajuan' => count($nominalPengajuan),
            //     'decoded_deskripsi_pengajuan' => count($deskripsiPengajuan),
            //     'data_restitusi' => $restitusi
            // ]);
            
            
            return redirect('/admin/restitusi_karyawan')->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $th) {
            // return redirect('/admin/restitusi_karyawan')->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
            return response()->json([
                'status' => 'Debugging',
                'message' => 'Data Renponse di ambil.',
                'request' => $request->all(),
                'pesan_error' => $th->getMessage()
                // 'filesFinal'=>$finalFiles,
            ]);
        }
    }

    public function reject_dr(Request $request, $id)
    {
        try {
            // Temukan data restitusi karyawan berdasarkan ID
            $restitusi = RestitusiKaryawan::findOrFail($id);
    
            // dd($restitusi);
            // Lakukan logika persetujuan DR
            $restitusi->reject_notes = "Penolakan dari dokter : " . $request->reject_notes;
            $restitusi->status_pengajuan = 1;
            $restitusi->save();
            Log::info("Request received for rejecting dokter", $request->all());
    
            return redirect('/admin/restitusi_karyawan')->with('success', 'Reject DR.');

        } catch (\Throwable $th) {
            return redirect('/admin/restitusi_karyawan')->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            // Find the restitusi by ID or throw a 404 if not found
            $restitusi = RestitusiKaryawan::findOrFail($id);

            // Simpan nomor surat sebelum dihapus
            $noSurat = $restitusi->no_surat_rs;

            // Delete the restitusi record from the database
            $restitusi->delete();

            // Set flash message with nomor surat
            return redirect('/admin/restitusi_karyawan')->with('success', "Data dengan No. Surat $noSurat berhasil dihapus.");
        } catch (\Exception $e) {
            // Log error for debugging
            Log::error("Error deleting data: " . $e->getMessage());
    
            // Set flash message for error
            return redirect('/admin/restitusi_karyawan')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    
    public function approval_screening(Request $request, $id)
    {
        try {
            // Temukan data restitusi karyawan berdasarkan ID
            $restitusi = RestitusiKaryawan::findOrFail($id);
    
            // dd($restitusi);
            // Lakukan logika persetujuan DR
            $restitusi->status_pengajuan = '2';
            $restitusi->tanggal_approval_screening = now();
            $restitusi->save();
    
            return redirect('/admin/restitusi_karyawan')->with('success', 'Approval Screening.');
            // return response()->json([
            //     'status' => 'failed',
            //     'message' => 'Data Gagal.',
            //     'request' => $request->all(),
            //     // 'message' => $th->getMessage(),
            //     // 'filesFinal'=>$finalFiles,
            // ]);

        } catch (\Throwable $th) {
            return redirect('/admin/restitusi_karyawan')->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function reject_screening(Request $request, $id)
    {
        try {
            // Temukan data restitusi karyawan berdasarkan ID
            $restitusi = RestitusiKaryawan::findOrFail($id);
    
            // dd($restitusi);
            // Lakukan logika persetujuan DR
            $restitusi->reject_notes = "Penolakan dari Screening : ". $request->reject_notes;
            $restitusi->status_pengajuan = 0;
            $restitusi->save();
            Log::info("Request received for rejecting screening", $request->all());
    
            return redirect('/admin/restitusi_karyawan')->with('success', 'Reject Screening.');

        } catch (\Throwable $th) {
            return redirect('/admin/restitusi_karyawan')->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }


        public function approval_dr(Request $request, string $id)
    {

        // return response()->json([
        //         'status' => 'Debugging',
        //         'message' => 'Data Response berhasil diproses.',
        //         'request' => $request->all(),
        //     ]);
        try {
            Log::info('Uploading Attachment dari Approval Dokter: ' . $id . ', Request Data: ', $request->all());
            
            // Decode uploaded_files dan removed_files
            $uploadedFiles = json_decode($request->input('uploaded_files', '[]'), true) ?? [];
            $removedFiles = json_decode($request->input('removed_files', '[]'), true) ?? [];
            $approvedBiaya = json_decode($request->input('approved_biaya', '[]'), true) ?? [];
            $idRincianBiaya = json_decode($request->input('id_rincian_biaya', '[]'), true) ?? [];
            $nominalDokter = json_decode($request->input('nominal_dokter', '[]'), true) ?? [];
            $presentaseDokter = json_decode($request->input('presentase', '[]'), true) ?? [];

            // Ambil data klaim dari database
            $restitusi = RestitusiKaryawan::findOrFail($id);
            $currentFiles = json_decode($restitusi->url_file_dr, true) ?? [];

            // Hapus file lama
            foreach ($removedFiles as $file) {
                $filePath = public_path('uploads/Restitusi_Karyawan/' . $file);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            $currentFiles = array_values(array_diff($currentFiles, $removedFiles));

            // Tambahkan file baru
            $currentFiles = array_merge($currentFiles, $uploadedFiles);

            // Update data rincian biaya
            // Update data rincian biaya
            foreach ($idRincianBiaya as $index => $idRincian) {
                $nominal = $nominalDokter[$index] ?? null;

                // Abaikan data jika nominal dokter hanya berisi "Rp" atau kosong
                if (empty($nominal) || trim($nominal) === "Rp") {
                    // Update hanya jika checkbox di-check
                    if (in_array($idRincian, $approvedBiaya)) {
                        $rincian = RincianBiaya::findOrFail($idRincian);
                        $hasil_hitung = ($rincian->nominal_pengajuan * $presentaseDokter[$index])/100;
                        RincianBiaya::where('id_rincian_biaya', $idRincian)->update([
                            'nominal_akhir' => $hasil_hitung, // Jika diapprove, gunakan nominal pengajuan
                            'status_rincian_biaya' => 3, // Status disetujui
                            'updated_by' => auth()->user()->id_user,
                            'updated_at' => now(),
                        ]);
                    }
                    continue;
                }

                // Jika nominal dokter valid, update data rincian biaya
                $nominalDokterCleaned = str_replace(['Rp', '.', ','], '', $nominal); // Membersihkan string nominal dokter
                $hasilhitungDokter = ($nominalDokterCleaned * $presentaseDokter[$index])/100;
                RincianBiaya::where('id_rincian_biaya', $idRincian)->update([
                    'nominal_akhir' => $hasilhitungDokter, // Gunakan nominal dokter sebagai nominal akhir
                    'nominal_dokter' => $nominalDokterCleaned, // Update kolom nominal dokter
                    'status_rincian_biaya' => 3, // Status disetujui
                    'updated_by' => auth()->user()->id_user,
                    'updated_at' => now(),
                ]);
            }


            // Update database restitusi
            $restitusi->update([
                'url_file_dr' => json_encode(array_unique($currentFiles)),
                'status_pengajuan' => 3, // Status disetujui dokter
                'tanggal_approval_dokter' => now(),
                'reject_notes' => null,
            ]);

            
            return redirect('/admin/restitusi_karyawan')->with('success', 'Approve by DR.');
        } catch (\Throwable $th) {
            Log::error('Error in approval_dr:', ['error' => $th->getMessage()]);
            return response()->json([
                'status' => 'Error',
                'message' => 'Terjadi kesalahan.',
                'error' => $th->getMessage(),
                // 'presentase' => $presentaseDokter[0],
            ], 500);
        }
    }

    public function approval_vp(Request $request, $id)
    {
        try {
            // Temukan data restitusi karyawan berdasarkan ID
            $restitusi = RestitusiKaryawan::findOrFail($id);
    
            // dd($restitusi);
            // Lakukan logika persetujuan DR
            $restitusi->status_pengajuan = '4';
            $restitusi->tanggal_approval_vp = now();
            $restitusi->save();

            $rincian_update = RincianBiaya::where('id_kategori', $restitusi->id_pengajuan)
            ->where('status_rincian_biaya', 3)
            ->update([
                'status_rincian_biaya' => 4, // Status disetujui
            ]);


            return redirect('/admin/restitusi_karyawan')->with('success', 'Approval VP.');
        } catch (\Throwable $th) {
            return redirect('/admin/restitusi_karyawan')->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function reject_vp(Request $request, $id)
    {
        try {
            // Temukan data restitusi karyawan berdasarkan ID
            $restitusi = RestitusiKaryawan::findOrFail($id);
    
            // dd($restitusi);
            // Lakukan logika persetujuan DR
            $restitusi->reject_notes = "Penolakan dari VP OSDM : ". $request->reject_notes;
            $restitusi->status_pengajuan = 2;
            $restitusi->save();
            Log::info("Request received for rejecting from VP", $request->all());
    
            return redirect('/admin/restitusi_karyawan')->with('success', 'Reject VP.');

        } catch (\Throwable $th) {
            return redirect('/admin/restitusi_karyawan')->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }

    public function downloadPDF(Request $request)
    {
        $restitusi = RestitusiKaryawan::where('id_pengajuan', $request->id_pengajuan)->first();
        // Periksa apakah data ditemukan
        if (!$restitusi) {
            return abort(404, 'Data tidak ditemukan');
        }
    
        $rincianBiaya = RincianBiaya::where('id_kategori', $restitusi->id_pengajuan)
        ->where('status_rincian_biaya', 4)
        ->sum('nominal_akhir');
        $formatted_total_biaya = format_currency($rincianBiaya);
        $tanggalPecah = explode("-",$restitusi->tanggal_pengajuan);
        $data['formatNoPA'] = $tanggalPecah;
        $data['tanggal'] = Carbon::parse($restitusi->tanggal_pengajuan)->translatedFormat('d F Y');
        $data['deskripsi'] = $restitusi->deskripsi;
        $tanggal_raw = Carbon::parse($restitusi->tanggal_pengajuan);
        $tahun = $tanggal_raw->year;
        $bulan_romawi = $this->convertToRoman($tanggal_raw->month);
        $data['formatNoPA'] = $bulan_romawi.'/'.$tahun;
        $data['totalBiaya'] = ($formatted_total_biaya);
        $data['rawBiaya'] = $rincianBiaya;
        $data['terbilang'] = terbilang($rincianBiaya) . " Rupiah";
        // return response()->json([
        //     'status' => 'Test Request',
        //     'message' => 'Request Di ambil .',
        //     'data' => $data,

        //     // 'presentase' => $presentaseDokter[0],
        // ], 500);
        return view('forms.FormPA', compact('data'));
        // return redirect(url('forms/FormPA.html?print=1'));
    }

}
