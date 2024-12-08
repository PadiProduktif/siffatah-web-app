<?php

namespace App\Http\Controllers\KelengkapanKerja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KelengkapanKerja\KelengkapanKerja;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
// use Illuminate\Container\Attributes\Log;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class KelengkapanKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelengkapan = KelengkapanKerja::all();
        $user = Auth::user();
        $data['user'] = $user->fullname;
        $data['kelengkapan'] = $kelengkapan;
        $data['chartData'] = [
            'sepatu_kantor' => KelengkapanKerja::select(DB::raw("IF(sepatu_kantor IS NULL OR sepatu_kantor = '' OR sepatu_kantor = '-' OR sepatu_kantor = null, 'Tidak Dapat', sepatu_kantor) as sepatu_kantor"), DB::raw('COUNT(*) as jumlah'))
                ->groupBy('sepatu_kantor')
                ->get(),
            'sepatu_safety' => KelengkapanKerja::select(DB::raw("IF(sepatu_safety IS NULL OR sepatu_safety = null OR sepatu_safety = '' OR sepatu_safety = '-', 'Tidak Dapat', sepatu_safety) as sepatu_safety"), DB::raw('COUNT(*) as jumlah'))
                ->groupBy('sepatu_safety')
                ->get(),
            'wearpack_cover_all' => KelengkapanKerja::select(DB::raw("IF(wearpack_cover_all IS NULL OR wearpack_cover_all = null OR wearpack_cover_all = '' OR wearpack_cover_all = '-', 'Tidak Dapat', wearpack_cover_all) as wearpack_cover_all"), DB::raw('COUNT(*) as jumlah'))
                ->groupBy('wearpack_cover_all')
                ->get(),
            'jaket_shift' => KelengkapanKerja::select(DB::raw("IF(jaket_shift IS NULL OR jaket_shift = null OR jaket_shift = '' OR jaket_shift = '-', 'Tidak Dapat', jaket_shift) as jaket_shift"), DB::raw('COUNT(*) as jumlah'))
                ->groupBy('jaket_shift')
                ->get(),
            'seragam_olahraga' => KelengkapanKerja::select(DB::raw("IF(seragam_olahraga IS NULL OR seragam_olahraga = null OR seragam_olahraga = '' OR seragam_olahraga = '-', 'Tidak Dapat', seragam_olahraga) as seragam_olahraga"), DB::raw('COUNT(*) as jumlah'))
                ->groupBy('seragam_olahraga')
                ->get(),
            'jaket_casual' => KelengkapanKerja::select(DB::raw("IF(jaket_casual IS NULL OR jaket_casual = null OR jaket_casual = '' OR jaket_casual = '-', 'Tidak Dapat', jaket_casual) as jaket_casual"), DB::raw('COUNT(*) as jumlah'))
                ->groupBy('jaket_casual')
                ->get(),
            'seragam_dinas_harian' => KelengkapanKerja::select(DB::raw("IF(seragam_dinas_harian IS NULL OR seragam_dinas_harian = null OR seragam_dinas_harian = '' OR seragam_dinas_harian = '-', 'Tidak Dapat', seragam_dinas_harian) as seragam_dinas_harian"), DB::raw('COUNT(*) as jumlah'))
                ->groupBy('seragam_dinas_harian')
                ->get(),
        ];


        return view('dashboard/kelengkapan-kerja',$data);
    }
    public function index_sepatu()
    {
        $kelengkapan = KelengkapanKerja::all();
        $user = Auth::user();
        $data['user'] = $user->fullname;
        // Check if data exists
        // if ($kelengkapan->isEmpty()) {
        //     return response()->json([
        //         'status' => 'success',
        //         'message' => 'No data available',
        //         'data' => []
        //     ], 204); // 204 No Content
        // }

        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Data retrieved successfully',
        //     'data' => $user->fullname
        // ], 200); // 200 OK
        return view('dashboard/kelengkapan-kerja/sepatu',compact('data'));
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
        // Validasi input
        try {
            // Simpan data langsung ke database tanpa validasi Laravel
            KelengkapanKerja::create([
                'id_badge' => $request->id_badge,
                'nama_karyawan' => $request->nama_karyawan,
                'cost_center' => $request->cost_center,
                'unit_kerja' => $request->unit_kerja,
                'sepatu_kantor' => $request->sepatu_kantor,
                'sepatu_safety' => $request->sepatu_safety,
                'wearpack_cover_all' => $request->wearpack_cover_all,
                'jaket_shift' => $request->jaket_shift,
                'seragam_olahraga' => $request->seragam_olahraga,
                'jaket_casual' => $request->jaket_casual,
                'seragam_dinas_harian' => $request->seragam_dinas_harian,
            ]);
    
            return redirect()->back()->with('success', 'Data berhasil ditambah!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
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
                    if ($key < 2) {
                        continue;
                    }

                    // Hanya masukkan nilai, abaikan jika panjang data terlalu besar
                    KelengkapanKerja::create([
                        'id_badge' => substr($row[1] ?? '', 0, 50), // Pastikan panjang data sesuai tipe di database
                        'nama_karyawan' => substr($row[2] ?? '', 0, 1000),
                        'cost_center' => substr($row[4] ?? '', 0, 1000),
                        'unit_kerja' => substr($row[5] ?? '', 0, 1000), // Perhatikan panjang maksimal
                        'sepatu_kantor' => $row[6] ?? null,
                        'sepatu_safety' => $row[7] ?? null,
                        'wearpack_cover_all' => $row[8] ?? null,
                        'jaket_shift' => $row[9],
                        'seragam_olahraga' => $row[10],
                        'jaket_casual' => $row[11],
                        'seragam_dinas_harian' => $row[12],
                        // Tambahkan kolom lainnya
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
        $kelengkapan = KelengkapanKerja::where('id_kelengkapan_kerja', $id)->first();
    
        if (!$kelengkapan) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Data tidak ditemukan',
            ], 404); // 404 Not Found
        }
    
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil ditemukan',
            'data' => $kelengkapan
        ], 200); // 200 OK
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Check required fields
        if (empty($request->id_badge) || empty($request->nama_karyawan) || empty($request->cost_center)) {
            // return response()->json([
            //     'status' => 'Failed',
            //     'message' => 'ID badge, nama karyawan, dan cost center tidak boleh kosong',
            // ], 400); // 400 Bad Request
            return redirect()->back()->with('failed', 'Data gagal diperbarui! ID badge, nama karyawan, dan cost center tidak boleh kosong');
        }

        // Find the record
        $kelengkapan = KelengkapanKerja::where('id_kelengkapan_kerja', $id)->first();
        if (!$kelengkapan) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Data tidak ditemukan',
            ], 404); // 404 Not Found
            return redirect()->back()->with('failed', 'Data gagal diperbarui! Data tidak ditemukan');
        }

        // Update fields
        $kelengkapan->id_badge = $request->input('id_badge');
        $kelengkapan->nama_karyawan = $request->input('nama_karyawan');
        $kelengkapan->cost_center = $request->input('cost_center');
        $kelengkapan->unit_kerja = $request->input('unit_kerja');
        $kelengkapan->sepatu_kantor = $request->input('sepatu_kantor');
        $kelengkapan->sepatu_safety = $request->input('sepatu_safety');
        $kelengkapan->wearpack_cover_all = $request->input('wearpack_cover_all');
        $kelengkapan->jaket_shift = $request->input('jaket_shift');
        $kelengkapan->seragam_olahraga = $request->input('seragam_olahraga');
        $kelengkapan->jaket_casual = $request->input('jaket_casual');
        $kelengkapan->seragam_dinas_harian = $request->input('seragam_dinas_harian');

        // Save updated record
        $kelengkapan->save();
        return redirect()->back()->with('success', 'Data berhasil di perbarui!');
        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Data berhasil diperbarui',
        //     'data' => $kelengkapan
        // ], 200); // 200 OK
    }

    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
{
    try {
        Log::info("Menghapus data dengan ID: {$id}"); // Log untuk debugging awal

        $kelengkapan = KelengkapanKerja::findOrFail($id);
        $kelengkapan->delete();

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

            KelengkapanKerja::whereIn('id_kelengkapan_kerja', $ids)->delete(); // Hapus data berdasarkan ID

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
