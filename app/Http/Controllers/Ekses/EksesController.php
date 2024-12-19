<?php

namespace App\Http\Controllers\Ekses;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\Ekses\Ekses;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EksesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            
            $data['ekses'] = Ekses::all();
            
            
            return view('dashboard/ekses' ,$data); 

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




    public function store(Request $request)
    {
        $cleanedValue = str_replace(['Rp', '.', ','], '', $request->jumlah_pengajuan);

        try {
            // Simpan Data Utama
            $ekses = Ekses::create([
                'id_ekses' => rand(10, 99999999),
                'id_member' => $request->id_member,
                'id_badge' => $request->id_badge,
                'nama_karyawan' => $request->nama_karyawan,
                'unit_kerja' => $request->unit_kerja,
                'nama_pasien' => $request->nama_pasien,
                'deskripsi' => $request->deskripsi,
                'tanggal_pengajuan' => $request->tanggal_pengajuan,
                'jumlah_ekses' => $cleanedValue,
                'file_url' => $request->uploaded_files, // Default kosong, akan diupdate nanti
            ]);

            Log::info("Menambah data Ekses Request Data: ", $request->all());

            // Decode uploaded_files dari string JSON ke array
            $uploadedFiles = json_decode($request->uploaded_files, true) ?? [];

            // Validasi jika uploadedFiles adalah array
            if (is_array($uploadedFiles) && count($uploadedFiles) > 0) {
                $movedFiles = [];
                foreach ($uploadedFiles as $fileName) {
                    $tempPath = storage_path("app/public/temp/{$fileName}");
                    $finalPath = public_path("uploads/Ekses/{$fileName}");

                    if (file_exists($tempPath)) {
                        // Buat folder tujuan jika belum ada
                        if (!file_exists(public_path('uploads/Ekses'))) {
                            mkdir(public_path('uploads/Ekses'), 0755, true);
                        }
                        // Pindahkan file
                        rename($tempPath, $finalPath);
                        $movedFiles[] = $fileName;
                    }
                }

                // Log untuk debug
                Log::info("Files moved to final path: " . json_encode($movedFiles));

                // Simpan nama file ke dalam database
                if (!empty($movedFiles)) {
                    $ekses->file_url = json_encode($movedFiles);
                    $ekses->save();
                }
            } else {
                Log::warning("No uploaded files found or invalid format.");
            }

            return redirect()->back()->with('success', 'Data berhasil ditambah!');
        } catch (\Exception $e) {
            Log::error("Error Insert Ekses Item: ", ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function uploadTemp(Request $request)
    {
    
        // return response()->json(['error' => 'No file uploaded'], 400);
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/Ekses'), $fileName);
        
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
            $filePath = public_path("uploads/Ekses/{$filename}");
            unlink($filePath);
            Log::info("File Terhapus dari Public Upload Ekses: " . json_encode($filename));
            return response()->json(['success' => true]);
        }
    
        return response()->json(['error' => 'File not found'], 404);
    }
    
    /**
     * Store a newly created resource in storage.
     */


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
                    $tanggal = convertIndonesianDate(($row[7]));
                    $tanggal_pengajuan = Carbon::createFromFormat('d F Y', $tanggal)->format('Y-m-d');
                    $cleanedValue = str_replace(['Rp.', '.'], '', $row[8]);
                    $floatValue = floatval($cleanedValue);
                    // Hanya masukkan nilai, abaikan jika panjang data terlalu besar
                    Ekses::create([
                        'id_ekses' => rand(10, 99999999),
                        'id_member' => substr($row[1] ?? '', 0, 50), // Pastikan panjang data sesuai tipe di database
                        'id_badge' => substr($row[2] ?? '', 0, 1000),
                        'nama_karyawan' => substr($row[3] ?? '', 0, 1000),
                        'unit_kerja' => substr($row[4] ?? '', 0, 1000), // Perhatikan panjang maksimal
                        'nama_pasien' => $row[5] ?? null,
                        'deskripsi' => $row[6] ?? null,
                        'tanggal_pengajuan' => $tanggal_pengajuan,
                        'jumlah_ekses' => $floatValue
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
    

    public function update(Request $request, $id)
    {
        // Log request data untuk debug
        Log::info('Updating Ekses ID: ' . $id . ', Request Data: ', $request->all());

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

        // Ambil data ekses dari database
        $ekses = Ekses::findOrFail($id);
        $currentFiles = json_decode($ekses->file_url, true) ?? [];

        // 1. Hapus file dari array dan juga file fisik
        if (!empty($removedFiles)) {
            foreach ($removedFiles as $file) {
                $filePath = public_path('uploads/Ekses/' . $file);

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
        $uang_ekses = str_replace(['Rp', '.', ','], '', $request->jumlah_pengajuan);

        $ekses->update([
            'file_url' => json_encode($finalFiles),
            'id_member' => $request->id_member,
            'id_badge' => $request->id_badge,
            'nama_karyawan' => $request->nama_karyawan,
            'unit_kerja' => $request->unit_kerja,
            'nama_pasien' => $request->nama_pasien,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'jumlah_ekses' => $uang_ekses,
            'deskripsi' => $request->deskripsi,
        ]);

        // Log hasil akhir
        Log::info('Final File URL:', $finalFiles);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diupdate.',
            'data' => $ekses,
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
            $ekses = Ekses::findOrFail($id);
    
            // Hapus file attachment jika ada
            if ($ekses->file_url) {
                $fileUrls = json_decode($ekses->file_url, true); // Decode JSON ke array
                if (is_array($fileUrls)) {
                    foreach ($fileUrls as $file) {
                        $filePath = public_path("uploads/Ekses/{$file}");
                        if (file_exists($filePath)) {
                            unlink($filePath); // Hapus file dari direktori
                            Log::info("File dihapus: {$filePath}");
                        }
                    }
                }
            }
    
            // Hapus data dari database
            $ekses->delete();
            Log::info("Data dengan ID: {$id} berhasil dihapus.");
    
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
            $eksesList = Ekses::whereIn('id_ekses', $ids)->get();
    
            // Hapus semua file attachment yang terkait
            foreach ($eksesList as $ekses) {
                if ($ekses->file_url) {
                    $fileUrls = json_decode($ekses->file_url, true);
                    if (is_array($fileUrls)) {
                        foreach ($fileUrls as $file) {
                            $filePath = public_path("uploads/Ekses/{$file}");
                            if (file_exists($filePath)) {
                                unlink($filePath); // Hapus file
                                Log::info("File dihapus: {$filePath}");
                            }
                        }
                    }
                }
            }
    
            // Hapus data dari database
            Ekses::whereIn('id_ekses', $ids)->delete();
    
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
