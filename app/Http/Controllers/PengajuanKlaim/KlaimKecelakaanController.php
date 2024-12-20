<?php

namespace App\Http\Controllers\PengajuanKlaim;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanKlaim\klaim_kecelakaan;
use App\Models\MasterData\DataKaryawan;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;


class KlaimKecelakaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            
            
            
            
            
            $dataKaryawan = DataKaryawan::all(); // Ambil semua data karyawan
            // dd(4498);
            // Retrieve all klaim_kecelakaan data
            $data['pengajuanKlaim'] = Klaim_kecelakaan::all();

            // $data = [
            //     'dataKlaim' => $dataKlaim,
            //     'dataKaryawan' => $dataKaryawan
            // ];
            // dd($dataKaryawan);

            // return view('dashboard/pengajuan-klaim', compact('klaim'));
            return view('dashboard/pengajuan-klaim/pengajuan-klaim-kecelakaan', $data);
            // return view('dashboard/pengajuan-klaim');

            // // Return success response
            // return response()->json([
            //     'status' => 'success',
            //     'message' => 'Data retrieved successfully',
            //     'data' => $klaim
            // ], 200);

        } catch (\Exception $e) {
            // Log error for debugging
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
        // return response()->json([
        //     'status' => 'data test',
        //     'message' => 'Test',
        //     'data' => json_decode($request->uploaded_files),
        // ], 200);
        // die();
        // Validate required fields and file
        $validatedData = $request->validate([
            'id_badge' => 'required|string|max:255',
            'nama_karyawan' => 'required|string|max:255',
            'unit_kerja' => 'nullable|string|max:255',
            'nama_asuransi' => 'nullable|string|max:255',
            'rs_klinik' => 'nullable|string|max:255',
            'tanggal_kejadian' => 'nullable|date',
            'nama_keluarga' => 'nullable|string|max:255',
            'hubungan_keluarga' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            // 'file_url' => 'nullable|file|mimes:jpg,png,pdf|max:2048'
        ]);

        try {
            // Handle file upload if present

            // Create new klaim_kecelakaan record
            $klaim = Klaim_kecelakaan::create([
                'id_klaim_kecelakaan' => rand(10, 99999999),
                'id_badge' => $validatedData['id_badge'],
                'nama_karyawan' => $validatedData['nama_karyawan'],
                'unit_kerja' => $validatedData['unit_kerja'],
                'nama_asuransi' => $validatedData['nama_asuransi'],
                'rs_klinik' => $validatedData['rs_klinik'],
                'tanggal_kejadian' => $validatedData['tanggal_kejadian'],
                'nama_keluarga' => $validatedData['nama_keluarga'],
                'hubungan_keluarga' => $validatedData['hubungan_keluarga'],
                'deskripsi' => $validatedData['deskripsi'],
                'file_url' => $request->uploaded_files,
            ]);
            Log::info("Menambah data Ekses Request Data: ", $request->all());

            // // Decode uploaded_files dari string JSON ke array
            // $uploadedFiles = json_decode($request->uploaded_files, true) ?? [];

            // // Validasi jika uploadedFiles adalah array
            // if (is_array($uploadedFiles) && count($uploadedFiles) > 0) {
            //     $movedFiles = [];
            //     foreach ($uploadedFiles as $fileName) {
            //         $tempPath = storage_path("app/public/temp/{$fileName}");
            //         $finalPath = public_path("uploads/Ekses/{$fileName}");

            //         if (file_exists($tempPath)) {
            //             // Buat folder tujuan jika belum ada
            //             if (!file_exists(public_path('uploads/Ekses'))) {
            //                 mkdir(public_path('uploads/Ekses'), 0755, true);
            //             }
            //             // Pindahkan file
            //             rename($tempPath, $finalPath);
            //             $movedFiles[] = $fileName;
            //         }
            //     }

            //     // Log untuk debug
            //     Log::info("Files moved to final path: " . json_encode($movedFiles));

            //     // Simpan nama file ke dalam database
            //     if (!empty($movedFiles)) {
            //         $ekses->file_url = json_encode($movedFiles);
            //         $ekses->save();
            //     }
            // } else {
            //     Log::warning("No uploaded files found or invalid format.");
            // }

            return redirect()->back()->with('success', 'Data berhasil ditambah!');

        } catch (\Exception $e) {
            // Log error for debugging
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
            $file->move(public_path('uploads/PengajuanKlaim/klaim_Kecelakaan'), $fileName);
        
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
            Log::info("File Terhapus dari Public Upload Ekses Temp: " . json_encode($filename));
            return response()->json(['success' => true]);
        }else{
            $filePath = public_path("uploads/PengajuanKlaim/klaim_Kecelakaan/{$filename}");
            unlink($filePath);
            Log::info("File Terhapus dari Public Upload Ekses: " . json_encode($filename));
            return response()->json(['success' => true]);
        }
    
        return response()->json(['error' => 'File not found'], 404);
    }

    public function uploadExcel(Request $request){


            // Validasi file
        $validator = Validator::make($request->all(), [
            'file_excel' => 'required|mimes:xlsx,xls',
        ]);
        
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Proses unggah file
        if ($request->hasFile('file_excel')) {
            $path = $request->file('file_excel')->getRealPath();
            $data = Excel::toArray([], $request->file('file_excel'));

            // Validasi apakah data tidak kosong
            if (!empty($data) && count($data[0]) > 0) {
                foreach ($data[0] as $key => $row) {
                    // Lewati baris pertama (header)
                    if ($key < 1) {
                        continue;
                    }
                    if (is_numeric($row[6])) {
                        $tanggal_kejadian = $this->excelDateToDate($row[6]);
                    }
                    // $tanggal = convertIndonesianDate(($row[7]));
                    // $tanggal_pengajuan = Carbon::createFromFormat('d F Y', $tanggal)->format('Y-m-d');
                    $cleanedValue = str_replace(['Rp.', '.'], '', $row[8]);
                    $floatValue = floatval($cleanedValue);
                    // Hanya masukkan nilai, abaikan jika panjang data terlalu besar
                    Klaim_kecelakaan::create([
                        'id_klaim_kecelakaan' => rand(10, 99999999),
                        'id_badge' => substr($row[1] ?? '', 0, 50), // Pastikan panjang data sesuai tipe di database
                        'nama_karyawan' => substr($row[2] ?? '', 0, 1000),
                        'unit_kerja' => substr($row[3] ?? '', 0, 1000),
                        'nama_asuransi' => substr($row[4] ?? '', 0, 1000), // Perhatikan panjang maksimal
                        'rs_klinik' => $row[5] ?? null,
                        'tanggal_kejadian' => $tanggal_kejadian,
                        'nama_keluarga' => $row[7] ?? null,
                        'hubungan_keluarga' => $row[8] ?? null,
                        'deskripsi' => $row[9] ?? null,
                    ]);
                }
            }
            

            return redirect()->back()->with('success', 'Data berhasil diunggah!');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah file!');
    }

    private function excelDateToDate($excelDate)
    {
        return Carbon::createFromFormat('Y-m-d', '1900-01-01')->addDays($excelDate - 2)->format('Y-m-d');
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
        $klaim = Klaim_kecelakaan::where('id_klaim_kecelakaan', $id)->first();

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
            'tanggal_kejadian' => 'nullable|date',
            'nama_keluarga' => 'nullable|string|max:255',
            'hubungan_keluarga' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:jpg,png,pdf|max:2048'
        ]);
    
        try {
            // Find the klaim by ID or return 404 if not found
            $klaim = Klaim_kecelakaan::findOrFail($id);
    
            // Handle file upload if present
            if ($request->hasFile('file')) {
                // Delete the old file if it exists
                if ($klaim->file_url) {
                    $oldFile = public_path("uploads/PengajuanKlaim/klaim_Kecelakaan/{$klaim->file_url}");
                    if (file_exists($oldFile)) {
                        unlink($oldFile);
                    }
                }
    
                // Store the new file
                $file = $request->file('file');
                $fileName = rand(10, 99999999) . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/PengajuanKlaim/klaim_Kecelakaan'), $fileName);
                $klaim->file_url = $fileName;
            }
    
            // Update klaim data with validated data
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
            $klaim = Klaim_kecelakaan::findOrFail($id);
    
            // Delete associated file if it exists
            if ($klaim->file_url) {
                $filePath = public_path("uploads/PengajuanKlaim/klaim_Kecelakaan/{$klaim->file_url}");
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