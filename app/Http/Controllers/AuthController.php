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
        if (!Auth::attempt($request->only('username', 'password'))) {
        //     return response()->json(['message' => 'Unauthorized',
        //     'username' => $request->username,
        //     'password' => $request->password,   
        // ], 401);
            Session::flash('error', 'User anda tidak ditemukan');
            return redirect('/login');
        }

        $user = User::where('username', $request->username)->firstOrFail();
        // dd(
        //     $request->only('username', 'password'),
        //     $user
        // );

        // if (!Hash::check($request->password, $user->password)) {
        //     // return response()->json(['message' => 'Unauthorized'], 401);
        //     Session::flash('error', 'password yang anda masukan salah');
        //     return redirect('/login');
        // }

        $token = $user->createToken('auth_token')->plainTextToken;

        // return response()->json([
        //     'success' => true,
        //     'access_token' => $token,
        //     'token_type' => 'Bearer',
        // ],200);

        Session::flash('success', 'berhasil login');
        return redirect('admin/dashboard');
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

