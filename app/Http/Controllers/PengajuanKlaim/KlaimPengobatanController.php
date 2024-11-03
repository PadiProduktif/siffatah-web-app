<?php

namespace App\Http\Controllers\PengajuanKlaim;

use App\Http\Controllers\Controller;
use App\Models\PengajuanKlaim\klaim_pengobatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KlaimPengobatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Retrieve all klaim_pengobatan data
            $klaim = klaim_pengobatan::all();
    
            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Data retrieved successfully',
                'data' => $klaim
            ], 200);
    
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
            'unit_kerja' => 'nullable|string|max:255',
            'nama_asuransi' => 'nullable|string|max:255',
            'rs_klinik' => 'nullable|string|max:255',
            'tanggal_pengajuan' => 'nullable|date',
            'nominal' => 'nullable|numeric',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,png,pdf|max:2048'
        ]);
    
        try {
            // Handle file upload if present
            $fileName = null;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = rand(10, 99999999) . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/PengajuanKlaim/Klaim_Pengobatan/'), $fileName);
            }
    
            // Create a new klaim_pengobatan record
            $klaim = klaim_pengobatan::create([
                'id_klaim_pengobatan' => rand(10, 99999999),
                'id_badge' => $validatedData['id_badge'],
                'nama_karyawan' => $validatedData['nama_karyawan'],
                'unit_kerja' => $validatedData['unit_kerja'],
                'nama_asuransi' => $validatedData['nama_asuransi'],
                'rs_klinik' => $validatedData['rs_klinik'],
                'tanggal_pengajuan' => $validatedData['tanggal_pengajuan'],
                'nominal' => $validatedData['nominal'],
                'deskripsi' => $validatedData['deskripsi'],
                'file_url' => $fileName,
            ]);
    
            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Data successfully created',
                'data' => $klaim
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
        // Find the klaim by ID
        $klaim = klaim_pengobatan::find($id);
    
        // Check if klaim exists
        if (!$klaim) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found',
            ], 404);
        }
    
        // Return success response with klaim data
        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully',
            'data' => $klaim,
        ], 200);
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
            'unit_kerja' => 'nullable|string|max:255',
            'nama_asuransi' => 'nullable|string|max:255',
            'rs_klinik' => 'nullable|string|max:255',
            'tanggal_pengajuan' => 'nullable|date',
            'nominal' => 'nullable|numeric',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,png,pdf|max:2048'
        ]);
    
        try {
            // Find the klaim by ID or throw a 404 if not found
            $klaim = klaim_pengobatan::findOrFail($id);
    
            // Handle file upload if present
            if ($request->hasFile('file')) {
                // Delete the old file if it exists
                if ($klaim->file_url) {
                    $oldFile = public_path("uploads/PengajuanKlaim/Klaim_Pengobatan/{$klaim->file_url}");
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
    
                // Save the new file
                $file = $request->file('file');
                $fileName = rand(10, 99999999) . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/PengajuanKlaim/Klaim_Pengobatan'), $fileName);
                $validatedData['file_url'] = $fileName;
            }
    
            // Update klaim data
            $klaim->update($validatedData);
    
            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Data successfully updated',
                'data' => $klaim
            ], 200);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Return a 404 response if klaim is not found
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
            // Find the klaim by ID or throw a 404 if not found
            $klaim = klaim_pengobatan::findOrFail($id);
    
            // Delete associated file if it exists
            if ($klaim->file_url) {
                $filePath = public_path("uploads/PengajuanKlaim/Klaim_Pengobatan/{$klaim->file_url}");
                if (file_exists($filePath)) {
                    unlink($filePath); // Delete the file
                }
            }
    
            // Delete the klaim record from the database
            $klaim->delete();
    
            // Return a success response
            return response()->json([
                'status' => 'success',
                'message' => 'Data successfully deleted',
            ], 200);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Return a 404 response if klaim is not found
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found',
            ], 404);
    
        } catch (\Exception $e) {
            // Log error for debugging
            Log::error("Error deleting data: " . $e->getMessage());
    
            // Return a 500 response for any other error
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}
