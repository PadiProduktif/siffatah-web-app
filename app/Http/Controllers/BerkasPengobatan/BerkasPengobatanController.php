<?php

namespace App\Http\Controllers\BerkasPengobatan;

use App\Http\Controllers\Controller;
use App\Models\BerkasPengobatan\BerkasPengobatan;
use Illuminate\Http\Request;

class BerkasPengobatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $obat = BerkasPengobatan::select('*')->get();
        // $test_echo = rand(0, 99999);
        //untuk mengirim json di postman
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil Mendapatkan Data',
            'data' => $obat
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

        if ($request->id_badge == null && $request->nama_karyawan == null) {
            return response()->json([
                'status' => 'Gagal',
                'message' => 'id badge dan nama karyawan tidak boleh kosong',
               
            ]);
        }else {
            $obat = BerkasPengobatan::create([
                'id_berkas_pengobatan'=> rand(10,99999999),
                'id_badge' => $request->id_badge,
                'nama_karyawan' => $request->nama_karyawan,
                'jabatan_karyawan' => $request->jabatan_karyawan,
                'nama_anggota_keluarga' => $request->nama_anggota_keluarga,
                'hubungan_keluarga' => $request->hubungan_keluarga,
                'deskripsi' => $request->deskripsi,
                'nominal' => $request->nominal,
                'rs_klinik' => $request->rs_klinik,
                'urgensi' => $request->urgensi,
                'no_surat_rs' => $request->no_surat_rs,
                'tanggal_pengobatan' => $request->tanggal_pengobatan,
                'status' => $request->status,
                'keterangan' => $request->keterangan,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil Memasukan Data',
                // 'data' => $karyawan
                'data' => $obat
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
        $obat = BerkasPengobatan::where('id_berkas_pengobatan', $id)->first();

        return response()->json($obat);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $obat = BerkasPengobatan::where('id_berkas_pengobatan', $id)->first();
        $obat->id_badge = $request->input('id_badge');
        $obat->nama_karyawan = $request->input('nama_karyawan');
        $obat->jabatan_karyawan = $request->input('jabatan_karyawan');
        $obat->nama_anggota_keluarga = $request->input('nama_anggota_keluarga');
        $obat->hubungan_keluarga = $request->input('hubungan_keluarga');
        $obat->deskripsi = $request->input('deskripsi');
        $obat->nominal = $request->input('nominal');
        $obat->rs_klinik = $request->input('rs_klinik');
        $obat->urgensi = $request->input('urgensi');
        $obat->no_surat_rs = $request->input('no_surat_rs');
        $obat->tanggal_pengobatan = $request->input('tanggal_pengobatan');
        $obat->status = $request->input('status');
        $obat->keterangan = $request->input('keterangan');
        //json siapa saja yang berkeluarga dengan orang tersebut
        // $karyawan->keluarga = $request->input('nama_karyawan');
        // untuk data url berkas data diri
        $obat->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui',
            'data' => $obat
        ]);
        // return redirect()->route('karyawan.index')->with('success', 'Karyawan updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $obat = BerkasPengobatan::where('id_berkas_pengobatan', $id)->first();

        
        if (!$obat) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'User Tidak Ditemukan',
            ]);
        }

       
        $obat->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus',
        ]);
        
    }
}

