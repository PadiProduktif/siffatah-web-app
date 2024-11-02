<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterData\DataNonKaryawan;

class MasterDataNonKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Retrieve all non-karyawan data
            $non_karyawan = DataNonKaryawan::all();

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Data retrieved successfully.',
                'data' => $non_karyawan
            ], 200);

        } catch (\Exception $e) {
            // Log the error for debugging

            // Return error response
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
        // Validate required fields
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'nullable|string|max:10',
            'hubungan_keluarga' => 'nullable|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'pendidikan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:500',
            'agama' => 'nullable|string|max:255',
            'status_pernikahan' => 'nullable|string|max:255',
            'pekerjaan' => 'nullable|string|max:255',
            'nik' => 'nullable|string|max:20',
            'kewarganegaraan' => 'nullable|string|max:50',
            'url_foto_diri' => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
            'id_karyawan_terkait' => 'nullable|integer',
        ]);
    
        try {
            // Handle file upload if present
            $url_foto_diri_fileName = null;
            if ($request->hasFile('url_foto_diri')) {
                $url_foto_diri = $request->file('url_foto_diri');
                $url_foto_diri_fileName = rand(10, 99999999) . '_' . $url_foto_diri->getClientOriginalName();
                $url_foto_diri->move(public_path('uploads/non_karyawan/url_foto_diri/'), $url_foto_diri_fileName);
            }
    
            // Create a new record in DataNonKaryawan
            $non_karyawan = DataNonKaryawan::create([
                'id_non_karyawan' => rand(10, 99999999),
                'nama' => $validatedData['nama'],
                'jenis_kelamin' => $validatedData['jenis_kelamin'],
                'hubungan_keluarga' => $validatedData['hubungan_keluarga'],
                'tempat_lahir' => $validatedData['tempat_lahir'],
                'tanggal_lahir' => $validatedData['tanggal_lahir'],
                'pendidikan' => $validatedData['pendidikan'],
                'alamat' => $validatedData['alamat'],
                'agama' => $validatedData['agama'],
                'status_pernikahan' => $validatedData['status_pernikahan'],
                'pekerjaan' => $validatedData['pekerjaan'],
                'nik' => $validatedData['nik'],
                'kewarganegaraan' => $validatedData['kewarganegaraan'],
                'url_foto_diri' => $url_foto_diri_fileName,
                'id_karyawan_terkait' => $validatedData['id_karyawan_terkait'],
            ]);
    
            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Data successfully created.',
                'data' => $non_karyawan
            ], 201);
    
        } catch (\Exception $e) {
            // Log the error for debugging
    
            // Return error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create data.',
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
        // Find the record by ID
        $non_karyawan = DataNonKaryawan::where('id_non_karyawan', $id)->first();
    
        // Check if the record exists
        if ($non_karyawan) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data retrieved successfully.',
                'data' => $non_karyawan
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found.',
            ], 404);
        }
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate required fields
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'nullable|string|max:10',
            'hubungan_keluarga' => 'nullable|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'pendidikan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:500',
            'agama' => 'nullable|string|max:255',
            'status_pernikahan' => 'nullable|string|max:255',
            'pekerjaan' => 'nullable|string|max:255',
            'nik' => 'nullable|string|max:20',
            'kewarganegaraan' => 'nullable|string|max:50',
            'foto_diri' => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
            'id_karyawan_terkait' => 'nullable|integer',
        ]);
    
        try {
            // Find the non-karyawan record by ID or fail with 404
            $non_karyawan = DataNonKaryawan::findOrFail($id);
    
            // Handle file upload if present
            if ($request->hasFile('foto_diri')) {
                // Delete the old file if it exists
                if ($non_karyawan->url_foto_diri) {
                    $oldFile = public_path("uploads/non_karyawan/url_foto_diri/{$non_karyawan->url_foto_diri}");
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
    
                // Save the new file
                $file = $request->file('foto_diri');
                $fileName = rand(10, 99999999) . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/non_karyawan/url_foto_diri/'), $fileName);
                $non_karyawan->url_foto_diri = $fileName;
            }
    
            // Update other fields
            $non_karyawan->update($validatedData);
    
            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Data updated successfully',
                'data' => $non_karyawan
            ], 200);
    
        } catch (\Exception $e) {
            // Log error for debugging
    
            // Return error response
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
        try {
            // Find the non-karyawan record by ID or throw a 404 if not found
            $non_karyawan = DataNonKaryawan::findOrFail($id);
    
            // Delete associated file if it exists
            if ($non_karyawan->url_foto_diri) {
                $filePath = public_path("uploads/non_karyawan/url_foto_diri/{$non_karyawan->url_foto_diri}");
                if (file_exists($filePath)) {
                    unlink($filePath); // Delete the file
                }
            }
    
            // Delete the non-karyawan record from the database
            $non_karyawan->delete();
    
            // Return a success response
            return response()->json([
                'status' => 'success',
                'message' => 'Data successfully deleted',
            ], 200);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Return a 404 response if the record is not found
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found',
            ], 404);
    
        } catch (\Exception $e) {
            // Log error for debugging
    
            // Return a 500 response for any other error
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
}
