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
                  Berita Sekolah
                </h1>
                <p class="lead mb-5 text-muted" data-aos="fade-up" data-aos-delay="200">
                  Informasi Terkini Seputar Kegiatan dan Prestasi Sekolah XYZ
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ======= Featured News Section ======= -->
    <section class="section pb-0 position-relative">
      <div class="section-bg-pattern"></div>
      <div class="container">
        <div class="row mb-5">
          <div class="col-12" data-aos="fade-up">
            <div class="section-badge mb-3 text-center">
              <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
                <i class="bi bi-star me-2"></i>Berita Utama
              </span>
            </div>
            <h2 class="display-5 fw-bold text-center mb-4">
              Sorotan <span class="text-primary">Terkini</span>
            </h2>
          </div>
        </div>

        <div class="row">
          <!-- Main Featured Article -->
          <div class="col-lg-8 mb-4" data-aos="fade-right">
            <article class="featured-article">
              <div class="article-image">
                <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" alt="Siswa SMA XYZ Raih Juara 1 Olimpiade Sains Nasional" class="img-fluid rounded-4">
                <div class="article-overlay">
                  <div class="article-category">
                    <span class="badge bg-success">Prestasi</span>
                  </div>
                  <div class="article-date">
                    <i class="bi bi-calendar3 me-2"></i>15 Januari 2024
                  </div>
                </div>
              </div>
              <div class="article-content">
                <h3 class="article-title">
                  <a href="{{ route('berita.detail') }}" class="text-decoration-none">Siswa SMA XYZ Raih Juara 1 Olimpiade Sains Nasional 2024</a>
                </h3>
                <p class="article-excerpt">
                  Prestasi membanggakan kembali ditorehkan siswa Sekolah XYZ. Ahmad Rizki Pratama, siswa kelas XI IPA berhasil meraih juara 1 dalam Olimpiade Sains Nasional bidang Fisika yang diselenggarakan di Jakarta...
                </p>
                <div class="article-meta">
                  <div class="author-info">
                    <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" alt="Author" class="author-avatar">
                    <div class="author-details">
                      <span class="author-name">Drs. Budi Santoso</span>
                      <span class="author-role">Kepala Sekolah</span>
                    </div>
                  </div>
                  <div class="article-stats">
                    <span class="stat-item"><i class="bi bi-eye me-1"></i>1,234</span>
                    <span class="stat-item"><i class="bi bi-heart me-1"></i>89</span>
                    <span class="stat-item"><i class="bi bi-share me-1"></i>45</span>
                  </div>
                </div>
              </div>
            </article>
          </div>

          <!-- Side Articles -->
          <div class="col-lg-4" data-aos="fade-left" data-aos-delay="200">
            <div class="side-articles">
              <div class="side-article mb-4">
                <div class="row g-3">
                  <div class="col-4">
                    <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Pembukaan Lab Komputer Baru" class="img-fluid rounded-3">
                  </div>
                  <div class="col-8">
                    <div class="article-category mb-2">
                      <span class="badge bg-info bg-opacity-20 text-white">Fasilitas</span>
                    </div>
                    <h5 class="article-title">
                      <a href="#" class="text-decoration-none">Pembukaan Lab Komputer Baru dengan Teknologi Terdepan</a>
                    </h5>
                    <div class="article-meta small text-muted">
                      <i class="bi bi-calendar3 me-2"></i>12 Januari 2024
                    </div>
                  </div>
                </div>
              </div>

              <div class="side-article mb-4">
                <div class="row g-3">
                  <div class="col-4">
                    <img src="https://images.unsplash.com/photo-1544717297-fa95b6ee9643?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Kegiatan Bakti Sosial" class="img-fluid rounded-3">
                  </div>
                  <div class="col-8">
                    <div class="article-category mb-2">
                      <span class="badge bg-warning bg-opacity-20 text-white">Kegiatan</span>
                    </div>
                    <h5 class="article-title">
                      <a href="#" class="text-decoration-none">Bakti Sosial Siswa XYZ untuk Korban Bencana Alam</a>
                    </h5>
                    <div class="article-meta small text-muted">
                      <i class="bi bi-calendar3 me-2"></i>10 Januari 2024
                    </div>
                  </div>
                </div>
              </div>

              <div class="side-article mb-4">
                <div class="row g-3">
                  <div class="col-4">
                    <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&q=80" alt="Workshop Guru" class="img-fluid rounded-3">
                  </div>
                  <div class="col-8">
                    <div class="article-category mb-2">
                      <span class="badge bg-success bg-opacity-20 text-white">Pendidikan</span>
                    </div>
                    <h5 class="article-title">
                      <a href="#" class="text-decoration-none">Workshop Pengembangan Kurikulum Digital</a>
                    </h5>
                    <div class="article-meta small text-muted">
                      <i class="bi bi-calendar3 me-2"></i>8 Januari 2024
                    </div>
                  </div>
                </div>
              </div>

              <div class="trending-topics">
                <h6 class="fw-bold mb-3">
                  <i class="bi bi-fire text-danger me-2"></i>Topik Trending
                </h6>
                <div class="trending-list">
                  <div class="trending-item">
                    <span class="trending-number text-black">1</span>
                    <a href="#" class="trending-link">Pendaftaran Siswa Baru 2024/2025</a>
                  </div>
                  <div class="trending-item">
                    <span class="trending-number text-black">2</span>
                    <a href="#" class="trending-link">Program Beasiswa Prestasi</a>
                  </div>
                  <div class="trending-item">
                    <span class="trending-number text-black">3</span>
                    <a href="#" class="trending-link">Ekstrakurikuler Robotika</a>
                  </div>
                  <div class="trending-item">
                    <span class="trending-number text-black">4</span>
                    <a href="#" class="trending-link">Study Tour ke Jepang</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ======= News Categories Section ======= -->
    <section class="section bg-light">
      <div class="container">
        <div class="row mb-5">
          <div class="col-12 text-center" data-aos="fade-up">
            <div class="section-badge mb-3">
              <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                <i class="bi bi-grid me-2"></i>Kategori Berita
              </span>
            </div>
            <h2 class="display-5 fw-bold mb-4">
              Berita Berdasarkan <span class="text-success">Kategori</span>
            </h2>
          </div>
        </div>

        <!-- Category Filter -->
        <div class="row mb-5" data-aos="fade-up" data-aos-delay="100">
          <div class="col-12">
            <div class="category-filter text-center">
              <button class="filter-btn active" data-filter="all">
                <i class="bi bi-grid-3x3-gap me-2"></i>Semua Berita
              </button>
              <button class="filter-btn" data-filter="prestasi">
                <i class="bi bi-trophy me-2"></i>Prestasi
              </button>
              <button class="filter-btn" data-filter="kegiatan">
                <i class="bi bi-calendar-event me-2"></i>Kegiatan
              </button>
              <button class="filter-btn" data-filter="fasilitas">
                <i class="bi bi-building me-2"></i>Fasilitas
              </button>
              <button class="filter-btn" data-filter="pendidikan">
                <i class="bi bi-book me-2"></i>Pendidikan
              </button>
              <button class="filter-btn" data-filter="alumni">
                <i class="bi bi-people me-2"></i>Alumni
              </button>
            </div>
          </div>
        </div>

        <!-- News Grid -->
        <div class="row g-4" id="news-grid">
          <!-- News Item 1 -->
          <div class="col-lg-4 col-md-6 news-item" data-category="prestasi" data-aos="fade-up">
            <article class="news-card">
              <div class="news-image">
                <img src="https://images.unsplash.com/photo-1606127486207-9ba386ba6b49?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Juara Debat Bahasa Inggris" class="img-fluid">
                <div class="news-overlay">
                  <div class="news-category">
                    <span class="badge bg-success">Prestasi</span>
                  </div>
                </div>
              </div>
              <div class="news-content">
                <div class="news-meta">
                  <span class="news-date"><i class="bi bi-calendar3 me-1"></i>14 Jan 2024</span>
                  <span class="news-author"><i class="bi bi-person me-1"></i>Tim Redaksi</span>
                </div>
                <h4 class="news-title">
                  <a href="#" class="text-decoration-none">Tim Debat Bahasa Inggris Raih Juara Nasional</a>
                </h4>
                <p class="news-excerpt">
                  Tim debat Sekolah XYZ berhasil meraih juara 1 dalam kompetisi debat bahasa Inggris tingkat nasional...
                </p>
                <div class="news-footer">
                  <a href="#" class="read-more">
                    Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
                  </a>
                  <div class="news-stats">
                    <span><i class="bi bi-eye"></i> 856</span>
                    <span><i class="bi bi-heart"></i> 42</span>
                  </div>
                </div>
              </div>
            </article>
          </div>

          <!-- News Item 2 -->
          <div class="col-lg-4 col-md-6 news-item" data-category="kegiatan" data-aos="fade-up" data-aos-delay="100">
            <article class="news-card">
              <div class="news-image">
                <img src="https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Festival Seni Budaya" class="img-fluid">
                <div class="news-overlay">
                  <div class="news-category">
                    <span class="badge bg-warning">Kegiatan</span>
                  </div>
                </div>
              </div>
              <div class="news-content">
                <div class="news-meta">
                  <span class="news-date"><i class="bi bi-calendar3 me-1"></i>13 Jan 2024</span>
                  <span class="news-author"><i class="bi bi-person me-1"></i>Humas Sekolah</span>
                </div>
                <h4 class="news-title">
                  <a href="#" class="text-decoration-none">Festival Seni Budaya Nusantara 2024</a>
                </h4>
                <p class="news-excerpt">
                  Sekolah XYZ menggelar festival seni budaya dengan menampilkan beragam kesenian tradisional Indonesia...
                </p>
                <div class="news-footer">
                  <a href="#" class="read-more">
                    Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
                  </a>
                  <div class="news-stats">
                    <span><i class="bi bi-eye"></i> 1,245</span>
                    <span><i class="bi bi-heart"></i> 78</span>
                  </div>
                </div>
              </div>
            </article>
          </div>

          <!-- News Item 3 -->
          <div class="col-lg-4 col-md-6 news-item" data-category="fasilitas" data-aos="fade-up" data-aos-delay="200">
            <article class="news-card">
              <div class="news-image">
                <img src="https://images.unsplash.com/photo-1581092160562-40aa08e78837?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Perpustakaan Digital" class="img-fluid">
                <div class="news-overlay">
                  <div class="news-category">
                    <span class="badge bg-info">Fasilitas</span>
                  </div>
                </div>
              </div>
              <div class="news-content">
                <div class="news-meta">
                  <span class="news-date"><i class="bi bi-calendar3 me-1"></i>11 Jan 2024</span>
                  <span class="news-author"><i class="bi bi-person me-1"></i>Admin</span>
                </div>
                <h4 class="news-title">
                  <a href="#" class="text-decoration-none">Perpustakaan Digital Berteknologi AI Diluncurkan</a>
                </h4>
                <p class="news-excerpt">
                  Sekolah XYZ meluncurkan perpustakaan digital canggih dengan teknologi AI untuk membantu siswa belajar...
                </p>
                <div class="news-footer">
                  <a href="#" class="read-more">
                    Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
                  </a>
                  <div class="news-stats">
                    <span><i class="bi bi-eye"></i> 967</span>
                    <span><i class="bi bi-heart"></i> 53</span>
                  </div>
                </div>
              </div>
            </article>
          </div>

          <!-- News Item 4 -->
          <div class="col-lg-4 col-md-6 news-item" data-category="pendidikan" data-aos="fade-up">
            <article class="news-card">
              <div class="news-image">
                <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Kurikulum Baru" class="img-fluid">
                <div class="news-overlay">
                  <div class="news-category">
                    <span class="badge bg-primary">Pendidikan</span>
                  </div>
                </div>
              </div>
              <div class="news-content">
                <div class="news-meta">
                  <span class="news-date"><i class="bi bi-calendar3 me-1"></i>9 Jan 2024</span>
                  <span class="news-author"><i class="bi bi-person me-1"></i>Kurikulum</span>
                </div>
                <h4 class="news-title">
                  <a href="#" class="text-decoration-none">Implementasi Kurikulum Merdeka di Sekolah XYZ</a>
                </h4>
                <p class="news-excerpt">
                  Sekolah XYZ resmi menerapkan Kurikulum Merdeka untuk meningkatkan kualitas pembelajaran siswa...
                </p>
                <div class="news-footer">
                  <a href="#" class="read-more">
                    Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
                  </a>
                  <div class="news-stats">
                    <span><i class="bi bi-eye"></i> 1,456</span>
                    <span><i class="bi bi-heart"></i> 89</span>
                  </div>
                </div>
              </div>
            </article>
          </div>

          <!-- News Item 5 -->
          <div class="col-lg-4 col-md-6 news-item" data-category="alumni" data-aos="fade-up" data-aos-delay="100">
            <article class="news-card">
              <div class="news-image">
                <img src="https://images.unsplash.com/photo-1531482615713-2afd69097998?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Alumni Success Story" class="img-fluid">
                <div class="news-overlay">
                  <div class="news-category">
                    <span class="badge bg-secondary">Alumni</span>
                  </div>
                </div>
              </div>
              <div class="news-content">
                <div class="news-meta">
                  <span class="news-date"><i class="bi bi-calendar3 me-1"></i>7 Jan 2024</span>
                  <span class="news-author"><i class="bi bi-person me-1"></i>Alumni Network</span>
                </div>
                <h4 class="news-title">
                  <a href="#" class="text-decoration-none">Alumni XYZ Raih Beasiswa S2 di Harvard University</a>
                </h4>
                <p class="news-excerpt">
                  Sarah Putri, alumni angkatan 2020, berhasil meraih beasiswa penuh untuk melanjutkan studi S2 di Harvard...
                </p>
                <div class="news-footer">
                  <a href="#" class="read-more">
                    Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
                  </a>
                  <div class="news-stats">
                    <span><i class="bi bi-eye"></i> 2,134</span>
                    <span><i class="bi bi-heart"></i> 156</span>
                  </div>
                </div>
              </div>
            </article>
          </div>

          <!-- News Item 6 -->
          <div class="col-lg-4 col-md-6 news-item" data-category="prestasi" data-aos="fade-up" data-aos-delay="200">
            <article class="news-card">
              <div class="news-image">
                <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Lomba Robotika" class="img-fluid">
                <div class="news-overlay">
                  <div class="news-category">
                    <span class="badge bg-success">Prestasi</span>
                  </div>
                </div>
              </div>
              <div class="news-content">
                <div class="news-meta">
                  <span class="news-date"><i class="bi bi-calendar3 me-1"></i>5 Jan 2024</span>
                  <span class="news-author"><i class="bi bi-person me-1"></i>STEM Club</span>
                </div>
                <h4 class="news-title">
                  <a href="#" class="text-decoration-none">Juara 1 Kompetisi Robotika Tingkat Provinsi</a>
                </h4>
                <p class="news-excerpt">
                  Ekstrakurikuler Robotika Sekolah XYZ berhasil menyabet juara 1 dalam kompetisi robotika provinsi...
                </p>
                <div class="news-footer">
                  <a href="#" class="read-more">
                    Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
                  </a>
                  <div class="news-stats">
                    <span><i class="bi bi-eye"></i> 1,678</span>
                    <span><i class="bi bi-heart"></i> 94</span>
                  </div>
                </div>
              </div>
            </article>
          </div>
        </div>

        <!-- Load More Button -->
        <div class="row mt-5">
          <div class="col-12 text-center" data-aos="fade-up">
            <button class="btn btn-outline-primary btn-lg rounded-pill px-5" id="loadMore">
              <i class="bi bi-arrow-down-circle me-2"></i>Muat Lebih Banyak
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- ======= Newsletter Section ======= -->
    <section class="section newsletter-section position-relative overflow-hidden">
      <div class="newsletter-bg"></div>
      <div class="floating-elements">
        <div class="floating-shape shape-1"></div>
        <div class="floating-shape shape-2"></div>
      </div>
      
      <div class="container position-relative">
        <div class="row justify-content-center">
          <div class="col-lg-8 text-center" data-aos="fade-up">
            <div class="newsletter-content">
              <div class="newsletter-icon mb-4">
                <i class="bi bi-envelope-heart text-white"></i>
              </div>
              <h2 class="display-5 fw-bold text-white mb-4">
                Jangan Lewatkan <span class="text-warning">Berita Terbaru</span>
              </h2>
              <p class="lead text-white-75 mb-5">
                Berlangganan newsletter kami dan dapatkan informasi terkini seputar kegiatan, prestasi, dan pengumuman penting dari Sekolah XYZ langsung di email Anda.
              </p>
              
              <div class="newsletter-form">
                <form class="row g-3 justify-content-center">
                  <div class="col-md-6">
                    <div class="input-group input-group-lg">
                      <span class="input-group-text bg-white border-0">
                        <i class="bi bi-envelope text-muted"></i>
                      </span>
                      <input type="email" class="form-control border-0" placeholder="Masukkan email Anda">
                    </div>
                  </div>
                  <div class="col-md-auto">
                    <button type="submit" class="btn btn-warning btn-lg px-4 rounded-pill">
                      <i class="bi bi-send me-2"></i>Berlangganan
                    </button>
                  </div>
                </form>
                <p class="small text-white-50 mt-3">
                  <i class="bi bi-shield-check me-1"></i>
                  Email Anda aman bersama kami. Tidak ada spam!
                </p>
              </div>
              
              <div class="newsletter-stats mt-5">
                <div class="row g-4">
                  <div class="col-md-4">
                    <div class="stat-item">
                      <h4 class="text-warning fw-bold">5,000+</h4>
                      <p class="text-white-75 small mb-0">Subscriber Aktif</p>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="stat-item">
                      <h4 class="text-warning fw-bold">500+</h4>
                      <p class="text-white-75 small mb-0">Artikel Diterbitkan</p>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="stat-item">
                      <h4 class="text-warning fw-bold">4.8/5</h4>
                      <p class="text-white-75 small mb-0">Rating Newsletter</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <!-- Custom Styles -->
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

    .hero-breadcrumb .breadcrumb {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border-radius: 25px;
      padding: 0.75rem 1.5rem;
    }

    .hero-breadcrumb .breadcrumb-item a {
      color: rgba(255, 255, 255, 0.8);
      text-decoration: none;
    }

    .hero-breadcrumb .breadcrumb-item.active {
      color: white;
    }

    /* Featured Article */
    .featured-article {
      background: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease;
    }

    .featured-article:hover {
      transform: translateY(-10px);
    }

    .article-image {
      position: relative;
      height: 300px;
      overflow: hidden;
    }

    .article-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .featured-article:hover .article-image img {
      transform: scale(1.05);
    }

    .article-overlay {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(transparent, rgba(0, 0, 0, 0.3));
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
      padding: 1.5rem;
    }

    .article-category .badge {
      font-size: 0.9rem;
      padding: 0.5rem 1rem;
      border-radius: 15px;
    }

    .article-date {
      color: white;
      font-weight: 500;
      background: rgba(0, 0, 0, 0.3);
      padding: 0.5rem 1rem;
      border-radius: 15px;
      backdrop-filter: blur(10px);
    }

    .article-content {
      padding: 2rem;
    }

    .article-title {
      font-size: 1.5rem;
      font-weight: 700;
      line-height: 1.3;
      margin-bottom: 1rem;
    }

    .article-title a {
      color: #333;
      transition: color 0.3s ease;
    }

    .article-title a:hover {
      color: var(--primary-color);
    }

    .article-excerpt {
      color: #666;
      line-height: 1.6;
      margin-bottom: 1.5rem;
    }

    .article-meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .author-info {
      display: flex;
      align-items: center;
    }

    .author-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 0.75rem;
    }

    .author-details {
      display: flex;
      flex-direction: column;
    }

    .author-name {
      font-weight: 600;
      font-size: 0.9rem;
      color: #333;
    }

    .author-role {
      font-size: 0.8rem;
      color: #666;
    }

    .article-stats {
      display: flex;
      gap: 1rem;
    }

    .stat-item {
      color: #666;
      font-size: 0.9rem;
    }

    /* Side Articles */
    .side-articles {
      background: white;
      border-radius: 20px;
      padding: 2rem;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
      height: fit-content;
    }

    .side-article {
      padding-bottom: 1.5rem;
      border-bottom: 1px solid #eee;
    }

    .side-article:last-child {
      border-bottom: none;
      margin-bottom: 0;
      padding-bottom: 0;
    }

    .side-article .article-title {
      font-size: 1rem;
      font-weight: 600;
      line-height: 1.4;
      margin-bottom: 0.5rem;
    }

    .side-article .article-title a {
      color: #333;
    }

    .side-article .article-title a:hover {
      color: var(--primary-color);
    }

    /* Trending Topics */
    .trending-topics {
      margin-top: 2rem;
      padding-top: 2rem;
      border-top: 2px solid #f8f9fa;
    }

    .trending-list {
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .trending-item {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .trending-number {
      background: var(--primary-color);
      color: white;
      width: 30px;
      height: 30px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 0.9rem;
      flex-shrink: 0;
    }

    .trending-link {
      color: #333;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s ease;
    }

    .trending-link:hover {
      color: var(--primary-color);
    }

    /* Category Filter */
    .category-filter {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .filter-btn {
      background: white;
      border: 2px solid #e9ecef;
      color: #666;
      padding: 0.75rem 1.5rem;
      border-radius: 25px;
      font-weight: 500;
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .filter-btn:hover,
    .filter-btn.active {
      background: var(--primary-color);
      border-color: var(--primary-color);
      color: black;
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
    }

    /* News Cards */
    .news-card {
      background: white;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      height: 100%;
    }

    .news-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }

    .news-image {
      position: relative;
      height: 200px;
      overflow: hidden;
    }

    .news-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.3s ease;
    }

    .news-card:hover .news-image img {
      transform: scale(1.1);
    }

    .news-overlay {
      position: absolute;
      top: 1rem;
      left: 1rem;
    }

    .news-content {
      padding: 1.5rem;
      display: flex;
      flex-direction: column;
      height: calc(100% - 200px);
    }

    .news-meta {
      display: flex;
      justify-content: space-between;
      margin-bottom: 1rem;
      font-size: 0.85rem;
      color: #666;
    }

    .news-title {
      font-size: 1.1rem;
      font-weight: 700;
      line-height: 1.4;
      margin-bottom: 1rem;
      flex-grow: 1;
    }

    .news-title a {
      color: #333;
      text-decoration: none;
      transition: color 0.3s ease;
    }

    .news-title a:hover {
      color: var(--primary-color);
    }

    .news-excerpt {
      color: #666;
      line-height: 1.6;
      margin-bottom: 1.5rem;
      display: -webkit-box;
      -webkit-line-clamp: 3;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .news-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: auto;
    }

    .read-more {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 600;
      font-size: 0.9rem;
      transition: all 0.3s ease;
    }

    .read-more:hover {
      color: var(--primary-color);
      transform: translateX(5px);
    }

    .news-stats {
      display: flex;
      gap: 1rem;
      font-size: 0.85rem;
      color: #666;
    }

    .news-stats span {
      display: flex;
      align-items: center;
      gap: 0.25rem;
    }

    /* Newsletter Section */
    .newsletter-section {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      position: relative;
    }

    .newsletter-bg::before {
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
      animation-delay: 4s;
    }

    @keyframes floatShape {
      0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.7; }
      50% { transform: translateY(-30px) rotate(180deg); opacity: 1; }
    }

    .newsletter-icon {
      font-size: 4rem;
    }

    .newsletter-form .input-group {
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      border-radius: 50px;
      overflow: hidden;
    }

    .newsletter-form .form-control {
      padding: 1rem 1.5rem;
      font-size: 1.1rem;
    }

    .newsletter-form .form-control:focus {
      box-shadow: none;
      border-color: transparent;
    }

    .newsletter-stats {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      padding: 2rem;
      backdrop-filter: blur(10px);
    }

    .stat-item {
      text-align: center;
    }

    /* Section Background */
    .section-bg-pattern::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: linear-gradient(45deg, transparent 40%, rgba(0, 123, 255, 0.05) 50%, transparent 60%);
    }

    /* Section Badge */
    .section-badge {
      margin-bottom: 1rem;
    }

    /* Load More Button */
    #loadMore {
      transition: all 0.3s ease;
    }

    #loadMore:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .display-3 { font-size: 2.5rem; }
      .display-5 { font-size: 1.75rem; }
      
      .article-content { padding: 1.5rem; }
      .side-articles { padding: 1.5rem; }
      .news-content { padding: 1.25rem; }
      
      .category-filter {
        justify-content: flex-start;
        overflow-x: auto;
        padding-bottom: 1rem;
      }
      
      .filter-btn {
        flex-shrink: 0;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
      }
      
      .article-meta {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
      }
      
      .news-footer {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
      }
      
      .newsletter-form .row {
        gap: 1rem;
      }
      
      .newsletter-form .col-md-6,
      .newsletter-form .col-md-auto {
        width: 100%;
      }
    }

    /* Animation on scroll */
    .news-item {
      opacity: 0;
      transform: translateY(30px);
      animation: fadeInUp 0.6s ease-out forwards;
    }

    @keyframes fadeInUp {
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Filter Animation */
    .news-item.filtered-out {
      display: none;
    }

    /* Smooth transitions */
    * {
      transition: all 0.3s ease;
    }
  </style>

  <!-- JavaScript -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Category Filter
      const filterBtns = document.querySelectorAll('.filter-btn');
      const newsItems = document.querySelectorAll('.news-item');

      filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
          // Remove active class from all buttons
          filterBtns.forEach(b => b.classList.remove('active'));
          // Add active class to clicked button
          this.classList.add('active');

          const filterValue = this.getAttribute('data-filter');

          newsItems.forEach(item => {
            if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
              item.style.display = 'block';
              item.classList.remove('filtered-out');
            } else {
              item.style.display = 'none';
              item.classList.add('filtered-out');
            }
          });
        });
      });

      // Newsletter Form
      const newsletterForm = document.querySelector('.newsletter-form form');
      newsletterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const email = this.querySelector('input[type="email"]').value;
        if (email) {
          alert('Terima kasih! Anda telah berlangganan newsletter kami.');
          this.reset();
        }
      });

      // Load More Button
      const loadMoreBtn = document.getElementById('loadMore');
      loadMoreBtn.addEventListener('click', function() {
        // Simulate loading more content
        this.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Memuat...';
        this.disabled = true;
        
        setTimeout(() => {
          this.innerHTML = '<i class="bi bi-check-circle me-2"></i>Semua berita telah dimuat';
          this.classList.replace('btn-outline-primary', 'btn-success');
        }, 2000);
      });

      // Smooth scroll for breadcrumb
      document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
          e.preventDefault();
          const target = document.querySelector(this.getAttribute('href'));
          if (target) {
            target.scrollIntoView({
              behavior: 'smooth',
              block: 'start'
            });
          }
        });
      });
    });
  </script>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <!-- AOS JS -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({
      duration: 1000,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    });
  </script>

@endsection