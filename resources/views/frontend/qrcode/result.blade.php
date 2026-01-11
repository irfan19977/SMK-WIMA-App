@extends('home.layouts.app')

@section('title', 'Hasil QR Code')

@push('styles')
<style>
.hover-card {
    transition: all 0.3s ease;
    cursor: pointer;
}
.hover-card:hover {
    background-color: #e7f1ff !important;
    transform: translateY(-3px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>
@endpush

@section('content')
<div class="container py-5">
    <!-- Success Header -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <div class="alert alert-success shadow-sm" role="alert">
                <h4 class="alert-heading mb-2">
                    <i class="icon-check"></i> QR Code Berhasil Dibuat!
                </h4>
                <p class="mb-0">QR Code Anda siap digunakan dan diunduh</p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Left: QR Code Display -->
                        <div class="col-md-5">
                            <div class="card bg-light border-0">
                                <div class="card-body text-center p-4">
                                    <h5 class="mb-4">
                                        <i class="icon-qrcode"></i> QR Code Anda
                                    </h5>
                                    
                                    @if($format === 'svg' && $rawSvg)
                                        <div class="d-flex justify-content-center mb-3">
                                            <div class="p-4 bg-white rounded shadow-sm" style="display: inline-block;">
                                                {!! $rawSvg !!}
                                            </div>
                                        </div>
                                    @elseif($format === 'eps')
                                        <div class="alert alert-warning">
                                            <i class="icon-info"></i>
                                            <strong>Informasi:</strong> File EPS tidak dapat ditampilkan di browser. Silakan unduh untuk melihat hasilnya.
                                        </div>
                                    @else
                                        <div class="d-flex justify-content-center mb-3">
                                            <div class="p-4 bg-white rounded shadow-sm" style="display: inline-block;">
                                                <img src="{{ $qrcode }}" 
                                                     alt="QR Code" 
                                                     class="img-fluid" 
                                                     style="max-width: {{ $size }}px; height: auto;"
                                                     onerror="this.onerror=null; this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22200%22%20height%3D%22200%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Ctext%20x%3D%22100%22%20y%3D%22100%22%20font-family%3D%22Arial%22%20font-size%3D%2216%22%20text-anchor%3D%22middle%22%20alignment-baseline%3D%22middle%22%3EGagal%20memuat%20gambar%3C%2Ftext%3E%3C%2Fsvg%3E';">
                                            </div>
                                        </div>
                                    @endif
                                    
                                    @if($format !== 'eps')
                                    <div class="alert alert-info mb-3">
                                        <small>
                                            <i class="icon-info"></i> <strong>Cara Scan:</strong> Arahkan kamera smartphone ke QR Code
                                        </small>
                                    </div>
                                    @endif
                                    
                                    <div class="mb-0">
                                        <span class="badge bg-primary">Format: {{ strtoupper($format) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right: Details & Actions -->
                        <div class="col-md-7">
                            <h5 class="mb-3">
                                <i class="icon-info"></i> Detail QR Code
                            </h5>
                            
                            @if(isset($shortUrl) && $shortUrl !== $url)
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <i class="icon-link"></i> URL Pendek
                                </label>
                                <div class="alert alert-primary mb-0">
                                    <a href="{{ $shortUrl }}" target="_blank" class="text-break">{{ $shortUrl }}</a>
                                </div>
                            </div>
                            @endif
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold">
                                    <i class="icon-globe"></i> URL Tujuan
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="{{ $url }}" id="url-text" readonly>
                                    <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('url-text')">
                                        <i class="icon-docs"></i> Salin
                                    </button>
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <i class="icon-info"></i> QR Code mengarah ke URL ini
                                </small>
                            </div>
                            
                            <hr>
                            
                            <!-- Action Buttons -->
                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <button type="button" 
                                            class="btn btn-success w-100"
                                            onclick="downloadQRCode()">
                                        Unduh QR Code
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('qrcode.index') }}" class="btn btn-outline-info w-100">
                                        Buat QR Code Baru
                                    </a>
                                </div>
                            </div>
                            
                            <div class="alert alert-warning mb-3">
                                <small>
                                    <strong><i class="icon-lightbulb"></i> Tips:</strong> Simpan QR Code untuk poster, brosur, atau media promosi
                                </small>
                            </div>
                            
                            <!-- Quick Links -->
                            <div class="card border-primary">
                                <div class="card-body p-3">
                                    <h6 class="card-title mb-3">
                                        <i class="icon-link"></i> Link Cepat
                                    </h6>
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <a href="{{ route('pendaftaran.index') }}" class="text-decoration-none">
                                                <div class="text-center p-2 bg-light rounded hover-card">
                                                    <i class="icon-note" style="font-size: 1.5rem; color: #0d6efd;"></i>
                                                    <div class="mt-2">
                                                        <small class="d-block fw-bold text-dark">Pendaftaran</small>
                                                        <small class="text-muted">PPDB Online</small>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <a href="{{ route('profile-sekolah.index') }}" class="text-decoration-none">
                                                <div class="text-center p-2 bg-light rounded hover-card">
                                                    <i class="icon-home" style="font-size: 1.5rem; color: #0d6efd;"></i>
                                                    <div class="mt-2">
                                                        <small class="d-block fw-bold text-dark">Profile</small>
                                                        <small class="text-muted">Sekolah</small>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <a href="{{ route('berita.index') }}" class="text-decoration-none">
                                                <div class="text-center p-2 bg-light rounded hover-card">
                                                    <i class="icon-docs" style="font-size: 1.5rem; color: #0d6efd;"></i>
                                                    <div class="mt-2">
                                                        <small class="d-block fw-bold text-dark">Berita</small>
                                                        <small class="text-muted">Informasi</small>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <a href="{{ route('contact.index') }}" class="text-decoration-none">
                                                <div class="text-center p-2 bg-light rounded hover-card">
                                                    <i class="icon-phone" style="font-size: 1.5rem; color: #0d6efd;"></i>
                                                    <div class="mt-2">
                                                        <small class="d-block fw-bold text-dark">Kontak</small>
                                                        <small class="text-muted">Hubungi</small>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Info -->
            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="mb-3">
                        <i class="icon-book"></i> Cara Menggunakan QR Code
                    </h6>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <div class="d-flex align-items-start">
                                <span class="badge bg-primary me-2">1</span>
                                <small>Unduh QR Code dalam format yang Anda pilih</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="d-flex align-items-start">
                                <span class="badge bg-primary me-2">2</span>
                                <small>Cetak atau tampilkan di media digital</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="d-flex align-items-start">
                                <span class="badge bg-primary me-2">3</span>
                                <small>Scan dengan kamera smartphone untuk akses cepat</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Mencegah Firebase Dynamic Links menangkap link di halaman ini
if (typeof firebase !== 'undefined' && firebase.dynamicLinks) {
    firebase.dynamicLinks().onLink(function(link) {
        // Biarkan link dibuka secara normal
        return false;
    });
}

// Fungsi untuk download QR Code
function downloadQRCode() {
    const qrcodeData = '{{ $qrcode }}';
    const extension = '{{ $extension }}';
    const filename = 'qrcode-{{ time() }}.' + extension;
    
    // Create a temporary link element
    const link = document.createElement('a');
    link.href = qrcodeData;
    link.download = filename;
    
    // Append to body, click, and remove
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Fungsi untuk menyalin teks ke clipboard
function copyToClipboard(elementId = 'url-text') {
    const copyText = document.getElementById(elementId);
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    
    // Modern clipboard API
    if (navigator.clipboard) {
        navigator.clipboard.writeText(copyText.value).then(function() {
            showCopySuccess();
        }).catch(function() {
            // Fallback to old method
            document.execCommand("copy");
            showCopySuccess();
        });
    } else {
        document.execCommand("copy");
        showCopySuccess();
    }
}

function showCopySuccess() {
    const button = event.target.closest('button');
    const originalHTML = button.innerHTML;
    const originalClass = button.className;
    
    // Update tampilan tombol
    button.innerHTML = '<i class="icon-check"></i> Tersalin!';
    button.className = button.className.replace('btn-outline-secondary', 'btn-success');
    
    // Kembalikan ke keadaan semula setelah 2 detik
    setTimeout(() => {
        button.innerHTML = originalHTML;
        button.className = originalClass;
    }, 2000);
}
</script>
@endpush
@endsection
