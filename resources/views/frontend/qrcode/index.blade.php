@extends('home.layouts.app')

@section('title', 'QR Code Generator')

@push('styles')
<style>
.format-card {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}
.format-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}
.format-card.active {
    border-color: #667eea;
    background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%);
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
                        <p data-aos="fade-up" data-aos-delay="200">Buat QR Code profesional untuk URL Anda dengan mudah dan cepat</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Section Banner Area -->
        
        <!-- Start QR Code Generator Area -->
        <div class="contact-area ptb-100">
            <div class="container">
                <div class="row">
                    <!-- Info Card -->
                    <div class="col-lg-4">
                        <div class="contact-info">
                            <div class="info-details">
                                <h3>Kenapa QR Code?</h3>
                                <ul>
                                    <li><i class='bx bx-bolt'></i> <strong>Cepat & Mudah:</strong> Generate QR Code dalam hitungan detik</li>
                                    <li><i class='bx bx-layer'></i> <strong>Multi Format:</strong> PNG, SVG, dan EPS tersedia</li>
                                    <li><i class='bx bx-shield'></i> <strong>High Quality:</strong> Error correction level tinggi</li>
                                </ul>
                            </div>
                            <div class="alert alert-info">
                                <small>
                                    <i class='bx bx-lightbulb'></i> <strong>Tips:</strong> PNG untuk digital, SVG untuk desain grafis
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Card -->
                    <div class="col-lg-8">
                        <div class="contact-content">
                            <div class="header-content">
                                <h2>Buat QR Code Anda</h2>
                                <p>Isi formulir di bawah ini untuk membuat QR Code</p>
                            </div>
                            
                            <form action="{{ route('qrcode.generate') }}" method="POST">
                                @csrf
                                
                                <!-- URL Input -->
                                <div class="mb-4">
                                    <label for="url" class="form-label fw-bold">
                                        <i class='bx bx-link'></i> URL Tujuan
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class='bx bx-globe'></i>
                                        </span>
                                        <input type="url" 
                                               class="form-control @error('url') is-invalid @enderror" 
                                               id="url" 
                                               name="url" 
                                               placeholder="https://forms.gle/example" 
                                               value="{{ old('url') }}" 
                                               required>
                                    </div>
                                    <small class="text-muted mt-2 d-block">
                                        <i class='bx bx-info-circle'></i> Masukkan URL lengkap dengan https://
                                    </small>
                                    @error('url')
                                        <div class="text-danger mt-2"><small>{{ $message }}</small></div>
                                    @enderror
                                </div>

                                <!-- Text Above QR Code -->
                                <div class="mb-4">
                                    <label for="qr_text" class="form-label fw-bold">
                                        <i class='bx bx-file'></i> Teks di Atas QR Code
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class='bx bx-pencil'></i>
                                        </span>
                                        <input type="text" 
                                               class="form-control @error('qr_text') is-invalid @enderror" 
                                               id="qr_text" 
                                               name="qr_text" 
                                               placeholder="Contoh: Scan untuk mengisi form" 
                                               value="{{ old('qr_text') }}">
                                    </div>
                                    <small class="text-muted mt-2 d-block">
                                        <i class='bx bx-info-circle'></i> Teks ini akan muncul di atas QR Code (opsional)
                                    </small>
                                    @error('qr_text')
                                        <div class="text-danger mt-2"><small>{{ $message }}</small></div>
                                    @enderror
                                </div>
                                
                                <!-- Format Selection -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold mb-3">
                                        <i class='bx bx-file-image'></i> Pilih Format
                                        <span class="text-danger">*</span>
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
                                            <label class="btn format-card w-100 py-4 rounded-3 @if(old('format', 'png') == $value) active @endif" for="format_{{ $value }}">
                                                <div class="text-center">
                                                    <i class='bx bx-file mb-2' style="font-size: 2rem; color: #667eea;"></i>
                                                    <div class="fw-bold">{{ $label }}</div>
                                                    <small class="text-muted">.{{ $value }}</small>
                                                </div>
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
                                    <button type="submit" class="default-btn">
                                        <i class='bx bx-magic'></i> Generate QR Code
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End QR Code Generator Area -->

@push('scripts')
<script>
// Add active class to selected format
document.querySelectorAll('input[name="format"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('.format-card').forEach(card => {
            card.classList.remove('active');
        });
        this.nextElementSibling.classList.add('active');
    });
});
</script>
@endpush

@endsection
