@forelse($latestNews as $index => $news)
<div class="col-lg-4 col-md-6 news-item" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
    <article class="news-card">
        <div class="news-image">
            <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="img-fluid">
            <div class="news-overlay">
                <div class="news-category">
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
                    <span class="badge {{ $badgeClass }}">{{ $news->category }}</span>
                </div>
            </div>
        </div>
        <div class="news-content">
            <div class="news-meta">
                <span class="news-date"><i class="bi bi-calendar3 me-1"></i>{{ $news->published_at->format('d M Y') }}</span>
                <span class="news-author"><i class="bi bi-person me-1"></i>{{ $news->user->name }}</span>
            </div>
            <h4 class="news-title">
                <a href="{{ route('berita.detail', $news->slug) }}" class="text-decoration-none">{{ $news->title }}</a>
            </h4>
            <p class="news-excerpt">
                {{ Str::limit(strip_tags($news->content), 120) }}
            </p>
            <div class="news-footer">
                <a href="{{ route('berita.detail', $news->slug) }}" class="read-more">
                    Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
                </a>
                <div class="news-stats">
                    <span><i class="bi bi-eye"></i> {{ number_format($news->view_count) }}</span>
                </div>
            </div>
        </div>
    </article>
</div>
@empty
<div class="col-12 text-center py-5">
    <div class="alert alert-info">
        <i class="bi bi-info-circle me-2"></i> Tidak ada berita yang ditemukan.
    </div>
</div>
@endforelse

@if($latestNews->hasMorePages())
<div class="col-12 text-center mt-4 load-more-container">
    <button class="btn btn-primary load-more" data-next-page="{{ $latestNews->currentPage() + 1 }}" data-last-page="{{ $latestNews->lastPage() }}">
        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
        Muat Lebih Banyak
    </button>
</div>
@endif
