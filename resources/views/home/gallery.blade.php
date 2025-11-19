@extends('home.layouts.app')

@section('content')
<main id="main">

  <!-- ======= Hero Section ======= -->
  <section class="hero-section inner-page position-relative overflow-hidden">
    <!-- Animated Background Elements -->
    <div class="position-absolute w-100 h-100" style="top: 0; left: 0; z-index: 1;">
      <div class="floating-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>
        <div class="shape shape-4"></div>
      </div>
    </div>

    <div class="wave">
      <svg width="1920px" height="265px" viewBox="0 0 1920 265" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
          <g id="Apple-TV" transform="translate(0.000000, -402.000000)" fill="#FFFFFF">
            <path d="M0,439.134243 C175.04074,464.89273 327.944386,477.771974 458.710937,477.771974 C654.860765,477.771974 870.645295,442.632362 1205.9828,410.192501 C1429.54114,388.565926 1667.54687,411.092417 1920,477.771974 L1920,667 L1017.15166,667 L0,667 L0,439.134243 Z" id="Path"></path>
          </g>
        </g>
      </svg>
    </div>

    <div class="container position-relative" style="z-index: 2;">
      <div class="row align-items-center min-vh-50">
        <div class="col-12">
          <div class="row justify-content-center">
            <div class="col-md-10 text-center hero-text">
              <h1 class="display-4 fw-bold mb-4 text-white" data-aos="fade-up" data-aos-delay="300">
                Galeri {{ $jurusan }}
              </h1>
              <p class="lead text-white" data-aos="fade-up" data-aos-delay="400">
                Dokumentasi Kegiatan Pembelajaran dan Fasilitas {{ $jurusan }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ======= Gallery Grid Section ======= -->
  <section class="section">
    <div class="container">
      <!-- Gallery Grid -->
      <div class="row g-3">
        @foreach($galleries as $g)
          <div class="col-lg-3 col-md-6">
            <div class="gallery-item">
              <img src="{{ $g->image ? asset('storage/' . $g->image) : asset('assets/img/default-news.jpg') }}" alt="{{ $g->title }}" class="img-fluid rounded-3">
              <div class="gallery-overlay">
                <div class="gallery-content">
                  <i class="bi bi-images"></i>
                  <h6 class="text-white mt-2">{{ $g->title }}</h6>
                  <p class="text-white-50 small">{{ Str::limit(strip_tags($g->description), 80) }}</p>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

    </div>
  </section>

  <!-- ======= Call to Action ======= -->
  <section class="section cta-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
          <h3>Tertarik Bergabung dengan {{ $jurusan }}?</h3>
          <p class="mb-4">Jadilah bagian dari generasi profesional muda yang siap menghadapi tantangan industri</p>
          <a href="{{ route('pendaftaran.index') }}" class="btn btn-light btn-lg">
            <i class="bi bi-pencil-square me-2"></i>Daftar Sekarang
          </a>
        </div>
      </div>
    </div>
  </section>

</main>
@endsection

@push('styles')
<style>
  /* Hero Section Animations */
  .floating-shapes {
    position: absolute;
    width: 100%;
    height: 100%;
    overflow: hidden;
  }

  .shape {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
  }

  .shape-1 {
    width: 80px;
    height: 80px;
    top: 20%;
    left: 10%;
    animation-delay: 0s;
  }

  .shape-2 {
    width: 120px;
    height: 120px;
    top: 60%;
    right: 15%;
    animation-delay: 2s;
  }

  .shape-3 {
    width: 60px;
    height: 60px;
    top: 40%;
    left: 70%;
    animation-delay: 4s;
  }

  .shape-4 {
    width: 100px;
    height: 100px;
    bottom: 20%;
    left: 20%;
    animation-delay: 1s;
  }

  @keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(10deg); }
  }

  .wave {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    overflow: hidden;
    line-height: 0;
  }

  .wave svg {
    position: relative;
    display: block;
    width: calc(100% + 1.3px);
    height: 265px;
  }

  .hero-text {
    padding: 40px 0;
  }

  /* Gallery Item Styles */
  .gallery-item {
    position: relative;
    overflow: hidden;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
  }

  .gallery-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  }

  .gallery-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0, 123, 255, 0.9) 0%, rgba(40, 167, 69, 0.9) 100%);
    display: flex;
    align-items: flex-end;
    opacity: 0;
    transition: opacity 0.3s ease;
    padding: 20px;
  }

  .gallery-item:hover .gallery-overlay {
    opacity: 1;
  }

  .gallery-content {
    color: white;
    text-align: center;
    width: 100%;
  }

  .gallery-content i {
    font-size: 2rem;
    margin-bottom: 10px;
  }

  .gallery-content h6 {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 5px;
  }

  .gallery-content p {
    font-size: 0.875rem;
    margin: 0;
    line-height: 1.4;
  }

  /* CTA Section */
  .cta-section {
    background: linear-gradient(to right, rgb(39, 70, 133) 0%, rgb(61, 179, 197) 100%);
    color: #fff;
    padding: 80px 0;
  }

  .cta-section h3 {
    color: #fff;
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 20px;
  }

  .cta-section p {
    color: rgba(255, 255, 255, 0.9);
    font-size: 1.1rem;
    line-height: 1.6;
  }

  .cta-section .btn-light {
    background: #ffffff;
    color: #2d71a1;
    border: none;
    padding: 15px 40px;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .cta-section .btn-light:hover {
    background: #f8f9fa;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  }

  /* Responsive */
  @media (max-width: 768px) {
    .hero-section.inner-page h1 {
      font-size: 2rem;
    }

    .gallery-item {
      margin-bottom: 20px;
    }

    .gallery-content i {
      font-size: 1.5rem;
    }

    .cta-section h3 {
      font-size: 1.8rem;
    }

    .cta-section .btn-light {
      padding: 12px 30px;
    }
  }
</style>
@endpush