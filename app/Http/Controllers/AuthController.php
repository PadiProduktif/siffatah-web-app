<?php

namespace App\Http\Controllers;

use App\Models\Auth\Roles;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Registration
    public function register(Request $request)
    {

        $user = User::create([
            'username' => $request->username,
            'fullname' => $request->fullname,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 1
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function get_role(){
        $roles = Roles::select('*')->get();
        // $test_echo = rand(0, 99999);
        //untuk mengirim json di postman
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil Mendapatkan Data',
            'data' => $roles
        ]);
    }

    public function login(){
        return view('Auth/login');
    }

    // Login
    public function actionLogin(Request $request)
    {
        // Cek apakah username dan password cocok
        if (!Auth::attempt($request->only('username', 'password'))) {
            Session::flash('error', 'User atau password Anda salah');
            return redirect('/login');
        }
    
        // Ambil data user berdasarkan username
        $user = User::where('username', $request->username)->firstOrFail();
    
        // Buat token (opsional, hanya jika digunakan untuk API)
        $token = $user->createToken('auth_token')->plainTextToken;

        // Mapping role ke URL dashboard dan pesan notifikasi
        $roleRoutes = [
            'superadmin' => ['url' => 'admin/dashboard', 'message' => 'Berhasil login sebagai Superadmin'],
            'dr_hph' => ['url' => '/dashboard', 'message' => 'Berhasil login sebagai dr_hph'],
            'vp_osdm' => ['url' => '/dashboard', 'message' => 'Berhasil login sebagai VP OSDM'],
            'tko' => ['url' => '/dashboard', 'message' => 'Berhasil login sebagai TKO'],
        ];

        // Periksa apakah role user sesuai dengan yang ada di mapping
        if (array_key_exists($user->role, $roleRoutes)) {
            $route = $roleRoutes[$user->role];
            return redirect($route['url'])->with('toast_success', $route['message']);;
        }

        // Role tidak dikenali
        return redirect('/login')->with('error', 'Role Anda tidak diizinkan');
    }

    // Logout
    public function logout(Request $request)
    {
            // Hapus semua token akses milik pengguna
    $request->user()->tokens()->delete();

    // Logout dari sesi pengguna jika ada
    Auth::guard('web')->logout();

    // Redirect ke halaman login
    return redirect('/login')->with('status', 'Logout berhasil!');
    }
}

