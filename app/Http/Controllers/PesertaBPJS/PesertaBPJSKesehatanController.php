<?php

namespace App\Http\Controllers\PesertaBPJS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PesertaBPJS\PesertaBPJSKesehatan;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;


class PesertaBPJSKesehatanController extends Controller
{
    public function index()
    {
        try {       
            
            $data['pesertaBPJS'] = PesertaBPJSKesehatan::all();
            return view('dashboard/pesertaBPJS/peserta-bpjs-kesehatan', $data);


        } catch (\Exception $e) {
            // Log error for debugging
            Log::error("Error retrieving data: " . $e->getMessage());

            // Return error response
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
