<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterData\DataKaryawan;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use \DateTime;
use Illuminate\Http\Request;

class MasterDataKaryawanController extends Controller
{
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
    public function detail($id=null)
    {
        // Cari data karyawan berdasarkan ID
        $dataKaryawan = DataKaryawan::find($id);
        $dataKeluarga = [
            'pasangan' => [],
            'anak' => [],
        ];
        $dataKeluarga['pasangan'][] = [
            'nama' => 'Fulanah',
            'NIK' => '12313112123',
            'status' => 'istri',
            'badge_parent' => $dataKaryawan['id_badge'],
        ];
        $dataKeluarga['anak'][] = [
            'nama' => 'Fulanah',
            'NIK' => '12313112123',
            'status' => 'anak',
            'tgl_lahir' => '14 Agustus',
            'badge_parent' => $dataKaryawan['id_badge'],
        ];
        $dataKeluarga['anak'][] = [
            'nama' => 'Fulan',
            'NIK' => '5645646456',
            'status' => 'anak',
            'badge_parent' => $dataKaryawan['id_badge'],
        ];
        $dataKaryawan['keluarga'] = $dataKeluarga;
        $dataImages = [
            [
                'name' => 'KTP',
                'url' => 'https://picsum.photos/id/237/200/300'
            ],
            [
                'name' => 'KK',
                'url' => 'https://picsum.photos/id/237/200/300'
            ],
        ];
        $dataKaryawan['files'] = $dataImages;

        // dd($dataKaryawan);
        // dd(
        //     $id,
        //     $dataKaryawan,
        // );
        return view('extras/master-data-karyawan-detail', compact('dataKaryawan'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_badge' => 'required|string|max:255',
            'nama_karyawan' => 'required|string|max:255',
            'gelar_depan' => 'nullable|string|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'gelar_belakang' => 'nullable|string|max:255',
            'pendidikan' => 'nullable|string|max:255',
            'alamat' => 'nullable|string|max:500',
            'agama' => 'nullable|string|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu', // Validasi nilai tertentu
            'status_pernikahan' => 'nullable|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tanggal_lahir' => 'nullable|date|before:today', // Pastikan tanggal lahir sebelum hari ini
            'jenis_kelamin' => 'nullable|string|in:Laki-laki,Perempuan',
        ]);

        dd($validatedData);
        try {
            $karyawan = DataKaryawan::create([
                'id_badge' => $validatedData['id_badge'],
                'nama_karyawan' => $validatedData['nama_karyawan'],
                'gelar_depan' => $validatedData['gelar_depan'],
                'gelar_belakang' => $validatedData['gelar_belakang'],
                'pendidikan' => $validatedData['pendidikan'],
                'alamat' => $validatedData['alamat'],
                'agama' => $validatedData['agama'],
                'status_pernikahan' => $validatedData['status_pernikahan'],
                'tempat_lahir' => $validatedData['tempat_lahir'],
                'tanggal_lahir' => $validatedData['tanggal_lahir'],
                'jenis_kelamin' => $validatedData['jenis_kelamin'],
            ]);

            // Redirect dengan pesan sukses
            return redirect('/admin/master_data_karyawan')->with('success', 'Data berhasil disimpan.');
        } catch (\Exception $e) {
            return redirect('/admin/master_data_karyawan')->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

//     public function uploadExcel(Request $request)
// {
//     // Validasi file unggahan
//     $validator = Validator::make($request->all(), [
//         'file_excel' => 'required|mimes:xlsx,xls',
//     ]);

//     if ($validator->fails()) {
//         return redirect()
//             ->back()
//             ->withErrors($validator)
//             ->withInput()
//             ->with('toast_error', 'Validasi gagal. Silakan periksa kembali input Anda.');
//     }

//     if ($request->hasFile('file_excel')) {
//         $path = $request->file('file_excel')->getRealPath();
//         $data = Excel::toArray([], $request->file('file_excel'));

//         if (!empty($data) && count($data[0]) > 0) {
//             foreach ($data[0] as $key => $row) {
//                 // Lewati header (baris pertama)
//                 if ($key < 1) {
//                     continue;
//                 }

//                 $badge = substr($row[0] ?? '', 0, 50);

//                 // Cek jika data karyawan dengan ID badge sudah ada
//                 $dataKaryawan = DataKaryawan::where('id_badge', $badge)->first();
//                 if ($dataKaryawan) {
//                     continue;
//                 }

//                 $birthday = date('Y-m-d', strtotime("1900-01-01") + ((substr($row[23] ?? '', 0, 50) - 2) * 86400));
                
//                 // dd(
//                 //     substr($row[23] ?? '', 0, 50),
//                 //     date('Y-m-d', strtotime("1900-01-01") + ((substr($row[23] ?? '', 0, 50) - 2) * 86400))
//                 // );
//                 // Buat data karyawan baru
//                 DataKaryawan::create([
//                     'id_badge' => $badge,
//                     'nama_karyawan' => substr($row[1] ?? '', 0, 100),
//                     'gelar_depan' => substr($row[2] ?? '', 0, 50),
//                     'nama_lengkap' => substr($row[3] ?? '', 0, 100),
//                     'gelar_belakang' => substr($row[4] ?? '', 0, 50),
//                     'pendidikan' => '',
//                     'alamat' => '',
//                     'agama' => substr($row[26] ?? '', 0, 50),
//                     'status_pernikahan' => substr($row[25] ?? '', 0, 50),
//                     'tempat_lahir' => substr($row[22] ?? '', 0, 50),
//                     'tanggal_lahir' => $birthday,
//                     // 'tanggal_lahir' => !empty($row[23]) 
//                     //     ? Carbon::createFromFormat('d/m/Y', $row[23])->format('Y-m-d') 
//                     //     : null,
//                     'jenis_kelamin' => 'Laki-laki',
//                     // 'jenis_kelamin' => $row[24] === 'Male' 
//                     //     ? 'Laki-laki' 
//                     //     : ($row[24] === 'Female' ? 'Perempuan' : null),
//                     'cost_center' => substr($row[31] ?? '', 0, 50),
//                     'unit_kerja' => substr($row[32] ?? '', 0, 50),
//                     'rek' => substr($row[28] ?? '', 0, 50),
//                     'rek_loc' => substr($row[27] ?? '', 0, 50),
//                     'files' => [],
//                 ]);
//             }
//         }

//         // Berhasil
//         return redirect()
//             ->back()
//             ->with('toast_success', 'Data berhasil diunggah!');
//     }

//     // Jika file tidak ditemukan
//     return redirect()
//         ->back()
//         ->with('toast_error', 'File tidak ditemukan. Silakan unggah file yang benar.');
// }

    
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
                    if ($key < 1) continue;
            
                    $totalProcessed++; // Tambahkan ke total data yang diproses
                    
                    $badge = substr($row[0] ?? '', 0, 50);
                    $dataKaryawan = DataKaryawan::where('id_badge', $badge)->first();
                    if ($dataKaryawan) continue;

                    try {
                        $dataImp = [
                            'id_badge' => substr($row[0] ?? '', 0, 50),
                            'nama_karyawan' => substr($row[1] ?? '', 0, 100),
                            'gelar_depan' => substr($row[2] ?? '', 0, 50),
                            'nama_lengkap' => substr($row[3] ?? '', 0, 100),
                            'gelar_belakang' => substr($row[4] ?? '', 0, 50),
                            'cost_center' => substr($row[31] ?? '', 0, 50),
                            'agama' => substr($row[26] ?? '', 0, 50),
                            'status_pernikahan' => substr($row[25] ?? '', 0, 50),
                            'tempat_lahir' => substr($row[22] ?? '', 0, 50),
                            'tanggal_lahir' => !empty($row[23]) ? Carbon::parse($row[23])->format('Y-m-d') : null,
                            'jenis_kelamin' => $row[24] === 'Male' ? 'Laki-laki' : ($row[24] === 'Female' ? 'Perempuan' : null),
                            'rek' => substr($row[28] ?? '', 0, 50),
                            'rek_loc' => substr($row[27] ?? '', 0, 50),
                            'files' => '',
                            'updated_at' => now(),
                            'updated_by' => auth()->user()->role,
                            'created_at' => now(),
                            'created_by' => auth()->user()->role,
                        ];
            
                        DataKaryawan::create($dataImp);
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

        return redirect("/admin/master_data_karyawan/detail/$id")
        ->with('toast_success', 'Data karyawan berhasil diperbarui.');
        // try {
        //     $karyawan = DataKaryawan::findOrFail($id); // Fetch or fail if not found

        //     // Helper function to handle file update
        //     $updateFile = function ($file, $oldFilePath, $directory) {
        //         if ($file) {
        //             if ($oldFilePath) {
        //                 $oldFile = public_path("uploads/karyawan/{$directory}/{$oldFilePath}");
        //                 if (file_exists($oldFile)) {
        //                     unlink($oldFile); // Delete old file if exists
        //                 }
        //             }
        //             $fileName = rand(10, 99999999) . '_' . $file->getClientOriginalName();
        //             $file->move(public_path("uploads/karyawan/{$directory}/"), $fileName);
        //             return $fileName;
        //         }
        //         return $oldFilePath;
        //     };

        //     // Update file fields with helper function
        //     $karyawan->url_foto_diri = $updateFile($request->file('foto_diri'), $karyawan->url_foto_diri, 'foto_diri');
        //     $karyawan->url_file_ktp = $updateFile($request->file('file_ktp'), $karyawan->url_file_ktp, 'file_ktp');
        //     $karyawan->url_file_kk = $updateFile($request->file('file_kk'), $karyawan->url_file_kk, 'file_kk');
        //     $karyawan->url_file_buku_nikah = $updateFile($request->file('buku_nikah'), $karyawan->url_file_buku_nikah, 'buku_nikah');
        //     $karyawan->url_file_akta_kelahiran = $updateFile($request->file('akta_kelahiran'), $karyawan->url_file_akta_kelahiran, 'akta_kelahiran');
        //     $karyawan->url_npwp = $updateFile($request->file('npwp'), $karyawan->url_npwp, 'npwp');
        //     $karyawan->url_lamaran_pekerjaan = $updateFile($request->file('lamaran_pekerjaan'), $karyawan->url_lamaran_pekerjaan, 'lamaran_pekerjaan');

        //     // Update other fields
        //     $karyawan->fill($validatedData);
        //     $karyawan->save();

        //     // Return success response
        //     return response()->json([
        //         'status' => 'success',
        //         'message' => 'Data updated successfully.',
        //         'data' => $karyawan
        //     ], 200);

        // } catch (\Exception $e) {
            

        //     // Return error response
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Failed to update data.',
        //         'error' => $e->getMessage()
        //     ], 500);
        // }
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
