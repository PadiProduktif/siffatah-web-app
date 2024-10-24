<?php

namespace App\Http\Controllers\PengajuanKlaim;

use App\Http\Controllers\Controller;
use App\Models\PengajuanKlaim\klaim_pengobatan;
use Illuminate\Http\Request;

class KlaimPengobatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $klaim = klaim_pengobatan::select('*')->get();
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
                $file->move(public_path('uploads/PengajuanKlaim/Klaim_Pengobatan/'), $fileName);
            }else {
                $fileName = null;
            }
            $klaim = klaim_pengobatan::create([
                'id_klaim_pengobatan'=> rand(10,99999999),
                'id_badge' => $request->id_badge,
                'nama_karyawan' => $request->nama_karyawan,
                'unit_kerja' => $request->unit_kerja,
                'nama_asuransi' => $request->nama_asuransi,
                'rs_klinik' => $request->rs_klinik,
                'tanggal_pengajuan' => $request->tanggal_pengajuan,
                'nominal' => $request->nominal,
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
        $klaim = klaim_pengobatan::where('id_klaim_pengobatan', $id)->first();

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
            $klaim = klaim_pengobatan::where('id_klaim_pengobatan', $id)->first();
            // return response()->json([
            //     'status' => 'Gagal',
            //     // 'id_member' => $request->input('id_member'),
            //     // 'id_badge' => $request->input('id_badge'),
            //     'klaim_pengobatan' => $klaim
            // ]);
            // die();
            $klaim->id_badge = $request->input('id_badge');
            $klaim->nama_karyawan = $request->input('nama_karyawan');
            $klaim->unit_kerja = $request->input('unit_kerja');
            $klaim->nama_asuransi = $request->input('nama_asuransi');
            $klaim->rs_klinik = $request->input('rs_klinik');
            $klaim->tanggal_pengajuan = $request->input('tanggal_pengajuan');
            $klaim->nominal = $request->input('nominal');
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
        $klaim = klaim_pengobatan::where('id_klaim_pengobatan', $id)->first();

        
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
