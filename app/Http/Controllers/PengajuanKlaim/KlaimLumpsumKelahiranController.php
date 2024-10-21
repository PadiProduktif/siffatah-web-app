<?php

namespace App\Http\Controllers\PengajuanKlaim;

use App\Http\Controllers\Controller;
use App\Models\PengajuanKlaim\klaim_lumpsum_kelahiran;
use Illuminate\Http\Request;

class KlaimLumpsumKelahiranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $klaim = klaim_lumpsum_kelahiran::select('*')->get();
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
            $klaim = klaim_lumpsum_kelahiran::create([
                'id_lumpsum_kelahiran'=> rand(10,99999999),
                'id_badge' => $request->id_badge,
                'nama_karyawan' => $request->nama_karyawan,
                'unit_kerja' => $request->unit_kerja,
                'nama_pasangan' => $request->nama_pasangan,
                'anak_ke' => $request->anak_ke,
                'rumah_sakit' => $request->rumah_sakit,
                'tanggal_pengajuan' => $request->tanggal_pengajuan,
                'tanggal_approve' => $request->tanggal_approve,
                'nominal' => $request->nominal,
                'deskripsi' => $request->deskripsi,
                'filename' => $request->filename,
                'file_url' => $request->file_url,
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
        $klaim = klaim_lumpsum_kelahiran::where('id_lumpsum_kelahiran', $id)->first();

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
            $klaim = klaim_lumpsum_kelahiran::where('id_lumpsum_kelahiran', $id)->first();
            // return response()->json([
            //     'status' => 'Gagal',
            //     // 'id_member' => $request->input('id_member'),
            //     // 'id_badge' => $request->input('id_badge'),
            //     'klaim_lumpsum_kelahiran' => $klaim
            // ]);
            // die();
            $klaim->id_badge = $request->input('id_badge');
            $klaim->nama_karyawan = $request->input('nama_karyawan');
            $klaim->unit_kerja = $request->input('unit_kerja');
            $klaim->nama_pasangan = $request->input('nama_pasangan');
            $klaim->anak_ke = $request->input('anak_ke');
            $klaim->rumah_sakit = $request->input('rumah_sakit');
            $klaim->tanggal_pengajuan = $request->input('tanggal_pengajuan');
            $klaim->tanggal_approve = $request->input('tanggal_approve');
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
        $klaim = klaim_lumpsum_kelahiran::where('id_lumpsum_kelahiran', $id)->first();

        
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
