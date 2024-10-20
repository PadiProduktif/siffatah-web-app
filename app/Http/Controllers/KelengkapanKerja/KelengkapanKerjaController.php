<?php

namespace App\Http\Controllers\KelengkapanKerja;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KelengkapanKerja\KelengkapanKerja;

class KelengkapanKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kelengkapan = KelengkapanKerja::select('*')->get();
        // $test_echo = rand(0, 99999);
        //untuk mengirim json di postman
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil Mendapatkan Data',
            'data' => $kelengkapan
        ]);
        // return view('MasterData/DataKaryawan/list_karyawan', ['karyawan' => $karyawan]);
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

        if ($request->id_badge == null && $request->nama_karyawan == null && $request->cost_center == null) {
            return response()->json([
                'status' => 'Gagal',
                'message' => 'id badge, nama karyawan, dan cost center tidak boleh kosong',
               
            ]);
        }else {
            $kelengkapan = KelengkapanKerja::create([
                'id_kelengkapan_kerja'=> rand(10,99999999),
                'id_badge' => $request->id_badge,
                'nama_karyawan' => $request->nama_karyawan,
                'cost_center' => $request->cost_center,
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
                // 'data' => $karyawan
                'data' => $kelengkapan
            ]);
        }

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

        return response()->json($kelengkapan);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        // return response()->json([
        //     'status' => 'Gagal',
        //     'id_member' => $request->input('id_member'),
        //     'id_badge' => $request->input('id_badge'),
        // ]);
        // die();
        if ($request->id_badge == null && $request->nama_karyawan == null && $request->cost_center == null) {
            return response()->json([
                'status' => 'Gagal',
                'message' => 'id badge dan nama karyawan tidak boleh kosong',
               
            ]);
        }else {
            $kelengkapan = KelengkapanKerja::where('id_kelengkapan_kerja', $id)->first();
            // return response()->json([
            //     'status' => 'Gagal',
            //     // 'id_member' => $request->input('id_member'),
            //     // 'id_badge' => $request->input('id_badge'),
            //     'KelengkapanKerja' => $kelengkapan
            // ]);
            // die();
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
        }
        // return response()->json([
        //     'status' => 'Gagal',
        //     'id_member' => $request->input('id_member'),
        //     'id_badge' => $request->input('id_badge'),
        // ]);
        // die();
        
        //json siapa saja yang berkeluarga dengan orang tersebut
        // $karyawan->keluarga = $request->input('nama_karyawan');
        // untuk data url berkas data diri
        $kelengkapan->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui',
            'data' => $kelengkapan
        ]);
        // return redirect()->route('karyawan.index')->with('success', 'Karyawan updated successfully');
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
                'message' => 'User Tidak Ditemukan',
            ]);
        }

       
        $kelengkapan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus',
        ]);
        
    }
}
