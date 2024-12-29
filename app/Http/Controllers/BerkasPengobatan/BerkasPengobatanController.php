<?php

namespace App\Http\Controllers\BerkasPengobatan;

use App\Http\Controllers\Controller;
use App\Models\BerkasPengobatan\BerkasPengobatan;
use App\Models\MasterData\DataKaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class BerkasPengobatanController extends Controller
{
    public function index()
    {
        try {
            $obat = BerkasPengobatan::orderBy('id_berkas_pengobatan', 'desc')->get();
            $karyawan = DataKaryawan::orderBy('nama_karyawan', 'asc')->get();
    
            // Mengembalikan view dengan data yang diambil
            return view('dashboard.berkas-pengobatan', [
                'obat' => $obat,
                'karyawan' => $karyawan,
            ]);
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Error fetching data in BerkasPengobatanController@index: ' . $e->getMessage());
    
            // Redirect ke halaman error atau tampilkan pesan ke user
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data.');
        }
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        try {
            $cleanedValue = str_replace(['Rp', '.', ','], '', $request->nominal);
        
            $obat = BerkasPengobatan::create([
                'id_berkas_pengobatan' => rand(10, 99999999),
                'id_badge' => $request->input('id_badge'),
                'nama_karyawan' => $request->input('nama_karyawan'),
                'jabatan_karyawan' => $request->input('jabatan_karyawan'),
                'nama_anggota_keluarga' => $request->input('nama_anggota_keluarga'),
                'hubungan_keluarga' => $request->input('hubungan_keluarga'),
                'deskripsi' => $request->input('deskripsi'),
                'nominal' => $cleanedValue,
                'rs_klinik' => $request->input('rs_klinik'),
                'urgensi' => $request->input('urgensi'),
                'no_surat_rs' => $request->input('no_surat_rs'),
                'tanggal_pengobatan' => $request->input('tanggal_pengobatan'),
                'deskripsi' => $request->input('deskripsi'),
                'file_url' => $request->uploaded_files,
                'updated_at' => now(),
                'updated_by' => auth()->user()->role,
                'created_at' => now(),
                'created_by' => auth()->user()->role,
            ]);
            
            // Log::info("Menambah data berkas pengobatan Request Data: ", $request->all());
            
            Log::info("Menambah data Berkas Pengobatan Request Data: ", $request->all());
            return redirect('/admin/berkas-pengobatan/')->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $e) {
            // return redirect('/admin/berkas-pengobatan')->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
            Log::error("Error creating data: " . $e->getMessage());

            // Return error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function uploadTemp(Request $request)
    {
    
        // return response()->json(['error' => 'No file uploaded'], 400);
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/BerkasPengobatan'), $fileName);
        
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
            Log::info("File Terhapus dari Public Upload Klaim Kecelakaan Temp: " . json_encode($filename));
            return response()->json(['success' => true]);
        }else{
            $filePath = public_path("uploads/BerkasPengobatan/{$filename}");
            unlink($filePath);
            Log::info("File Terhapus dari Public Upload Klaim Pengobatan: " . json_encode($filename));
            return response()->json(['success' => true]);
        }
    
        return response()->json(['error' => 'File not found'], 404);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $obat = BerkasPengobatan::where('id_berkas_pengobatan', $id)->first();

    
        if ($obat) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data retrieved successfully.',
                'data' => $obat
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found for the provided ID.',
            ], 404); // 404 Not Found
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    // Validate required fields
    $validatedData = $request->validate([
        'id_badge' => 'required',
        'nama_karyawan' => 'required',
        'file' => 'sometimes|file|mimes:jpeg,png,jpg,pdf|max:2048' // File validation
    ]);

    try {
        $obat = BerkasPengobatan::where('id_berkas_pengobatan', $id)->firstOrFail();

        
        $fileName = $obat->file_url; 
        if ($request->hasFile('file')) {
            
            $oldFilePath = public_path("uploads/BerkasPengobatan/{$obat->file_url}");
            if ($obat->file_url && File::exists($oldFilePath)) {
                File::delete($oldFilePath);
            }

            
            $file = $request->file('file');
            $fileName = rand(10,99999999) . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/BerkasPengobatan/'), $fileName);
        }

        
        $obat->update([
            'id_badge' => $request->input('id_badge'),
            'nama_karyawan' => $request->input('nama_karyawan'),
            'jabatan_karyawan' => $request->input('jabatan_karyawan'),
            'nama_anggota_keluarga' => $request->input('nama_anggota_keluarga'),
            'hubungan_keluarga' => $request->input('hubungan_keluarga'),
            'deskripsi' => $request->input('deskripsi'),
            'nominal' => $request->input('nominal'),
            'rs_klinik' => $request->input('rs_klinik'),
            'urgensi' => $request->input('urgensi'),
            'no_surat_rs' => $request->input('no_surat_rs'),
            'tanggal_pengobatan' => $request->input('tanggal_pengobatan'),
            'status' => $request->input('status'),
            'keterangan' => $request->input('keterangan'),
            'file_url' => $fileName,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui',
            'data' => $obat
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to update data.',
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        $obat = BerkasPengobatan::where('id_berkas_pengobatan', $id)->first();

        
        if (!$obat) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found.',
            ], 404); 
        }

        
        if ($obat->file_url && file_exists(public_path("uploads/BerkasPengobatan/{$obat->file_url}"))) {
            unlink(public_path("uploads/BerkasPengobatan/{$obat->file_url}"));
        }

        
        $obat->delete();

        
        return response()->json([
            'status' => 'success',
            'message' => 'Data deleted successfully.',
        ], 200); 
    }

}

