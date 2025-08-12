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
                            Profile Sekolah
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>

    <!-- ======= Sejarah Sekolah ======= -->
    <section class="section pb-0 position-relative">
      <div class="section-bg-pattern"></div>
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 pe-lg-5" data-aos="fade-right">
            <div class="content-card">
              <div class="section-badge mb-3">
                <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                  <i class="bi bi-clock-history me-2"></i>Sejarah Kami
                </span>
              </div>
              <h2 class="display-5 fw-bold mb-4">
                Perjalanan <span class="text-primary">35 Tahun</span><br>
                Mencerdaskan Bangsa
              </h2>
              <div class="timeline-content">
                <div class="timeline-item mb-4">
                  <div class="timeline-marker bg-primary"></div>
                  <div class="timeline-content-inner">
                    <h5 class="fw-bold text-primary">1985 - Pendirian</h5>
                    <p class="text-muted">Dimulai dengan 5 ruang kelas sederhana dan visi besar untuk mencerdaskan generasi bangsa.</p>
                  </div>
                </div>
                <div class="timeline-item mb-4">
                  <div class="timeline-marker bg-success"></div>
                  <div class="timeline-content-inner">
                    <h5 class="fw-bold text-success">1995 - Ekspansi</h5>
                    <p class="text-muted">Pengembangan fasilitas laboratorium dan perpustakaan modern pertama di daerah.</p>
                  </div>
                </div>
                <div class="timeline-item mb-4">
                  <div class="timeline-marker bg-warning"></div>
                  <div class="timeline-content-inner">
                    <h5 class="fw-bold text-warning">2010 - Digitalisasi</h5>
                    <p class="text-muted">Implementasi teknologi digital dalam pembelajaran dan sistem manajemen sekolah.</p>
                  </div>
                </div>
                <div class="timeline-item">
                  <div class="timeline-marker bg-info"></div>
                  <div class="timeline-content-inner">
                    <h5 class="fw-bold text-info">2020 - Smart School</h5>
                    <p class="text-muted">Transformasi menjadi sekolah cerdas dengan program STEM dan pembelajaran hybrid.</p>
                  </div>
                </div>
              </div>
              <div class="mt-4">
                <a href="#achievements" class="btn btn-primary btn-lg rounded-pill px-4">
                  <i class="bi bi-trophy me-2"></i>Lihat Prestasi Kami
                </a>
              </div>
            </div>
          </div>
          <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
            <div class="image-stack">
              <div class="image-card image-card-1">
                <img src="data:image/jpeg;base64,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" alt="Sekolah Dulu" class="img-fluid rounded-4 shadow">
                <div class="image-overlay">
                  <span class="badge bg-white text-dark">1985</span>
                </div>
              </div>
              <div class="image-card image-card-2">
                <img src="data:image/jpeg;base64,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" alt="Sekolah Sekarang" class="img-fluid rounded-4 shadow">
                <div class="image-overlay">
                  <span class="badge bg-primary text-white">2024</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ======= Visi Misi ======= -->
    <section class="section bg-light position-relative overflow-hidden">
      <div class="decorative-bg"></div>
      <div class="container">
        <div class="row justify-content-center mb-5">
          <div class="col-lg-8 text-center" data-aos="fade-up">
            <div class="section-badge mb-3">
              <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                <i class="bi bi-bullseye me-2"></i>Visi & Misi
              </span>
            </div>
            <h2 class="display-5 fw-bold mb-4">Arah & Tujuan <span class="text-primary">Pendidikan</span></h2>
            <p class="lead text-muted">Komitmen kami dalam membentuk generasi unggul masa depan</p>
          </div>
        </div>
        
        <div class="row g-5">
          <div class="col-lg-6" data-aos="fade-up">
            <div class="vision-card h-100">
              <div class="card-icon mb-4">
                <div class="icon-circle bg-primary bg-gradient">
                  <i class="bi bi-eye text-white fs-2"></i>
                </div>
              </div>
              <h3 class="fw-bold mb-4 text-primary">Visi Sekolah</h3>
              <div class="vision-content">
                <blockquote class="blockquote">
                  <p class="lead mb-4">"Menjadi lembaga pendidikan unggul yang menghasilkan generasi cerdas, berkarakter mulia, dan berdaya saing global dengan tetap berpegang teguh pada nilai-nilai budaya bangsa."</p>
                </blockquote>
                <div class="vision-highlights">
                  <span class="highlight-tag">Unggul</span>
                  <span class="highlight-tag">Berkarakter</span>
                  <span class="highlight-tag">Global</span>
                  <span class="highlight-tag">Budaya Bangsa</span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
            <div class="mission-card h-100">
              <div class="card-icon mb-4">
                <div class="icon-circle bg-success bg-gradient">
                  <i class="bi bi-flag text-white fs-2"></i>
                </div>
              </div>
              <h3 class="fw-bold mb-4 text-success">Misi Sekolah</h3>
              <div class="mission-list">
                <div class="mission-item">
                  <div class="mission-icon">
                    <i class="bi bi-check-circle-fill text-success"></i>
                  </div>
                  <p>Menyelenggarakan pendidikan berkualitas dengan kurikulum yang relevan dan inovatif</p>
                </div>
                <div class="mission-item">
                  <div class="mission-icon">
                    <i class="bi bi-check-circle-fill text-success"></i>
                  </div>
                  <p>Mengembangkan potensi akademik dan non-akademik peserta didik secara optimal</p>
                </div>
                <div class="mission-item">
                  <div class="mission-icon">
                    <i class="bi bi-check-circle-fill text-success"></i>
                  </div>
                  <p>Membangun karakter peserta didik yang berakhlak mulia dan berbudi pekerti luhur</p>
                </div>
                <div class="mission-item">
                  <div class="mission-icon">
                    <i class="bi bi-check-circle-fill text-success"></i>
                  </div>
                  <p>Menciptakan lingkungan belajar yang kondusif, aman, dan menyenangkan</p>
                </div>
                <div class="mission-item">
                  <div class="mission-icon">
                    <i class="bi bi-check-circle-fill text-success"></i>
                  </div>
                  <p>Mengintegrasikan teknologi dalam proses pembelajaran</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ======= Program Unggulan ======= -->
    <section class="section pb-5 position-relative ">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 pe-lg-5" data-aos="fade-right">
            <div class="section-badge mb-3">
              <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                <i class="bi bi-star me-2"></i>Program Unggulan
              </span>
            </div>
            <h2 class="display-5 fw-bold mb-4">
              Inovasi <span class="text-warning">Pembelajaran</span><br>
              Masa Depan
            </h2>
            <p class="lead text-muted mb-5">Program-program terdepan yang mempersiapkan siswa menghadapi tantangan global</p>
            
            <div class="programs-accordion">
              <div class="program-item active mb-4">
                <div class="program-header">
                  <div class="program-icon bg-primary bg-gradient">
                    <i class="bi bi-translate text-white"></i>
                  </div>
                  <div class="program-content">
                    <h5 class="fw-bold mb-2">Program Bilingual</h5>
                    <p class="text-muted mb-0">Pembelajaran menggunakan dua bahasa (Indonesia dan Inggris) untuk mempersiapkan siswa menghadapi era globalisasi dengan standar internasional.</p>
                    <div class="program-features mt-2">
                      <span class="feature-badge">Native Speaker</span>
                      <span class="feature-badge">TOEFL Prep</span>
                      <span class="feature-badge">International Curriculum</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="program-item mb-4">
                <div class="program-header">
                  <div class="program-icon bg-success bg-gradient">
                    <i class="bi bi-gear text-white"></i>
                  </div>
                  <div class="program-content">
                    <h5 class="fw-bold mb-2">STEM Education</h5>
                    <p class="text-muted mb-0">Program Science, Technology, Engineering, and Mathematics yang mengintegrasikan teknologi modern dalam pembelajaran berbasis proyek.</p>
                    <div class="program-features mt-2">
                      <span class="feature-badge">Robotics</span>
                      <span class="feature-badge">Coding</span>
                      <span class="feature-badge">3D Printing</span>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="program-item mb-4">
                <div class="program-header">
                  <div class="program-icon bg-info bg-gradient">
                    <i class="bi bi-heart text-white"></i>
                  </div>
                  <div class="program-content">
                    <h5 class="fw-bold mb-2">Character Building</h5>
                    <p class="text-muted mb-0">Program pembentukan karakter melalui kegiatan ekstrakurikuler, mentoring, dan program kepemimpinan yang terintegrasi.</p>
                    <div class="program-features mt-2">
                      <span class="feature-badge">Leadership</span>
                      <span class="feature-badge">Mentoring</span>
                      <span class="feature-badge">Community Service</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="mt-4">
              <a href="#programs" class="btn btn-warning btn-lg rounded-pill px-4 me-3">
                <i class="bi bi-arrow-right me-2"></i>Lihat Semua Program
              </a>
              <a href="#" class="btn btn-outline-warning btn-lg rounded-pill px-4">
                Download Brosur
              </a>
            </div>
          </div>
          
          <div class="col-lg-6" data-aos="fade-left" data-aos-delay="200">
            <div class="programs-visual">
              <div class="floating-card card-1">
                <div class="card bg-primary text-white p-4 rounded-4 shadow">
                  <div class="d-flex align-items-center">
                    <div class="icon-lg bg-white bg-opacity-20 rounded-3 me-3">
                      <i class="bi bi-globe fs-3"></i>
                    </div>
                    <div>
                      <h6 class="fw-bold mb-1">Global Perspective</h6>
                      <small class="opacity-75">International Standards</small>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="floating-card card-2">
                <div class="card bg-success text-white p-4 rounded-4 shadow">
                  <div class="d-flex align-items-center">
                    <div class="icon-lg bg-white bg-opacity-20 rounded-3 me-3">
                      <i class="bi bi-cpu fs-3"></i>
                    </div>
                    <div>
                      <h6 class="fw-bold mb-1">Tech Integration</h6>
                      <small class="opacity-75">Smart Learning</small>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="floating-card card-3">
                <div class="card bg-info text-white p-4 rounded-4 shadow">
                  <div class="d-flex align-items-center">
                    <div class="icon-lg bg-white bg-opacity-20 rounded-3 me-3">
                      <i class="bi bi-people fs-3"></i>
                    </div>
                    <div>
                      <h6 class="fw-bold mb-1">Character Focus</h6>
                      <small class="opacity-75">Holistic Development</small>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="central-image">
                <img src="https://lh3.googleusercontent.com/Q7h_pz1CZP1pr1BiRXJS5-otc3RxuB8fKHN8y0vA9yCm2twe-m_6BaTE5GIqSuZ48tR97as_WRC7feDBp4f2ApStHtFyhmK0-UfWis6unr9gyzeDFQRA8QKBTq9mBa920yy7K2NSEzwItoNpQbKPeQs" alt="Siswa Belajar" class="img-fluid rounded-4 shadow-lg">
                <div class="image-overlay">
                  <div class="play-button">
                    <i class="bi bi-play-fill"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ======= Fasilitas Modern ======= -->
    <section class="section bg-light position-relative">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-6 order-lg-2 ps-lg-5" data-aos="fade-left">
            <div class="section-badge mb-3">
              <span class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill">
                <i class="bi bi-building me-2"></i>Fasilitas
              </span>
            </div>
            <h2 class="display-5 fw-bold mb-4">
              Fasilitas <span class="text-info">Berstandar</span><br>
              Internasional
            </h2>
            <p class="lead text-muted mb-4">Lingkungan belajar yang nyaman dan teknologi terdepan untuk mendukung proses pembelajaran optimal</p>
            
            <div class="facilities-grid row g-3 mb-5">
              <div class="col-6">  
                <div class="facility-card">
                  <div class="facility-icon bg-primary bg-gradient">
                    <i class="bi bi-door-open text-white"></i>
                  </div>
                  <div class="facility-info">
                    <h4 class="fw-bold text-primary">45</h4>
                    <p class="small text-muted mb-0">Ruang Kelas Ber-AC</p>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="facility-card">
                  <div class="facility-icon bg-success bg-gradient">
                    <i class="bi bi-flask text-white"></i>
                  </div>
                  <div class="facility-info">
                    <h4 class="fw-bold text-success">8</h4>
                    <p class="small text-muted mb-0">Lab Modern</p>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="facility-card">
                  <div class="facility-icon bg-warning bg-gradient">
                    <i class="bi bi-book text-white"></i>
                  </div>
                  <div class="facility-info">
                    <h4 class="fw-bold text-warning">15K+</h4>
                    <p class="small text-muted mb-0">Koleksi Buku</p>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="facility-card">
                  <div class="facility-icon bg-danger bg-gradient">
                    <i class="bi bi-dribbble text-white"></i>
                  </div>
                  <div class="facility-info">
                    <h4 class="fw-bold text-danger">3</h4>
                    <p class="small text-muted mb-0">Lapangan Olahraga</p>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="facility-features">
              <h5 class="fw-bold mb-3">Fasilitas Unggulan:</h5>
              <div class="feature-list">
                <div class="feature-item">
                  <i class="bi bi-wifi text-primary"></i>
                  <span>WiFi High Speed</span>
                </div>
                <div class="feature-item">
                  <i class="bi bi-projector text-success"></i>
                  <span>Smart Board</span>
                </div>
                <div class="feature-item">
                  <i class="bi bi-camera-video text-warning"></i>
                  <span>Recording Studio</span>
                </div>
                <div class="feature-item">
                  <i class="bi bi-hospital text-danger"></i>
                  <span>Medical Center</span>
                </div>
              </div>
            </div>
            
            <div class="mt-4">
              <a href="#facilities" class="btn btn-info btn-lg rounded-pill px-4">
                <i class="bi bi-camera me-2"></i>Virtual Tour
              </a>
            </div>
          </div>
          
          <div class="col-lg-6 order-lg-1" data-aos="fade-right" data-aos-delay="200">
            <div class="facilities-showcase">
              <div class="facility-image-grid">
                <div class="facility-img facility-img-1">
                  <img src="https://c1.wallpaperflare.com/preview/940/856/834/various-education-school-study.jpg" alt="Ruang Kelas" class="img-fluid rounded-3">
                  <div class="facility-label">
                    <span class="badge bg-primary">Smart Classroom</span>
                  </div>
                </div>
                <div class="facility-img facility-img-2">
                  <img src="https://media.istockphoto.com/id/506340238/id/foto/kamar-pc.jpg?s=612x612&w=0&k=20&c=651Bvk2cMQgiLYX4l7B8ivmjd1uO1NQQyIHNZw8ayu0=" alt="Laboratorium" class="img-fluid rounded-3">
                  <div class="facility-label">
                    <span class="badge bg-success">Science Lab</span>
                  </div>
                </div>
                <div class="facility-img facility-img-3">
                  <img src="https://img.lovepik.com/photo/20211121/medium/lovepik-a-spacious-and-bright-library-reading-room-picture_500583453.jpg" alt="Perpustakaan" class="img-fluid rounded-3">
                  <div class="facility-label">
                    <span class="badge bg-warning">Digital Library</span>
                  </div>
                </div>
                <div class="facility-img facility-img-4">
                  <img src="https://e1.pxfuel.com/desktop-wallpaper/386/724/desktop-wallpaper-phoenix-and-tucson-area-sport-court-construction-and-renovation-tennis-court.jpg" alt="Olahraga" class="img-fluid rounded-3">
                  <div class="facility-label">
                    <span class="badge bg-danger">Sports Complex</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ======= Prestasi Section ======= -->
    <section class="section pb-0 position-relative overflow-hidden" id="achievements">
      <div class="achievement-bg"></div>
      <div class="container">
        <div class="row text-center mb-5">
          <div class="col-12" data-aos="fade-up">
            <div class="section-badge mb-3">
              <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                <i class="bi bi-trophy me-2"></i>Prestasi
              </span>
            </div>
            <h2 class="display-4 fw-bold mb-4">
              Pencapaian <span class="text-success">Membanggakan</span>
            </h2>
            <p class="lead text-muted">Bukti nyata kualitas pendidikan dan dedikasi seluruh keluarga besar Sekolah XYZ</p>
          </div>
        </div>
        
        <div class="row g-4 mb-5">
          <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="achievement-card">
              <div class="achievement-icon bg-primary bg-gradient">
                <i class="bi bi-trophy text-white"></i>
              </div>
              <div class="achievement-content">
                <div class="achievement-number" data-count="150">0</div>
                <h5 class="fw-bold text-primary mb-2">Juara Olimpiade</h5>
                <p class="text-muted small mb-3">Tingkat Nasional & Internasional</p>
                <div class="achievement-tags">
                  <span class="achievement-tag">Matematika</span>
                  <span class="achievement-tag">Sains</span>
                  <span class="achievement-tag">Bahasa</span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="achievement-card">
              <div class="achievement-icon bg-success bg-gradient">
                <i class="bi bi-mortarboard text-white"></i>
              </div>
              <div class="achievement-content">
                <div class="achievement-number" data-count="95">0</div>
                <span class="achievement-percent">%</span>
                <h5 class="fw-bold text-success mb-2">Kelulusan PTN</h5>
                <p class="text-muted small mb-3">Diterima di Perguruan Tinggi Negeri</p>
                <div class="achievement-tags">
                  <span class="achievement-tag">UI</span>
                  <span class="achievement-tag">ITB</span>
                  <span class="achievement-tag">UGM</span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="achievement-card">
              <div class="achievement-icon bg-warning bg-gradient">
                <i class="bi bi-award text-white"></i>
              </div>
              <div class="achievement-content">
                <div class="achievement-number" data-count="25">0</div>
                <span class="achievement-plus">+</span>
                <h5 class="fw-bold text-warning mb-2">Penghargaan</h5>
                <p class="text-muted small mb-3">Sekolah Berprestasi</p>
                <div class="achievement-tags">
                  <span class="achievement-tag">Adiwiyata</span>
                  <span class="achievement-tag">ISO</span>
                  <span class="achievement-tag">Akreditasi A</span>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="achievement-card">
              <div class="achievement-icon bg-info bg-gradient">
                <i class="bi bi-people text-white"></i>
              </div>
              <div class="achievement-content">
                <div class="achievement-number" data-count="5000">0</div>
                <span class="achievement-plus">+</span>
                <h5 class="fw-bold text-info mb-2">Alumni Sukses</h5>
                <p class="text-muted small mb-3">Tersebar di Berbagai Profesi</p>
                <div class="achievement-tags">
                  <span class="achievement-tag">Dokter</span>
                  <span class="achievement-tag">Engineer</span>
                  <span class="achievement-tag">CEO</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Achievement Gallery -->
        <div class="row" data-aos="fade-up" data-aos-delay="500">
          <div class="col-12">
            <div class="achievement-gallery">
              <h4 class="text-center fw-bold mb-4">Galeri Prestasi Terbaru</h4>
              <div class="achievement-slider">
                <div class="achievement-slide">
                  <img src="https://www.smpkatolikpelitabangsa.sch.id/wp-content/uploads/2020/10/IMG_20180418_073919-scaled.jpg" alt="Juara Olimpiade Matematika" class="img-fluid rounded-3">
                  <div class="slide-overlay">
                    <h6 class="text-white fw-bold">Juara 1 Olimpiade Matematika Nasional</h6>
                    <small class="text-white-50">Jakarta, 2024</small>
                  </div>
                </div>
                <div class="achievement-slide">
                  <img src="https://www.smpkatolikpelitabangsa.sch.id/wp-content/uploads/2020/10/IMG_20180418_073919-scaled.jpg" alt="Best School Award" class="img-fluid rounded-3">
                  <div class="slide-overlay">
                    <h6 class="text-white fw-bold">Best School Award</h6>
                    <small class="text-white-50">Kemendikbud, 2024</small>
                  </div>
                </div>
                <div class="achievement-slide">
                  <img src="https://www.smpkatolikpelitabangsa.sch.id/wp-content/uploads/2020/10/IMG_20180418_073919-scaled.jpg" alt="Green School Award" class="img-fluid rounded-3">
                  <div class="slide-overlay">
                    <h6 class="text-white fw-bold">Adiwiyata Nasional</h6>
                    <small class="text-white-50">KLHK, 2023</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ======= Testimonials Section ======= -->
    <section class="section border-top position-relative overflow-hidden">
      <div class="testimonial-bg"></div>
      <div class="container">
        <div class="row justify-content-center text-center mb-5">
          <div class="col-lg-8" data-aos="fade-up">
            <div class="section-badge mb-3">
              <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                <i class="bi bi-chat-heart me-2"></i>Testimoni
              </span>
            </div>
            <h2 class="display-5 fw-bold mb-4">Apa Kata <span class="text-primary">Mereka</span></h2>
            <p class="lead text-muted">Pengalaman nyata dari alumni, orang tua, dan mitra pendidikan</p>
          </div>
        </div>
        
        <div class="row justify-content-center">
          <div class="col-lg-10">
            <div class="testimonials-container" data-aos="fade-up" data-aos-delay="200">
              <div class="testimonials-slider swiper">
                <div class="swiper-wrapper">

                  <div class="swiper-slide">
                    <div class="testimonial-card">
                      <div class="testimonial-header">
                        <div class="stars-rating mb-3">
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                        </div>
                        <h4 class="fw-bold text-primary mb-3">Pendidikan Berkualitas Tinggi!</h4>
                      </div>
                      
                      <div class="testimonial-content">
                        <blockquote class="blockquote">
                          <p class="lead text-muted mb-4">"Sekolah XYZ memberikan pendidikan yang sangat berkualitas. Anak saya tidak hanya pintar secara akademik, tetapi juga memiliki karakter yang baik. Guru-gurunya sangat berdedikasi dan fasilitas sekolahnya lengkap."</p>
                        </blockquote>
                      </div>

                      <div class="testimonial-author">
                        <div class="author-avatar">
                          <img src="{{ asset('frontend/assets/img/person_1.jpg') }}" alt="Orang Tua" class="img-fluid rounded-circle">
                          <div class="author-badge">
                            <i class="bi bi-patch-check-fill text-primary"></i>
                          </div>
                        </div>
                        <div class="author-info">
                          <h6 class="fw-bold mb-1">Ibu Sarah Wijaya</h6>
                          <p class="text-muted small mb-0">Orang Tua Siswa Kelas XII</p>
                          <div class="author-tags mt-2">
                            <span class="tag">Parent</span>
                            <span class="tag">2020-2024</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="swiper-slide">
                    <div class="testimonial-card">
                      <div class="testimonial-header">
                        <div class="stars-rating mb-3">
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                        </div>
                        <h4 class="fw-bold text-success mb-3">Tempat Terbaik Menuntut Ilmu!</h4>
                      </div>
                      
                      <div class="testimonial-content">
                        <blockquote class="blockquote">
                          <p class="lead text-muted mb-4">"Saya bangga menjadi alumni Sekolah XYZ. Pendidikan yang saya terima di sini sangat membantu saya dalam berkarir. Program STEM dan bilingual memberikan bekal yang sangat berharga untuk masa depan."</p>
                        </blockquote>
                      </div>

                      <div class="testimonial-author">
                        <div class="author-avatar">
                          <img src="{{ asset('frontend/assets/img/person_2.jpg') }}" alt="Alumni" class="img-fluid rounded-circle">
                          <div class="author-badge">
                            <i class="bi bi-mortarboard-fill text-success"></i>
                          </div>
                        </div>
                        <div class="author-info">
                          <h6 class="fw-bold mb-1">Ahmad Pratama, S.T.</h6>
                          <p class="text-muted small mb-0">Software Engineer di Google</p>
                          <div class="author-tags mt-2">
                            <span class="tag">Alumni</span>
                            <span class="tag">Class of 2018</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="swiper-slide">
                    <div class="testimonial-card">
                      <div class="testimonial-header">
                        <div class="stars-rating mb-3">
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                        </div>
                        <h4 class="fw-bold text-info mb-3">Lingkungan Belajar yang Kondusif!</h4>
                      </div>
                      
                      <div class="testimonial-content">
                        <blockquote class="blockquote">
                          <p class="lead text-muted mb-4">"Sekolah XYZ memiliki lingkungan belajar yang sangat mendukung. Selain akademik, anak-anak juga diajarkan nilai-nilai moral dan etika yang baik. Ekstrakurikulernya juga beragam dan berkualitas."</p>
                        </blockquote>
                      </div>

                      <div class="testimonial-author">
                        <div class="author-avatar">
                          <img src="{{ asset('frontend/assets/img/person_3.jpg') }}" alt="Orang Tua" class="img-fluid rounded-circle">
                          <div class="author-badge">
                            <i class="bi bi-heart-fill text-danger"></i>
                          </div>
                        </div>
                        <div class="author-info">
                          <h6 class="fw-bold mb-1">Bapak Joko Santoso</h6>
                          <p class="text-muted small mb-0">Orang Tua Siswa Kelas X</p>
                          <div class="author-tags mt-2">
                            <span class="tag">Parent</span>
                            <span class="tag">2022-2025</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="swiper-slide">
                    <div class="testimonial-card">
                      <div class="testimonial-header">
                        <div class="stars-rating mb-3">
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                          <i class="bi bi-star-fill text-warning"></i>
                        </div>
                        <h4 className="fw-bold text-warning mb-3">Partnership yang Excellent!</h4>
                      </div>
                      
                      <div class="testimonial-content">
                        <blockquote class="blockquote">
                          <p class="lead text-muted mb-4">"Sebagai mitra industri, kami sangat terkesan dengan kualitas lulusan Sekolah XYZ. Mereka memiliki skill teknis yang baik dan soft skill yang excellent. Sangat siap kerja!"</p>
                        </blockquote>
                      </div>

                      <div class="testimonial-author">
                        <div class="author-avatar">
                          <img src="{{ asset('frontend/assets/img/person_2.jpg') }}" alt="Industry Partner" class="img-fluid rounded-circle">
                          <div class="author-badge">
                            <i class="bi bi-building text-warning"></i>
                          </div>
                        </div>
                        <div class="author-info">
                          <h6 class="fw-bold mb-1">Dr. Lisa Andriani</h6>
                          <p class="text-muted small mb-0">HR Director PT. Tech Innovation</p>
                          <div class="author-tags mt-2">
                            <span class="tag">Industry Partner</span>
                            <span class="tag">Since 2019</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>
                
                <!-- Navigation -->
                <div class="swiper-pagination"></div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Testimonial Stats -->
        <div class="row mt-5" data-aos="fade-up" data-aos-delay="400">
          <div class="col-12">
            <div class="testimonial-stats text-center">
              <div class="row g-4">
                <div class="col-md-3 col-6">
                  <div class="stat-item">
                    <h4 class="fw-bold text-primary">4.9/5</h4>
                    <p class="text-muted small mb-0">Rating Kepuasan Orang Tua</p>
                  </div>
                </div>
                <div class="col-md-3 col-6">
                  <div class="stat-item">
                    <h4 class="fw-bold text-success">98%</h4>
                    <p class="text-muted small mb-0">Alumni Merekomendasikan</p>
                  </div>
                </div>
                <div class="col-md-3 col-6">
                  <div class="stat-item">
                    <h4 class="fw-bold text-warning">500+</h4>
                    <p class="text-muted small mb-0">Testimoni Positif</p>
                  </div>
                </div>
                <div class="col-md-3 col-6">
                  <div class="stat-item">
                    <h4 class="fw-bold text-info">50+</h4>
                    <p class="text-muted small mb-0">Mitra Industri</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ======= CTA Section ======= -->
    <section class="section cta-section position-relative overflow-hidden">
      <div class="cta-bg"></div>
      <div class="floating-elements">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
        <div class="floating-shape shape-3"></div>
      </div>
      
      <div class="container position-relative">
        <div class="row align-items-center">
          <div class="col-lg-8 text-center text-lg-start mb-5 mb-lg-0" data-aos="fade-right">
            <div class="cta-badge mb-3">
              <span class="badge bg-white bg-opacity-20 text-white px-3 py-2 rounded-pill">
                <i class="bi bi-rocket me-2"></i>Mulai Perjalanan Pendidikan
              </span>
            </div>
            <h2 class="display-4 fw-bold text-white mb-4">
              Bergabunglah dengan<br>
              <span class="text-warning">Keluarga Besar</span> Sekolah XYZ
            </h2>
            <p class="lead text-white-75 mb-4">
              Daftarkan putra-putri Anda dan berikan mereka pendidikan terbaik untuk masa depan yang gemilang. 
              Raih kesempatan emas menjadi bagian dari sekolah terdepan di Indonesia.
            </p>
            
            <div class="cta-features mb-4">
              <div class="feature-item">
                <i class="bi bi-check-circle-fill text-warning me-2"></i>
                <span class="text-white">Pendaftaran Online 24/7</span>
              </div>
              <div class="feature-item">
                <i class="bi bi-check-circle-fill text-warning me-2"></i>
                <span class="text-white">Beasiswa hingga 100%</span>
              </div>
              <div class="feature-item">
                <i class="bi bi-check-circle-fill text-warning me-2"></i>
                <span class="text-white">Konsultasi Gratis</span>
              </div>
            </div>
          </div>
          
          <div class="col-lg-4 text-center" data-aos="fade-left" data-aos-delay="200">
            <div class="cta-actions">
              <div class="action-card mb-4">
                <div class="card bg-white bg-opacity-10 backdrop-blur border-0 text-white ms-2">
                  <div class="card-body p-4">
                    <div class="urgency-badge mb-3">
                      <span class="badge bg-danger bg-gradient px-3 py-2 rounded-pill">
                        <i class="bi bi-clock me-1"></i>Pendaftaran Terbatas!
                      </span>
                    </div>
                    <h5 class="fw-bold mb-2">Tahun Ajaran 2024/2025</h5>
                    <p class="small text-white-75 mb-3">Sisa kuota hanya 50 siswa</p>
                    <div class="countdown-timer">
                      <div class="countdown-item">
                        <span class="countdown-number">45</span>
                        <span class="countdown-label">Hari</span>
                      </div>
                      <div class="countdown-item">
                        <span class="countdown-number">12</span>
                        <span class="countdown-label">Jam</span>
                      </div>
                      <div class="countdown-item">
                        <span class="countdown-number">30</span>
                        <span class="countdown-label">Menit</span>
                      </div>
                    </div>
                  </div>
                </div>
                  </div>
                </div>
              </div>
              
              <div class="cta-buttons">
                <a href="{{ route('pendaftaran') }}" class="btn btn-warning btn-lg rounded-pill px-5 mb-3 btn-glow">
                  <i class="bi bi-person-plus me-2"></i>Daftar Sekarang
                </a>
                <a href="#" class="btn btn-outline-light btn-lg rounded-pill px-5 mb-3">
                  <i class="bi bi-info-circle me-2"></i>Info Pendaftaran
                </a>
                <div class="contact-info mt-3">
                  <p class="small text-white-75 mb-2">
                    <i class="bi bi-telephone me-2"></i>
                    <strong>Hotline:</strong> 0800-1234-5678
                  </p>
                  <p class="small text-white-75 mb-0">
                    <i class="bi bi-whatsapp me-2"></i>
                    <strong>WhatsApp:</strong> +62 812-3456-7890
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Success Stories Preview -->
        <div class="row mt-5" data-aos="fade-up" data-aos-delay="300">
          <div class="col-12">
            <div class="success-preview text-center">
              <h5 class="text-white fw-bold mb-3">Join 5000+ Alumni Sukses</h5>
              <div class="alumni-avatars">
                <img src="{{ asset('frontend/assets/img/person_1.jpg') }}" alt="Alumni" class="alumni-avatar">
                <img src="{{ asset('frontend/assets/img/person_2.jpg') }}" alt="Alumni" class="alumni-avatar">
                <img src="{{ asset('frontend/assets/img/person_3.jpg') }}" alt="Alumni" class="alumni-avatar">
                <img src="{{ asset('frontend/assets/img/person_1.jpg') }}" alt="Alumni" class="alumni-avatar">
                <div class="alumni-count">
                  <span class="text-white fw-bold">+5K</span>
                </div>
              </div>
              <p class="small text-white-75 mt-2">Alumni kami tersebar di Google, Microsoft, UI, ITB, Harvard, dan perusahaan top dunia</p>
            </div>
          </div>
        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <!-- Custom Styles for Enhanced Visual Appeal -->
  <style>
    /* Floating Shapes Animation */
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
    
    /* Text Gradient */
    .text-gradient {
      background: linear-gradient(45deg, #007bff, #28a745, #ffc107);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }
    
    /* Content Cards */
    .content-card {
      background: rgba(255, 255, 255, 0.05);
      border-radius: 20px;
      padding: 2rem;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    /* Timeline Styles */
    .timeline-item {
      position: relative;
      padding-left: 2rem;
    }
    
    .timeline-marker {
      position: absolute;
      left: 0;
      top: 0;
      width: 12px;
      height: 12px;
      border-radius: 50%;
    }
    
    .timeline-item::before {
      content: '';
      position: absolute;
      left: 5px;
      top: 12px;
      bottom: -20px;
      width: 2px;
      background: linear-gradient(to bottom, #007bff, transparent);
    }
    
    .timeline-item:last-child::before {
      display: none;
    }
    
    /* Image Stack */
    .image-stack {
      position: relative;
      height: 400px;
    }
    
    .image-card {
      position: absolute;
      border-radius: 20px;
      overflow: hidden;
      transition: transform 0.3s ease;
    }
    
    .image-card-1 {
      top: 0;
      left: 0;
      width: 70%;
      height: 60%;
      z-index: 2;
    }
    
    .image-card-2 {
      bottom: 0;
      right: 0;
      width: 70%;
      height: 60%;
      z-index: 1;
    }
    
    .image-card:hover {
      transform: scale(1.05);
    }
    
    .image-overlay {
      position: absolute;
      top: 1rem;
      right: 1rem;
    }
    
    /* Vision & Mission Cards */
    .vision-card, .mission-card {
      background: white;
      border-radius: 20px;
      padding: 2rem;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }
    
    .vision-card:hover, .mission-card:hover {
      transform: translateY(-10px);
    }
    
    .icon-circle {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .highlight-tag {
      display: inline-block;
      background: rgba(0, 123, 255, 0.1);
      color: #007bff;
      padding: 0.25rem 0.75rem;
      border-radius: 20px;
      font-size: 0.875rem;
      font-weight: 500;
      margin: 0.25rem 0.25rem 0 0;
    }
    
    .mission-item {
      display: flex;
      align-items: flex-start;
      margin-bottom: 1rem;
    }
    
    .mission-icon {
      margin-right: 0.75rem;
      margin-top: 0.25rem;
    }
    
    /* Program Cards */
    .program-item {
      background: white;
      border-radius: 15px;
      padding: 1.5rem;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      cursor: pointer;
    }
    
    .program-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }
    
    .program-header {
      display: flex;
      align-items: flex-start;
    }
    
    .program-icon {
      width: 60px;
      height: 60px;
      border-radius: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 1rem;
      flex-shrink: 0;
    }
    
    .feature-badge {
      display: inline-block;
      background: rgba(0, 123, 255, 0.1);
      color: #007bff;
      padding: 0.25rem 0.5rem;
      border-radius: 10px;
      font-size: 0.75rem;
      margin: 0.25rem 0.25rem 0 0;
    }
    
    /* Floating Cards for Programs */
    .programs-visual {
      position: relative;
      height: 500px;
    }
    
    .floating-card {
      position: absolute;
      animation: floatCard 4s ease-in-out infinite;
    }
    
    .card-1 {
      top: 35%;
      left: 0;
      animation-delay: 0s;
    }
    
    .card-2 {
      top: 60%;
      right: 0;
      animation-delay: 1s;
    }
    
    .card-3 {
      top: 90%;
      bottom: 0;
      left: 20%;
      animation-delay: 2s;
    }
    
    .central-image {
      position: absolute;
      top: 5%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 80%;
      border-radius: 20px;
      overflow: hidden;
    }
    
    .image-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.3);
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity 0.3s ease;
    }
    
    .central-image:hover .image-overlay {
      opacity: 1;
    }
    
    .play-button {
      width: 80px;
      height: 80px;
      background: rgba(255, 255, 255, 0.9);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2rem;
      color: #007bff;
    }
    
    @keyframes floatCard {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-15px); }
    }
    
    /* Facility Cards */
    .facility-card {
      background: white;
      border-radius: 15px;
      padding: 1.5rem;
      text-align: center;
      box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }
    
    .facility-card:hover {
      transform: translateY(-5px);
    }
    
    .facility-icon {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1rem;
    }
    
    .feature-list {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
    }
    
    .feature-item {
      display: flex;
      align-items: center;
      font-weight: 500;
    }
    
    .feature-item i {
      margin-right: 0.5rem;
      font-size: 1.2rem;
    }
    
    /* Facilities Showcase */
    .facility-image-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 1rem;
      height: 400px;
    }
    
    .facility-img {
      position: relative;
      border-radius: 15px;
      overflow: hidden;
      transition: transform 0.3s ease;
    }
    
    .facility-img:hover {
      transform: scale(1.05);
    }
    
    .facility-label {
      position: absolute;
      top: 1rem;
      left: 1rem;
    }
    
    /* Achievement Cards */
    .achievement-card {
      background: white;
      border-radius: 20px;
      padding: 2rem;
      text-align: center;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
      height: 100%;
    }
    
    .achievement-card:hover {
      transform: translateY(-10px);
    }
    
    .achievement-icon {
      width: 80px;
      height: 80px;
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      font-size: 2rem;
    }
    
    .achievement-number {
      font-size: 3rem;
      font-weight: 800;
      line-height: 1;
      display: inline-block;
    }
    
    .achievement-percent, .achievement-plus {
      font-size: 2rem;
      font-weight: 600;
      margin-left: 0.25rem;
    }
    
    .achievement-tag {
      display: inline-block;
      background: rgba(0, 0, 0, 0.05);
      color: #666;
      padding: 0.25rem 0.5rem;
      border-radius: 10px;
      font-size: 0.75rem;
      margin: 0.25rem 0.25rem 0 0;
    }
    
    .achievement-gallery {
      background: rgba(255, 255, 255, 0.05);
      border-radius: 20px;
      padding: 2rem;
      backdrop-filter: blur(10px);
    }
    
    .achievement-slider {
      display: flex;
      gap: 1rem;
      overflow-x: auto;
      padding: 1rem 0;
    }
    
    .achievement-slide {
      flex: 0 0 300px;
      position: relative;
      border-radius: 15px;
      overflow: hidden;
    }
    
    .slide-overlay {
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
      padding: 2rem 1rem 1rem;
    }
    
    /* Testimonial Cards */
    .testimonials-container {
      position: relative;
    }
    
    .testimonial-card {
      background: white;
      border-radius: 25px;
      padding: 2.5rem;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
      margin: 1rem;
      transition: transform 0.3s ease;
    }
    
    .testimonial-card:hover {
      transform: translateY(-5px);
    }
    
    .stars-rating {
      text-align: center;
    }
    
    .stars-rating i {
      font-size: 1.2rem;
      margin: 0 0.1rem;
    }
    
    .testimonial-author {
      display: flex;
      align-items: center;
      margin-top: 2rem;
    }
    
    .author-avatar {
      position: relative;
      margin-right: 1rem;
    }
    
    .author-avatar img {
      width: 80px;
      height: 80px;
      object-fit: cover;
    }
    
    .author-badge {
      position: absolute;
      bottom: -5px;
      right: -5px;
      background: white;
      border-radius: 50%;
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .author-tags {
      display: flex;
      gap: 0.5rem;
    }
    
    .tag {
      background: rgba(0, 123, 255, 0.1);
      color: #007bff;
      padding: 0.25rem 0.5rem;
      border-radius: 10px;
      font-size: 0.75rem;
      font-weight: 500;
    }
    
    .testimonial-stats {
      background: rgba(255, 255, 255, 0.05);
      border-radius: 20px;
      padding: 2rem;
      backdrop-filter: blur(10px);
    }
    
    .stat-item {
      padding: 1rem;
    }
    
    /* CTA Section */
    .cta-section {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      position: relative;
    }
    
    .cta-bg::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
    }
    
    .floating-shape {
      position: absolute;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      animation: floatShape 8s ease-in-out infinite;
    }
    
    .floating-shape.shape-1 {
      width: 100px;
      height: 100px;
      top: 20%;
      left: 10%;
      animation-delay: 0s;
    }
    
    .floating-shape.shape-2 {
      width: 150px;
      height: 150px;
      top: 60%;
      right: 15%;
      animation-delay: 3s;
    }
    
    .floating-shape.shape-3 {
      width: 80px;
      height: 80px;
      bottom: 20%;
      left: 60%;
      animation-delay: 6s;
    }
    
    @keyframes floatShape {
      0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.7; }
      50% { transform: translateY(-30px) rotate(180deg); opacity: 1; }
    }
    
    .cta-features {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }
    
    .cta-features .feature-item {
      display: flex;
      align-items: center;
    }
    
    .action-card {
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      width: 125%;
    }

    .backdrop-blur {
        width: 95%;
      }
    
    .urgency-badge {
      animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }
    
    .countdown-timer {
      display: flex;
      justify-content: center;
      gap: 1rem;
      margin-top: 1rem;
    }
    
    .countdown-item {
      text-align: center;
    }
    
    .countdown-number {
      display: block;
      font-size: 1.5rem;
      font-weight: bold;
      color: #ffc107;
    }
    
    .countdown-label {
      font-size: 0.75rem;
      color: rgba(255, 255, 255, 0.7);
    }
    
    .btn-glow {
      box-shadow: 0 0 20px rgba(255, 193, 7, 0.3);
      animation: glow 2s ease-in-out infinite alternate;
    }
    
    @keyframes glow {
      from { box-shadow: 0 0 20px rgba(255, 193, 7, 0.3); }
      to { box-shadow: 0 0 30px rgba(255, 193, 7, 0.6); }
    }
    
    .alumni-avatars {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: -10px;
      margin: 1rem 0;
    }
    
    .alumni-avatar {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      border: 3px solid white;
      margin-left: -10px;
      transition: transform 0.3s ease;
    }
    
    .alumni-avatar:hover {
      transform: scale(1.1);
      z-index: 10;
    }
    
    .alumni-count {
      background: rgba(255, 255, 255, 0.2);
      color: white;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.75rem;
      font-weight: bold;
      margin-left: -10px;
      backdrop-filter: blur(10px);
    }
    
    /* Background Patterns */
    .section-bg-pattern::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(45deg, transparent 40%, rgba(0, 123, 255, 0.05) 50%, transparent 60%);
    }
    
    .decorative-bg::before {
      content: '';
      position: absolute;
      top: -50%;
      right: -20%;
      width: 300px;
      height: 300px;
      background: radial-gradient(circle, rgba(0, 123, 255, 0.1) 0%, transparent 70%);
      border-radius: 50%;
    }
    
    .decorative-bg::after {
      content: '';
      position: absolute;
      bottom: -30%;
      left: -10%;
      width: 200px;
      height: 200px;
      background: radial-gradient(circle, rgba(40, 167, 69, 0.1) 0%, transparent 70%);
      border-radius: 50%;
    }
    
    .testimonial-bg::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(135deg, rgba(0, 123, 255, 0.05) 0%, rgba(40, 167, 69, 0.05) 100%);
    }
    
    .achievement-bg::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: radial-gradient(ellipse at center, rgba(40, 167, 69, 0.1) 0%, transparent 70%);
    }
    
    /* Section Badges */
    .section-badge {
      text-align: center;
    }
    
    .hero-badge {
      animation: bounceIn 1s ease-out;
    }
    
    @keyframes bounceIn {
      0% { transform: scale(0.3); opacity: 0; }
      50% { transform: scale(1.05); }
      70% { transform: scale(0.9); }
      100% { transform: scale(1); opacity: 1; }
    }
    
    /* Stat Cards in Hero */
    .stat-card {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 15px;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      transition: transform 0.3s ease;
    }
    
    .stat-card:hover {
      transform: translateY(-5px);
      background: rgba(255, 255, 255, 0.15);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
      .display-3 { font-size: 2.5rem; }
      .display-4 { font-size: 2rem; }
      .display-5 { font-size: 1.75rem; }
      
      .hero-stats { margin-top: 2rem !important; }
      .content-card { padding: 1.5rem; }
      .timeline-item { padding-left: 1.5rem; }
      
      .image-stack { height: 300px; }
      .facility-image-grid { height: 300px; }

      .programs-visual {
          height: 600px; /* Tambah tinggi container */
          position: relative;
      }
      
      .central-image {
        top: 20%;
        width: 100%;
        height: auto;
      }

      .card-1 {
        top: 40%;
        left: 0px;
        animation-delay: 0s;
      }
      
      .card-2 {
        top: 60%;
        left: 70px;
        animation-delay: 1s;
      }
      
      .card-3 {
        top: 80%;
        bottom: 0;
        left: 20px;
        animation-delay: 2s;
      }

      .action-card {
        width: 100%;
      }

      .backdrop-blur {
        width: 95%;
      }

      .facilities-showcase {
        margin-top: 50px; /* Tambahkan margin atas */
      }

      .feature-list {
        grid-template-columns: 1fr;
        gap: 0.5rem;
      }
      
      .countdown-timer {
        gap: 0.5rem;
      }
      
      .alumni-avatars {
        gap: -5px;
      }
      
      .alumni-avatar {
        width: 40px;
        height: 40px;
        margin-left: -5px;
      }
      
      .alumni-count {
        width: 40px;
        height: 40px;
        margin-left: -5px;
        font-size: 0.65rem;
      }
    }

    /* Tablet (iPad) */
    @media (min-width: 768px) and (max-width: 991px) {
      .programs-visual {
        height: 750px;
        position: relative;
      }
      
      .central-image {
        top: 25%;
        width: 90%;
        height: auto;
      }

      .card-1 {
        top: 50%;
        left: 10%;
        animation-delay: 0s;
      }
      
      .card-2 {
        top: 67%;
        left: 40%;
        animation-delay: 1s; 
      }
      
      .card-3 {
        top: 85%;
        left: 25%;
        animation-delay: 2s;
      }

      .action-card {
        width: 100%;
      }

      .backdrop-blur {
        width: 97%;
      }

      .facilities-showcase {
        margin-top: 50px; /* Tambahkan margin atas */
      }

    }
    
    /* Smooth Scrolling */
    html {
      scroll-behavior: smooth;
    }
    
    /* Counter Animation */
    .achievement-number {
      opacity: 0;
      animation: countUp 2s ease-out forwards;
    }
    
    @keyframes countUp {
      0% { opacity: 0; transform: translateY(20px); }
      100% { opacity: 1; transform: translateY(0); }
    }
    
    /* Swiper Custom Styles */
    .swiper-button-next,
    .swiper-button-prev {
      color: #007bff;
      background: white;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    .swiper-button-next:after,
    .swiper-button-prev:after {
      font-size: 1rem;
      font-weight: bold;
    }
    
    .swiper-pagination-bullet {
      background: #007bff;
      opacity: 0.3;
    }
    
    .swiper-pagination-bullet-active {
      opacity: 1;
      transform: scale(1.2);
    }
    
    /* Loading Animation */
    .section {
      opacity: 0;
      animation: fadeInUp 0.8s ease-out forwards;
    }
    
    .section:nth-child(even) {
      animation-delay: 0.2s;
    }
    
    .section:nth-child(odd) {
      animation-delay: 0.4s;
    }
    
    @keyframes fadeInUp {
      0% {
        opacity: 0;
        transform: translateY(30px);
      }
      100% {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    /* Accessibility */
    .btn:focus,
    .btn:focus-visible {
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
      outline: none;
    }
    
    /* Print Styles */
    @media print {
      .floating-shapes,
      .floating-elements,
      .cta-section,
      .swiper-button-next,
      .swiper-button-prev,
      .swiper-pagination {
        display: none !important;
      }
      
      .section {
        page-break-inside: avoid;
        margin-bottom: 2rem;
      }
    }
  </style>
@endsection