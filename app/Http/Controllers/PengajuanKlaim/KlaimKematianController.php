<?php

namespace App\Http\Controllers\PengajuanKlaim;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PengajuanKlaim\klaim_kematian;

class KlaimKematianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $klaim = Klaim_kematian::select('*')->get();
        // $test_echo = rand(0, 99999);
        //untuk mengirim json di postman
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil Mendapatkan Data',
            'data' => $klaim
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
        // return response()->json([
        //     'status' => 'Gagal',
        //     'nama' => $request->nama,

        // ]);
        // die();
        if ($request->id_badge == null && $request->nama_karyawan == null) {
            return response()->json([
                'status' => 'Gagal',
                'message' => 'nama dan id tidak boleh kosong',
               
            ]);
        }else {

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = rand(10,99999999).'_'.$file->getClientOriginalName();
                $file->move(public_path('uploads/PengajuanKlaim/klaim_Kematian/'), $fileName);
            }else {
                $fileName = null;
            }

            $klaim = Klaim_kematian::create([
                'id_klaim_kematian'=> rand(10,99999999),
                'id_badge' => $request->id_badge,
                'nama_karyawan' => $request->nama_karyawan,
                'unit_kerja' => $request->unit_kerja,
                'nama_asuransi' => $request->nama_asuransi,
                'rs_klinik' => $request->rs_klinik,
                'tanggal_wafat' => $request->tanggal_kejadian,
                'nama_keluarga' => $request->nama_keluarga,
                'hubungan_keluarga' => $request->hubungan_keluarga,
                'no_polis' => $request->deskripsi,
                'file_url' => $fileName,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil Memasukan Data',
                // 'data' => $karyawan
                'data' => $klaim
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
        $klaim = Klaim_kematian::where('id_klaim_kematian', $id)->first();

        if (!$klaim) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'User Tidak Ditemukan',
            ]);
        }else {
            return response()->json([
                'status' => 'Failed',
                'message' => 'User Ditemukan',
                'data' => $klaim,
            ]);
        }
        
        
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
        if ($request->id_badge == null && $request->nama_karyawan == null) {
            return response()->json([
                'status' => 'Gagal',
                'message' => 'id badge dan nama karyawan tidak boleh kosong',
               
            ]);
        }else {
            $klaim = Klaim_kematian::where('id_Klaim_kematian', $id)->first();
            // return response()->json([
            //     'status' => 'Gagal',
            //     // 'id_member' => $request->input('id_member'),
            //     // 'id_badge' => $request->input('id_badge'),
            //     'Klaim_kematian' => $klaim
            // ]);
            // die();
            $klaim->id_badge = $request->input('id_badge');
            $klaim->nama_karyawan = $request->input('nama_karyawan');
            $klaim->unit_kerja = $request->input('unit_kerja');
            $klaim->nama_asuransi = $request->input('nama_asuransi');
            $klaim->rs_klinik = $request->input('rs_klinik');
            $klaim->tanggal_wafat = $request->input('tanggal_wafat');
            $klaim->nama_keluarga = $request->input('nama_keluarga');
            $klaim->hubungan_keluarga = $request->input('hubungan_keluarga');
            $klaim->no_polis = $request->input('no_polis');
            $klaim->filename = $request->input('filename');
            $klaim->file_url = $request->input('file_url');
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
        $klaim->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui',
            'data' => $klaim
        ]);
        // return redirect()->route('karyawan.index')->with('success', 'Karyawan updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $klaim = Klaim_kematian::where('id_Klaim_kematian', $id)->first();

        
        if (!$klaim) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'User Tidak Ditemukan',
            ]);
        }

       
        $klaim->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus',
        ]);
        
    }
}