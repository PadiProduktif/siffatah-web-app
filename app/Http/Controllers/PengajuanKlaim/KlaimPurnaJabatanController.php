<?php

namespace App\Http\Controllers\PengajuanKlaim;

use App\Http\Controllers\Controller;
use App\Models\PengajuanKlaim\klaim_purnajabatan;
use Illuminate\Http\Request;

class KlaimPurnaJabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $klaim = klaim_purnajabatan::select('*')->get();
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
        if ($request->nama == null && $request->jabatan == null) {
            return response()->json([
                'status' => 'Gagal',
                'message' => 'nama dan jabatan tidak boleh kosong',
               
            ]);
        }else {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = rand(10,99999999).'_'.$file->getClientOriginalName();
                $file->move(public_path('uploads/PengajuanKlaim/Klaim_PurnaJabatan/'), $fileName);
            }else {
                $fileName = null;
            }
            $klaim = klaim_purnajabatan::create([
                'id_klaim_purnajabatan'=> rand(10,99999999),
                'nama' => $request->nama,
                'jabatan' => $request->jabatan,
                'tanggal_lahir' => $request->tanggal_lahir,
                'mulai_asuransi' => $request->mulai_asuransi,
                'akhir_asuransi' => $request->akhir_asuransi,
                'nama_asuransi' => $request->nama_asuransi,
                'no_polis' => $request->no_polis,
                'premi_tahunan' => $request->premi_tahunan,
                'uang_tertanggung' => $request->uang_tertanggung,
                'deskripsi' => $request->deskripsi,
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
        $klaim = klaim_purnajabatan::where('id_klaim_purnajabatan', $id)->first();

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
        if ($request->nama == null && $request->jabatan == null) {
            return response()->json([
                'status' => 'Gagal',
                'message' => 'id badge dan nama karyawan tidak boleh kosong',
               
            ]);
        }else {
            $klaim = klaim_purnajabatan::where('id_klaim_purnajabatan', $id)->first();
            // return response()->json([
            //     'status' => 'Gagal',
            //     // 'id_member' => $request->input('id_member'),
            //     // 'id_badge' => $request->input('id_badge'),
            //     'klaim_purnajabatan' => $klaim
            // ]);
            // die();
            $klaim->nama = $request->input('nama');
            $klaim->jabatan = $request->input('jabatan');
            $klaim->tanggal_lahir = $request->input('tanggal_lahir');
            $klaim->mulai_asuransi = $request->input('mulai_asuransi');
            $klaim->akhir_asuransi = $request->input('akhir_asuransi');
            $klaim->nama_asuransi = $request->input('nama_asuransi');
            $klaim->no_polis = $request->input('no_polis');
            $klaim->premi_tahunan = $request->input('premi_tahunan');
            $klaim->uang_tertanggung = $request->input('uang_tertanggung');
            $klaim->deskripsi = $request->input('deskripsi');
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
        $klaim = klaim_purnajabatan::where('id_klaim_purnajabatan', $id)->first();

        
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
