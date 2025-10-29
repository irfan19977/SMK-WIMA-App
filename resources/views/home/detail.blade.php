@extends('home.layouts.app')

@section('content')
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
            <div class="article-category-hero mb-3" data-aos="fade-up" data-aos-delay="200">
              <span class="badge bg-success bg-opacity-20 text-white px-3 py-2 rounded-pill">
                <i class="bi bi-{{ $news->category === 'Prestasi' ? 'trophy' : ($news->category === 'Berita' ? 'newspaper' : 'info-circle') }} me-2"></i>{{ $news->category }}
              </span>
            </div>
            <h1 class="display-4 fw-bold mb-4 text-white" data-aos="fade-up" data-aos-delay="300">
              {{ $news->title }}
            </h1>
            <div class="article-meta d-inline-flex justify-content-center align-items-center flex-wrap gap-3 mb-4 px-4 py-2 rounded-pill bg-black bg-opacity-25" data-aos="fade-up" data-aos-delay="350">
              <div class="meta-item text-white">
                <i class="bi bi-calendar3 me-2"></i>
                <span>{{ $news->published_at ? $news->published_at->translatedFormat('d F Y') : $news->created_at->translatedFormat('d F Y') }}</span>
              </div>
              <div class="meta-divider text-white">•</div>
              <div class="meta-item text-white">
                <i class="bi bi-person me-2"></i>
                <span>{{ $news->user ? $news->user->name : 'Admin' }}</span>
              </div>
              <div class="meta-divider text-white">•</div>
              <div class="meta-item text-white">
                <i class="bi bi-eye me-2"></i>
                <span>{{ number_format($news->view_count) }}x dilihat</span>
              </div>
              <div class="meta-divider text-white">•</div>
              <div class="meta-item text-white">
                <i class="bi bi-clock me-2"></i>
                <span>{{ ceil(str_word_count(strip_tags($news->content)) / 250) }} min read</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ======= Article Content Section ======= -->
<section class="section py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <!-- Featured Image -->
        <div class="article-featured-image mb-5" data-aos="fade-up">
          @if($news->image)
            <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" class="img-fluid rounded-4 shadow-lg">
            @if($news->image_caption)
              <div class="image-caption mt-3 text-center">
                <small class="text-muted">{{ $news->image_caption }}</small>
              </div>
            @endif
          @endif
        </div>

        <!-- Article Content -->
        <article class="article-content" data-aos="fade-up" data-aos-delay="200">
          <!-- Lead Paragraph -->
          <p class="lead text-muted mb-4">
            {!! $news->excerpt !!}
          </p>

          <!-- Article Body -->
          <div class="article-body">
            @php
              // Split content into paragraphs
              $content = $news->content;
              
              // Create the achievements section HTML
              $achievements = '
              <div class="highlight-box my-5">
                <h4 class="h5 fw-bold mb-3">
                  <i class="bi bi-trophy text-warning me-2"></i>
                  Prestasi ' . config('app.name') . ' di Bidang Sains
                </h4>
                <ul class="list-unstyled">
                  <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>3 Medali Emas OSN Fisika (2022-2024)</li>
                  <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>2 Medali Perak OSN Kimia (2023-2024)</li>
                  <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>1 Medali Perunggu OSN Matematika (2024)</li>
                  <li class="mb-2"><i class="bi bi-check-circle text-success me-2"></i>Juara Umum Kompetisi Sains Regional 3 tahun berturut-turut</li>
                </ul>
              </div>';
              
              // Insert achievements after the first closing paragraph tag
              $content = preg_replace('/(<p>.*?<\/p>\s*<p>.*?<\/p>)/s', '$1' . $achievements, $content, 1);
              
              echo $content;
            @endphp
          </div>

          <!-- Tags -->
          <div class="article-tags mt-5">
            <h6 class="fw-bold mb-3">Tags:</h6>
            <div class="d-flex flex-wrap gap-2">
              @if(is_string($news->tags))
                @foreach(explode(',', $news->tags) as $tag)
                  <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">{{ trim($tag) }}</span>
                @endforeach
              @elseif(is_array($news->tags) || is_object($news->tags))
                @foreach($news->tags as $tag)
                  <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">{{ is_object($tag) ? $tag->name : $tag }}</span>
                @endforeach
              @else
                <span class="text-muted">Tidak ada tag</span>
              @endif
            </div>
          </div>

          <!-- Share Buttons -->
          <div class="article-share mt-5 pt-4 border-top">
            <h6 class="fw-bold mb-3">Bagikan Artikel:</h6>
            <div class="d-flex gap-3">
              @php
                  $currentUrl = url()->current();
                  $shareText = urlencode($news->title . ' - ' . config('app.name'));
                  $facebookUrl = 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode($currentUrl);
                  $twitterUrl = 'https://twitter.com/intent/tweet?url=' . urlencode($currentUrl) . '&text=' . $shareText;
                  $whatsappUrl = 'https://wa.me/?text=' . $shareText . ' ' . urlencode($currentUrl);
                  $emailUrl = 'mailto:?subject=' . urlencode($news->title) . '&body=' . $shareText . ' ' . $currentUrl;
              @endphp

              <a href="{{ $facebookUrl }}" target="_blank" class="btn btn-outline-primary btn-sm" onclick="window.open(this.href, 'popup', 'width=600,height=600'); return false;">
                <i class="bi bi-facebook me-2"></i>Facebook
              </a>
              <a href="{{ $twitterUrl }}" target="_blank" class="btn btn-outline-info btn-sm" onclick="window.open(this.href, 'popup', 'width=600,height=400'); return false;">
                <i class="bi bi-twitter-x me-2"></i>Twitter
              </a>
              <a href="{{ $whatsappUrl }}" target="_blank" class="btn btn-outline-success btn-sm">
                <i class="bi bi-whatsapp me-2"></i>WhatsApp
              </a>
              <a href="{{ $emailUrl }}" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-envelope me-2"></i>Email
              </a>
              <button class="btn btn-outline-secondary btn-sm" onclick="copyToClipboard('{{ $currentUrl }}')">
                <i class="bi bi-link-45deg me-2"></i>Salin Tautan
              </button>
              
              <!-- Native Share Button (will be hidden if not supported) -->
              <button class="btn btn-outline-info btn-sm d-none" id="nativeShareBtn">
                <i class="bi bi-share me-2"></i>Bagikan
              </button>
            </div>
          </div>
        </article>
      </div>

      <!-- Sidebar -->
      <div class="col-lg-4" data-aos="fade-left" data-aos-delay="300">
        <div class="sidebar">
          <!-- Author Info -->
          <div class="author-card mb-4">
            <div class="card border-0 shadow-sm">
              <div class="card-body text-center p-4">
                @if($news->user && $news->user->photo_path)
                  <img src="{{ asset('storage/' . $news->user->photo_path) }}" alt="{{ $news->user->name }}" class="author-avatar-large mb-3">
                @else
                  <div class="author-avatar-large mb-3 bg-secondary d-flex align-items-center justify-content-center text-white">
                    {{ strtoupper(substr($news->user ? $news->user->name : 'A', 0, 1)) }}
                  </div>
                @endif
                <h5 class="fw-bold mb-1">{{ $news->user ? $news->user->name : 'Admin' }}</h5>
                @if($news->user && $news->user->roles->isNotEmpty())
                  <p class="text-muted small mb-3">{{ $news->user->roles->first()->name }}</p>
                @else
                  <p class="text-muted small mb-3">Penulis</p>
                @endif
                <p class="small text-muted">
                  {{ $news->user && $news->user->bio ? $news->user->bio : 'Penulis artikel di ' . config('app.name') }}
                </p>
                <div class="social-links">
                  @if($news->user && $news->user->email)
                    <a href="mailto:{{ $news->user->email }}" class="text-decoration-none me-3" title="Email"><i class="bi bi-envelope"></i></a>
                  @endif
                  @if($news->user && $news->user->linkedin)
                    <a href="{{ $news->user->linkedin }}" class="text-decoration-none me-3" target="_blank" title="LinkedIn"><i class="bi bi-linkedin"></i></a>
                  @endif
                  @if($news->user && $news->user->twitter)
                    <a href="{{ $news->user->twitter }}" class="text-decoration-none" target="_blank" title="Twitter"><i class="bi bi-twitter"></i></a>
                  @endif
                </div>
              </div>
            </div>
          </div>

          <!-- Related Articles -->
          <div class="related-articles mb-4">
            <h5 class="fw-bold mb-4">
              <i class="bi bi-newspaper text-primary me-2"></i>
              Berita Terkait
            </h5>
            
            @forelse($relatedNews as $related)
              <div class="related-item mb-4">
                <div class="row g-3">
                  @if($related->image)
                    <div class="col-4">
                      <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->title }}" class="img-fluid rounded-3" style="width: 100%; height: 80px; object-fit: cover;">
                    </div>
                    <div class="col-8">
                  @else
                    <div class="col-12">
                  @endif
                    <div class="article-category mb-2">
                      <span class="badge bg-{{ $related->category === 'Prestasi' ? 'success' : ($related->category === 'Berita' ? 'primary' : 'info') }} bg-opacity-20 text-{{ $related->category === 'Prestasi' ? 'success' : ($related->category === 'Berita' ? 'primary' : 'info') }}">
                          {{ $related->category }}
                      </span>
                    </div>
                    <h6 class="article-title mb-2">
                      <a href="{{ route('berita.detail', $related->slug) }}" class="text-decoration-none">{{ $related->title }}</a>
                    </h6>
                    <div class="article-meta small text-muted">
                      <i class="bi bi-calendar3 me-1"></i>
                      {{ $related->published_at ? $related->published_at->translatedFormat('d M Y') : $related->created_at->translatedFormat('d M Y') }}
                      <i class="bi bi-eye ms-2 me-1"></i>{{ number_format($related->view_count) }}x
                    </div>
                  </div>
                </div>
              </div>
            @empty
              <p class="text-muted">Tidak ada berita terkait.</p>
            @endforelse
          </div>

          <!-- Popular Articles -->
          <div class="popular-articles">
            <h5 class="fw-bold mb-4">
              <i class="bi bi-fire text-danger me-2"></i>
              Berita Populer
            </h5>
            
            <div class="popular-list">
              @forelse($popularNews as $index => $popular)
                <div class="popular-item d-flex align-items-center mb-3">
                  <span class="popular-number me-3">{{ $index + 1 }}</span>
                  <div>
                    <h6 class="mb-1">
                      <a href="{{ route('berita.detail', $popular->slug) }}" class="text-decoration-none">
                        {{ $popular->title }}
                      </a>
                    </h6>
                    <div class="d-flex align-items-center gap-2 text-muted small">
                      <span><i class="bi bi-eye"></i> {{ number_format($popular->view_count) }}x</span>
                      <span>•</span>
                      <span><i class="bi bi-calendar3"></i> {{ $popular->published_at ? $popular->published_at->translatedFormat('d M Y') : $popular->created_at->translatedFormat('d M Y') }}</span>
                    </div>
                  </div>
                </div>
              @empty
                <p class="text-muted">Belum ada berita populer.</p>
              @endforelse
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ======= More Articles Section ======= -->
<section class="section bg-light py-5">
  <div class="container">
    <div class="row mb-5">
      <div class="col-12 text-center" data-aos="fade-up">
        <div class="section-badge mb-3">
          <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill">
            <i class="bi bi-grid me-2"></i>Berita Lainnya
          </span>
        </div>
        <h2 class="display-5 fw-bold mb-4">
          Baca Juga <span class="text-primary">Berita Lainnya</span>
        </h2>
      </div>
    </div>

    <div class="row g-4">
      @forelse($randomNews as $item)
      <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
          <article class="news-card h-100">
              <div class="news-image">
                  @if($item->image && Storage::disk('public')->exists($item->image))
                      <img src="{{ asset('storage/' . $item->image) }}" 
                           alt="{{ $item->title }}" 
                           class="img-fluid"
                           onerror="this.src='{{ asset('assets/img/default-news.jpg') }}'">
                  @else
                      <img src="{{ asset('assets/img/default-news.jpg') }}" 
                           alt="Default Image" 
                           class="img-fluid">
                  @endif
                  <div class="news-overlay">
                      <div class="news-category">
                          <span class="badge bg-{{ $item->category === 'Prestasi' ? 'success' : ($item->category === 'Berita' ? 'primary' : 'info') }}">
                              {{ $item->category }}
                          </span>
                      </div>
                  </div>
              </div>
              <div class="news-content">
                  <div class="news-meta">
                      <span class="news-date">
                          <i class="bi bi-calendar3 me-1"></i>
                          {{ $item->published_at ? $item->published_at->translatedFormat('d M Y') : $item->created_at->translatedFormat('d M Y') }}
                      </span>
                      <span class="news-author">
                          <i class="bi bi-person me-1"></i>{{ $item->user ? $item->user->name : 'Admin' }}
                      </span>
                  </div>
                  <h4 class="news-title">
                      <a href="{{ route('berita.detail', $item->slug) }}" class="text-decoration-none">
                          {{ Str::limit($item->title, 60) }}
                      </a>
                  </h4>
                  <p class="news-excerpt">
                      {{ Str::limit(strip_tags($item->excerpt ?? $item->content), 120) }}
                  </p>
                  <div class="news-footer">
                      <a href="{{ route('berita.detail', $item->slug) }}" class="read-more">
                          Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
                      </a>
                      <div class="news-stats">
                          <span><i class="bi bi-eye"></i> {{ number_format($item->view_count) }}</span>
                      </div>
                  </div>
              </div>
          </article>
      </div>
      @empty
      <div class="col-12 text-center">
          <p class="text-muted">Tidak ada berita lainnya untuk ditampilkan.</p>
      </div>
      @endforelse
    </div>

    <!-- View All News Button -->
    <div class="row mt-5">
      <div class="col-12 text-center" data-aos="fade-up">
        <a href="/berita" class="btn btn-primary btn-lg rounded-pill px-5">
          <i class="bi bi-grid-3x3-gap me-2"></i>Lihat Semua Berita
        </a>
      </div>
    </div>
  </div>
</section>

@push('scripts')
<script>
function copyToClipboard(text) {
  // Create a temporary input element
  const tempInput = document.createElement('input');
  tempInput.value = text;
  document.body.appendChild(tempInput);
  
  // Select the text
  tempInput.select();
  tempInput.setSelectionRange(0, 99999); // For mobile devices
  
  try {
    // Execute the copy command
    const successful = document.execCommand('copy');
    const message = successful ? 'Tautan berhasil disalin!' : 'Gagal menyalin tautan';
    
    // Show feedback to user
    const originalText = event.target.innerHTML;
    event.target.innerHTML = '<i class="bi bi-check2 me-2"></i>Tersalin!';
    
    // Revert button text after 2 seconds
    setTimeout(() => {
      event.target.innerHTML = originalText;
    }, 2000);
    
  } catch (err) {
    console.error('Gagal menyalin teks: ', err);
  }
  
  // Clean up
  document.body.removeChild(tempInput);
}

// Check for Web Share API support and show native share button if available
if (navigator.share) {
  const shareData = {
    title: '{{ addslashes($news->title) }}',
    text: '{{ addslashes(strip_tags($news->excerpt ?? $news->content)) }}',
    url: '{{ url()->current() }}'
  };

  const nativeShareBtn = document.getElementById('nativeShareBtn');
  if (nativeShareBtn) {
    nativeShareBtn.classList.remove('d-none');
    
    nativeShareBtn.addEventListener('click', async () => {
      try {
        await navigator.share(shareData);
      } catch (err) {
        console.log('Error sharing:', err);
      }
    });
  }
}
</script>

<!-- Custom Styles -->
<style>
/* Meta Info Styles */
.article-meta {
  display: inline-flex;
  align-items: center;
  flex-wrap: wrap;
  justify-content: center;
  padding: 0.5rem 1.5rem;
  margin: 0 auto;
}

.meta-item {
  display: flex;
  align-items: center;
  font-size: 0.95rem;
  font-weight: 500;
}

.meta-divider {
  margin: 0 0.5rem;
  font-weight: bold;
}

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

.article-category-hero .badge {
  font-size: 1rem;
  padding: 0.75rem 1.5rem;
}

.article-meta-hero {
  background: rgba(255, 255, 255, 0.1);
  backdrop-filter: blur(10px);
  border-radius: 25px;
  padding: 1rem 2rem;
  color: rgba(255, 255, 255, 0.9);
}

.meta-item {
  color: rgba(255, 255, 255, 0.9);
  font-weight: 500;
}

/* Article Content Styles */
.article-featured-image img {
  width: 100%;
  height: 400px;
  object-fit: cover;
}

.image-caption {
  font-style: italic;
}

.article-content {
  font-size: 1.1rem;
  line-height: 1.8;
  color: #333;
}

.article-content .lead {
  font-size: 1.3rem;
  font-weight: 400;
  line-height: 1.7;
}

.article-body p {
  margin-bottom: 1.5rem;
  text-align: justify;
}

.article-body h3 {
  color: #333;
  margin-top: 2.5rem;
  margin-bottom: 1.5rem;
  position: relative;
  padding-left: 1rem;
}

.article-body h3::before {
  content: '';
  position: absolute;
  left: 0;
  top: 0;
  height: 100%;
  width: 4px;
  background: var(--primary-color);
  border-radius: 2px;
}

/* Blockquote */
.blockquote-custom {
  background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  border-left: 5px solid var(--primary-color);
  padding: 2rem;
  border-radius: 15px;
  position: relative;
  font-style: italic;
}

.blockquote-custom::before {
  content: '"';
  font-size: 4rem;
  color: var(--primary-color);
  position: absolute;
  top: -10px;
  left: 20px;
  font-weight: bold;
  opacity: 0.3;
}

.blockquote-custom p {
  font-size: 1.2rem;
  font-weight: 500;
  margin-bottom: 0;
  color: #333;
}

.blockquote-footer {
  font-size: 1rem;
  font-weight: 600;
  color: var(--primary-color);
}

/* Highlight Box */
.highlight-box {
  background: linear-gradient(135deg, #e3f2fd 0%, #f3e5f5 100%);
  border: 1px solid rgba(0, 123, 255, 0.2);
  border-radius: 15px;
  padding: 2rem;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.highlight-box h4 {
  color: #333;
  margin-bottom: 1.5rem;
}

.highlight-box ul li {
  margin-bottom: 0.75rem;
  font-weight: 500;
}

/* Tags */
.article-tags .badge {
  font-size: 0.9rem;
  padding: 0.5rem 1rem;
  border-radius: 20px;
  font-weight: 500;
}

/* Share Buttons */
.article-share .btn {
  border-radius: 25px;
  font-weight: 500;
  transition: all 0.3s ease;
}

.article-share .btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

/* Sidebar Styles */
.sidebar {
  position: sticky;
  top: 2rem;
}

.author-card .card {
  border-radius: 20px;
  transition: transform 0.3s ease;
}

.author-card .card:hover {
  transform: translateY(-5px);
}

.author-avatar-large {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  object-fit: cover;
  border: 4px solid #fff;
  box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.social-links a {
  color: #666;
  font-size: 1.2rem;
  transition: color 0.3s ease;
}

.social-links a:hover {
  color: var(--primary-color);
}

/* Related Articles */
.related-articles {
  background: white;
  border-radius: 20px;
  padding: 2rem;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
  margin-bottom: 2rem;
}

.related-item {
  padding-bottom: 1.5rem;
  border-bottom: 1px solid #eee;
}

.related-item:last-child {
  border-bottom: none;
  margin-bottom: 0;
  padding-bottom: 0;
}

.related-item .article-title {
  font-size: 0.95rem;
  font-weight: 600;
  line-height: 1.4;
}

.related-item .article-title a {
  color: #333;
  transition: color 0.3s ease;
}

.related-item .article-title a:hover {
  color: var(--primary-color);
}

.related-item img {
  width: 100%;
  height: 60px;
  object-fit: cover;
}

/* Popular Articles */
.popular-articles {
  background: white;
  border-radius: 20px;
  padding: 2rem;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
}

.popular-number {
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

.popular-item h6 a {
  color: #333;
  font-size: 0.9rem;
  font-weight: 600;
  transition: color 0.3s ease;
}

.popular-item h6 a:hover {
  color: var(--primary-color);
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

/* Section Badge */
.section-badge {
  margin-bottom: 1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
  .display-4 { 
    font-size: 2rem; 
  }
  
  .display-5 { 
    font-size: 1.75rem; 
  }
  
  .article-meta-hero .d-flex {
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .article-featured-image img {
    height: 250px;
  }
  
  .article-content {
    font-size: 1rem;
  }
  
  .article-content .lead {
    font-size: 1.1rem;
  }
  
  .blockquote-custom {
    padding: 1.5rem;
  }
  
  .highlight-box {
    padding: 1.5rem;
  }
  
  .sidebar {
    position: static;
    margin-top: 3rem;
  }
  
  .related-articles,
  .popular-articles {
    padding: 1.5rem;
  }
  
  .article-share .d-flex {
    flex-wrap: wrap;
    gap: 0.5rem;
  }
  
  .article-share .btn {
    font-size: 0.85rem;
    padding: 0.5rem 1rem;
  }
}

@media (max-width: 576px) {
  
  .article-meta-hero {
    padding: 1rem;
  }
  
  .meta-item {
    font-size: 0.9rem;
  }
  
  .blockquote-custom::before {
    font-size: 3rem;
    top: -5px;
    left: 15px;
  }
  
  .related-item img {
    height: 50px;
  }
  
  .related-item .article-title {
    font-size: 0.9rem;
  }
  
  .popular-item h6 a {
    font-size: 0.85rem;
  }
  
  .popular-number {
    width: 25px;
    height: 25px;
    font-size: 0.8rem;
  }
}

/* Animation on scroll */
.article-content,
.sidebar {
  opacity: 0;
  transform: translateY(30px);
  animation: fadeInUp 0.8s ease-out forwards;
}

.sidebar {
  animation-delay: 0.3s;
}

@keyframes fadeInUp {
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Print Styles */
@media print {
  .sidebar,
  .article-share,
  .section:last-child {
    display: none;
  }
  
  .article-content {
    font-size: 12pt;
    line-height: 1.5;
  }
  
  .blockquote-custom {
    background: none;
    border: 1px solid #ccc;
  }
}
</style>

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


<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
@endsection