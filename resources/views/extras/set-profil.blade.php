@extends('layouts.app')  
@section('title', 'Pengaturan Profil')  
@section('content')  
  
@if(auth()->user()->role === 'tko')
    <div class="card mb-2">  
        <div class="card-header">  
            <h2 class="my-4">Set Employee Asset</h2>  
        </div>  
        <div class="card-body">  
            <div class="container-fluid">
                <form action="{{ route('karyawan.update.kelengkapan', auth()->user()->username) }}" method="POST">    
                    @csrf  
                    <div class="modal-body">  
                        <div class="row g-3">  
                            <!-- Informasi Dasar -->  
                            <div class="col-md-4">  
                                <label for="sepatu_kantor" class="form-label">Sepatu Kantor</label>  
                                <select class="form-select" id="sepatu_kantor" name="sepatu_kantor" required>    
                                    <option value="">Pilih Ukuran Sepatu</option>    
                                    @for ($i = 34; $i <= 45; $i++)    
                                        <option value="{{ $i }}" {{ $dataKaryawan->sepatu_kantor == $i ? 'selected' : '' }}>{{ $i }}</option>    
                                    @endfor    
                                </select>    
                            </div>  
      
                            <div class="col-md-4">    
                                <label for="sepatu_safety" class="form-label">Sepatu Safety</label>    
                                <select class="form-select" id="sepatu_safety" name="sepatu_safety" required>    
                                    <option value="">Pilih Ukuran Sepatu</option>    
                                    @for ($i = 34; $i <= 45; $i++)    
                                        <option value="{{ $i }}" {{ $dataKaryawan->sepatu_safety == $i ? 'selected' : '' }}>{{ $i }}</option>    
                                    @endfor    
                                </select>    
                            </div>    
      
                            <div class="col-md-4">    
                                <label for="wearpack" class="form-label">Wearpack Cover All</label>    
                                <select class="form-select" id="wearpack" name="wearpack" required>    
                                    <option value="">Pilih Jenis Ukuran</option>    
                                    @foreach (['S', 'M', 'L', 'XL', '2XL', '3XL', '4XL', '5XL', '6XL'] as $size)    
                                        <option value="{{ $size }}" {{ $dataKaryawan->wearpack == $size ? 'selected' : '' }}>{{ $size }}</option>    
                                    @endforeach    
                                </select>    
                            </div>    
      
                            <div class="col-md-4">    
                                <label for="jaket_shift" class="form-label">Jaket Shift</label>    
                                <select class="form-select" id="jaket_shift" name="jaket_shift" required>    
                                    <option value="">Pilih Ukuran</option>    
                                    @foreach (['S', 'M', 'L', 'XL', '2XL', '3XL', '4XL', '5XL', '6XL'] as $size)    
                                        <option value="{{ $size }}" {{ $dataKaryawan->jaket_shift == $size ? 'selected' : '' }}>{{ $size }}</option>    
                                    @endforeach    
                                </select>    
                            </div>    
      
                            <div class="col-md-4">    
                                <label for="seragam_olahraga" class="form-label">Seragam Olahraga</label>    
                                <select class="form-select" id="seragam_olahraga" name="seragam_olahraga" required>    
                                    <option value="">Pilih Ukuran</option>    
                                    @foreach (['S', 'M', 'L', 'XL', '2XL', '3XL', '4XL', '5XL', '6XL'] as $size)    
                                        <option value="{{ $size }}" {{ $dataKaryawan->seragam_olahraga == $size ? 'selected' : '' }}>{{ $size }}</option>    
                                    @endforeach    
                                </select>    
                            </div>    
      
                            <div class="col-md-4">    
                                <label for="jaket_casual" class="form-label">Jaket Casual</label>    
                                <select class="form-select" id="jaket_casual" name="jaket_casual" required>    
                                    <option value="">Pilih Ukuran Jaket</option>    
                                    @foreach (['S', 'M', 'L', 'XL', '2XL', '3XL', '4XL', '5XL', '6XL'] as $size)    
                                        <option value="{{ $size }}" {{ $dataKaryawan->jaket_casual == $size ? 'selected' : '' }}>{{ $size }}</option>    
                                    @endforeach    
                                </select>    
                            </div>    
      
                            <div class="col-md-4">    
                                <label for="seragam_dinas_harian" class="form-label">Seragam Dinas Harian</label>    
                                <select class="form-select" id="seragam_dinas_harian" name="seragam_dinas_harian" required>    
                                    <option value="">Pilih Ukuran Seragam</option>    
                                    @foreach (['S', 'M', 'L', 'XL', '2XL', '3XL', '4XL', '5XL', '6XL'] as $size)    
                                        <option value="{{ $size }}" {{ $dataKaryawan->seragam_dinas_harian == $size ? 'selected' : '' }}>{{ $size }}</option>    
                                    @endforeach    
                                </select>    
                            </div>    
                        </div>  
                    </div>  
                    <div class="modal-footer">  
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>  
                        <button type="submit" class="btn btn-primary">Simpan</button>  
                    </div>  
                </form>  
            </div>  
        </div>  
    </div>  
@endif
  
<div class="card">  
    <div class="card-header">  
        <h2 class="my-4">Update Password</h2>  
    </div>  
    <div class="card-body">  
        <div class="container-fluid">   
            <form method="POST" action="{{ route('password.update') }}">  
                @csrf  
          
                <!-- Current Password -->  
                <div class="mb-3">  
                    <label for="current_password" class="form-label">Password Lama</label>  
                    <div class="input-group">  
                        <input type="password" class="form-control" id="current_password" name="current_password" required>  
                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="current_password">  
                            <i class="bi bi-eye"></i>  
                        </button>  
                    </div>  
                </div>  
  
                <!-- New Password -->  
                <div class="mb-3">  
                    <label for="new_password" class="form-label">Password Baru</label>  
                    <div class="input-group">  
                        <input type="password" class="form-control" id="new_password" name="new_password" required>  
                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="new_password">  
                            <i class="bi bi-eye"></i>  
                        </button>  
                    </div>  
                </div>  
  
                <!-- Confirm New Password -->  
                <div class="mb-3">  
                    <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>  
                    <div class="input-group">  
                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>  
                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="new_password_confirmation">  
                            <i class="bi bi-eye"></i>  
                        </button>  
                    </div>  
                    <small id="password-match-feedback" class="text-danger" style="display: none;">Password tidak cocok</small>  
                </div>  
          
                <button type="submit" class="btn btn-primary">Perbarui Password</button>  
            </form>  
        </div>  
    </div>  
</div>  
  
@endsection  
  
@push('scripts')  
<script>  
    // Toggle password visibility  
    document.querySelectorAll('.toggle-password').forEach(button => {  
        button.addEventListener('click', function () {  
            const target = document.getElementById(this.getAttribute('data-target'));  
            const icon = this.querySelector('i');  
            if (target.type === 'password') {  
                target.type = 'text';  
                icon.classList.replace('bi-eye', 'bi-eye-slash');  
            } else {  
                target.type = 'password';  
                icon.classList.replace('bi-eye-slash', 'bi-eye');  
            }  
        });  
    });  
  
    // Compare new password and confirmation  
    const newPassword = document.getElementById('new_password');  
    const confirmPassword = document.getElementById('new_password_confirmation');  
    const feedback = document.getElementById('password-match-feedback');  
  
    // Event listener for password confirmation input  
    confirmPassword.addEventListener('input', () => {  
        if (confirmPassword.value !== newPassword.value) {  
            feedback.style.display = 'block';  
            feedback.textContent = 'Password tidak cocok';  
            confirmPassword.classList.add('is-invalid');  
        } else {  
            feedback.style.display = 'none';  
            confirmPassword.classList.remove('is-invalid');  
            confirmPassword.classList.add('is-valid');  
        }  
    });  
  
    // Optionally: Real-time validation for new password field  
    newPassword.addEventListener('input', () => {  
        if (confirmPassword.value !== '' && confirmPassword.value !== newPassword.value) {  
            feedback.style.display = 'block';  
            feedback.textContent = 'Password tidak cocok';  
            confirmPassword.classList.add('is-invalid');  
        } else {  
            feedback.style.display = 'none';  
            confirmPassword.classList.remove('is-invalid');  
            confirmPassword.classList.add('is-valid');  
        }  
    });  
</script>  
@endpush  
