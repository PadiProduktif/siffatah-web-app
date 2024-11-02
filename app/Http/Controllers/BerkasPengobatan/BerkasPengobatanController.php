<?php

namespace App\Http\Controllers\BerkasPengobatan;

use App\Http\Controllers\Controller;
use App\Models\BerkasPengobatan\BerkasPengobatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BerkasPengobatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            
            $obat = BerkasPengobatan::all();
    
            
            return response()->json([
                'status' => 'success',
                'message' => 'Data retrieved successfully.',
                'data' => $obat
            ], 200);
            
        } catch (\Exception $e) {
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        
    $validatedData = $request->validate([
        'id_badge' => 'required',
        'nama_karyawan' => 'required',
        'file' => 'sometimes|file|mimes:jpeg,png,jpg,pdf|max:2048' 
    ]);

    try {
        
        $fileName = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = rand(10, 99999999) . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/BerkasPengobatan/'), $fileName);
        }

        
        $obat = BerkasPengobatan::create([
            'id_berkas_pengobatan' => rand(10, 99999999),
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
            'message' => 'Data has been successfully added.',
            'file_message' => $fileName ? 'File uploaded successfully.' : 'No file uploaded.',
            'data' => $obat
        ], 201); 

    } catch (\Exception $e) {
        
        return response()->json([
            'status' => 'error',
            'message' => 'Failed to add data.',
            'error' => $e->getMessage()
        ], 500); 
    }

                
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

