@extends('home.layouts.app')

@section('title', 'Hasil QR Code')

@push('styles')
<style>
.qrcode-container {
    background: white;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}
.qrcode-inner {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    display: inline-block;
}
</style>
@endpush

@section('content')
        <!-- Start Section Banner Area -->
        <div class="section-banner bg-4">
            <div class="container">
                <div class="banner-spacing">
                    <div class="section-info">
                        <h2 data-aos="fade-up" data-aos-delay="100">QR Code Generator</h2>
                        <p data-aos="fade-up" data-aos-delay="200">Hasil QR Code Anda</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Section Banner Area -->
        
        <!-- Start QR Code Result Area -->
        <div class="contact-area ptb-100">
            <div class="container">
                <div class="row">
                    <!-- Left: QR Code Display -->
                    <div class="col-lg-5">
                        <div class="qrcode-container text-center">
                            <h3 class="mb-4">QR Code Anda</h3>
                            
                            @if($qrText)
                                <div class="alert alert-info mb-3">
                                    <p class="mb-0 fw-bold">{{ $qrText }}</p>
                                </div>
                            @endif
                            
                            @if($format === 'svg' && $rawSvg)
                                <div class="qrcode-inner mb-4">
                                    {!! $rawSvg !!}
                                </div>
                            @elseif($format === 'eps')
                                <div class="alert alert-warning mb-3">
                                    <i class='bx bx-info-circle'></i> File EPS tidak dapat ditampilkan di browser. Silakan unduh untuk melihat hasilnya.
                                </div>
                            @else
                                <div class="qrcode-inner mb-4">
                                    <img src="{{ $qrcode }}" 
                                         alt="QR Code" 
                                         class="img-fluid" 
                                         style="max-width: {{ $size }}px; height: auto;"
                                         onerror="this.onerror=null; this.src='data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22200%22%20height%3D%22200%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%3E%3Ctext%20x%3D%22100%22%20y%3D%22100%22%20font-family%3D%22Arial%22%20font-size%3D%2216%22%20text-anchor%3D%22middle%22%20alignment-baseline%3D%22middle%22%3EGagal%20memuat%20gambar%3C%2Ftext%3E%3C%2Fsvg%3E';">
                                </div>
                            @endif
                            
                            @if($format !== 'eps')
                            <div class="alert alert-secondary mb-3">
                                <small>
                                    <i class='bx bx-camera'></i> <strong>Cara Scan:</strong> Arahkan kamera smartphone ke QR Code
                                </small>
                            </div>
                            @endif
                            
                            <div class="mb-0">
                                <span class="badge bg-primary fs-6 px-3 py-2">Format: {{ strtoupper($format) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right: Details & Actions -->
                    <div class="col-lg-7">
                        <div class="contact-content">
                            <div class="header-content">
                                <h2>Detail QR Code</h2>
                                <p>QR Code Anda telah berhasil dibuat dan siap digunakan.</p>
                            </div>
                            
                            @if(isset($shortUrl) && $shortUrl !== $url)
                            <div class="mb-4">
                                <label class="form-label fw-bold mb-2">
                                    <i class='bx bx-link'></i> URL Pendek
                                </label>
                                <div class="alert alert-primary">
                                    <a href="{{ $shortUrl }}" target="_blank" class="text-primary text-break fw-bold">{{ $shortUrl }}</a>
                                </div>
                            </div>
                            @endif
                            
                            <div class="mb-4">
                                <label class="form-label fw-bold mb-2">
                                    <i class='bx bx-globe'></i> URL Tujuan
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class='bx bx-link'></i>
                                    </span>
                                    <input type="text" class="form-control" value="{{ $url }}" id="url-text" readonly>
                                    <button class="btn btn-outline-primary" type="button" onclick="copyToClipboard('url-text')">
                                        <i class='bx bx-copy'></i> Salin
                                    </button>
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    <i class='bx bx-info-circle'></i> QR Code mengarah ke URL ini
                                </small>
                            </div>
                            
                            <hr class="my-4">
                            
                            <!-- Action Buttons -->
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <button type="button" 
                                            class="default-btn w-100"
                                            onclick="downloadQRCode()">
                                        <i class='bx bx-download'></i> Unduh QR Code
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('qrcode.index') }}" class="default-btn w-100">
                                        <i class='bx bx-refresh'></i> Buat Baru
                                    </a>
                                </div>
                            </div>
                            
                            <div class="alert alert-warning">
                                <small>
                                    <i class='bx bx-lightbulb'></i> <strong>Tips:</strong> Simpan QR Code untuk poster, brosur, atau media promosi
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End QR Code Result Area -->

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
    const qrText = '{{ $qrText ?? '' }}';
    const filename = 'qrcode-{{ time() }}.' + extension;
    
    // Create a canvas to combine text and QR code
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    
    // Load the QR code image
    const img = new Image();
    img.onload = function() {
        const qrSize = img.width;
        const padding = 40;
        const textHeight = qrText ? 60 : 0;
        
        // Set canvas size (QR code + padding + text area)
        canvas.width = qrSize + (padding * 2);
        canvas.height = qrSize + (padding * 2) + textHeight;
        
        // Fill white background
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        // Draw text if exists
        if (qrText) {
            ctx.fillStyle = '#333333';
            ctx.font = 'bold 24px Arial';
            ctx.textAlign = 'center';
            ctx.fillText(qrText, canvas.width / 2, padding + 30);
        }
        
        // Draw QR code
        const qrY = padding + textHeight;
        ctx.drawImage(img, padding, qrY, qrSize, qrSize);
        
        // Convert canvas to image and download
        const dataUrl = canvas.toDataURL('image/' + extension);
        const link = document.createElement('a');
        link.href = dataUrl;
        link.download = filename;
        
        // Append to body, click, and remove
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };
    
    img.src = qrcodeData;
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
    button.className = button.className.replace('btn-outline-primary', 'btn-success');
    
    // Kembalikan ke keadaan semula setelah 2 detik
    setTimeout(() => {
        button.innerHTML = originalHTML;
        button.className = originalClass;
    }, 2000);
}
</script>
@endpush
@endsection
