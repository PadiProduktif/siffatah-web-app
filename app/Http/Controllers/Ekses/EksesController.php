<?php

namespace App\Http\Controllers\Ekses;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ekses\Ekses;

class EksesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ekses = Ekses::select('*')->get();
        // $test_echo = rand(0, 99999);
        //untuk mengirim json di postman
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil Mendapatkan Data',
            'data' => $ekses
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

        if ($request->id_badge == null && $request->nama_karyawan == null && $request->id_member == null && $request->unit_kerja == null && $request->nama_pasien == null) {
            return response()->json([
                'status' => 'Gagal',
                'message' => 'id badge dan nama karyawan tidak boleh kosong',
               
            ]);
        }else {
            $ekses = Ekses::create([
                'id_ekses'=> rand(10,99999999),
                'id_member' => $request->id_member,
                'id_badge' => $request->id_badge,
                'nama_karyawan' => $request->nama_karyawan,
                'unit_kerja' => $request->unit_kerja,
                'nama_pasien' => $request->nama_pasien,
                'deskripsi' => $request->deskripsi,
                'tanggal_pengajuan' => $request->tanggal_pengajuan,
                'jumlah_ekses' => $request->jumlah_ekses,
                'filename' => $request->filename,
                'file_url' => $request->file_url,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil Memasukan Data',
                // 'data' => $karyawan
                'data' => $ekses
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
        $ekses = Ekses::where('id_ekses', $id)->first();

        return response()->json($ekses);
        
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
        if ($request->id_badge == null && $request->nama_karyawan == null && $request->id_member == null && $request->unit_kerja == null && $request->nama_pasien == null) {
            return response()->json([
                'status' => 'Gagal',
                'message' => 'id badge dan nama karyawan tidak boleh kosong',
               
            ]);
        }else {
            $ekses = Ekses::where('id_ekses', $id)->first();
            // return response()->json([
            //     'status' => 'Gagal',
            //     // 'id_member' => $request->input('id_member'),
            //     // 'id_badge' => $request->input('id_badge'),
            //     'ekses' => $ekses
            // ]);
            // die();
            $ekses->id_member = $request->input('id_member');
            $ekses->id_badge = $request->input('id_badge');
            $ekses->nama_karyawan = $request->input('nama_karyawan');
            $ekses->unit_kerja = $request->input('unit_kerja');
            $ekses->nama_pasien = $request->input('nama_pasien');
            $ekses->deskripsi = $request->input('deskripsi');
            $ekses->tanggal_pengajuan = $request->input('tanggal_pengajuan');
            $ekses->jumlah_ekses = $request->input('jumlah_ekses');
            $ekses->filename = $request->input('filename');
            $ekses->file_url = $request->input('file_url');
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
        $ekses->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui',
            'data' => $ekses
        ]);
        // return redirect()->route('karyawan.index')->with('success', 'Karyawan updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ekses = Ekses::where('id_ekses', $id)->first();

        
        if (!$ekses) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'User Tidak Ditemukan',
            ]);
        }

       
        $ekses->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus',
        ]);
        
    }
}
