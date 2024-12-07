<?php

namespace App\Http\Controllers\KelengkapanKerja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KelengkapanKerja\KelengkapanKerja;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;


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
            $kelengkapan = KelengkapanKerja::findOrFail($id);
            $kelengkapan->delete();
    
            return response()->json(['message' => 'Data berhasil dihapus.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }

}
