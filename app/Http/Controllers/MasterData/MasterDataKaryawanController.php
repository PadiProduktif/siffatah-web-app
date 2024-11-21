<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterData\DataKaryawan;
use Illuminate\Http\Request;

class MasterDataKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $karyawan = DataKaryawan::all();
            return view('extras/master-data-karyawan', compact('karyawan'));
        } catch (\Exception $e) {


            // Return error response with 500 status code
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

        dd(4498);
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'id_badge' => 'required|string|max:255',
            'nama_karyawan' => 'required|string|max:255',
            'gelar_depan' => 'nullable|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'gelar_belakang' => 'nullable|string|max:255',
            'pendidikan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:500',
            'agama' => 'nullable|string|max:255',
            'status_pernikahan' => 'nullable|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|string|max:50',
            'keluarga' => 'nullable|string|max:500',
            // 'foto_diri' => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
            // 'file_ktp' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
            // 'file_kk' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
            // 'buku_nikah' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
            // 'akta_kelahiran' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
            // 'npwp' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
            // 'lamaran_pekerjaan' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        try {
            // Helper function for file upload
            $uploadFile = function ($file, $directory) {
                if ($file) {
                    $fileName = rand(10, 99999999) . '_' . $file->getClientOriginalName();
                    $file->move(public_path("uploads/karyawan/{$directory}/"), $fileName);
                    return $fileName;
                }
                return null;
            };

            // Upload files
            // $foto_diri_fileName = $uploadFile($request->file('foto_diri'), 'foto_diri');
            // $file_ktp_fileName = $uploadFile($request->file('file_ktp'), 'file_ktp');
            // $file_kk_fileName = $uploadFile($request->file('file_kk'), 'file_kk');
            // $buku_nikah_fileName = $uploadFile($request->file('buku_nikah'), 'buku_nikah');
            // $akta_kelahiran_fileName = $uploadFile($request->file('akta_kelahiran'), 'akta_kelahiran');
            // $npwp_fileName = $uploadFile($request->file('npwp'), 'npwp');
            // $lamaran_pekerjaan_fileName = $uploadFile($request->file('lamaran_pekerjaan'), 'lamaran_pekerjaan');

            // Create new karyawan record
            $karyawan = DataKaryawan::create([
                // 'id_karyawan' => rand(10, 99999999),
                'id_badge' => $validatedData['id_badge'],
                'nama_karyawan' => $validatedData['nama_karyawan'],
                // 'gelar_depan' => $validatedData['gelar_depan'],
                // 'nama_lengkap' => $validatedData['nama_lengkap'],
                // 'gelar_belakang' => $validatedData['gelar_belakang'],
                // 'pendidikan' => $validatedData['pendidikan'],
                // 'alamat' => $validatedData['alamat'],
                // 'agama' => $validatedData['agama'],
                // 'status_pernikahan' => $validatedData['status_pernikahan'],
                // 'tempat_lahir' => $validatedData['tempat_lahir'],
                // 'tanggal_lahir' => $validatedData['tanggal_lahir'],
                // 'jenis_kelamin' => $validatedData['jenis_kelamin'],
                // 'keluarga' => $validatedData['keluarga'],

                // 'url_foto_diri' => $foto_diri_fileName,
                // 'url_file_ktp' => $file_ktp_fileName,
                // 'url_file_kk' => $file_kk_fileName,
                // 'url_file_buku_nikah' => $buku_nikah_fileName,
                // 'url_file_akta_kelahiran' => $akta_kelahiran_fileName,
                // 'url_npwp' => $npwp_fileName,
                // 'url_lamaran_pekerjaan' => $lamaran_pekerjaan_fileName,
            ]);


            // Return success response
            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'Data successfully created.',
            //     'data' => $karyawan
            // ], 201);

            // Redirect ke halaman master data karyawan dengan pesan sukses
            return redirect('/admin/master_data_karyawan')->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to store data.',
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
        // Find karyawan by ID
        $karyawan = DataKaryawan::where('id_karyawan', $id)->first();

        // Check if the karyawan exists
        if ($karyawan) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data retrieved successfully.',
                'data' => $karyawan
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
        // Validate the request
        $validatedData = $request->validate([
            'id_badge' => 'required|string|max:255',
            'nama_karyawan' => 'required|string|max:255',
            'gelar_depan' => 'nullable|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'gelar_belakang' => 'nullable|string|max:255',
            'pendidikan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:500',
            'agama' => 'nullable|string|max:255',
            'status_pernikahan' => 'nullable|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date',
            'jenis_kelamin' => 'nullable|string|max:50',
            'foto_diri' => 'nullable|file|mimes:jpg,png,jpeg|max:2048',
            'file_ktp' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'file_kk' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'buku_nikah' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'akta_kelahiran' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'npwp' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'lamaran_pekerjaan' => 'nullable|file|mimes:pdf|max:2048'
        ]);

        try {
            $karyawan = DataKaryawan::findOrFail($id); // Fetch or fail if not found

            // Helper function to handle file update
            $updateFile = function ($file, $oldFilePath, $directory) {
                if ($file) {
                    if ($oldFilePath) {
                        $oldFile = public_path("uploads/karyawan/{$directory}/{$oldFilePath}");
                        if (file_exists($oldFile)) {
                            unlink($oldFile); // Delete old file if exists
                        }
                    }
                    $fileName = rand(10, 99999999) . '_' . $file->getClientOriginalName();
                    $file->move(public_path("uploads/karyawan/{$directory}/"), $fileName);
                    return $fileName;
                }
                return $oldFilePath;
            };

            // Update file fields with helper function
            $karyawan->url_foto_diri = $updateFile($request->file('foto_diri'), $karyawan->url_foto_diri, 'foto_diri');
            $karyawan->url_file_ktp = $updateFile($request->file('file_ktp'), $karyawan->url_file_ktp, 'file_ktp');
            $karyawan->url_file_kk = $updateFile($request->file('file_kk'), $karyawan->url_file_kk, 'file_kk');
            $karyawan->url_file_buku_nikah = $updateFile($request->file('buku_nikah'), $karyawan->url_file_buku_nikah, 'buku_nikah');
            $karyawan->url_file_akta_kelahiran = $updateFile($request->file('akta_kelahiran'), $karyawan->url_file_akta_kelahiran, 'akta_kelahiran');
            $karyawan->url_npwp = $updateFile($request->file('npwp'), $karyawan->url_npwp, 'npwp');
            $karyawan->url_lamaran_pekerjaan = $updateFile($request->file('lamaran_pekerjaan'), $karyawan->url_lamaran_pekerjaan, 'lamaran_pekerjaan');

            // Update other fields
            $karyawan->fill($validatedData);
            $karyawan->save();

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'Data updated successfully.',
                'data' => $karyawan
            ], 200);

        } catch (\Exception $e) {
            

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
        // Find the karyawan record by ID
        $karyawan = DataKaryawan::find($id);
    
        // Check if karyawan exists
        if (!$karyawan) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found',
            ], 404);
        }
    
        // Delete associated files if they exist
        $files = [
            'foto_diri' => $karyawan->url_foto_diri,
            'file_ktp' => $karyawan->url_file_ktp,
            'file_kk' => $karyawan->url_file_kk,
            'buku_nikah' => $karyawan->url_file_buku_nikah,
            'akta_kelahiran' => $karyawan->url_file_akta_kelahiran,
            'npwp' => $karyawan->url_npwp,
            'lamaran_pekerjaan' => $karyawan->url_lamaran_pekerjaan,
        ];
    
        foreach ($files as $directory => $fileName) {
            if ($fileName) {
                $filePath = public_path("uploads/karyawan/{$directory}/{$fileName}");
                if (file_exists($filePath)) {
                    unlink($filePath); // Delete file if it exists
                }
            }
        }
    
        // Delete the karyawan record from the database
        $karyawan->delete();
    
        
        return redirect('/admin/master_data_karyawan')->with('success', 'Data berhasil dihapus.');
        // Return a success response
        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'User deleted successfully',
        // ], 200);
    }
    
}
