<?php

namespace App\Http\Controllers\RestitusiKaryawan;

use App\Http\Controllers\Controller;
use App\Models\RestitusiKaryawan\RestitusiKaryawan;
use Illuminate\Http\Request;

class RestitusiKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $restitusi = RestitusiKaryawan::select('*')->get();
        // $test_echo = rand(0, 99999);
        //untuk mengirim json di postman
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil Mendapatkan Data',
            'data' => $restitusi
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
                'message' => 'id badge, nama karyawan, dan cost center tidak boleh kosong',
               
            ]);
        }else {
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = rand(10,99999999).'_'.$file->getClientOriginalName();
                $file->move(public_path('uploads/Restitusi_Karyawan/'), $fileName);
            }else {
                $fileName = null;
            }
            $restitusi = RestitusiKaryawan::create([
                'id_pengajuan'=> rand(10,99999999),
                'id_badge' => $request->id_badge,
                'nama_karyawan' => $request->nama_karyawan,
                'jabatan_karyawan' => $request->jabatan_karyawan,
                'nama_anggota_keluarga' => $request->nama_anggota_keluarga,
                'hubungan_keluarga' => $request->hubungan_keluarga,
                'deskripsi' => $request->deskripsi,
                'nominal' => $request->nominal,
                'rumah_sakit' => $request->rumah_sakit,
                'urgensi' => $request->urgensi,
                'no_surat_rs' => $request->no_surat_rs,
                'tanggal_pengobatan' => $request->tanggal_pengobatan,
                'keterangan_pengajuan' => $request->keterangan_pengajuan,
                'url_file' => $fileName,
                'status_pengajuan' => $request->status_pengajuan,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil Memasukan Data',
                // 'data' => $karyawan
                'data' => $restitusi
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
        $restitusi = RestitusiKaryawan::where('id_pengajuan', $id)->first();

        return response()->json($restitusi);
        
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
            $restitusi = RestitusiKaryawan::where('id_pengajuan', $id)->first();
            // return response()->json([
            //     'status' => 'Gagal',
            //     // 'id_member' => $request->input('id_member'),
            //     // 'id_badge' => $request->input('id_badge'),
            //     'RestitusiKaryawan' => $restitusi
            // ]);
            // die();
            $restitusi->id_badge = $request->input('id_badge');
            $restitusi->nama_karyawan = $request->input('nama_karyawan');
            $restitusi->jabatan_karyawan = $request->input('jabatan_karyawan');
            $restitusi->nama_anggota_keluarga = $request->input('nama_anggota_keluarga');
            $restitusi->hubungan_karyawan = $request->input('hubungan_karyawan');
            $restitusi->deskripsi = $request->input('deskripsi');
            $restitusi->nominal = $request->input('nominal');
            $restitusi->rumah_sakit = $request->input('rumah_sakit');
            $restitusi->urgensi = $request->input('urgensi');
            $restitusi->no_surat_rs = $request->input('no_surat_rs');
            $restitusi->tanggal_pengobatan = $request->input('tanggal_pengobatan');
            $restitusi->keterangan_pengajuan = $request->input('keterangan_pengajuan');
            $restitusi->filename = $request->input('filename');
            $restitusi->url_file = $request->input('url_file');
            $restitusi->status_pengajuan = $request->input('status_pengajuan');

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
        $restitusi->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui',
            'data' => $restitusi
        ]);
        // return redirect()->route('karyawan.index')->with('success', 'Karyawan updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $restitusi = RestitusiKaryawan::where('id_pengajuan', $id)->first();

        
        if (!$restitusi) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'User Tidak Ditemukan',
            ]);
        }

       
        $restitusi->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus',
        ]);
        
    }
}
