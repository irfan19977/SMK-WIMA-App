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
                Teknik Bisnis Sepeda Motor
              </h1>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ======= Program Description ======= -->
  <section class="section">
    <div class="container">
      <div class="row">
        <div class="col-lg-8 mx-auto">
          <h2 class="section-heading text-center mb-5">Tentang Program Studi</h2>
          <p class="lead text-center mb-4">
            Jurusan Teknik Bisnis Sepeda Motor (TBSM) merupakan program keahlian yang memadukan kompetensi teknik sepeda motor dengan prinsip-prinsip bisnis dan entrepreneurship modern.
          </p>

          <div class="row mt-4">
            <div class="col-md-6">
              <h4><i class="bi bi-bullseye text-primary me-2"></i>Visi Program</h4>
              <p>Menjadi program studi terdepan dalam menghasilkan technopreneur muda yang inovatif di bidang industri sepeda motor dengan kompetensi teknik dan bisnis yang berdaya saing global.</p>
            </div>
            <div class="col-md-6">
              <h4><i class="bi bi-check-circle text-primary me-2"></i>Kompetensi Utama</h4>
              <ul>
                <li>Motorcycle Technician</li>
                <li>Business Development</li>
                <li>Workshop Management</li>
                <li>Parts & Service Specialist</li>
                <li>Motorcycle Entrepreneur</li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ======= Facilities ======= -->
  <section class="section bg-light">
    <div class="container">
      <div class="row justify-content-center text-center mb-5">
        <div class="col-md-6">
          <h2 class="section-heading">Fasilitas Modern</h2>
          <p class="text-muted">Workshop dan showroom sepeda motor dengan teknologi terkini</p>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="feature-card text-center">
            <div class="feature-icon">
              <i class="bi bi-bicycle"></i>
            </div>
            <h5>Workshop Motor</h5>
            <p class="text-muted">Workshop lengkap dengan berbagai jenis sepeda motor untuk praktik perawatan dan perbaikan</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-card text-center">
            <div class="feature-icon">
              <i class="bi bi-shop"></i>
            </div>
            <h5>Showroom Mini</h5>
            <p class="text-muted">Showroom display sepeda motor dengan sistem penjualan dan pemasaran modern</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-card text-center">
            <div class="feature-icon">
              <i class="bi bi-tools"></i>
            </div>
            <h5>Parts Center</h5>
            <p class="text-muted">Pusat suku cadang dengan sistem manajemen inventory dan katalog digital</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ======= Curriculum ======= -->
  <section class="section">
    <div class="container">
      <div class="row justify-content-center text-center mb-5">
        <div class="col-md-6">
          <h2 class="section-heading">Kurikulum & Mata Pelajaran</h2>
          <p class="text-muted">Kompetensi yang akan dikuasai selama 3 tahun pembelajaran</p>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <h4>Kelas X (Dasar)</h4>
          <ul class="list-styled">
            <li>Dasar-Dasar Otomotif</li>
            <li>Gambar Teknik Motor</li>
            <li>Prinsip Bisnis Dasar</li>
            <li>Matematika Bisnis</li>
            <li>Bahasa Inggris Bisnis</li>
          </ul>

          <h4 class="mt-4">Kelas XI (Pengembangan)</h4>
          <ul class="list-styled">
            <li>Teknik Motor Bensin</li>
            <li>Manajemen Bengkel</li>
            <li>Pemasaran & Penjualan</li>
            <li>Keuangan Bisnis</li>
            <li>Digital Marketing</li>
          </ul>
        </div>

        <div class="col-md-6">
          <h4>Kelas XII (Produksi)</h4>
          <ul class="list-styled">
            <li>Project Based Learning</li>
            <li>Praktik Kerja Industri (PKL)</li>
            <li>Business Plan Development</li>
            <li>Customer Service Excellence</li>
            <li>Industry Standard Certification</li>
          </ul>

          <!--<div class="certification-box mt-4 p-3 bg-light rounded">-->
          <!--  <h6><i class="bi bi-award text-warning me-2"></i>Sertifikasi yang Didapat</h6>-->
          <!--  <div class="row mt-2">-->
          <!--    <div class="col-6">-->
          <!--      <small class="text-muted">• Motorcycle Technician</small><br>-->
          <!--      <small class="text-muted">• Business Management</small><br>-->
          <!--      <small class="text-muted">• Sales & Marketing</small>-->
          <!--    </div>-->
          <!--    <div class="col-6">-->
          <!--      <small class="text-muted">• BNSP Certified</small><br>-->
          <!--      <small class="text-muted">• Customer Service</small><br>-->
          <!--      <small class="text-muted">• Entrepreneurship</small>-->
          <!--    </div>-->
          <!--  </div>-->
          <!--</div>-->
        </div>
      </div>
    </div>
  </section>

  <!-- ======= Career Prospects ======= -->
  <section class="section">
    <div class="container">
      <div class="row justify-content-center text-center mb-5">
        <div class="col-md-6">
          <h2 class="section-heading">Prospek Karir</h2>
          <p class="text-muted">Berbagai peluang karir menanti lulusan TBSM di industri sepeda motor</p>
        </div>
      </div>

      <div class="row">
        <div class="col-md-3 mb-4">
          <div class="text-center">
            <i class="bi bi-tools" style="font-size: 2.5rem; margin-bottom: 15px;"></i>
            <h6>Motorcycle Technician</h6>
            <p class="text-muted">Spesialis perawatan dan perbaikan sepeda motor di bengkel resmi</p>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="text-center">
            <i class="bi bi-shop" style="font-size: 2.5rem; margin-bottom: 15px;"></i>
            <h6>Workshop Owner</h6>
            <p class="text-muted">Pengusaha bengkel sepeda motor dengan manajemen modern</p>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="text-center">
            <i class="bi bi-graph-up" style="font-size: 2.5rem; margin-bottom: 15px;"></i>
            <h6>Sales Specialist</h6>
            <p class="text-muted">Spesialis penjualan sepeda motor dengan teknik marketing</p>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="text-center">
            <i class="bi bi-people" style="font-size: 2.5rem; margin-bottom: 15px;"></i>
            <h6>Service Advisor</h6>
            <p class="text-muted">Konsultan service yang memberikan pelayanan terbaik</p>
          </div>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-6 mb-3">
          <div class="text-center">
            <i class="bi bi-building" style="font-size: 2.5rem; margin-bottom: 15px;"></i>
            <h6>Parts Manager</h6>
            <p class="text-muted">Manager suku cadang dengan sistem inventory modern</p>
          </div>
        </div>
        <div class="col-md-6 mb-3">
          <div class="text-center">
            <i class="bi bi-lightbulb" style="font-size: 2.5rem; margin-bottom: 15px;"></i>
            <h6>Motorcycle Entrepreneur</h6>
            <p class="text-muted">Wirausahawan di bidang industri sepeda motor</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ======= Gallery ======= -->
  <section class="section">
    <div class="container">
      <div class="row justify-content-center text-center mb-5">
        <div class="col-md-6">
          <h2 class="section-heading">Galeri TBSM</h2>
          <p class="text-muted">Kegiatan pembelajaran dan fasilitas jurusan Teknik Bisnis Sepeda Motor</p>
        </div>
      </div>

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

      <div class="text-center mt-5">
        <a href="{{ route('gallery.tbsm') }}" class="btn btn-outline-primary btn-lg">
          <i class="bi bi-images me-2"></i>
          Lihat Galeri Lengkap
        </a>
      </div>
    </div>
  </section>

  <!-- ======= Call to Action ======= -->
  <section class="section cta-section">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 text-center">
          <h3>Bergabunglah dengan Jurusan Teknik Bisnis Sepeda Motor</h3>
          <p class="mb-4">Jadilah technopreneur muda yang siap membangun bisnis di industri sepeda motor dengan kompetensi teknik dan bisnis yang unggul</p>
          <a href="{{ route('pendaftaran.index') }}" class="btn btn-light btn-lg" style="padding: 15px 30px; font-weight: 600; font-size: 16px;">
            Daftar Sekarang
          </a>
        </div>
      </div>
    </div>
  </section>

</main>
@endsection

@push('styles')
<style>
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

  .section-heading {
    color: #2d3436;
    font-weight: 700;
  }

  .feature-card {
    background: white;
    border-radius: 10px;
    padding: 30px 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
  }

  .feature-card:hover {
    transform: translateY(-5px);
  }

  .feature-icon {
    width: 60px;
    height: 60px;
    background: #007bff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
  }

  .feature-icon i {
    font-size: 1.5rem;
    color: white;
  }

  .list-styled {
    list-style: none;
    padding: 0;
  }

  .list-styled li {
    padding: 8px 0;
    padding-left: 25px;
    position: relative;
  }

  .list-styled li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: #28a745;
    font-weight: bold;
  }

  .certification-box {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
  }

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
    font-weight: 600;
    margin-bottom: 5px;
  }

  /* CTA Section Styling */
  .cta-section {
    background: linear-gradient(to right, rgb(39, 70, 133) 0%, rgb(61, 179, 197) 100%);
    color: #fff;
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
    max-width: 600px;
    margin: 0 auto 30px auto;
  }

  .cta-section .btn-light {
    background: #ffffff !important;
    color: #2d71a1 !important;
    border: none !important;
    transition: all 0.3s ease;
    margin: 0 auto;
    display: inline-block;
  }

  .cta-section .btn-light:hover {
    background: #f8f9fa !important;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
  }

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
      font-size: 2rem;
    }

    .cta-section p {
      font-size: 1rem;
      margin: 0 auto 25px auto;
    }

    .cta-section .btn-light {
      padding: 12px 25px;
      font-size: 14px;
    }
  }
</style>
@endpush
