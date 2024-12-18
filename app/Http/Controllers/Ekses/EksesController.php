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
                'file_url' => json_encode($request->uploaded_files),

            ]);
    
            // Pindahkan file dari folder sementara ke folder final
            if ($request->uploaded_files) {
                $uploadedFiles = [];
                foreach ($request->uploaded_files as $fileName) {
                    $tempPath = storage_path("app/public/temp/{$fileName}");
                    $finalPath = public_path("uploads/Ekses/{$fileName}");

                    if (file_exists($tempPath)) {
                        if (!file_exists(public_path('uploads/Ekses'))) {
                            mkdir(public_path('uploads/Ekses'), 0755, true);
                        }
                        rename($tempPath, $finalPath);
                        $uploadedFiles[] = $fileName;
                    }
                }

                // Debug to ensure filenames are collected
                Log::info("Uploaded files: " . json_encode($uploadedFiles));

                if (!empty($uploadedFiles)) {
                    $ekses->file_url = json_encode($uploadedFiles); // Simpan file sebagai JSON
                    $ekses->save(); // Simpan model ke database
                }
            }
    
            return redirect()->back()->with('success', 'Data berhasil ditambah!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }

    public function uploadTemp(Request $request)
    {
        // $request->validate([
        //     'file' => 'required|file|mimes:jpeg,jpg,png,pdf|max:5120',
        // ]);
    
        // if ($request->hasFile('file')) {
        //     $file = $request->file('file');
        //     $filename = time() . '_' . $file->getClientOriginalName();
        //     $file->storeAs('public/temp', $filename);
    
        //     return response()->json(['fileName' => $filename]);
        // }
    
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
            return response()->json(['success' => true]);
        }
    
        return response()->json(['error' => 'File not found'], 404);
    }
    
    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $cleanedValue = str_replace(['Rp', ',', ' '], '', $request->jumlah_pengajuan);

    //     // Validation
    //     $request->validate([
    //         'id_badge' => 'required',
    //         'nama_karyawan' => 'required',
    //         'id_member' => 'required',
    //         'unit_kerja' => 'required',
    //         'nama_pasien' => 'required',
    //         // 'file' => 'sometimes|file|mimes:jpeg,png,jpg,pdf|max:2048', // Optional file validation
    //     ]);


    //     try {
    //         $ekses = Ekses::create([
    //             'id_ekses' => rand(10, 99999999), // Use auto-incremented ID if possible
    //             'id_member' => $request->id_member,
    //             'id_badge' => $request->id_badge,
    //             'nama_karyawan' => $request->nama_karyawan,
    //             'unit_kerja' => $request->unit_kerja,
    //             'nama_pasien' => $request->nama_pasien,
    //             'deskripsi' => $request->deskripsi,
    //             'tanggal_pengajuan' => $request->tanggal_pengajuan,
    //             'jumlah_ekses' => $cleanedValue,
    //             // 'file_url' => $fileName,
    //         ]);
    //         return redirect()->back()->with('success', 'Data berhasil ditambah!');
    //     } catch (\Throwable $th) {
    //         return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
    //     }
        

    // }

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
    // public function update(Request $request, string $id)
    // {
    //     $cleanedValue = str_replace(['Rp', '.', ' '], '', $request->jumlah_pengajuan);
    //     // Validate the request input
    //     $request->validate([
    //         'id_member' => 'required',
    //         'id_badge' => 'required',
    //         'nama_karyawan' => 'required',
    //         'unit_kerja' => 'required',
    //         'nama_pasien' => 'required',
    //         // 'file' => 'sometimes|file|mimes:jpeg,png,jpg,pdf|max:2048'
    //     ]);
    //     if (empty($request->id_badge) || empty($request->id_member) || empty($request->nama_karyawan || empty($request->unit_kerja)) || empty($request->nama_pasien)) {

    //         return redirect()->back()->with('failed', 'Data gagal diperbarui! ID badge, nama karyawan, dan cost center tidak boleh kosong');
    //     }

    //     // Find the record by ID
    //     $ekses = Ekses::where('id_ekses', $id)->first();

    //     if (!$ekses) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Data not found.'
    //         ], 404); // 404 Not Found
    //         return redirect()->back()->with('failed', 'Data gagal diperbarui! Data tidak ditemukan');
    //     }

    //     // // Handle file upload
    //     // if ($request->hasFile('file')) {
    //     //     // Delete old file if it exists
    //     //     if ($ekses->file_url) {
    //     //         $oldFilePath = public_path("uploads/Ekses/{$ekses->file_url}");
    //     //         if (file_exists($oldFilePath)) {
    //     //             unlink($oldFilePath);
    //     //         }
    //     //     }

    //     //     // Save new file
    //     //     $file = $request->file('file');
    //     //     $fileName = rand(10, 99999999) . '_' . $file->getClientOriginalName();
    //     //     $file->move(public_path('uploads/Ekses/'), $fileName);
    //     //     $ekses->file_url = $fileName;
    //     // }

    //     // Update record fields
    //     $ekses->id_member = $request->input('id_member');
    //     $ekses->id_badge = $request->input('id_badge');
    //     $ekses->nama_karyawan = $request->input('nama_karyawan');
    //     $ekses->unit_kerja = $request->input('unit_kerja');
    //     $ekses->nama_pasien = $request->input('nama_pasien');
    //     $ekses->deskripsi = $request->input('deskripsi');
    //     $ekses->tanggal_pengajuan = $request->input('tanggal_pengajuan');
    //     $ekses->jumlah_ekses = $cleanedValue;
        
    //     // // Save changes
    //     $ekses->save();
    //     return redirect()->back()->with('success', 'Data berhasil di perbarui!');
        
    // }

    // public function update(Request $request, $id)
    // {
    //     Log::info("Updating Ekses ID: {$id}, Request Data: ", $request->all());

    //     try {
    //         $ekses = Ekses::findOrFail($id);

    //         // Logika Update Field Utama
    //         $ekses->update([
    //             'id_member' => $request->id_member,
    //             'id_badge' => $request->id_badge,
    //             'nama_karyawan' => $request->nama_karyawan,
    //             'unit_kerja' => $request->unit_kerja,
    //             'nama_pasien' => $request->nama_pasien,
    //             'deskripsi' => $request->deskripsi,
    //             'tanggal_pengajuan' => $request->tanggal_pengajuan,
    //             'jumlah_ekses' => str_replace(['Rp', '.', ','], '', $request->jumlah_pengajuan),
    //         ]);

    //         // Hapus file lama jika ada
    //         if ($request->has('removed_files') && !empty($request->removed_files)) {
    //             $filesToRemove = json_decode($request->removed_files, true) ?? [];
    //             Log::info("Files to Remove: ", $filesToRemove);
            
    //             foreach ($filesToRemove as $file) {
    //                 $filePath = public_path("uploads/Ekses/{$file}");
    //                 if (file_exists($filePath)) {
    //                     unlink($filePath);
    //                     Log::info("File deleted: {$filePath}");
    //                 }
    //             }
            
    //             // Update kolom file_url dengan sisa file
    //             $remainingFiles = array_diff(json_decode($ekses->file_url, true) ?? [], $filesToRemove);
    //             $ekses->file_url = json_encode($remainingFiles);
    //             $ekses->save();
    //         } else {
    //             Log::info("No files to remove.");
    //         }

    //         // Simpan file baru
    //         if ($request->hasFile('uploaded_files')) {
    //             $uploadedFiles = [];
    //             foreach ($request->file('uploaded_files') as $file) {
    //                 $fileName = time() . '_' . $file->getClientOriginalName();
    //                 $file->move(public_path('uploads/Ekses/'), $fileName);
    //                 $uploadedFiles[] = $fileName;
    //                 // $pesan = "harusnya sudah terupload brai";
    //             }


    //             // die();
    //             // Gabungkan file lama dan baru
    //             // $finalFiles = array_merge(json_decode($ekses->file_url, true) ?? [], $uploadedFiles);
    //             // $ekses->file_url = json_encode($finalFiles);
    //             // $ekses->save();

    //             // Log::info("Files successfully updated: ", $finalFiles);
    //         }
    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Data retrieved successfully.',
    //             'data' => $request->all(),
    //             // 'pesan' => $uploadedFiles,
    //         ], 200); // 200 OK

    //         // return redirect()->back()->with('success', 'Data berhasil diperbarui.');
    //     } catch (\Exception $e) {
    //         Log::error("Error updating Ekses ID: {$id}", ['error' => $e->getMessage()]);
    //         return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data.');
    //     }
    // }

    // public function update(Request $request, $id)
    // {
    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Data retrieved successfully.',
    //         'data' => $request->all(),
    //     ], 200); // 200 OK
    //     // $data = Ekses::find($id);
    
    //     // // Handle Removed Files
    //     // if ($request->filled('removed_files')) {
    //     //     $removedFiles = json_decode($request->input('removed_files'), true);
    //     //     foreach ($removedFiles as $file) {
    //     //         $filePath = public_path("uploads/Ekses/{$file}");
    //     //         if (file_exists($filePath)) {
    //     //             unlink($filePath);
    //     //             // $remainingFiles = array_diff(json_decode($ekses->file_url, true) ?? [], $filesToRemove);
    //     //             // $data->file_url = json_encode($remainingFiles);
    //     //             // $data->save();
    //     //             Log::info("File deleted: {$filePath}");
    //     //             // $pesan = "Harusnya sudah Terhapus";
    //     //         }else{
    //     //             // $pesan = "filenya tidak ada bor";
    //     //         }
                
    //     //     }
    //     //      return response()->json([
    //     //     'status' => 'Test',
    //     //     'message' => 'Data Need To Be Test.',
    //     //     'data' => $removedFiles,
    //     //     // 'pesan' => $pesan
    //     //     ], 200); // 200 OKK
    //     // }
    
    //     //     if ($request->hasFile('uploaded_files')) {
    //     //         $uploadedFiles = [];
    //     //         foreach ($request->file('uploaded_files') as $file) {
    //     //             Log::info('File found in request: ' . $file->getClientOriginalName());
            
    //     //             $fileName = time() . '_' . $file->getClientOriginalName();
    //     //             $destinationPath = public_path('uploads/Ekses/');
            
    //     //             // Debug apakah path tujuan benar
    //     //             Log::info('Destination path: ' . $destinationPath);
            
    //     //             // Pindahkan file
    //     //             $file->move($destinationPath, $fileName);
            
    //     //             Log::info('File moved successfully: ' . $fileName);
            
    //     //             $uploadedFiles[] = $fileName;
    //     //         }

    //     //         return response()->json([
    //     //             'status' => 'Test',
    //     //             'message' => 'Data Need To Be Test.',
    //     //             'data' => $uploadedFiles,
    //     //             // 'pesan' => $pesan
    //     //             ], 200); // 200 OKK
    //     //     }
    
    //     // // Simpan Data File
    //     // $currentFiles = json_decode($data->file_url, true) ?? [];
    //     // $data->file_url = json_encode(array_merge($currentFiles, $uploadedFiles));
    //     // $data->save();
    
    //     // return redirect()->back()->with('success', 'Data berhasil diperbarui.');
    // }

    public function update(Request $request, $id)
    {
        // Log request data untuk debug
        Log::info('Updating Ekses ID: ' . $id . ', Request Data: ', $request->all());
    
        // Ambil data dari request
        // Decode uploaded_files dan removed_files dari string JSON ke array
        $uploadedFiles = $request->input('uploaded_files', '[]'); // Default ke string JSON kosong
        $uploadedFiles = json_decode($uploadedFiles, true); // Konversi ke array

        $removedFiles = $request->input('removed_files', '[]'); // Default ke string JSON kosong
        $removedFiles = json_decode($removedFiles, true); // Konversi ke array

        // Log untuk memastikan hasil decode
        Log::info('Decoded Uploaded Files:', $uploadedFiles);
        Log::info('Decoded Removed Files:', $removedFiles);

        // Pastikan kedua variabel adalah array
        if (!is_array($uploadedFiles)) {
            $uploadedFiles = [];
        }

        if (!is_array($removedFiles)) {
            $removedFiles = [];
        }
        // Ambil data ekses dari database
        $ekses = Ekses::findOrFail($id);
        $currentFiles = json_decode($ekses->file_url, true) ?? []; // File yang sudah tersimpan di DB
    
        // 1. **Remove file**: Hapus file dari array dan juga file fisik jika ada
        if (!empty($removedFiles)) {
            foreach ($removedFiles as $file) {
                $filePath = public_path('uploads/Ekses/' . $file);
                if (file_exists($filePath)) {
                    unlink($filePath); // Hapus file fisik
                    Log::info('File removed: ' . $filePath);
                }
            }
    
            // Hilangkan file yang dihapus dari array `currentFiles`
            $currentFiles = array_diff($currentFiles, $removedFiles);
        }
    
        // 2. **Add new files**: Tambahkan nama file baru ke array `currentFiles`
        if (!empty($uploadedFiles)) {
            $currentFiles = array_merge($currentFiles, $uploadedFiles);
            Log::info('New files added: ', $uploadedFiles);
        }
    
        // Hilangkan duplikat nama file
        $finalFiles = array_unique($currentFiles);
    
        // 3. Update data ke database
        $uang_ekses = str_replace(['Rp', '.', ','], '', $request->jumlah_pengajuan);
    
        $ekses->update([
            'file_url' => json_encode($finalFiles), // Simpan file yang terbaru
            'id_member' => $request->id_member,
            'id_badge' => $request->id_badge,
            'nama_karyawan' => $request->nama_karyawan,
            'unit_kerja' => $request->unit_kerja,
            'nama_pasien' => $request->nama_pasien,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'jumlah_ekses' => $uang_ekses,
            'deskripsi' => $request->deskripsi,
        ]);
    
        // Log final file list
        Log::info('Final File URL:', $finalFiles);
    
        // Return response
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
