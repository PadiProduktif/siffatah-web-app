<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\MasterData\DataKaryawan;
use Illuminate\Http\Request;

class MasterDataKaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $karyawan = DataKaryawan::select('*')->get();
        // $test_echo = rand(0, 99999);
        //untuk mengirim json di postman
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil Mendapatkan Data',
            'data' => $karyawan
        ]);
        // return view('MasterData/DataKaryawan/list_karyawan', ['karyawan' => $karyawan]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('MasterData/DataKaryawan/add_karyawan');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $karyawan = DataKaryawan::create([
            'id_karyawan'=> rand(10,99999999),
            'id_badge' => $request->input('id_badge'),
            'nama_karyawan' => $request->input('nama_karyawan'),
            'gelar_depan' => $request->input('gelar_depan'),
            'nama_lengkap' => $request->input('nama_lengkap'),
            'gelar_belakang' => $request->input('gelar_belakang'),
            'pendidikan' => $request->input('pendidikan'),
            'alamat' => $request->input('alamat'),
            'agama' => $request->input('agama'),
            'status_pernikahan' => $request->input('status_pernikahan'),
            'tempat_lahir' => $request->input('tempat_lahir'),
            'tanggal_lahir' => $request->input('tanggal_lahir'),
            'jenis_kelamin' => $request->input('jenis_kelamin'),
            'keluarga' => $request->input('keluarga'),
            'url_foto_diri' => $request->input('url_foto_diri'),
            'url_file_ktp' => $request->input('url_file_ktp'),
            'url_file_kk' => $request->input('url_file_kk'),
            'url_file_buku_nikah' => $request->input('url_file_buku_nikah'),
            'url_file_akta_kelahiran' => $request->input('url_file_akta_kelahiran'),
            'url_npwp' => $request->input('url_npwp'),
            'url_lamaran_pekerjaan' => $request->input('url_lamaran_pekerjaan'),
        ]);


        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil Memasukan Data',
            // 'data' => $karyawan
            'data' => $karyawan
        ]);
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
        $karyawan = DataKaryawan::where('id_karyawan', $id)->first();

        return response()->json($karyawan);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $karyawan = DataKaryawan::find($id);
        $karyawan->id_badge = $request->input('id_badge');
        $karyawan->nama_karyawan = $request->input('nama_karyawan');
        $karyawan->gelar_depan = $request->input('gelar_depan');
        $karyawan->nama_lengkap = $request->input('nama_lengkap');
        $karyawan->gelar_belakang = $request->input('gelar_belakang');
        $karyawan->pendidikan = $request->input('pendidikan');
        $karyawan->alamat = $request->input('alamat');
        $karyawan->agama = $request->input('agama');
        $karyawan->status_pernikahan = $request->input('status_pernikahan');
        $karyawan->tempat_lahir = $request->input('tempat_lahir');
        $karyawan->tanggal_lahir = $request->input('tanggal_lahir');
        $karyawan->jenis_kelamin = $request->input('jenis_kelamin');
        //json siapa saja yang berkeluarga dengan orang tersebut
        // $karyawan->keluarga = $request->input('nama_karyawan');
        // untuk data url berkas data diri
        $karyawan->save();
        return response()->json([
            'status' => 'success',
            'message' => 'User berhasil diperbarui',
            'data' => $karyawan
        ]);
        // return redirect()->route('karyawan.index')->with('success', 'Karyawan updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $karyawan = DataKaryawan::find($id);

        
        if (!$karyawan) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'User Tidak Ditemukan',
            ]);
        }

       
        $karyawan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User berhasil dihapus',
        ]);
        
    }
}
