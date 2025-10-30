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
          @if($featuredNews->isNotEmpty())
          <div class="col-lg-8 mb-4" data-aos="fade-right">
            @php $firstNews = $featuredNews->first(); @endphp
            <article class="featured-article">
              <div class="article-image">
                <img src="{{ $firstNews->image_url }}" alt="{{ $firstNews->title }}" class="img-fluid rounded-4">
                <div class="article-overlay">
                  <div class="article-category">
                    @php
                      $badgeClass = [
                        'prestasi' => 'bg-success',
                        'kegiatan' => 'bg-warning',
                        'fasilitas' => 'bg-info',
                        'pendidikan' => 'bg-primary',
                        'alumni' => 'bg-secondary',
                        'umum' => 'bg-dark'
                      ][strtolower($firstNews->category)] ?? 'bg-primary';
                    @endphp
                    <span class="badge {{ $badgeClass }} rounded-3 py-1">{{ $firstNews->category }}</span>
                  </div>
                  <div class="article-date">
                    <i class="bi bi-calendar3 me-2"></i>{{ $firstNews->published_at->translatedFormat('d F Y') }}
                  </div>
                </div>
              </div>
              <div class="article-content">
                <h3 class="article-title">
                  <a href="{{ route('berita.detail', $firstNews->slug) }}" class="text-decoration-none">{{ $firstNews->title }}</a>
                </h3>
                <p class="article-excerpt">
                  {{ $firstNews->excerpt ?? Str::limit(strip_tags($firstNews->content), 150) }}
                </p>
                <div class="article-meta">
                  <div class="author-info">
                    <img src="{{ $firstNews->user->photo_path ?? 'https://ui-avatars.com/api/?name='.urlencode($firstNews->user->name).'&background=random' }}" alt="{{ $firstNews->user->name }}" class="author-avatar">
                    <div class="author-details">
                      <span class="author-name">{{ $firstNews->user->name }}</span>
                      <span class="author-role">{{ $firstNews->user->roles->first() ? $firstNews->user->roles->first()->name : 'Admin' }}</span>
                    </div>
                  </div>
                  <div class="article-stats">
                    <span class="stat-item"><i class="bi bi-eye me-1"></i>{{ number_format($firstNews->view_count) }}</span>
                    <span class="stat-item"><i class="bi bi-chat-left-text me-1"></i>{{ $firstNews->comments_count ?? 0 }}</span>
                  </div>
                </div>
              </div>
            </article>
          </div>
          @endif

          <!-- Side Articles -->
          <div class="col-lg-4" data-aos="fade-left" data-aos-delay="200">
            <div class="side-articles">
              @forelse($sideNews as $news)
              <div class="side-article mb-4">
                <div class="row g-3">
                  <div class="col-4">
                    <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="img-fluid rounded-3" style="height: 80px; object-fit: cover;">
                  </div>
                  <div class="col-8">
                    <div class="article-category mb-2">
                      @php
                        $badgeClass = [
                          'prestasi' => 'bg-success',
                          'kegiatan' => 'bg-warning',
                          'fasilitas' => 'bg-info',
                          'pendidikan' => 'bg-primary',
                          'alumni' => 'bg-secondary',
                          'umum' => 'bg-dark'
                        ][strtolower($news->category)] ?? 'bg-primary';
                      @endphp
                      <span class="badge {{ $badgeClass }} text-white rounded-3 py-1">{{ $news->category }}</span>
                    </div>
                    <h5 class="article-title" style="font-size: 0.9rem; line-height: 1.3;">
                      <a href="{{ route('berita.detail', $news->slug) }}" class="text-decoration-none">{{ Str::limit($news->title, 45) }}</a>
                    </h5>
                    <div class="d-flex justify-content-between align-items-center">
                      <div class="article-meta small text-muted">
                        <i class="bi bi-calendar3 me-1"></i>{{ $news->published_at->format('d M Y') }}
                      </div>
                      <div class="article-views small text-muted">
                        <i class="bi bi-eye me-1"></i>{{ number_format($news->view_count) }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @empty
                <div class="alert alert-info small">
                  <i class="bi bi-info-circle me-1"></i> Belum ada berita terbaru
                </div>
              @endforelse

              @if($trendingNews->isNotEmpty())
              <div class="trending-topics">
                <h6 class="fw-bold mb-3">
                  <i class="bi bi-fire text-danger me-2"></i>Berita Populer
                </h6>
                <div class="trending-list">
                  @foreach($trendingNews as $index => $news)
                  <div class="trending-item">
                    <span class="trending-number text-dark">{{ $index + 1 }}</span>
                    <div>
                      <a href="{{ route('berita.detail', $news->slug) }}" class="trending-link" title="{{ $news->title }}">
                        {{ Str::limit($news->title, 35) }}
                      </a>
                      <span class="badge bg-secondary bg-opacity-10 text-muted small ms-2">{{ number_format($news->view_count) }}x</span>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>
              @else
              <div class="trending-topics">
                <h6 class="fw-bold mb-3">
                  <i class="bi bi-fire text-danger me-2"></i>Berita Populer
                </h6>
                <div class="alert alert-info small">
                  <i class="bi bi-info-circle me-1"></i> Belum ada data kunjungan berita
                </div>
              </div>
              @endif
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
              <button class="filter-btn {{ !request('category') ? 'active' : '' }}" data-category="">
                <i class="bi bi-grid-3x3-gap me-2"></i>Semua Berita
              </button>
              @foreach($categories as $category)
                @php
                  // Map category to icon
                  $icons = [
                    'Prestasi' => 'trophy',
                    'Kegiatan' => 'calendar-event',
                    'Fasilitas' => 'building',
                    'Pendidikan' => 'book',
                    'Alumni' => 'people',
                    'Umum' => 'newspaper'
                  ];
                  $icon = $icons[$category] ?? 'newspaper';
                @endphp
                <button class="filter-btn {{ request('category') == strtolower($category) ? 'active' : '' }}" data-category="{{ strtolower($category) }}">
                  <i class="bi bi-{{ $icon }} me-2"></i>{{ $category }}
                </button>
              @endforeach
            </div>
          </div>
        </div>

        <!-- News Grid -->
        <div class="row g-4" id="news-grid">
          @include('home.partials.news-grid', ['latestNews' => $latestNews])
        </div>

        <!-- Pagination -->
        @if($latestNews->hasPages())
        <div class="row mt-5">
          <div class="col-12">
            <nav aria-label="News pagination">
              <ul class="pagination justify-content-center">
                {{-- Previous Page Link --}}
                @if($latestNews->onFirstPage())
                  <li class="page-item disabled">
                    <span class="page-link"><i class="bi bi-chevron-left"></i></span>
                  </li>
                @else
                  <li class="page-item">
                    <a class="page-link" href="{{ $latestNews->appends(request()->query())->previousPageUrl() }}" aria-label="Previous">
                      <i class="bi bi-chevron-left"></i>
                    </a>
                  </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach($latestNews->getUrlRange(1, $latestNews->lastPage()) as $page => $url)
                  @if($page == $latestNews->currentPage())
                    <li class="page-item active" aria-current="page">
                      <span class="page-link">{{ $page }}</span>
                    </li>
                  @else
                    <li class="page-item">
                      <a class="page-link" href="{{ $latestNews->appends(request()->query())->url($page) }}">{{ $page }}</a>
                    </li>
                  @endif
                @endforeach

                {{-- Next Page Link --}}
                @if($latestNews->hasMorePages())
                  <li class="page-item">
                    <a class="page-link" href="{{ $latestNews->appends(request()->query())->nextPageUrl() }}" aria-label="Next">
                      <i class="bi bi-chevron-right"></i>
                    </a>
                  </li>
                @else
                  <li class="page-item disabled">
                    <span class="page-link"><i class="bi bi-chevron-right"></i></span>
                  </li>
                @endif
              </ul>
            </nav>
          </div>
        </div>
        @endif
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
                      <input type="email" class="form-control border-0" placeholder="Masukkan email Anda" required>
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
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Handle category filter click
      $('.filter-btn').on('click', function(e) {
        e.preventDefault();
        
        // Update active button
        $('.filter-btn').removeClass('active');
        $(this).addClass('active');
        
        const category = $(this).data('category');
        const url = category ? "/berita/kategori/" + category : "/berita";
        
        // Show loading state
        $('#news-grid').html('<div class="col-12 text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        
        // Update URL without page reload
        const newUrl = category ? '{{ url()->current() }}?category=' + category : '{{ route('berita.index') }}';
        window.history.pushState({ path: newUrl }, '', newUrl);
        
        // Load news by category
        loadNews(url);
      });
      
      // Handle browser back/forward buttons
      window.onpopstate = function(event) {
        if (event.state && event.state.path) {
          window.location.href = event.state.path;
        }
      };
      
      // Load more news when clicking the load more button
      $(document).on('click', '.load-more', function() {
        const $button = $(this);
        const nextPage = $button.data('next-page');
        const lastPage = $button.data('last-page');
        const currentUrl = window.location.pathname + window.location.search;
        const isCategoryPage = currentUrl.includes('/kategori/');
        
        if (nextPage > lastPage) {
          $button.prop('disabled', true).text('Tidak ada berita lagi');
          return;
        }
        
        $button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memuat...');
        
        let url = currentUrl + (currentUrl.includes('?') ? '&' : '?') + 'page=' + nextPage;
        
        if (isCategoryPage) {
          // For category pages, use the byCategory method
          const category = currentUrl.split('/').pop().split('?')[0];
          url = '/berita/kategori/' + category + '?page=' + nextPage;
        }
        
        $.ajax({
          url: url,
          type: 'GET',
          dataType: 'json',
          success: function(response) {
            if (response.html) {
              // Remove the load more button
              $('.load-more-container').remove();
              
              // Append new news items
              $('#news-grid').append($(response.html));
              
              // If there are more pages, update the load more button
              if (response.nextPageUrl) {
                $button.data('next-page', response.currentPage + 1);
                $button.data('last-page', response.lastPage);
                $button.prop('disabled', false).html('Muat Lebih Banyak');
              } else {
                $button.prop('disabled', true).text('Tidak ada berita lagi');
              }
            }
          },
          error: function(xhr) {
            console.error('Error loading more news:', xhr);
            $button.prop('disabled', false).html('Muat Lebih Banyak <i class="bi bi-arrow-clockwise"></i>');
          }
        });
      });
      
      // Function to load news by URL
      function loadNews(url) {
        $.ajax({
          url: url,
          type: 'GET',
          dataType: 'json',
          success: function(response) {
            if (response.html) {
              $('#news-grid').html(response.html);
              
              // Smooth scroll to the news grid
              $('html, body').animate({
                scrollTop: $('#news-grid').offset().top - 100
              }, 500);
            }
          },
          error: function(xhr) {
            console.error('Error loading news:', xhr);
            $('#news-grid').html('<div class="col-12 text-center py-5"><div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-2"></i> Gagal memuat berita. Silakan coba lagi.</div></div>');
          }
        });
      }
      // Elements
      const filterButtons = document.querySelectorAll('.filter-btn');
      const newsItems = document.querySelectorAll('.news-item'); // Menggunakan class news-item yang sesuai dengan struktur HTML
      const loadMoreBtn = document.getElementById('loadMore');
      let visibleItems = 6;
      const itemsPerLoad = 3;
      let currentFilter = 'all';

      // Simple filter function
      function filterNews(category) {
        currentFilter = category;
        let count = 0;
        
        newsItems.forEach(item => {
          const itemCategory = item.getAttribute('data-category') ? item.getAttribute('data-category').toLowerCase() : '';
          
          if (category === 'all' || itemCategory === category.toLowerCase()) {
            item.style.display = ''; // Mengosongkan style display untuk mengembalikan ke default
            count++;
          } else {
            item.style.display = 'none';
          }
        });

        // Update load more button
        updateLoadMoreButton();
      }

      // Filter button click handler
      filterButtons.forEach(button => {
        button.addEventListener('click', function() {
          // Update active button
          filterButtons.forEach(btn => btn.classList.remove('active'));
          this.classList.add('active');
          
          // Get filter value
          const filterValue = this.getAttribute('data-filter');
          
          // Apply filter
          filterNews(filterValue);
          
          // Smooth scroll to news grid
          const newsGrid = document.getElementById('news-grid');
          if (newsGrid) {
            newsGrid.scrollIntoView({ 
              behavior: 'smooth',
              block: 'start' 
            });
          }
        });
      });

      // Initialize
      if (filterButtons.length > 0) {
        filterButtons[0].classList.add('active');
      }
      filterNews('all');

      // Newsletter Form
      const newsletterForm = document.querySelector('.newsletter-form form');
      if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
          e.preventDefault();
          const emailInput = this.querySelector('input[type="email"]');
          if (emailInput && emailInput.value) {
            alert('Terima kasih! Anda telah berlangganan newsletter kami.');
            this.reset();
          }
        });
      }

      // Load More Button functionality
      if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
          this.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Memuat...';
          this.disabled = true;
          
          // Simulate loading more content
          setTimeout(() => {
            visibleItems += itemsPerLoad;
            filterNews(currentFilter);
            
            this.innerHTML = 'Muat Lebih Banyak';
            this.disabled = false;
            
            // Hide button if all items are shown
            updateLoadMoreButton();
          }, 500);
        });
      }

      // Update load more button visibility
      function updateLoadMoreButton() {
        if (!loadMoreBtn) return;
        
        const visibleCount = Array.from(newsItems).filter(item => 
          item.style.display !== 'none'
        ).length;
        
        const totalCount = currentFilter === 'all' 
          ? newsItems.length 
          : Array.from(newsItems).filter(item => 
              item.getAttribute('data-category') && 
              item.getAttribute('data-category').toLowerCase() === currentFilter.toLowerCase()
            ).length;
        
        loadMoreBtn.style.display = visibleCount < totalCount ? 'inline-flex' : 'none';
      }
      
      // Initialize load more button
      updateLoadMoreButton();
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