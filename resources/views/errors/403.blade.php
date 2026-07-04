@extends('layouts.master-without-nav')
@section('title')
    403 Error
@endsection
@section('content')
    <div class="auth-error d-flex align-items-center min-vh-100">
        <div class="bg-overlay bg-light"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-8">
                    <div>
                        <div class="text-center mb-4">
                            <div class="mt-5">
                                <h1 class="error-title mt-5"><span>403!</span></h1>
                                <h4 class="mt-2 text-uppercase mt-4">Akses Ditolak</h4>
                                <p class="mt-4 text-muted w-50 mx-auto">Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Silakan kembali ke dashboard.</p>
                            </div>

                            <div class="mt-5 text-center">
                                <a class="btn btn-primary waves-effect waves-light" href="{{ url('/dashboard') }}">Kembali ke Dashboard</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection