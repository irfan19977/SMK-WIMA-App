@extends('home.layouts.app')

@section('title', 'QR Code Generator')

@section('content')
<div class="container py-5">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h2 class="fw-bold mb-2"><i class="icon-qrcode"></i> QR Code Generator</h2>
            <p class="text-muted">Buat QR Code untuk URL Anda dengan mudah dan cepat</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <!-- Info Card -->
        <div class="col-lg-4 col-md-5 mb-4 mb-lg-0">
            <div class="card shadow-sm h-100">
                <div class="card-body p-4">
                    <h5 class="card-title mb-3"><i class="icon-info"></i> Informasi</h5>
                    <hr>
                    <div class="mb-3">
                        <h6 class="text-primary mb-2"><i class="icon-check"></i> Mudah Digunakan</h6>
                        <p class="small text-muted mb-0">Cukup masukkan URL dan pilih format yang diinginkan</p>
                    </div>
                    <hr class="my-3">
                    <div class="mb-3">
                        <h6 class="text-primary mb-2"><i class="icon-check"></i> Multi Format</h6>
                        <p class="small text-muted mb-0">Tersedia format PNG, SVG, dan EPS</p>
                    </div>
                    <hr class="my-3">
                    <div class="alert alert-warning mb-0">
                        <small><strong>Tips:</strong> Gunakan format PNG untuk media digital dan SVG untuk desain grafis</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="col-lg-7 col-md-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0"><i class="icon-settings"></i> Pengaturan QR Code</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('qrcode.generate') }}" method="POST">
                        @csrf
                        
                        <!-- URL Input -->
                        <div class="mb-4">
                            <label for="url" class="form-label">
                                <i class="icon-link"></i> URL Tujuan <span class="text-danger">*</span>
                            </label>
                            <input type="url" 
                                   class="form-control @error('url') is-invalid @enderror" 
                                   id="url" 
                                   name="url" 
                                   placeholder="https://forms.gle/example" 
                                   value="{{ old('url') }}" 
                                   required>
                            <small class="form-text text-muted">
                                <i class="icon-info"></i> Masukkan URL lengkap dengan https://
                            </small>
                            @error('url')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Format Selection -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="icon-file-image"></i> Format File <span class="text-danger">*</span>
                            </label>
                            <div class="row g-3">
                                @foreach($formats as $value => $label)
                                <div class="col-4">
                                    <input type="radio" 
                                           class="btn-check" 
                                           name="format" 
                                           id="format_{{ $value }}" 
                                           value="{{ $value }}" 
                                           {{ old('format', 'png') == $value ? 'checked' : '' }}>
                                    <label class="btn btn-outline-primary w-100 py-3" for="format_{{ $value }}">
                                        <i class="icon-file" style="font-size: 1.5rem;"></i><br>
                                        <strong>{{ $label }}</strong><br>
                                        <small class="text-muted">.{{ $value }}</small>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            @error('format')
                                <div class="text-danger mt-2"><small>{{ $message }}</small></div>
                            @enderror
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg py-3">
                                <i class="icon-magic"></i> Generate QR Code
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
