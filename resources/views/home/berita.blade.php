@extends('home.layouts.app')

@section('content')
 <!-- Start Section Banner Area -->
        <div class="section-banner bg-2">
            <div class="container">
                <div class="banner-spacing">
                    <div class="section-info">
                        <h2 data-aos="fade-up" data-aos-delay="100">{{ __('home.news_banner_title') }}</h2>
                        <p data-aos="fade-up" data-aos-delay="200">{{ __('home.news_banner_description') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Section Banner Area -->
        
        <!-- End Blog Area -->
        <div class="blog-area ptb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="widget-area">
                            <div class="widget widget-search">
                                <h3 class="widget-title">
                                    {{ __('home.news_search') }}
                                </h3>
                                <form class="search-form">
                                    <label>
                                        <span class="screen-reader-text">{{ __('home.news_search_for') }}</span>
                                        <input type="search" class="search-field" placeholder="{{ __('home.news_search_placeholder') }}">
                                    </label>
                                    <button type="submit"><i class='bx bx-search'></i></button>
                                </form>
                            </div>
                            <div class="widget widget-catagories">
                                <h3 class="widget-title">
                                    {{ __('home.news_categories') }}
                                </h3>
                                
                                <ul>
                                    @if(isset($categories) && count($categories) > 0)
                                        @foreach($categories as $category)
                                            <li><h3><a href="{{ route('berita.category', $category->category) }}">{{ $category->category }}</a></h3> <span>({{ $category->total }})</span></li>
                                        @endforeach
                                    @else
                                        <li><h3><a href="#">{{ __('home.news_no_categories') }}</a></h3> <span>(0)</span></li>
                                    @endif
                                </ul>

                            </div>
                            {{-- <div class="widget widget-tags">
                                <h3 class="widget-title">
                                    {{ __('home.news_popular_tags') }}
                                </h3>
                                <ul>
                                    <li><a href="#">Aktivitas</a></li>
                                    <li><a href="#">Alumni</a></li>
                                    <li><a href="#">Kampus</a></li>
                                    <li><a href="#">Pembelajaran Digital</a></li>
                                    <li><a href="#">Pendidikan</a></li>
                                    <li><a href="#">Pengalaman</a></li>
                                    <li><a href="#">Pembelajaran Internasional</a></li>
                                    <li><a href="#">Kehidupan Bisnis</a></li>
                                    <li><a href="#">Biaya Pendidikan</a></li>
                                    <li><a href="#">Keterampilan</a></li>
                                    <li><a href="#">Kehidupan Bisnis</a></li>
                                    <li><a href="#">Sarjana</a></li>
                                </ul>
                            </div> --}}
                        </div>
                    </div>
                    <div class="col-lg-8">
                            @if(isset($featuredNews) && count($featuredNews) > 0)
                            <div class="row">
                                @foreach ($featuredNews as $news)
                                    <div class="col-lg-6 col-sm-6 col-md-6">
                                        <div class="blog-single-card">
                                            <div class="image">
                                                <img src="{{ $news->thumbnail_url }}" alt="{{ $news->title }}">
                                                <span class="badge badge-primary" style="position: absolute; top: 10px; left: 10px;">{{ $news->category }}</span>
                                            </div>
                                            
                                            <div class="content">
                                                <div class="meta">
                                                    <ul>
                                                        <li><a href="#">{{ $news->user->name }}</a></li>
                                                        <li>{{ $news->published_at->translatedFormat('d F Y') }}</li>
                                                    </ul>
                                                </div>
                                                <h3><a href="{{ route('berita.detail', $news->slug) }}">{{ $news->title }}</a></h3>
                                                <a class="butn" href="{{ route('berita.detail', $news->slug) }}">{{ __('home.news_read_more') }} <i class="bx bx-right-arrow-alt"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @else
                                <div class="text-center py-5">
                                    <h3 class="text-muted">{{ __('home.news_no_articles') }}</h3>
                                </div>
                            @endif
                        </div>
                        @if(isset($featuredNews) && count($featuredNews) > 0 && $featuredNews->lastPage() > 1)
                        <div class="blog-pagi">
                            <ul class="pagination">
                                <li class="page-item">
                                  <a class="page-link" href="#" aria-label="{{ __('home.news_pagination_previous') }}">
                                    <span aria-hidden="true"><i class='bx bx-arrow-back'></i></span>
                                  </a>
                                </li>
                                @foreach ($featuredNews->getUrlRange(1, $featuredNews->lastPage()) as $page => $url)
                                    @if ($page == $featuredNews->currentPage())
                                        <li class="page-item"><a class="page-link active" href="#">{{ $page }}</a></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                    @endif
                                @endforeach
                                <li class="page-item">
                                  <a class="page-link" href="#" aria-label="{{ __('home.news_pagination_next') }}">
                                    <span aria-hidden="true"><i class='bx bx-arrow-back bx-rotate-180' ></i></span>
                                  </a>
                                </li>
                              </ul>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- End Blog Area -->
@endsection

@push('style')
    <style>
    .blog-single-card .image {
        position: relative;
        overflow: hidden;
        height: 250px; /* Fixed height untuk semua foto */
    }

    .blog-single-card .image img {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Memastikan foto memenuhi container tanpa distortion */
        transition: transform 0.3s ease;
    }

    .blog-single-card .image:hover img {
        transform: scale(1.05);
    }

    .blog-single-card {
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    /* Badge kategori styling */
    .blog-single-card .badge {
        position: absolute;
        top: 10px;
        left: 10px;
        z-index: 10;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 600;
        border-radius: 4px;
        background: linear-gradient(135deg, #2eca7f, #1ea971);
        color: white;
        box-shadow: 0 2px 8px rgba(46, 202, 127, 0.3);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>
@endpush