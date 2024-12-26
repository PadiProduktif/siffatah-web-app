@extends('layouts.app')

@section('title', 'Set Password')

@section('content')

<div class="card">

    <div class="card-header">
        <h2 class="my-4">UPDATE PASSWORD</h2>
    </div>
    <div class="card-body">
        
        <div class="container-fluid">
        
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
        
                <!-- Current Password -->
                <div class="mb-3">
                    <label for="current_password" class="form-label">Password Lama</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                </div>
        
                <!-- New Password -->
                <div class="mb-3">
                    <label for="new_password" class="form-label">Password Baru</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>
        
                <!-- Confirm New Password -->
                <div class="mb-3">
                    <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                </div>
        
                <button type="submit" class="btn btn-primary">Perbarui Password</button>
            </form>
        </div>
    </div>
</div>

@endsection
