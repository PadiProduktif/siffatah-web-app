<?php

namespace App\Http\Controllers\PengajuanKlaim;

use App\Http\Controllers\Controller;
use App\Models\PengajuanKlaim\klaim_pengobatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class KlaimPengobatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {       
            $data['pengajuanKlaim'] = klaim_pengobatan::all();
            return view('dashboard/pengajuan-klaim/pengajuan-klaim-pengobatan', $data);


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
        $cleanedValue = str_replace(['Rp', '.', ','], '', $request->nominal);
        // Validate the request data
    
        try {
            // Handle file upload if present
            
    
            // Create a new klaim_pengobatan record
            $klaim = klaim_pengobatan::create([
                'id_klaim_pengobatan' => rand(10, 99999999),
                'id_badge' => $request->id_badge,
                'nama_karyawan' => $request->nama_karyawan,
                'unit_kerja' => $request->unit_kerja,
                'nama_asuransi' => $request->nama_asuransi,
                'rs_klinik' => $request->rs_klinik,
                'tanggal_pengajuan' => $request->tanggal_pengajuan,
                'nominal' => $cleanedValue,
                'deskripsi' => $request->deskripsi,
                'file_url' => $request->uploaded_files,
            ]);
            Log::info("Menambah data klaim Pengobatan Request Data: ", $request->all());
            // Return success response
            return redirect()->back()->with('success', 'Data berhasil ditambah!');
    
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
    public function uploadTemp(Request $request)
    {
    
        // return response()->json(['error' => 'No file uploaded'], 400);
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/PengajuanKlaim/Klaim_Pengobatan'), $fileName);
        
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
            $filePath = public_path("uploads/PengajuanKlaim/Klaim_Pengobatan/{$filename}");
            unlink($filePath);
            Log::info("File Terhapus dari Public Upload Klaim Pengobatan: " . json_encode($filename));
            return response()->json(['success' => true]);
        }
    
        return response()->json(['error' => 'File not found'], 404);
    }
    public function uploadExcel(Request $request){

        function convertIndonesianDate($tanggal)
        {
            $bulan = [
                'Januari' => 'January',
                'Februari' => 'February',
                'Maret' => 'March',
                'April' => 'April',
                'Mei' => 'May',
                'Juni' => 'June',
                'Juli' => 'July',
                'Agustus' => 'August',
                'September' => 'September',
                'Oktober' => 'October',
                'November' => 'November',
                'Desember' => 'December'
            ];

            foreach ($bulan as $indo => $eng) {
                $tanggal = str_replace($indo, $eng, $tanggal);
            }

            return $tanggal;
        }

            // Validasi file
        $validator = Validator::make($request->all(), [
            'file_excel' => 'required|mimes:xlsx,xls',
        ]);
        Carbon::setLocale('id');
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
                    $tanggal = convertIndonesianDate(($row[6]));
                    $tanggal_pengajuan = Carbon::createFromFormat('d F Y', $tanggal)->format('Y-m-d');
                    $cleanedValue = str_replace(['Rp.', '.'], '', $row[7]);
                    $floatValue = floatval($cleanedValue);
                    // Hanya masukkan nilai, abaikan jika panjang data terlalu besar
                    klaim_pengobatan::create([
                        'id_klaim_pengobatan' => rand(10, 99999999),
                        'id_badge' => substr($row[1] ?? '', 0, 50), // Pastikan panjang data sesuai tipe di database
                        'nama_karyawan' => substr($row[2] ?? '', 0, 1000),
                        'unit_kerja' => substr($row[3] ?? '', 0, 1000),
                        'nama_asuransi' => substr($row[4] ?? '', 0, 1000), // Perhatikan panjang maksimal
                        'rs_klinik' => $row[5] ?? null,
                        'tanggal_pengajuan' => $tanggal_pengajuan,
                        'nominal' => $floatValue,
                        'deskripsi' =>  substr($row[8] ?? '', 0, 1000),


                    ]);
                }
            }
            

            return redirect()->back()->with('success', 'Data berhasil diunggah!');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah file!');
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
       // Log request data untuk debug
       Log::info('Updating Pengajuan Klaim ID: ' . $id . ', Request Data: ', $request->all());

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
       $klaim = klaim_pengobatan::findOrFail($id);
       $currentFiles = json_decode($klaim->file_url, true) ?? [];

       // 1. Hapus file dari array dan juga file fisik
       if (!empty($removedFiles)) {
           foreach ($removedFiles as $file) {
               $filePath = public_path('uploads/PengajuanKlaim/Klaim_Pengobatan/' . $file);

               // Hapus file fisik jika ada
               if (file_exists($filePath)) {
                   unlink($filePath);
                   Log::info('File removed: ' . $filePath);
               }
           }

           // Hapus file dari array currentFiles
           $currentFiles = array_values(array_diff($currentFiles, $removedFiles));
           Log::info('Files after removal:', $currentFiles);
       }

       // 2. Tambahkan file baru ke currentFiles
       if (!empty($uploadedFiles)) {
           // Filter uploaded_files agar tidak ada file yang sudah dihapus
           $uploadedFiles = array_diff($uploadedFiles, $removedFiles);
       
           // Gabungkan file baru ke currentFiles
           $currentFiles = array_merge($currentFiles, $uploadedFiles);
           Log::info('Files after addition:', $currentFiles);
       }

       // Hilangkan duplikat nama file dan reset index
       $finalFiles = array_values(array_unique($currentFiles));

       // 3. Update data ke database
       $uang_klaim = str_replace(['Rp', '.', ','], '', $request->nominal);

       $klaim->update([
           'file_url' => json_encode($finalFiles),
           'id_badge' => $request->id_badge,
           'nama_karyawan' => $request->nama_karyawan,
           'unit_kerja' => $request->unit_kerja,
           'asuransi' => $request->asuransi,
           'rs_klinik' => $request->rs_klinik,
           'tanggal_pengajuan' => $request->tanggal_pengajuan,
           'nominal' => $uang_klaim,
           'deskripsi' => $request->deskripsi,
       ]);

       // Log hasil akhir
       Log::info('Final File URL:', $finalFiles);

       return response()->json([
           'status' => 'success',
           'message' => 'Data berhasil diupdate.',
           'data' => $klaim,
       ]);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            Log::info("Menghapus data dengan ID: {$id}"); // Log untuk debugging awal
            
            // Ambil data berdasarkan ID
            $klaim = klaim_pengobatan::findOrFail($id);
    
            // Hapus file attachment jika ada
            if ($klaim->file_url) {
                $fileUrls = json_decode($klaim->file_url, true); // Decode JSON ke array
                if (is_array($fileUrls)) {
                    foreach ($fileUrls as $file) {
                        $filePath = public_path("uploads/PengajuanKlaim/Klaim_Pengobatan/{$file}");
                        if (file_exists($filePath)) {
                            unlink($filePath); // Hapus file dari direktori
                            Log::info("File dihapus: {$filePath}");
                        }
                    }
                }
            }
    
            // Hapus data dari database
            $klaim->delete();
            Log::info("Data klaim Pengobatan dengan ID: {$id} berhasil dihapus.");
    
            return response()->json(['message' => 'Data dan file attachment berhasil dihapus.'], 200);
        } catch (\Exception $e) {
            Log::error("Error saat menghapus data dengan ID: {$id}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    
    }

    public function deleteMultiple(Request $request)
    {
        try {
            $ids = $request->input('ids'); // Ambil array ID dari request
            Log::info('IDs yang akan dihapus: ', $ids);
            
    
            // Ambil semua data berdasarkan ID
            $klaimList = klaim_pengobatan::whereIn('id_klaim_pengobatan', $ids)->get();
    
            // Hapus semua file attachment yang terkait
            foreach ($klaimList as $klaim) {
                if ($klaim->file_url) {
                    $fileUrls = json_decode($klaim->file_url, true);
                    if (is_array($fileUrls)) {
                        foreach ($fileUrls as $file) {
                            $filePath = public_path("uploads/PengajuanKlaim/Klaim_Pengobatan/{$file}");
                            if (file_exists($filePath)) {
                                unlink($filePath); // Hapus file
                                Log::info("File dihapus: {$filePath}");
                            }
                        }
                    }
                }
            }
    
            // Hapus data dari database
            klaim_pengobatan::whereIn('id_klaim_pengobatan', $ids)->delete();
    
            return response()->json(['message' => 'Data dan file attachment berhasil dihapus.'], 200);
        } catch (\Exception $e) {
            Log::error('Error saat menghapus data:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
    
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }
}
