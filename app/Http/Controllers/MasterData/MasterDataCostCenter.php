<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\CostCenter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class MasterDataCostCenter extends Controller
{
    public function index()
    {
        try {
            $list_cost_center = CostCenter::all();
            return view('extras/master-data-cost-center', compact('list_cost_center'));
        } catch (\Exception $e) {

            // Return error response with 500 status code
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        
        // Validate the request data
        try {
            // Handle file upload if present
            // Create a new klaim_pengobatan record
            $klaim = CostCenter::create([
                'id_cost_center' => rand(10, 99999999),
                'cost_center' => $request->cost_center,
                'nama_bagian' => $request->nama_bagian,
            ]);
            Log::info("Menambah data Cost Center, Berikut Request Data: ", $request->all());
            // Return success response
            return redirect()->back()->with('success', 'Data berhasil ditambah!');

        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error("Error creating data: " . $e->getMessage());

            // Return error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create data',
                'error' => $e->getMessage(),
                'request' => $request->all()
            ], 500);
        }
        
    }

    public function update(Request $request, string $id)
    {

        try {
            Log::info('Updating Master Data Cost Center: ' . $id . ', Request Data: ', $request->all());
            $CostCenter = CostCenter::findOrFail($id);
            $CostCenter->update([

                'cost_center' => $request->cost_center,
                'nama_bagian' => $request->nama_bagian,
    
            ]);
            return redirect()->back()->with('success', 'Data berhasil di Perbarui!');
        } catch (\Throwable $th) {
            
            return response()->json([
                'status' => 'failed',
                'message' => $th->getMessage(),
                'data' => $request->all(),
            ]);
        }

    }
    public function uploadExcel(Request $request){

                    // Validasi file
        $validator = Validator::make($request->all(), [
            'file_excel' => 'required|mimes:xlsx,xls',
        ]);
        
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Proses unggah file
        if ($request->hasFile('file_excel')) {
            $path = $request->file('file_excel')->getRealPath();
            $data = Excel::toArray([], $request->file('file_excel'));

            // Validasi apakah data tidak kosong
            if (!empty($data) && count($data[0]) > 0) {
                foreach ($data[0] as $key => $row) {
                    // Lewati baris pertama (header)
                    if ($key < 1) {
                        continue;
                    }
                    
                    
                    
                    // Hanya masukkan nilai, abaikan jika panjang data terlalu besar
                    CostCenter::create([


                        'id_cost_center' => rand(10, 99999999),
                        'cost_center' => substr($row[0] ?? '', 0, 50),
                        'nama_bagian' => substr($row[1] ?? '', 0, 1000),
                        
                        
                    ]);
                }
            }
            

            return redirect()->back()->with('success', 'Data berhasil diunggah!');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah file!');
    }

    public function destroy(string $id)
    {
        try {
            Log::info("Menghapus data Cost Center  ID: {$id}"); // Log untuk debugging awal
            
            // Ambil data berdasarkan ID
            $CostCenter = CostCenter::findOrFail($id);

    
            // Hapus data dari database
            $CostCenter->delete();
            Log::info("Data Cost Center dengan ID: {$id} berhasil dihapus.");
    
            return response()->json(['message' => 'Data berhasil dihapus.'], 200);
        } catch (\Exception $e) {
            Log::error("Error saat menghapus data dengan ID: {$id}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    
    }

    public function deleteMultiple(Request $request)
    {
        try {
            $ids = $request->input('ids'); // Ambil array ID dari request
            Log::info('IDs yang akan dihapus: ', $ids);
            
    
            // Ambil semua data berdasarkan ID
            $CostCenter = CostCenter::whereIn('id_cost_center', $ids)->get();
    
    
            // Hapus data dari database
            CostCenter::whereIn('id_cost_center', $ids)->delete();
    
            return response()->json(['message' => 'Data berhasil dihapus.'], 200);
        } catch (\Exception $e) {
            Log::error('Error saat menghapus data:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
    
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus data.'], 500);
        }
    }
}
