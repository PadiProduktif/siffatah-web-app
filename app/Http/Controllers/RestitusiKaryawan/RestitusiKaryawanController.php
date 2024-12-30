<?php

namespace App\Http\Controllers\RestitusiKaryawan;

use App\Http\Controllers\Controller;
use App\Models\MasterData\DataKaryawan;
use App\Models\RestitusiKaryawan\RestitusiKaryawan;
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
        }

        // Urutkan hasil secara descending
        $restitusi = $query->orderBy('table_pengajuan_reimburse.id_pengajuan', 'desc')->get();

        // return view('dashboard/restitusi-karyawan', compact('restitusi'));
        
        $karyawan = DataKaryawan::orderBy('nama_karyawan', 'asc')->get();
        
        // Mengembalikan view dengan data yang diambil
        return view('dashboard/restitusi-karyawan', [
            'restitusi' => $restitusi,
            'karyawan' => $karyawan,
        ]);
    }

    public function store(Request $request)
    {

        $cleanedValue = str_replace(['Rp', '.', ','], '', $request->nominal);
        try {
            $validatedData = $request->validate([
                'id_badge' => 'required|string|max:255',
                // 'nama_karyawan' => 'required|string|max:255',
                // 'jabatan_karyawan' => 'nullable|string|max:255',
                // 'nama_anggota_keluarga' => 'nullable|string|max:255',
                // 'hubungan_keluarga' => 'nullable|string|max:255',
                'tanggal_pengobatan' => 'nullable|date',
                'urgensi' => 'nullable|string|in:Low,Medium,High',
                'deskripsi' => 'nullable|string',
                // 'nominal' => 'nullable|numeric',
                'rumah_sakit' => 'nullable|string|max:255',
                'no_surat_rs' => 'nullable|string|max:255',
                'keterangan_pengajuan' => 'nullable|string',
                'status_pengajuan' => 'nullable|numeric',
                // 'status_pengajuan' => 'nullable|string',
                // 'file' => 'nullable|file|mimes:jpg,png,pdf|max:2048'
            ]);
            // Create new RestitusiKaryawan record
            $restitusi = RestitusiKaryawan::create([
                'id_pengajuan' => rand(10, 99999999),
                'id_badge' => $validatedData['id_badge'],
                // 'nama_karyawan' => $validatedData['nama_karyawan'],
                // 'jabatan_karyawan' => $validatedData['jabatan_karyawan'],
                // 'nama_anggota_keluarga' => $validatedData['nama_anggota_keluarga'],
                // 'hubungan_keluarga' => $validatedData['hubungan_keluarga'],
                'deskripsi' => $validatedData['deskripsi'],
                // 'nominal' => $validatedData['nominal'],
                'nominal' => $cleanedValue,
                'rumah_sakit' => $validatedData['rumah_sakit'],
                'urgensi' => $validatedData['urgensi'],
                'no_surat_rs' => $validatedData['no_surat_rs'],
                'tanggal_pengobatan' => $validatedData['tanggal_pengobatan'],
                'keterangan_pengajuan' => $validatedData['keterangan_pengajuan'],
                'url_file' => $request->uploaded_files,
                // 'url_file' => $fileName,
                'status_pengajuan' => '1',
            ]);
            return redirect('/admin/restitusi_karyawan')->with('success', 'Data berhasil disimpan.');
            
        } catch (\Throwable $th) {
            return redirect('/admin/restitusi_karyawan')->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
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
            //     'status' => 'success',
            //     'message' => 'Data Gagal.',
            //     'request' => $request->all(),
            //     'message' => $th->getMessage(),
            //     // 'filesFinal'=>$finalFiles,
            // ]);
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
    
    
    public function approval_dr(Request $request, $id)
    {
        try {
            // Temukan data restitusi karyawan berdasarkan ID
            $restitusi = RestitusiKaryawan::findOrFail($id);
    
            // dd($restitusi);
            // Lakukan logika persetujuan DR
            $restitusi->status_pengajuan = '2';
            $restitusi->save();

            return redirect('/admin/restitusi_karyawan')->with('success', 'Approval DR.');
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
            $restitusi->status_pengajuan = '3';
            $restitusi->save();

            return redirect('/admin/restitusi_karyawan')->with('success', 'Approval DR.');
        } catch (\Throwable $th) {
            return redirect('/admin/restitusi_karyawan')->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }
    }
}
