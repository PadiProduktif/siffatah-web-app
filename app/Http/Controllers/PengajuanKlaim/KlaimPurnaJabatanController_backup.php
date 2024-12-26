<?php

namespace App\Http\Controllers\PengajuanKlaim;

use App\Http\Controllers\Controller;
use App\Models\PengajuanKlaim\klaim_purnajabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class KlaimPurnaJabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {       
            $data['pengajuanKlaim'] = klaim_purnajabatan::all();
            return view('dashboard/pengajuan-klaim/pengajuan-klaim-purnajabatan', $data);


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
                    if (is_numeric($row[4])||is_numeric($row[3])) {
                        $mulai_asuransi = $this->excelDateToDate($row[4]);
                        $tanggal_lahir = $this->excelDateToDate($row[3]);
                    }
                    if (is_numeric($row[5])) {
                        $akhir_asuransi = $this->excelDateToDate($row[5]);
                    }else {
                        $akhir_asuransi = $row[5];
                    }
                    $premi_tahunan = str_replace(['Rp.', '.'], '', $row[8]);
                    $uang_tertanggung = str_replace(['Rp.', '.'], '', $row[9]);
                    // Hanya masukkan nilai, abaikan jika panjang data terlalu besar
                    klaim_purnajabatan::create([
                        'id_klaim_purnajabatan' => rand(10, 99999999), // Pastikan panjang data sesuai tipe di database
                        'nama' => substr($row[1] ?? '', 0, 1000),
                        'jabatan' => substr($row[2] ?? '', 0, 1000),
                        'tanggal_lahir' => $tanggal_lahir, // Perhatikan panjang maksimal
                        'mulai_asuransi' => $mulai_asuransi,
                        'akhir_asuransi' => $akhir_asuransi,
                        'nama_asuransi' => $row[6] ?? null,
                        'no_polis' => $row[7] ?? null,
                        'premi_tahunan' =>  $premi_tahunan,
                        'uang_tertanggung' =>  $uang_tertanggung,
                        'deskripsi' =>  $row[10] ?? null,


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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $premi_tahunan = str_replace(['Rp', '.', ','], '', $request->premi_tahunan);
        $uang_tertanggung = str_replace(['Rp', '.', ','], '', $request->uang_tertanggung);
        // Validate the request data

        try {
            // Handle file upload if present
            

            // Create a new klaim_pengobatan record
            $klaim = klaim_purnajabatan::create([
                'id_klaim_purnajabatan' => rand(10, 99999999),
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'tanggal_lahir' => $request->tanggal_lahir,
                'mulai_asuransi' => $request->mulai_asuransi,
                'akhir_asuransi' => $request->akhir_asuransi,
                'nama_asuransi' => $request->nama_asuransi,
                'no_polis' => $request->no_polis,
                'premi_tahunan' => $premi_tahunan,            
                'uang_tertanggung' => $uang_tertanggung,
                'deskripsi' => $request->deskripsi,
                'file_url' => $request->uploaded_files,
            ]);
            Log::info("Menambah data klaim PurnaJabatan Request Data: ", $request->all());
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
            $file->move(public_path('uploads/PengajuanKlaim/Klaim_PurnaJabatan'), $fileName);
        
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
            Log::info("File Terhapus dari Public Upload Klaim Purna Jabatan Temp: " . json_encode($filename));
            return response()->json(['success' => true]);
        }else{
            $filePath = public_path("uploads/PengajuanKlaim/Klaim_PurnaJabatan/{$filename}");
            unlink($filePath);
            Log::info("File Terhapus dari Public Upload Klaim PurnaJabatan: " . json_encode($filename));
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
        // Find the klaim by ID
        $klaim = klaim_purnajabatan::find($id);
    
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
        // Log request data untuk debug
       Log::info('Updating Pengajuan Klaim PurnaJabatan ID: ' . $id . ', Request Data: ', $request->all());

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
       $klaim = klaim_purnajabatan::findOrFail($id);
       $currentFiles = json_decode($klaim->file_url, true) ?? [];

       // 1. Hapus file dari array dan juga file fisik
       if (!empty($removedFiles)) {
           foreach ($removedFiles as $file) {
               $filePath = public_path('uploads/PengajuanKlaim/Klaim_PurnaJabatan/' . $file);

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
        $premi_tahunan = str_replace(['Rp', '.', ','], '', $request->uang_tertanggung);
        $uang_tertanggung = str_replace(['Rp', '.', ','], '', $request->uang_tertanggung);
        $klaim->update([
            'file_url' => json_encode($finalFiles),
            'nama' => $request->nama,
            'jabatan' => $request->jabatan,
            'tanggal_lahir' => $request->tanggal_lahir,
            'mulai_asuransi' => $request->mulai_asuransi,
            'akhir_asuransi' => $request->akhir_asuransi,
            'nama_asuransi' => $request->nama_asuransi,
            'no_polis' => $request->no_polis,
            'premi_tahunan' => $premi_tahunan,            
            'uang_tertanggung' => $uang_tertanggung,
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
            Log::info("Menghapus data PurnaJabatan dengan ID: {$id}"); // Log untuk debugging awal
            
            // Ambil data berdasarkan ID
            $klaim = klaim_purnajabatan::findOrFail($id);
    
            // Hapus file attachment jika ada
            if ($klaim->file_url) {
                $fileUrls = json_decode($klaim->file_url, true); // Decode JSON ke array
                if (is_array($fileUrls)) {
                    foreach ($fileUrls as $file) {
                        $filePath = public_path("uploads/PengajuanKlaim/Klaim_PurnaJabatan/{$file}");
                        if (file_exists($filePath)) {
                            unlink($filePath); // Hapus file dari direktori
                            Log::info("File dihapus: {$filePath}");
                        }
                    }
                }
            }
    
            // Hapus data dari database
            $klaim->delete();
            Log::info("Data klaim Purna Jabatan dengan ID: {$id} berhasil dihapus.");
    
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
            $klaimList = klaim_purnajabatan::whereIn('id_klaim_purnajabatan', $ids)->get();
    
            // Hapus semua file attachment yang terkait
            foreach ($klaimList as $klaim) {
                if ($klaim->file_url) {
                    $fileUrls = json_decode($klaim->file_url, true);
                    if (is_array($fileUrls)) {
                        foreach ($fileUrls as $file) {
                            $filePath = public_path("uploads/PengajuanKlaim/Klaim_PurnaJabatan/{$file}");
                            if (file_exists($filePath)) {
                                unlink($filePath); // Hapus file
                                Log::info("File dihapus: {$filePath}");
                            }
                        }
                    }
                }
            }
    
            // Hapus data dari database
            klaim_purnajabatan::whereIn('id_klaim_purnajabatan', $ids)->delete();
    
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
