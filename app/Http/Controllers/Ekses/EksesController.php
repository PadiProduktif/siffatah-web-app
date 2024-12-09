<?php

namespace App\Http\Controllers\Ekses;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Models\Ekses\Ekses;
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cleanedValue = str_replace(['Rp', ',', ' '], '', $request->jumlah_pengajuan);

        // Validation
        $request->validate([
            'id_badge' => 'required',
            'nama_karyawan' => 'required',
            'id_member' => 'required',
            'unit_kerja' => 'required',
            'nama_pasien' => 'required',
            // 'file' => 'sometimes|file|mimes:jpeg,png,jpg,pdf|max:2048', // Optional file validation
        ]);


        try {
            $ekses = Ekses::create([
                'id_ekses' => rand(10, 99999999), // Use auto-incremented ID if possible
                'id_member' => $request->id_member,
                'id_badge' => $request->id_badge,
                'nama_karyawan' => $request->nama_karyawan,
                'unit_kerja' => $request->unit_kerja,
                'nama_pasien' => $request->nama_pasien,
                'deskripsi' => $request->deskripsi,
                'tanggal_pengajuan' => $request->tanggal_pengajuan,
                'jumlah_ekses' => $cleanedValue,
                // 'file_url' => $fileName,
            ]);
            return redirect()->back()->with('success', 'Data berhasil ditambah!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
        

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
    public function update(Request $request, string $id)
    {
        $cleanedValue = str_replace(['Rp', '.', ' '], '', $request->jumlah_pengajuan);
        // Validate the request input
        $request->validate([
            'id_member' => 'required',
            'id_badge' => 'required',
            'nama_karyawan' => 'required',
            'unit_kerja' => 'required',
            'nama_pasien' => 'required',
            // 'file' => 'sometimes|file|mimes:jpeg,png,jpg,pdf|max:2048'
        ]);
        if (empty($request->id_badge) || empty($request->id_member) || empty($request->nama_karyawan || empty($request->unit_kerja)) || empty($request->nama_pasien)) {

            return redirect()->back()->with('failed', 'Data gagal diperbarui! ID badge, nama karyawan, dan cost center tidak boleh kosong');
        }

        // Find the record by ID
        $ekses = Ekses::where('id_ekses', $id)->first();

        if (!$ekses) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found.'
            ], 404); // 404 Not Found
            return redirect()->back()->with('failed', 'Data gagal diperbarui! Data tidak ditemukan');
        }

        // // Handle file upload
        // if ($request->hasFile('file')) {
        //     // Delete old file if it exists
        //     if ($ekses->file_url) {
        //         $oldFilePath = public_path("uploads/Ekses/{$ekses->file_url}");
        //         if (file_exists($oldFilePath)) {
        //             unlink($oldFilePath);
        //         }
        //     }

        //     // Save new file
        //     $file = $request->file('file');
        //     $fileName = rand(10, 99999999) . '_' . $file->getClientOriginalName();
        //     $file->move(public_path('uploads/Ekses/'), $fileName);
        //     $ekses->file_url = $fileName;
        // }

        // Update record fields
        $ekses->id_member = $request->input('id_member');
        $ekses->id_badge = $request->input('id_badge');
        $ekses->nama_karyawan = $request->input('nama_karyawan');
        $ekses->unit_kerja = $request->input('unit_kerja');
        $ekses->nama_pasien = $request->input('nama_pasien');
        $ekses->deskripsi = $request->input('deskripsi');
        $ekses->tanggal_pengajuan = $request->input('tanggal_pengajuan');
        $ekses->jumlah_ekses = $cleanedValue;
        
        // // Save changes
        $ekses->save();
        return redirect()->back()->with('success', 'Data berhasil di perbarui!');
        
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        try {
            Log::info("Menghapus data dengan ID: {$id}"); // Log untuk debugging awal
    
            $ekses = Ekses::findOrFail($id);
            $ekses->delete();
    
            Log::info("Data dengan ID: {$id} berhasil dihapus.");
    
            return response()->json(['message' => 'Data berhasil dihapus.'], 200);
        } catch (\Exception $e) {
            // Log detail error ke file log
            Log::error("Error saat menghapus data dengan ID: {$id}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            // Kembalikan respon error
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
        
    }

    public function deleteMultiple(Request $request)
    {
        try {
            $ids = $request->input('ids'); // Ambil array ID dari request
            Log::info('IDs yang akan dihapus: ', $ids); // Log IDs yang diterima

            Ekses::whereIn('id_ekses', $ids)->delete(); // Hapus data berdasarkan ID

            return response()->json(['message' => 'Data berhasil dihapus.'], 200);
        } catch (\Exception $e) {
            // Log detail error
            Log::error('Error saat menghapus data:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['message' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }

}
