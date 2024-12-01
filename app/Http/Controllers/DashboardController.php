<?php

namespace App\Http\Controllers;

use App\Models\Auth\Roles;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MasterData\DataKaryawan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // $dataKaryawan = DataKaryawan::all();
        $countDataKaryawanActive = DataKaryawan::count();
        // $countDataKaryawanActive = DataKaryawan::where('status', 'active')->count();
        // dd(
        //     $countDataKaryawanActive,
        // );
        $data = [
            'countDataKaryawanActive' => $countDataKaryawanActive,
            'countDataKaryawanPensiun' => 4,
        ];
        return view('home', $data);
    }

}

