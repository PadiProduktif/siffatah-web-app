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

    return view('dashboard/restitusi-karyawan', compact('restitusi'));
}

    

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                // 'id_badge' => 'required|string|max:255',
                // 'nama_karyawan' => 'required|string|max:255',
                // 'jabatan_karyawan' => 'nullable|string|max:255',
                // 'nama_anggota_keluarga' => 'nullable|string|max:255',
                // 'hubungan_keluarga' => 'nullable|string|max:255',
                'tanggal_pengobatan' => 'nullable|date',
                'urgensi' => 'nullable|string|in:Low,Medium,High',
                'deskripsi' => 'nullable|string',
                'nominal' => 'nullable|numeric',
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
                'nominal' => 0,
                'rumah_sakit' => $validatedData['rumah_sakit'],
                'urgensi' => $validatedData['urgensi'],
                'no_surat_rs' => $validatedData['no_surat_rs'],
                'tanggal_pengobatan' => $validatedData['tanggal_pengobatan'],
                'keterangan_pengajuan' => $validatedData['keterangan_pengajuan'],
                // 'url_file' => $fileName,
                'status_pengajuan' => '1',
            ]);
            return redirect('/admin/restitusi_karyawan')->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $th) {
            // Menangkap pesan kesalahan dan menampilkannya
            return redirect('/admin/restitusi_karyawan')->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }

        // dd($validatedData);
    
        //     // Handle file upload if present
        //     $fileName = null;
        //     if ($request->hasFile('file')) {
        //         $file = $request->file('file');
        //         $fileName = rand(10, 99999999) . '_' . $file->getClientOriginalName();
        //         $file->move(public_path('uploads/Restitusi_Karyawan/'), $fileName);
        //     }
    
    
        //     // Return success response
        //     return response()->json([
        //         'status' => 'success',
        //         'message' => 'Data successfully created',
        //         'data' => $restitusi
        //     ], 201);
    
        // } catch (\Exception $e) {
        //     // Log the error for debugging
        //     Log::error("Error creating data: " . $e->getMessage());
    
        //     // Return error response
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Failed to create data',
        //         'error' => $e->getMessage()
        //     ], 500);
        // }
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
