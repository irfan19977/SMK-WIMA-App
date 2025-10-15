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
                Teknik Kendaraan Ringan
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
            Jurusan Teknik Kendaraan Ringan (TKR) merupakan program keahlian unggulan yang fokus pada pengembangan kompetensi di bidang otomotif, perawatan, dan perbaikan kendaraan bermotor.
          </p>

          <div class="row mt-4">
            <div class="col-md-6">
              <h4><i class="bi bi-bullseye text-primary me-2"></i>Visi Program</h4>
              <p>Menjadi program studi terdepan dalam menghasilkan tenaga ahli otomotif yang kompeten, inovatif, dan siap bersaing di industri otomotif modern dengan standar internasional.</p>
            </div>
            <div class="col-md-6">
              <h4><i class="bi bi-check-circle text-primary me-2"></i>Kompetensi Utama</h4>
              <ul>
                <li>Automotive Technician</li>
                <li>Engine Specialist</li>
                <li>Vehicle Maintenance Expert</li>
                <li>Automotive Electric Specialist</li>
                <li>Quality Control Inspector</li>
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
          <p class="text-muted">Workshop dan laboratorium otomotif dengan peralatan terkini</p>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 mb-4">
          <div class="feature-card text-center">
            <div class="feature-icon">
              <i class="bi bi-tools"></i>
            </div>
            <h5>Workshop Otomotif</h5>
            <p class="text-muted">Workshop lengkap dengan peralatan modern untuk praktik perbaikan dan perawatan kendaraan</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-card text-center">
            <div class="feature-icon">
              <i class="bi bi-car-front"></i>
            </div>
            <h5>Lab Mesin</h5>
            <p class="text-muted">Laboratorium mesin dengan engine test bed, transmission test, dan peralatan diagnostik modern</p>
          </div>
        </div>
        <div class="col-md-4 mb-4">
          <div class="feature-card text-center">
            <div class="feature-icon">
              <i class="bi bi-battery-charging"></i>
            </div>
            <h5>Electrical Lab</h5>
            <p class="text-muted">Laboratorium sistem elektrikal dengan scanner diagnostic, battery test, dan wiring simulator</p>
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
            <li>Gambar Teknik Otomotif</li>
            <li>Sistem Mesin Dasar</li>
            <li>Matematika Teknik</li>
            <li>Bahasa Inggris Teknik</li>
          </ul>

          <h4 class="mt-4">Kelas XI (Pengembangan)</h4>
          <ul class="list-styled">
            <li>Mesin Bensin & Diesel</li>
            <li>Sistem Transmisi</li>
            <li>Sistem Kelistrikan</li>
            <li>Chasis & Suspension</li>
            <li>Engine Management</li>
          </ul>
        </div>

        <div class="col-md-6">
          <h4>Kelas XII (Produksi)</h4>
          <ul class="list-styled">
            <li>Project Based Learning</li>
            <li>Praktik Kerja Industri (PKL)</li>
            <li>Automotive Diagnostic</li>
            <li>Vehicle Maintenance</li>
            <li>Industry Standard Certification</li>
          </ul>

          <div class="certification-box mt-4 p-3 bg-light rounded">
            <h6><i class="bi bi-award text-warning me-2"></i>Sertifikasi yang Didapat</h6>
            <div class="row mt-2">
              <div class="col-6">
                <small class="text-muted">• ASE Certification</small><br>
                <small class="text-muted">• Bosch Certified</small><br>
                <small class="text-muted">• Toyota Technical Education</small>
              </div>
              <div class="col-6">
                <small class="text-muted">• BNSP Certified</small><br>
                <small class="text-muted">• Motorcycle Technician</small><br>
                <small class="text-muted">• Electric Vehicle Ready</small>
              </div>
            </div>
          </div>
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
          <p class="text-muted">Berbagai peluang karir menanti lulusan TKR di industri otomotif</p>
        </div>
      </div>

      <div class="row">
        <div class="col-md-3 mb-4">
          <div class="text-center">
            <i class="bi bi-wrench-adjustable" style="font-size: 2.5rem; margin-bottom: 15px;"></i>
            <h6>Automotive Technician</h6>
            <p class="text-muted">Spesialis perbaikan dan perawatan kendaraan di bengkel resmi</p>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="text-center">
            <i class="bi bi-gear" style="font-size: 2.5rem; margin-bottom: 15px;"></i>
            <h6>Engine Specialist</h6>
            <p class="text-muted">Ahli mesin dengan keahlian diagnostik dan over-haul</p>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="text-center">
            <i class="bi bi-lightning-charge" style="font-size: 2.5rem; margin-bottom: 15px;"></i>
            <h6>Auto Electric Specialist</h6>
            <p class="text-muted">Spesialis sistem elektrikal dan elektronik kendaraan</p>
          </div>
        </div>
        <div class="col-md-3 mb-4">
          <div class="text-center">
            <i class="bi bi-clipboard-check" style="font-size: 2.5rem; margin-bottom: 15px;"></i>
            <h6>Quality Inspector</h6>
            <p class="text-muted">Quality control dan inspeksi kendaraan di industri manufaktur</p>
          </div>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-md-6 mb-3">
          <div class="text-center">
            <i class="bi bi-ev-front" style="font-size: 2.5rem; margin-bottom: 15px;"></i>
            <h6>EV Technician</h6>
            <p class="text-muted">Teknisi kendaraan listrik dan hybrid systems</p>
          </div>
        </div>
        <div class="col-md-6 mb-3">
          <div class="text-center">
            <i class="bi bi-shop" style="font-size: 2.5rem; margin-bottom: 15px;"></i>
            <h6>Workshop Owner</h6>
            <p class="text-muted">Wirausahawan di bidang jasa otomotif dan bengkel</p>
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
          <h2 class="section-heading">Galeri TKR</h2>
          <p class="text-muted">Kegiatan pembelajaran dan fasilitas jurusan Teknik Kendaraan Ringan</p>
        </div>
      </div>

      <div class="row g-3">
        <div class="col-lg-3 col-md-6">
          <div class="gallery-item">
            <img src="https://images.unsplash.com/photo-1486754735734-325b5831c3ad?w=400&h=300&fit=crop" alt="Workshop Otomotif" class="img-fluid rounded-3">
            <div class="gallery-overlay">
              <div class="gallery-content">
                <i class="bi bi-tools"></i>
                <h6 class="text-white mt-2">Workshop</h6>
                <p class="text-white-50 small">Praktik perbaikan kendaraan</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="gallery-item">
            <img src="https://images.unsplash.com/photo-1502877338535-766e1452684a?w=400&h=300&fit=crop" alt="Engine Laboratory" class="img-fluid rounded-3">
            <div class="gallery-overlay">
              <div class="gallery-content">
                <i class="bi bi-gear"></i>
                <h6 class="text-white mt-2">Engine Lab</h6>
                <p class="text-white-50 small">Laboratorium mesin dan transmis</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="gallery-item">
            <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=300&fit=crop" alt="Electrical System" class="img-fluid rounded-3">
            <div class="gallery-overlay">
              <div class="gallery-content">
                <i class="bi bi-battery-charging"></i>
                <h6 class="text-white mt-2">Electrical</h6>
                <p class="text-white-50 small">Sistem elektrikal dan elektronik</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="gallery-item">
            <img src="https://images.unsplash.com/photo-1550355191-aa8a80b41353?w=400&h=300&fit=crop" alt="Vehicle Diagnostic" class="img-fluid rounded-3">
            <div class="gallery-overlay">
              <div class="gallery-content">
                <i class="bi bi-search"></i>
                <h6 class="text-white mt-2">Diagnostic</h6>
                <p class="text-white-50 small">Diagnostic tools dan scanner</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="gallery-item">
            <img src="https://images.unsplash.com/photo-1544829099-b9a0c5303bea?w=400&h=300&fit=crop" alt="Car Maintenance" class="img-fluid rounded-3">
            <div class="gallery-overlay">
              <div class="gallery-content">
                <i class="bi bi-car-front"></i>
                <h6 class="text-white mt-2">Maintenance</h6>
                <p class="text-white-50 small">Perawatan berkala kendaraan</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="gallery-item">
            <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=300&fit=crop" alt="Body Repair" class="img-fluid rounded-3">
            <div class="gallery-overlay">
              <div class="gallery-content">
                <i class="bi bi-hammer"></i>
                <h6 class="text-white mt-2">Body Repair</h6>
                <p class="text-white-50 small">Perbaikan body dan painting</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="gallery-item">
            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&h=300&fit=crop" alt="Team Project" class="img-fluid rounded-3">
            <div class="gallery-overlay">
              <div class="gallery-content">
                <i class="bi bi-people"></i>
                <h6 class="text-white mt-2">Team Project</h6>
                <p class="text-white-50 small">Kerja sama tim dalam project</p>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-3 col-md-6">
          <div class="gallery-item">
            <img src="https://images.unsplash.com/photo-1553877522-43269d4ea984?w=400&h=300&fit=crop" alt="Certification" class="img-fluid rounded-3">
            <div class="gallery-overlay">
              <div class="gallery-content">
                <i class="bi bi-award"></i>
                <h6 class="text-white mt-2">Sertifikasi</h6>
                <p class="text-white-50 small">Program sertifikasi industri</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="text-center mt-5">
        <a href="#" class="btn btn-outline-primary btn-lg">
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
          <h3>Bergabunglah dengan Jurusan Teknik Kendaraan Ringan</h3>
          <p class="mb-4">Jadilah bagian dari generasi teknisi otomotif handal yang siap menghadapi perkembangan industri otomotif modern dengan standar internasional</p>
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
