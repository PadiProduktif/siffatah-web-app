<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterData\DataNonKaryawan;

class MasterDataNonKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $non_karyawan = DataNonKaryawan::select('*')->get();
        // $test_echo = rand(0, 99999);
        //untuk mengirim json di postman
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil Mendapatkan Data',
            'data' => $non_karyawan
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
        if ($request->nama == null) {
            return response()->json([
                'status' => 'Gagal',
                'message' => 'nama tidak boleh kosong',
               
            ]);
        }else {
            //url_foto_diri
            if ($request->hasFile('url_foto_diri')) {
                $url_foto_diri = $request->file('url_foto_diri');
                $url_foto_diri_fileName = rand(10,99999999).'_'.$url_foto_diri->getClientOriginalName();
                $url_foto_diri->move(public_path('uploads/non_karyawan/url_foto_diri/'), $url_foto_diri_fileName);
                    
            }else {
                $url_foto_diri_fileName = null;
            }
            $non_karyawan = DataNonKaryawan::create([
                'id_non_karyawan'=> rand(10,99999999),
                'nama' => $request->nama,
                'jenis_kelamin' => $request->jenis_kelamin,
                'hubungan_keluarga' => $request->hubungan_keluarga,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'pendidikan' => $request->pendidikan,
                'alamat' => $request->alamat,
                'agama' => $request->agama,
                'status_pernikahan' => $request->status_pernikahan,
                'pekerjaan' => $request->pekerjaan,
                'nik' => $request->nik,
                'kewarganegaraan' => $request->kewarganegaraan,
                'url_foto_diri' => $url_foto_diri_fileName,
                'id_karyawan_terkait' => $request->id_karyawan_terkait,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Berhasil Memasukan Data',
                // 'data' => $karyawan
                'data' => $non_karyawan
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
        $non_karyawan = DataNonKaryawan::where('id_non_karyawan', $id)->first();

        return response()->json($non_karyawan);
        
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
        if ($request->nama == null) {
            return response()->json([
                'status' => 'Gagal',
                'message' => 'id badge dan nama karyawan tidak boleh kosong',
               
            ]);
        }else {
            $non_karyawan = DataNonKaryawan::where('id_non_karyawan', $id)->first();
            // return response()->json([
            //     'status' => 'Gagal',
            //     // 'id_member' => $request->input('id_member'),
            //     // 'id_badge' => $request->input('id_badge'),
            //     'DataNonKaryawan' => $non_karyawan
            // ]);
            // die();
            $non_karyawan->nama = $request->input('nama');
            $non_karyawan->jenis_kelamin = $request->input('jenis_kelamin');
            $non_karyawan->hubungan_keluarga = $request->input('hubungan_keluarga');
            $non_karyawan->tempat_lahir = $request->input('tempat_lahir');
            $non_karyawan->tanggal_lahir = $request->input('tanggal_lahir');
            $non_karyawan->pendidikan = $request->input('pendidikan');
            $non_karyawan->alamat = $request->input('alamat');
            $non_karyawan->agama = $request->input('agama');
            $non_karyawan->status_pernikahan = $request->input('status_pernikahan');
            $non_karyawan->pekerjaan = $request->input('pekerjaan');
            $non_karyawan->nik = $request->input('nik');
            $non_karyawan->kewarganegaraan = $request->input('kewarganegaraan');
            $non_karyawan->url_foto_diri = $request->input('url_foto_diri');
            $non_karyawan->id_karyawan_terkait = $request->input('id_karyawan_terkait');
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
        $non_karyawan->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui',
            'data' => $non_karyawan
        ]);
        // return redirect()->route('karyawan.index')->with('success', 'Karyawan updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $non_karyawan = DataNonKaryawan::where('id_non_karyawan', $id)->first();

        
        if (!$non_karyawan) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'User Tidak Ditemukan',
            ]);
        }

       
        $non_karyawan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus',
        ]);
        
    }
}
