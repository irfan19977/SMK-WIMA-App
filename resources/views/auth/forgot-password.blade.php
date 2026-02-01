@extends('layouts.master-without-nav')
@section('title')
    Lupa Password - SMK PGRI LAWANG
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
                                                    <h4 class="mb-3">Reset Password!</h4>
                                                    <p class="mb-4">Masukkan email untuk menerima link reset password</p>
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
                                                        <h4 class="font-size-18">Lupa Password</h4>
                                                        <p class="text-muted">Masukkan email Anda untuk reset password</p>
                                                    </div>

                                                    <!-- Session Status -->
                                                    @if (session('status'))
                                                        <div class="alert alert-success" role="alert">
                                                            {{ session('status') }}
                                                        </div>
                                                    @endif

                                                    <form method="POST" action="{{ route('password.email') }}" class="auth-input">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="email" class="form-label">Email</label>
                                                            <input id="email" type="email"
                                                                class="form-control @error('email') is-invalid @enderror"
                                                                name="email" value="{{ old('email') }}" required
                                                                autocomplete="email" autofocus placeholder="Masukkan email">
                                                            @error('email')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>

                                                        <div class="mt-4">
                                                            <button class="btn btn-success w-100" type="submit">
                                                                <i class="mdi mdi-email-send me-2"></i> Kirim Link Reset
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
                                                    <p class="mb-0">Ingat password? <a href="{{ route('login') }}" class="fw-medium text-success">Masuk</a></p>
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
@endsection
