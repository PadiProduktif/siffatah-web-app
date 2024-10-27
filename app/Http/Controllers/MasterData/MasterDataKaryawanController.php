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
        //fotodiri
        if ($request->hasFile('foto_diri')) {
            $foto_diri = $request->file('foto_diri');
            $foto_diri_fileName = rand(10,99999999).'_'.$foto_diri->getClientOriginalName();
            $foto_diri->move(public_path('uploads/karyawan/foto_diri/'), $foto_diri_fileName);
            
        }else {
            $foto_diri_fileName = null;
        }
        //file_ktp
        if ($request->hasFile('file_ktp')) {
            $file_ktp = $request->file('file_ktp');
            $file_ktp_fileName = rand(10,99999999).'_'.$file_ktp->getClientOriginalName();
            $file_ktp->move(public_path('uploads/karyawan/file_ktp/'), $file_ktp_fileName);
                
        }else {
            $file_ktp_fileName = null;
        }
        //file_kk
        if ($request->hasFile('file_kk')) {
            $file_kk = $request->file('file_kk');
            $file_kk_fileName = rand(10,99999999).'_'.$file_kk->getClientOriginalName();
            $file_kk->move(public_path('uploads/karyawan/file_kk/'), $file_kk_fileName);
                
        }else {
            $file_kk_fileName = null;
        }
        //buku_nikah
        if ($request->hasFile('buku_nikah')) {
            $buku_nikah = $request->file('buku_nikah');
            $buku_nikah_fileName = rand(10,99999999).'_'.$buku_nikah->getClientOriginalName();
            $buku_nikah->move(public_path('uploads/karyawan/buku_nikah/'), $buku_nikah_fileName);
                
        }else {
            $buku_nikah_fileName = null;
        }
        //akta_kelahiran
        if ($request->hasFile('akta_kelahiran')) {
            $akta_kelahiran = $request->file('akta_kelahiran');
            $akta_kelahiran_fileName = rand(10,99999999).'_'.$akta_kelahiran->getClientOriginalName();
            $akta_kelahiran->move(public_path('uploads/karyawan/akta_kelahiran/'), $akta_kelahiran_fileName);
                
        }else {
            $akta_kelahiran_fileName = null;
        }
        //npwp
        if ($request->hasFile('npwp')) {
            $npwp = $request->file('npwp');
            $npwp_fileName = rand(10,99999999).'_'.$npwp->getClientOriginalName();
            $npwp->move(public_path('uploads/karyawan/npwp/'), $npwp_fileName);
                
        }else {
            $npwp_fileName = null;
        }
        //lamaran_pekerjaan
        if ($request->hasFile('lamaran_pekerjaan')) {
            $lamaran_pekerjaan = $request->file('lamaran_pekerjaan');
            $lamaran_pekerjaan_fileName = rand(10,99999999).'_'.$lamaran_pekerjaan->getClientOriginalName();
            $lamaran_pekerjaan->move(public_path('uploads/karyawan/lamaran_pekerjaan/'), $lamaran_pekerjaan_fileName);
                
        }else {
            $lamaran_pekerjaan_fileName = null;
        }
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
            'url_foto_diri' => $foto_diri_fileName,
            'url_file_ktp' => $file_kk_fileName,
            'url_file_kk' => $file_kk_fileName,
            'url_file_buku_nikah' => $buku_nikah_fileName,
            'url_file_akta_kelahiran' => $akta_kelahiran_fileName,
            'url_npwp' => $npwp_fileName,
            'url_lamaran_pekerjaan' => $lamaran_pekerjaan_fileName,
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
        if ($request->hasFile('foto_diri')) {
            if ($karyawan->url_foto_diri != null) {
                $fileName_outdated = public_path("uploads/karyawan/foto_diri/{$karyawan->url_foto_diri}");
                unlink($fileName_outdated);
            }
            
            
            $foto_diri = $request->file('foto_diri');
            $foto_diri_fileName = rand(10,99999999).'_'.$foto_diri->getClientOriginalName();
            $foto_diri->move(public_path('uploads/karyawan/foto_diri/'), $foto_diri_fileName);
            
        }else {
            $foto_diri_fileName = null;
        }
        //file_ktp
        if ($request->hasFile('file_ktp')) {
            if ($karyawan->url_file_ktp != null) {
                $fileName_outdated = public_path("uploads/karyawan/file_ktp/{$karyawan->url_file_ktp}");
                unlink($fileName_outdated);
            }

            $file_ktp = $request->file('file_ktp');
            $file_ktp_fileName = rand(10,99999999).'_'.$file_ktp->getClientOriginalName();
            $file_ktp->move(public_path('uploads/karyawan/file_ktp/'), $file_ktp_fileName);
                
        }else {
            $file_ktp_fileName = null;
        }
        //file_kk
        if ($request->hasFile('file_kk')) {
            if ($karyawan->url_file_kk != null) {
                $fileName_outdated = public_path("uploads/karyawan/file_kk/{$karyawan->url_file_kk}");
                unlink($fileName_outdated);
            }

            $file_kk = $request->file('file_kk');
            $file_kk_fileName = rand(10,99999999).'_'.$file_kk->getClientOriginalName();
            $file_kk->move(public_path('uploads/karyawan/file_kk/'), $file_kk_fileName);
                
        }else {
            $file_kk_fileName = null;
        }
        //buku_nikah
        if ($request->hasFile('buku_nikah')) {
            if ($karyawan->url_file_buku_nikah != null) {
                $fileName_outdated = public_path("uploads/karyawan/buku_nikah/{$karyawan->url_file_buku_nikah}");
                unlink($fileName_outdated);
            }

            $buku_nikah = $request->file('buku_nikah');
            $buku_nikah_fileName = rand(10,99999999).'_'.$buku_nikah->getClientOriginalName();
            $buku_nikah->move(public_path('uploads/karyawan/buku_nikah/'), $buku_nikah_fileName);
                
        }else {
            $buku_nikah_fileName = null;
        }
        //akta_kelahiran
        if ($request->hasFile('akta_kelahiran')) {
            if ($karyawan->url_file_akta_kelahiran != null) {
                $fileName_outdated = public_path("uploads/karyawan/akta_kelahiran/{$karyawan->url_file_akta_kelahiran}");
                unlink($fileName_outdated);
            }

            $akta_kelahiran = $request->file('akta_kelahiran');
            $akta_kelahiran_fileName = rand(10,99999999).'_'.$akta_kelahiran->getClientOriginalName();
            $akta_kelahiran->move(public_path('uploads/karyawan/akta_kelahiran/'), $akta_kelahiran_fileName);
                
        }else {
            $akta_kelahiran_fileName = null;
        }
        //npwp
        if ($request->hasFile('npwp')) {
            if ($karyawan->url_npwp != null) {
                $fileName_outdated = public_path("uploads/karyawan/npwp/{$karyawan->url_npwp}");
                unlink($fileName_outdated);
            }

            $npwp = $request->file('npwp');
            $npwp_fileName = rand(10,99999999).'_'.$npwp->getClientOriginalName();
            $npwp->move(public_path('uploads/karyawan/npwp/'), $npwp_fileName);
                
        }else {
            $npwp_fileName = null;
        }
        //lamaran_pekerjaan
        if ($request->hasFile('url_lamaran_pekerjaan')) {
            if ($karyawan->url_npwp != null) {
                $fileName_outdated = public_path("uploads/karyawan/lamaran_pekerjaan/{$karyawan->url_lamaran_pekerjaan}");
                unlink($fileName_outdated);
            }

            $lamaran_pekerjaan = $request->file('lamaran_pekerjaan');
            $lamaran_pekerjaan_fileName = rand(10,99999999).'_'.$lamaran_pekerjaan->getClientOriginalName();
            $lamaran_pekerjaan->move(public_path('uploads/karyawan/lamaran_pekerjaan/'), $lamaran_pekerjaan_fileName);
                
        }else {
            $lamaran_pekerjaan_fileName = null;
        }

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
        $karyawan->url_foto_diri = $foto_diri_fileName;
        $karyawan->url_file_ktp = $file_ktp_fileName;
        $karyawan->url_file_kk = $file_kk_fileName;
        $karyawan->url_file_buku_nikah = $buku_nikah_fileName;
        $karyawan->url_file_akta_kelahiran = $akta_kelahiran_fileName;
        $karyawan->url_npwp = $npwp_fileName;
        $karyawan->url_lamaran_pekerjaan = $lamaran_pekerjaan_fileName;

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
