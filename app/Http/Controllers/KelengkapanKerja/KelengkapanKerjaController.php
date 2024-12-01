<?php

namespace App\Http\Controllers\KelengkapanKerja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KelengkapanKerja\KelengkapanKerja;
use Illuminate\Support\Facades\Auth;

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
        // Validation for required fields
        $validatedData = $request->validate([
            'id_badge' => 'required',
            'nama_karyawan' => 'required',
            'cost_center' => 'required',
        ], [
            'id_badge.required' => 'ID badge tidak boleh kosong.',
            'nama_karyawan.required' => 'Nama karyawan tidak boleh kosong.',
            'cost_center.required' => 'Cost center tidak boleh kosong.',
        ]);
    
        // Create the record
        $kelengkapan = KelengkapanKerja::create([
            'id_badge' => $validatedData['id_badge'],
            'nama_karyawan' => $validatedData['nama_karyawan'],
            'cost_center' => $validatedData['cost_center'],
            'jabatan_karyawan' => $request->jabatan_karyawan,
            'grade' => $request->grade,
            'jenis_pakaian' => $request->jenis_pakaian,
            'jenis_kelengkapan_kantor' => $request->jenis_kelengkapan_kantor,
            'tahun' => $request->tahun,
            'warna' => $request->warna,
            'gender' => $request->gender,
            'ukuran' => $request->ukuran,
        ]);
    
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil Memasukan Data',
            'data' => $kelengkapan
        ], 201); // 201 Created
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
            return response()->json([
                'status' => 'Failed',
                'message' => 'ID badge, nama karyawan, dan cost center tidak boleh kosong',
            ], 400); // 400 Bad Request
        }

        // Find the record
        $kelengkapan = KelengkapanKerja::where('id_kelengkapan_kerja', $id)->first();
        if (!$kelengkapan) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Data tidak ditemukan',
            ], 404); // 404 Not Found
        }

        // Update fields
        $kelengkapan->id_badge = $request->input('id_badge');
        $kelengkapan->nama_karyawan = $request->input('nama_karyawan');
        $kelengkapan->cost_center = $request->input('cost_center');
        $kelengkapan->jabatan_karyawan = $request->input('jabatan_karyawan');
        $kelengkapan->grade = $request->input('grade');
        $kelengkapan->jenis_pakaian = $request->input('jenis_pakaian');
        $kelengkapan->jenis_kelengkapan_kantor = $request->input('jenis_kelengkapan_kantor');
        $kelengkapan->tahun = $request->input('tahun');
        $kelengkapan->warna = $request->input('warna');
        $kelengkapan->gender = $request->input('gender');
        $kelengkapan->ukuran = $request->input('ukuran');

        // Save updated record
        $kelengkapan->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui',
            'data' => $kelengkapan
        ], 200); // 200 OK
    }

    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kelengkapan = KelengkapanKerja::where('id_kelengkapan_kerja', $id)->first();

        if (!$kelengkapan) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'Data tidak ditemukan',
            ], 404); // 404 Not Found
        }

        $kelengkapan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus',
        ], 200); // 200 OK
    }

}
