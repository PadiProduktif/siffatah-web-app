<?php

namespace App\Http\Controllers\Ekses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ekses\Ekses;

class EksesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            
            $ekses = Ekses::all();

            
            return response()->json([
                'status' => 'success',
                'message' => 'Data retrieved successfully.',
                'data' => $ekses
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
        // Validation
        $request->validate([
            'id_badge' => 'required',
            'nama_karyawan' => 'required',
            'id_member' => 'required',
            'unit_kerja' => 'required',
            'nama_pasien' => 'required',
            'file' => 'sometimes|file|mimes:jpeg,png,jpg,pdf|max:2048', // Optional file validation
        ]);

        // Handle file upload if present
        $fileName = null;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = rand(10, 99999999) . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/Ekses/'), $fileName);
        }

        // Create the record in the database
        $ekses = Ekses::create([
            'id_ekses' => rand(10, 99999999), // Use auto-incremented ID if possible
            'id_member' => $request->id_member,
            'id_badge' => $request->id_badge,
            'nama_karyawan' => $request->nama_karyawan,
            'unit_kerja' => $request->unit_kerja,
            'nama_pasien' => $request->nama_pasien,
            'deskripsi' => $request->deskripsi,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'jumlah_ekses' => $request->jumlah_ekses,
            'file_url' => $fileName,
        ]);

        // Success response
        return response()->json([
            'status' => 'success',
            'message' => 'Data successfully added.',
            'data' => $ekses
        ], 201); // 201 Created
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
        // Retrieve the record
        $ekses = Ekses::where('id_ekses', $id)->first();

        // Check if the record exists
        if (!$ekses) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found.',
            ], 404); // 404 Not Found
        }

        // Success response with data
        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully.',
            'data' => $ekses
        ], 200); // 200 OK
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request input
        $request->validate([
            'id_member' => 'required',
            'id_badge' => 'required',
            'nama_karyawan' => 'required',
            'unit_kerja' => 'required',
            'nama_pasien' => 'required',
            'file' => 'sometimes|file|mimes:jpeg,png,jpg,pdf|max:2048'
        ]);

        // Find the record by ID
        $ekses = Ekses::where('id_ekses', $id)->first();

        if (!$ekses) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found.'
            ], 404); // 404 Not Found
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old file if it exists
            if ($ekses->file_url) {
                $oldFilePath = public_path("uploads/Ekses/{$ekses->file_url}");
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            // Save new file
            $file = $request->file('file');
            $fileName = rand(10, 99999999) . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/Ekses/'), $fileName);
            $ekses->file_url = $fileName;
        }

        // Update record fields
        $ekses->id_member = $request->input('id_member');
        $ekses->id_badge = $request->input('id_badge');
        $ekses->nama_karyawan = $request->input('nama_karyawan');
        $ekses->unit_kerja = $request->input('unit_kerja');
        $ekses->nama_pasien = $request->input('nama_pasien');
        $ekses->deskripsi = $request->input('deskripsi');
        $ekses->tanggal_pengajuan = $request->input('tanggal_pengajuan');
        $ekses->jumlah_ekses = $request->input('jumlah_ekses');

        // Save changes
        $ekses->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data updated successfully.',
            'data' => $ekses
        ], 200); // 200 OK
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the record by ID
        $ekses = Ekses::where('id_ekses', $id)->first();

        // Check if the record exists
        if (!$ekses) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found.'
            ], 404); // 404 Not Found
        }

        // If file exists, delete it
        if ($ekses->file_url) {
            $filePath = public_path("uploads/Ekses/{$ekses->file_url}");
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Delete the record from the database
        $ekses->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data deleted successfully.'
        ], 200); // 200 OK
    }

}
