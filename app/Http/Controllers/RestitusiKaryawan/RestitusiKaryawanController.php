<?php

namespace App\Http\Controllers\RestitusiKaryawan;

use App\Http\Controllers\Controller;
use App\Models\RestitusiKaryawan\RestitusiKaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RestitusiKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Retrieve all restitusi data
            $restitusi = RestitusiKaryawan::all();
    
            // Return success response
            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'Data retrieved successfully',
            //     'data' => $restitusi
            // ], 200);
            return view('dashboard/restitusi-karyawan');
    
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error("Error retrieving data: " . $e->getMessage());
    
            // Return error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve data',
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
        // Validate the request data
        $validatedData = $request->validate([
            'id_badge' => 'required|string|max:255',
            'nama_karyawan' => 'required|string|max:255',
            'jabatan_karyawan' => 'nullable|string|max:255',
            'nama_anggota_keluarga' => 'nullable|string|max:255',
            'hubungan_keluarga' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'nominal' => 'nullable|numeric',
            'rumah_sakit' => 'nullable|string|max:255',
            'urgensi' => 'nullable|string|max:255',
            'no_surat_rs' => 'nullable|string|max:255',
            'tanggal_pengobatan' => 'nullable|date',
            'keterangan_pengajuan' => 'nullable|string',
            'status_pengajuan' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,png,pdf|max:2048'
        ]);
    
        try {
            // Handle file upload if present
            $fileName = null;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = rand(10, 99999999) . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/Restitusi_Karyawan/'), $fileName);
            }
    
            // Create new RestitusiKaryawan record
            $restitusi = RestitusiKaryawan::create([
                'id_pengajuan' => rand(10, 99999999),
                'id_badge' => $validatedData['id_badge'],
                'nama_karyawan' => $validatedData['nama_karyawan'],
                'jabatan_karyawan' => $validatedData['jabatan_karyawan'],
                'nama_anggota_keluarga' => $validatedData['nama_anggota_keluarga'],
                'hubungan_keluarga' => $validatedData['hubungan_keluarga'],
                'deskripsi' => $validatedData['deskripsi'],
                'nominal' => $validatedData['nominal'],
                'rumah_sakit' => $validatedData['rumah_sakit'],
                'urgensi' => $validatedData['urgensi'],
                'no_surat_rs' => $validatedData['no_surat_rs'],
                'tanggal_pengobatan' => $validatedData['tanggal_pengobatan'],
                'keterangan_pengajuan' => $validatedData['keterangan_pengajuan'],
                'url_file' => $fileName,
                'status_pengajuan' => $validatedData['status_pengajuan'],
            ]);
    
            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Data successfully created',
                'data' => $restitusi
            ], 201);
    
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error("Error creating data: " . $e->getMessage());
    
            // Return error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create data',
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
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'id_badge' => 'required|string|max:255',
            'nama_karyawan' => 'required|string|max:255',
            'jabatan_karyawan' => 'nullable|string|max:255',
            'nama_anggota_keluarga' => 'nullable|string|max:255',
            'hubungan_keluarga' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'nominal' => 'nullable|numeric',
            'rumah_sakit' => 'nullable|string|max:255',
            'urgensi' => 'nullable|string|max:255',
            'no_surat_rs' => 'nullable|string|max:255',
            'tanggal_pengobatan' => 'nullable|date',
            'keterangan_pengajuan' => 'nullable|string',
            'status_pengajuan' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,png,pdf|max:2048'
        ]);
    
        try {
            // Find the restitusi by ID or throw a 404 if not found
            $restitusi = RestitusiKaryawan::findOrFail($id);
    
            // Handle file upload if present
            if ($request->hasFile('file')) {
                // Delete the old file if it exists
                if ($restitusi->url_file) {
                    $oldFilePath = public_path("uploads/Restitusi_Karyawan/{$restitusi->url_file}");
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
    
                // Save the new file
                $file = $request->file('file');
                $fileName = rand(10, 99999999) . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/Restitusi_Karyawan/'), $fileName);
                $validatedData['url_file'] = $fileName;
            }
    
            // Update restitusi data
            $restitusi->update($validatedData);
    
            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Data successfully updated',
                'data' => $restitusi
            ], 200);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Return a 404 response if restitusi is not found
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found',
            ], 404);
    
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error("Error updating data: " . $e->getMessage());
    
            // Return a 500 response for any other errors
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find the restitusi by ID or throw a 404 if not found
            $restitusi = RestitusiKaryawan::findOrFail($id);
    
            // Delete associated file if it exists
            if ($restitusi->url_file) {
                $filePath = public_path("uploads/Restitusi_Karyawan/{$restitusi->url_file}");
                if (file_exists($filePath)) {
                    unlink($filePath); // Delete the file
                }
            }
    
            // Delete the restitusi record from the database
            $restitusi->delete();
    
            // Return a success response
            return response()->json([
                'status' => 'success',
                'message' => 'Data successfully deleted',
            ], 200);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Return a 404 response if restitusi is not found
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found',
            ], 404);
    
        } catch (\Exception $e) {
            // Log error for debugging
            Log::error("Error deleting data: " . $e->getMessage());
    
            // Return a 500 response for any other errors
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}
