<?php

namespace App\Http\Controllers\RestitusiKaryawan;

use App\Http\Controllers\Controller;
use App\Models\MasterData\DataKaryawan;
use App\Models\RestitusiKaryawan\RestitusiKaryawan;
use App\Models\RincianBiaya\RincianBiaya;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        } else {
            
            $karyawan = DataKaryawan::orderBy('nama_karyawan', 'asc');
        }

        if ($role === 'dr_hph') {
            $query->where('table_pengajuan_reimburse.status_pengajuan', 2);
        }
        if ($role === 'vp_osdm') {
            $query->where('table_pengajuan_reimburse.status_pengajuan', 3);
        }

        $karyawan = $karyawan->get();
        // Urutkan hasil secara descending
        $restitusi = $query->orderBy('table_pengajuan_reimburse.id_pengajuan', 'desc')->get();

        
        // Mengembalikan view dengan data yang diambil
        return view('dashboard/restitusi-karyawan', [
            'restitusi' => $restitusi,
            'karyawan' => $karyawan,
        ]);
    }

    // public function store(Request $request)
    // {
    //     // return response()->json([
    //     //     'status' => 'test',
    //     //     'message' => 'Data Request Di panggil',
    //     //     'data' => $request->all()
    //     // ], 200);
        

    //     // $cleanedValue = str_replace(['Rp', '.', ','], '', $request->nominal);
    //     $id_restitusi = rand(10, 99999999);
    //     try {
    //         $validatedData = $request->validate([
    //             'id_badge' => 'required|string|max:255',
    //             // 'nama_karyawan' => 'required|string|max:255',
    //             // 'jabatan_karyawan' => 'nullable|string|max:255',
    //             // 'nama_anggota_keluarga' => 'nullable|string|max:255',
    //             // 'hubungan_keluarga' => 'nullable|string|max:255',
    //             'tanggal_pengobatan' => 'nullable|date',
    //             'urgensi' => 'nullable|string|in:Low,Medium,High',
    //             'deskripsi' => 'nullable|string',
    //             // 'nominal' => 'nullable|numeric',
    //             'rumah_sakit' => 'nullable|string|max:255',
    //             'no_surat_rs' => 'nullable|string|max:255',
    //             // 'keterangan_pengajuan' => 'nullable|string',
    //             'status_pengajuan' => 'nullable|numeric',
    //             // 'status_pengajuan' => 'nullable|string',
    //             // 'file' => 'nullable|file|mimes:jpg,png,pdf|max:2048'
    //         ]);
    //         // Create new RestitusiKaryawan record
    //         $restitusi = RestitusiKaryawan::create([
    //             'id_pengajuan' => $id_restitusi,
    //             'id_badge' => $validatedData['id_badge'],
    //             // 'nama_karyawan' => $validatedData['nama_karyawan'],
    //             // 'jabatan_karyawan' => $validatedData['jabatan_karyawan'],
    //             // 'nama_anggota_keluarga' => $validatedData['nama_anggota_keluarga'],
    //             // 'hubungan_keluarga' => $validatedData['hubungan_keluarga'],
    //             'deskripsi' => $validatedData['deskripsi'],
    //             // 'nominal' => $validatedData['nominal'],
    //             // 'nominal' => $cleanedValue,
    //             'rumah_sakit' => $validatedData['rumah_sakit'],
    //             'urgensi' => $validatedData['urgensi'],
    //             'no_surat_rs' => $validatedData['no_surat_rs'],
    //             'tanggal_pengobatan' => $validatedData['tanggal_pengobatan'],
    //             // 'keterangan_pengajuan' => $validatedData['keterangan_pengajuan'],
    //             'url_file' => $request->uploaded_files,
    //             // 'url_file' => $fileName,
    //             'status_pengajuan' => '1',
    //         ]);

    //         // Simpan rincian biaya
    //         foreach ($validatedData['nominal_pengajuan'] as $index => $nominal) {
    //             RincianBiaya::create([
    //                 'id_rincian_biaya' => rand(10, 99999999),
    //                 'id_badge' => $validatedData['id_badge'], // Ambil ID badge
    //                 'kategori' => "restitusi", // Sesuai kategori (Low, Medium, High)                    
    //                 'id_kategori' => $id_restitusi,//untuk parent dari restitusi
    //                 'rumah_sakit' => $validatedData['rumah_sakit'],
    //                 'no_surat_rs' => $validatedData['no_surat_rs'],
    //                 'deskripsi_biaya' => $validatedData['deskripsi_pengajuan'][$index] ?? '', // Deskripsi tiap rincian
    //                 'nominal_pengajuan' => str_replace(['Rp', '.', ','], '', $nominal), // Format nominal jadi angka
    //                 'status_rincian_biaya' => 1, // Status default
    //                 'created_by' => auth()->user()->id_user, // ID user yang membuat
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ]);
    //         }


            

    //         if (auth()->user()->role === 'superadmin') {
    //             return redirect('/admin/restitusi_karyawan')->with('success', 'Data berhasil disimpan.');
    //         } 
    //         return redirect('/restitusi_karyawan')->with('success', 'Data berhasil disimpan.');
            
    //     } catch (\Throwable $th) {
    //         // return redirect('/admin/restitusi_karyawan')->with('error', 'Terjadi kesalahan: ' . $th->getMessage());

    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Data gagal successfully',
    //             'data' => $th->getMessage()
    //         ], 200);
    //     }
    // }

    public function store(Request $request)
    {
        
        try {
            $id_restitusi = rand(10, 99999999);
            $validatedData = $request->validate([
                'id_badge' => 'required|string|max:255',
                'tanggal_pengobatan' => 'nullable|date',
                'urgensi' => 'nullable|string|in:Low,Medium,High',
                'deskripsi' => 'nullable|string',
                'rumah_sakit' => 'nullable|string|max:255',
                'no_surat_rs' => 'nullable|string|max:255',
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
                'no_surat_rs' => $validatedData['no_surat_rs'],
                'tanggal_pengobatan' => $validatedData['tanggal_pengobatan'],
                'url_file' => $request->uploaded_files,
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
                    'no_surat_rs' => $validatedData['no_surat_rs'],
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

    public function uploadExcel(Request $request){
        $validator = Validator::make($request->all(), [
            'file_excel' => 'required|mimes:xlsx,xls',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('toast_message', 'Validasi gagal. Silakan periksa kembali input Anda.');
        }
        
        // Proses unggah file
        if ($request->hasFile('file_excel')) {
            $path = $request->file('file_excel')->getRealPath();
            $data = Excel::toArray([], $request->file('file_excel'));

            if (!empty($data) && count($data[0]) > 0) {
                $totalProcessed = 0; // Total data yang diproses
                $successCount = 0;   // Data yang berhasil ditambahkan

                foreach ($data[0] as $key => $row) {
                    // Lewati baris pertama (header)
                    if ($key < 2) continue;
            
                    $totalProcessed++; // Tambahkan ke total data yang diproses

                    $badge = substr($row[3] ?? '', 0, 50);
                    $dataKaryawan = DataKaryawan::where('id_badge', $badge)->first();

                    if (!$dataKaryawan) continue;

                    try {

                        $tgl = Carbon::createFromFormat('d F Y', $tanggal)->format('Y-m-d');
                        $dataImp = [

                            // 'tanggal_pengobatan' => !empty($row[23]) ? Carbon::parse($row[23])->format('Y-m-d') : null,

                            'urgensi' => match ($row[24] ?? '') {
                                'Low' => 'Low',
                                'Medium' => 'Medium',
                                'High' => 'High',
                                default => null,
                            },


                            'status_pengajuan' => substr($row[27] ?? '', 0, 50),

                            'url_file' => '',

                            'updated_at' => now(),
                            'updated_by' => auth()->user()->role,
                            'created_at' => now(),
                            'created_by' => auth()->user()->role,
                        ];
            
                        dd(
                            $dataImp,
                            $row,
                            $badge,
                            $dataKaryawan,
                            4498,
                            $data[0],
                        );
                        RestitusiKaryawan::create($dataImp);
                        

                        $successCount++; // Tambahkan ke jumlah sukses
                    } catch (\Exception $e) {
                        // Log error atau abaikan jika terjadi kesalahan
                        \Log::error('Error adding data: ' . $e->getMessage());
                    }
                }
            }
            // Redirect dengan pesan jumlah sukses dan total
            return redirect()->back()->with('toast_message', 'success')->with(
                'toast_success',
                "$successCount dari $totalProcessed data berhasil diunggah!"
            );
        }

        return redirect()->back()->withErrors($validator)->withInput()->with('toast_message', 'Validasi gagal. Silakan periksa kembali input Anda.');
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

            // Log data awal
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

      
            $restitusi->update([
                'url_file' => json_encode($finalFiles),
                'rumah_sakit' => $request->rumah_sakit,
                'urgensi' => $request->urgensi,
                'no_surat_rs' => $request->no_surat_rs,
                'tanggal_pengobatan' => $request->tanggal_pengobatan,
                'keterangan_pengajuan' => $request->keterangan_pengajuan,
                'status_pengajuan' => 1,
                'reject_notes' => null,
                'deskripsi' => $request->deskripsi,
            ]);

            // $restitusi = RestitusiKaryawan::findOrFail($id);
            // $restitusi->update($validatedData);
            return redirect('/admin/restitusi_karyawan')->with('success', 'Data berhasil disimpan.');
            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'Data berhasil diupdate.',
            //     'request' => $request->all(),
            //     'filesFinal'=>$restitusi,
                
            // ]);
        } catch (\Throwable $th) {
            return redirect('/admin/restitusi_karyawan')->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
            // return response()->json([
            //     'status' => 'Failed',
            //     'message' => 'Data Gagal.',
            //     'request' => $request->all(),
            //     'message' => $th->getMessage(),
            //     // 'filesFinal'=>$finalFiles,
            // ]);
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
            Log::info("Request received for rejecting screening", $request->all());
    
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
    // public function approval_dr(Request $request, $id)
    // {
    //     try {
    //         // Temukan data restitusi karyawan berdasarkan ID
    //         $restitusi = RestitusiKaryawan::findOrFail($id);
    
    //         // dd($restitusi);
    //         // Lakukan logika persetujuan DR
    //         $restitusi->status_pengajuan = '3';
    //         $restitusi->save();

    //         return redirect('/admin/restitusi_karyawan')->with('success', 'Approval DR.');
    //     } catch (\Throwable $th) {
    //         return redirect('/admin/restitusi_karyawan')->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
    //     }
    // }

    public function approval_dr(Request $request, string $id)
    {
        try {
            Log::info('Uploading Attachment dari Approval Dokter: ' . $id . ', Request Data: ', $request->all());
    
            // Decode uploaded_files dan removed_files
            $uploadedFiles = $request->input('uploaded_files', '[]');
            $uploadedFiles = json_decode($uploadedFiles, true) ?? [];
    
            $removedFiles = $request->input('removed_files', '[]');
            $removedFiles = json_decode($removedFiles, true) ?? [];
    
            Log::info('Decoded Uploaded Files:', $uploadedFiles);
            Log::info('Decoded Removed Files:', $removedFiles);
    
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
    
            // Update database
            $restitusi->update([
                'url_file_dr' => json_encode(array_unique($currentFiles)),
                'status_pengajuan' => 3,
                'reject_notes' =>null
            ]);

            $biaya = RincianBiaya::create([
                'id_rincian_biaya' => rand(10, 99999999),
                'id_badge' => $restitusi->id_badge,
                'kategori' => "restitusi",
                //butuh input baru
                'nominal' => $request->nominal,
                'rumah_sakit' => $restitusi->rumah_sakit,
                'no_surat_rs' => $restitusi->no_surat_rs,
                'updated_by' => auth()->user()->role,
                'created_at' => now(),
                'created_by' => auth()->user()->role,
            ]);
    
            return redirect('/admin/restitusi_karyawan')->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $th) {
            return redirect('/admin/restitusi_karyawan')->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
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
            $restitusi->save();

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
}
