@extends('layouts.master-without-nav')
@section('title')
    Buat Password Baru - SMK PGRI LAWANG
@endsection
@section('content')
    <div class="auth-maintenance d-flex align-items-center min-vh-100">
        <div class="bg-overlay bg-light"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="auth-full-page-content d-flex min-vh-100 py-sm-5 py-4">
                        <div class="w-100">
                            <div class="d-flex flex-column h-100 py-0 py-xl-3">
                                <div class="text-center mb-4">
                                    <a href="{{ route('/') }}" class="">
                                        <img src="{{ asset('backend/assets/img/logo 1.png') }}" alt=""
                                            height="40" class="auth-logo mx-auto">
                                    </a>
                                    <p class="text-muted mt-2">Sistem Informasi Manajemen Sekolah</p>
                                </div>

                                <div class="card my-auto overflow-hidden">
                                    <div class="row g-0">
                                        <div class="col-lg-6">
                                            <div class="bg-overlay bg-success"></div>
                                            <div class="h-100 bg-auth align-items-end">
                                                <div class="p-4 text-white text-center">
                                                    <h4 class="mb-3">Buat Password Baru!</h4>
                                                    <p class="mb-4">Password baru Anda harus berbeda dari password sebelumnya</p>
                                                    <div class="mt-4">
                                                        <i class="mdi mdi-lock-reset-outline display-4"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="p-lg-5 p-4">
                                                <div>
                                                    <div class="text-center mt-1">
                                                        <h4 class="font-size-18">Buat Password Baru</h4>
                                                        <p class="text-muted">Password baru harus berbeda dari password yang lama</p>
                                                    </div>

                                                    <form method="POST" action="{{ route('password.update') }}" class="auth-input">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="email" class="form-label">Email</label>
                                                            <input id="email" type="email"
                                                                class="form-control @error('email') is-invalid @enderror"
                                                                name="email" value="{{ $email ?? old('email') }}" required
                                                                autocomplete="email" autofocus placeholder="Masukkan email">
                                                            @error('email')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label" for="password-input">Password</label>
                                                            <div class="position-relative">
                                                                <input type="password"
                                                                    class="form-control @error('password') is-invalid @enderror"
                                                                    name="password" placeholder="Masukkan password"
                                                                    id="password-input" required>
                                                                <span class="position-absolute end-0 top-50 translate-middle-y me-2" 
                                                                    id="password-addon" 
                                                                    style="cursor: pointer;">
                                                                    <i class="mdi mdi-eye-outline" style="color: #666;"></i>
                                                                </span>
                                                            </div>
                                                            @error('password')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                            <div class="form-text">Password harus 8-20 karakter</div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label" for="confirm-password-input">Konfirmasi Password</label>
                                                            <div class="position-relative">
                                                                <input type="password" class="form-control"
                                                                    name="password_confirmation" placeholder="Konfirmasi password"
                                                                    id="confirm-password-input" required>
                                                                <span class="position-absolute end-0 top-50 translate-middle-y me-2" 
                                                                    id="password-confirm-addon" 
                                                                    style="cursor: pointer;">
                                                                    <i class="mdi mdi-eye-outline" style="color: #666;"></i>
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="mt-4">
                                                            <button class="btn btn-success w-100" type="submit">
                                                                <i class="mdi mdi-lock-reset me-2"></i> Reset Password
                                                            </button>
                                                        </div>

                                                        <div class="mt-4 pt-2 text-center">
                                                            <div class="signin-other-title">
                                                                <h5 class="font-size-14 mb-3 title">Hubungi Administrator</h5>
                                                            </div>
                                                            <div class="pt-2 hstack gap-2 justify-content-center">
                                                                <a href="https://wa.me/6282233088346" class="btn btn-success btn-sm">
                                                                    <i class="mdi mdi-whatsapp font-size-16"></i>
                                                                </a>
                                                                <a href="https://t.me/Hekel256" class="btn btn-info btn-sm">
                                                                    <i class="mdi mdi-telegram font-size-16"></i>
                                                                </a>
                                                            </div>
                                                            <small class="text-muted d-block mt-2">*Hubungi hanya jika ada permasalahan</small>
                                                        </div>
                                                    </form>
                                                </div>

                                                <div class="mt-4 text-center">
                                                    <p class="mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="fw-medium text-success">Masuk</a></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
    </div>
@endsection
@section('scripts')
    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Password toggle for main password
            const passwordToggle = document.getElementById('password-addon');
            const passwordField = document.getElementById('password-input');
            
            passwordToggle.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    passwordToggle.innerHTML = '<i class="mdi mdi-eye-off-outline" style="color: #666;"></i>';
                } else {
                    passwordField.type = 'password';
                    passwordToggle.innerHTML = '<i class="mdi mdi-eye-outline" style="color: #666;"></i>';
                }
            });
            
            // Password toggle for confirm password
            const confirmPasswordToggle = document.getElementById('password-confirm-addon');
            const confirmPasswordField = document.getElementById('confirm-password-input');
            
            confirmPasswordToggle.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (confirmPasswordField.type === 'password') {
                    confirmPasswordField.type = 'text';
                    confirmPasswordToggle.innerHTML = '<i class="mdi mdi-eye-off-outline" style="color: #666;"></i>';
                } else {
                    confirmPasswordField.type = 'password';
                    confirmPasswordToggle.innerHTML = '<i class="mdi mdi-eye-outline" style="color: #666;"></i>';
                }
            });
        });
    </script>
@endsection
