@extends('layouts.app')
@section('title', 'Pengaturan Profil')
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