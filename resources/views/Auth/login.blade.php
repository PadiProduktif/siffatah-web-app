<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SI FATAH Login</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #f9fafb;
            font-family: Arial, sans-serif;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }
        .login-container img {
            max-height: 50px;
            margin: 0 10px;
        }
        .login-header {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }
        .login-container h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .login-container p {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 20px;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #e5e7eb;
        }
        .btn {
            background-color: #10b981;
            color: #fff;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #059669;
        }
        .forgot-password {
            margin-top: 10px;
            font-size: 14px;
            color: #3b82f6;
            text-decoration: none;
        }
        .forgot-password:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
        <img src="{{ asset('img/Logo BUMN png 3.png') }}" alt="Logo BUMN">
        <img src="{{ asset('img/Logo PT Pupuk Kujang png 1.png') }}" alt="Logo Pupuk Kujang">
        <img src="{{ asset('img/Logo Pupuk Indonesia png 2.png') }}" alt="Logo Pupuk Indonesia">
        </div>
        <h1>SI FATAH v0.01</h1>
        <p>Sistem Informasi Fasilitas dan Kesehatan</p>
        @if(session('error'))
            <div class="alert alert-danger">
                <b>Opps!</b> {{session('error')}}
            </div>
            @endif
        <h2>SELAMAT DATANG!</h2>
        <form action="{{ route('actionLogin') }}" method="POST">
            @csrf
            <input type="text" name="username" class="form-control" placeholder="Masukan Username Anda" required>
            <input type="password" name="password" class="form-control" placeholder="Masukan Password" required>
            <!-- <a href="#" class="forgot-password">Lupa Password?</a> -->
            <button type="submit" class="btn">Masuk</button>
        </form>
    </div>
</body>
</html>
