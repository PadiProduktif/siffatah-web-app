<?php

namespace App\Http\Controllers\BerkasPengobatan;

use App\Http\Controllers\Controller;
use App\Models\BerkasPengobatan\BerkasPengobatan;
use App\Models\TagihanRumahSakitModel;
use App\Models\RincianBiaya\RincianBiaya;
use App\Models\MasterData\DataKaryawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class BerkasPengobatanController extends Controller
{
    public function index()
    {
        
        $dataTagihan = TagihanRumahSakitModel::orderBy('updated_at', 'desc');
        // dd($dataTagihan);
        // $karyawan = DataKaryawan::orderBy('nama_karyawan', 'asc');
        $obat = BerkasPengobatan::orderBy('updated_at', 'desc');

        if (auth()->user()->role === 'tko') {
            $obat->where('id_badge', auth()->user()->username);
            // $karyawan->where('id_badge', auth()->user()->username);
        }

        $data['obat'] = $obat->get(); // Execute the query
        $data['dataTagihan'] = $dataTagihan->get(); // Execute the query
        // $data['karyawan'] = $karyawan->get(); // Execute the query

        // dd($data['dataTagihan']);
        // Mengembalikan view dengan data yang diambil
        return view('dashboard.berkas-pengobatan', [
            'obat' => $data['obat'],
            // 'karyawan' => $data['karyawan'],
            'dataTagihan' => $data['dataTagihan'],
        ]);
        try {
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Error fetching data in BerkasPengobatanController@index: ' . $e->getMessage());
    
            // Redirect ke halaman error atau tampilkan pesan ke user
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data.');
        }
    }
    
    public function show(string $id)
    {
        $dataTagihan = TagihanRumahSakitModel::where('id_tagihan', $id)->first();
        $dataKaryawan = DataKaryawan::orderBy('nama_karyawan', 'asc')->get();
        $dataObat = BerkasPengobatan::orderBy('updated_at', 'desc')->where('no_surat_rs', $dataTagihan->no_surat)->get(); // Execute the query

        
        $dataRincianBiayaRAW = RincianBiaya::orderBy('id_badge', 'asc')
        // ->where('id_badge', $value1->id_badge)
        ->where('no_surat_rs', $dataTagihan->no_surat)
        ->get();

        foreach ($dataObat as $key1 => $value1) {

            $dataRincianBiaya = RincianBiaya::orderBy('id_badge', 'asc')
            ->where('id_badge', $value1->id_badge)
            ->where('no_surat_rs', $dataTagihan->no_surat)
            ->where('id_kategori', $value1->id_badge)
            ->get();


            $totalBiayaPengajuan = 0;
            $totalBiayaDr = 0;
            foreach ($dataRincianBiaya as $key2 => $value2) {

                $totalBiayaPengajuan += $value2->nominal_pengajuan;
                $totalBiayaDr += $value2->nominal_dokter;
            }

            $value1['pasien'] = 'nama pasien';
            $value1['total_biaya_pengajuan'] = $totalBiayaPengajuan;
            $value1['total_biaya_dr'] = $totalBiayaDr;
            $value1['totalRincian'] = count($dataRincianBiaya);
            // $value1['dataRincian'][] = $value2;
        }
        
                    // dd(
                    //     // $dataTagihan,
                    //     // $dataKaryawan,
                    //     $dataObat,
                    //     $dataRincianBiayaRAW,
                    //     // $dataRincianBiaya,
                    // );
        try {
            return view('dashboard.tagihan-rumah-sakit-detail', [
                'dataObat' => $dataObat,
                'dataTagihan' => $dataTagihan,
                'dataKaryawan' => $dataKaryawan,
                'dataRincianBiayaRAW' => $dataRincianBiayaRAW,
            ]);
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Error fetching data in BerkasPengobatanController@index: ' . $e->getMessage());
    
            // Redirect ke halaman error atau tampilkan pesan ke user
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data.');
        }
    }

    public function store_invoice(Request $request) {
        $obat = TagihanRumahSakitModel::create([
            'id_berkas_pengobatan' => rand(10, 99999999),
            'rs_klinik' => $request->input('rs_klinik'),
            'urgensi' => $request->input('urgensi'),
            'no_surat' => $request->input('no_surat'),
            'tanggal_invoice' => $request->input('tanggal_invoice'),
            'keterangan' => $request->input('keterangan'),
            'status' => 1,
            'updated_at' => now(),
            'updated_by' => auth()->user()->role,
            'created_at' => now(),
            'created_by' => auth()->user()->role,
        ]);

        return redirect('/admin/berkas-pengobatan/')->with('success', 'Data berhasil disimpan.');
    }
    public function create()
    {
        
    }

    public function store(Request $request)
    {
        try {
            $dataTagihan = TagihanRumahSakitModel::where('id_tagihan', $request->id_tagihan)->orderBy('updated_at', 'desc')->first();

            $karyawan = DataKaryawan::where('id_badge', $request->input('id_badge'))
                ->orderBy('nama_karyawan', 'asc')
                ->first(); // Ambil data pertama yang sesuai dengan kondisi

            $idRand = rand(10, 99999999);
            $obat = BerkasPengobatan::create([
                'id_berkas_pengobatan' => $idRand,
                'id_badge' => $request->input('id_badge'),
                'nama_karyawan' => $karyawan['nama_karyawan'],
                'jabatan_karyawan' => $request->input('jabatan_karyawan'),
                'nama_anggota_keluarga' => $request->input('nama_anggota_keluarga'),
                // 'hubungan_keluarga' => $request->input('hubungan_keluarga'),
                'deskripsi' => $request->input('deskripsi'),
                'nominal' => 0,
                'rs_klinik' => $dataTagihan->rs_klinik,
                'urgensi' => $request->input('urgensi'),
                'no_surat_rs' => $dataTagihan->no_surat,
                'tanggal_pengobatan' => $request->input('tanggal_pengobatan'),
                'deskripsi' => $request->input('deskripsi'),
                'status' => 1,
                'file_url' => $request->uploaded_files,

                'updated_at' => now(),
                'updated_by' => auth()->user()->role,
                'created_at' => now(),
                'created_by' => auth()->user()->role,
            ]);

            foreach ($request['nominal_pengajuan'] as $index => $nominal) {
                $deskripsiBiaya = $request['deskripsi_pengajuan'][$index] ?? '';
                $nominalPengajuan = str_replace(['Rp', '.', ','], '', $nominal);

                if ($nominalPengajuan === '' || $deskripsiBiaya === '') continue;
                RincianBiaya::create([
                    'id_rincian_biaya' => rand(10, 99999999),
                    'id_badge' => $request->input('id_badge'),
                    'kategori' => "invoice",
                    'id_kategori' => $idRand,
                    'rumah_sakit' => $dataTagihan->rs_klinik,
                    'no_surat_rs' => $dataTagihan->no_surat,
                    'deskripsi_biaya' => $deskripsiBiaya,
                    'nominal_pengajuan' => $nominalPengajuan,
                    'status_rincian_biaya' => 1,
                    'created_by' => auth()->user()->id_user,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            Log::info("Menambah data Berkas Pengobatan Request Data: ", $request->all());
            return redirect('/admin/berkas-tagihan-rumah-sakit/'.$request->id_tagihan)->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $e) {
            return redirect('/admin/berkas-tagihan-rumah-sakit/'.$request->id_tagihan)->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }
    
    public function uploadTemp(Request $request)
    {
    
        // return response()->json(['error' => 'No file uploaded'], 400);
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/BerkasPengobatan'), $fileName);
        
            return response()->json([
                'fileName' => $fileName
            ]);
        }
        
        return response()->json(['error' => 'No file uploaded'], 400);
    }
    
    public function deleteTemp(Request $request)
    {
        $filename = $request->input('fileName');
        $filePath = storage_path("app/public/temp/{$filename}");
    
        if (file_exists($filePath)) {
            unlink($filePath);
            Log::info("File Terhapus dari Public Upload Klaim Kecelakaan Temp: " . json_encode($filename));
            return response()->json(['success' => true]);
        }else{
            $filePath = public_path("uploads/BerkasPengobatan/{$filename}");
            unlink($filePath);
            Log::info("File Terhapus dari Public Upload Klaim Pengobatan: " . json_encode($filename));
            return response()->json(['success' => true]);
        }
    
        return response()->json(['error' => 'File not found'], 404);
    }

    public function edit(string $id)
    {
        $obat = BerkasPengobatan::where('id_berkas_pengobatan', $id)->first();

    
        if ($obat) {
            return response()->json([
                'status' => 'success',
                'message' => 'Data retrieved successfully.',
                'data' => $obat
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found for the provided ID.',
            ], 404); // 404 Not Found
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    // Validate required fields

    try {

        Log::info('Updating Attachment Berkas Pengobatan: ' . $id . ', Request Data: ', $request->all());

            // Decode uploaded_files dan removed_files
            $uploadedFiles = $request->input('uploaded_files', '[]');
            $uploadedFiles = json_decode($uploadedFiles, true) ?? [];

            $removedFiles = $request->input('removed_files', '[]');
            $removedFiles = json_decode($removedFiles, true) ?? [];

            Log::info('Decoded Uploaded Files:', $uploadedFiles);
            Log::info('Decoded Removed Files:', $removedFiles);

            // Pastikan uploadedFiles dan removedFiles adalah array
            $uploadedFiles = is_array($uploadedFiles) ? $uploadedFiles : [];
            $removedFiles = is_array($removedFiles) ? $removedFiles : [];

            // Ambil data klaim dari database
            $obat = BerkasPengobatan::findOrFail($id);
            $currentFiles = json_decode($obat->file_url, true);
            if (!is_array($currentFiles)) {
                $currentFiles = [];
            }

            // Log data awal
            Log::info('Existing Files:', $currentFiles);

            // Hapus file dari database dan server jika ada dalam $removedFiles
            if (!empty($removedFiles)) {
                foreach ($removedFiles as $file) {
                    $filePath = public_path('uploads/BerkasPengobatan/' . $file);
                    if (file_exists($filePath)) {
                        unlink($filePath);
                        Log::info('File removed from server:', ['file' => $filePath]);
                    }
                }
                $currentFiles = array_values(array_diff($currentFiles, $removedFiles));
            }

            // Tambahkan file baru ke array yang ada
            if (!empty($uploadedFiles)) {
                $currentFiles = array_merge($currentFiles, $uploadedFiles);
            }

            // Hilangkan duplikat file dan reset index array
            $finalFiles = array_values(array_unique($currentFiles));
            $duit = str_replace(['Rp', '.', ','], '', $request->nominal);

        
        $obat->update([
            'id_badge' => $request->input('id_badge'),
            'nama_karyawan' => $request->input('nama_karyawan'),
            'jabatan_karyawan' => $request->input('jabatan_karyawan'),
            'nama_anggota_keluarga' => $request->input('nama_anggota_keluarga'),
            'hubungan_keluarga' => $request->input('hubungan_keluarga'),
            'deskripsi' => $request->input('deskripsi'),
            'nominal' => $duit,
            'rs_klinik' => $request->input('rs_klinik'),
            'urgensi' => $request->input('urgensi'),
            'no_surat_rs' => $request->input('no_surat_rs'),
            'tanggal_pengobatan' => $request->input('tanggal_pengobatan'),
            'status' => $request->input('status'),
            'keterangan' => $request->input('keterangan'),
            'file_url' => json_encode($finalFiles),
        ]);

        return redirect('/admin/berkas-pengobatan')->with('success', 'Data berhasil disimpan.');
        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Data berhasil diupdate.',
        //     'request' => $request->all(),
        //     'filesFinal'=>$obat,
            
        // ]);

    } catch (\Exception $e) {
        return redirect('/admin/berkas-pengobatan')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        // return response()->json([
        //     'status' => 'error',
        //     'message' => 'Failed to update data.',
        //     'error' => $e->getMessage()
        // ], 500);
    }
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $dataObat = BerkasPengobatan::where('id_berkas_pengobatan', $id)->first();
            $dataTagihan = TagihanRumahSakitModel::where('no_surat', $dataObat->no_surat_rs)->first();
            
            // Log::info("hapus data Berkas Pengobatan Request Data: ", $dataObat->all());
            $dataObat->delete();
            return redirect('/admin/berkas-tagihan-rumah-sakit/'.$dataTagihan->id_tagihan)->with('success', 'Data berhasil dihapus.');
            // return redirect('/admin/berkas-pengobatan/')->with('success', 'Data berhasil disimpan.');
        } catch (\Throwable $th) {
            return redirect('/admin/berkas-pengobatan')->with('error', 'Terjadi kesalahan: ' . $th->getMessage());
        }


        
        // if ($obat->file_url && file_exists(public_path("uploads/BerkasPengobatan/{$obat->file_url}"))) {
        //     unlink(public_path("uploads/BerkasPengobatan/{$obat->file_url}"));
        // }

        

    }

}

