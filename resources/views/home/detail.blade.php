@extends('home.layouts.app')

@section('meta')
    <!-- Meta Tags SEO untuk Detail Berita -->
    <title>{{ $news->title }} - {{ __('index.smk_pgri_lawang') }}</title>
    <meta name="description" content="{{ Str::limit(strip_tags($news->content), 160) }}">
    <meta name="keywords" content="{{ $news->tags }}, {{ $news->category }}, {{ __('index.smk_pgri_lawang') }}, berita, pendidikan">
    <meta name="author" content="{{ $news->user->name }}">
    <meta name="robots" content="index, follow">
    <meta name="language" content="{{ app()->getLocale() }}">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="{{ $news->title }}">
    <meta property="og:description" content="{{ Str::limit(strip_tags($news->content), 160) }}">
    <meta property="og:image" content="{{ $news->thumbnail_url }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="article">
    <meta property="og:site_name" content="{{ __('index.smk_pgri_lawang') }}">
    <meta property="article:published_time" content="{{ $news->published_at->format('c') }}">
    <meta property="article:author" content="{{ $news->user->name }}">
    <meta property="article:section" content="{{ $news->category }}">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $news->title }}">
    <meta name="twitter:description" content="{{ Str::limit(strip_tags($news->content), 160) }}">
    <meta name="twitter:image" content="{{ $news->thumbnail_url }}">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="{{ url()->current() }}">
    
    <!-- Structured Data - Article -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@type": "NewsArticle",
        "headline": "{{ Str::limit($news->title, 110) }}",
        "description": "{{ Str::limit(strip_tags($news->content), 160) }}",
        "image": "{{ $news->thumbnail_url }}",
        "author": {
            "@@type": "Person",
            "name": "{{ $news->user->name }}"
        },
        "publisher": {
            "@@type": "Organization",
            "name": "{{ __('index.smk_pgri_lawang') }}",
            "logo": {
                "@@type": "ImageObject",
                "url": "{{ asset('frontend/assets/img/logo/logo 1.png') }}"
            }
        },
        "datePublished": "{{ $news->published_at->format('c') }}",
        "dateModified": "{{ $news->updated_at->format('c') }}",
        "mainEntityOfPage": {
            "@@type": "WebPage",
            "@@id": "{{ url()->current() }}"
        }
    }
    </script>
@endsection

@section('content')
    <!-- Start Section Banner Area -->
    <div class="section-banner bg-3">
        <div class="container">
            <div class="banner-spacing">
                <div class="section-info">
                    <h2 data-aos="fade-up" data-aos-delay="100">Blog Details</h2>
                    <p data-aos="fade-up" data-aos-delay="200">{{ Str::limit(strip_tags($news->content), 150) }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- End Section Banner Area -->

    <!-- End Blog Area -->
    <div class="blog-area ptb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="blog-details-desc">
                        <div class="article-image">
                            <img src="{{ $news->thumbnail_url }}" alt="{{ $news->title }}">
                        </div>

                        <div class="article-content">
                            <div class="entry-meta">
                                <ul>
                                    <li><a href="#">{{ $news->user->name }}</a></li>
                                    <li>{{ $news->published_at->translatedFormat('d M Y') }}</li>
                                </ul>
                            </div>

                            <h3>{{ $news->title }}</h3>
                            <div style="overflow: hidden; max-width: 100%;">
                                <style>
                                    .article-content img {
                                        max-width: 100%;
                                        height: auto !important;
                                        margin: 10px 0;
                                    }
                                    .article-content img.text-left {
                                        float: left;
                                        margin-right: 15px;
                                        margin-bottom: 10px;
                                        margin-top: 10px;
                                    }
                                    .article-content img.text-right {
                                        float: right;
                                        margin-left: 15px;
                                        margin-bottom: 10px;
                                        margin-top: 10px;
                                    }
                                    .article-content img.text-center {
                                        display: block;
                                        margin-left: auto;
                                        margin-right: auto;
                                        margin: 15px auto;
                                    }
                                    .article-content img[style*="float: left"],
                                    .article-content img[style*="text-align: left"] {
                                        float: left !important;
                                        margin-right: 15px !important;
                                        margin-bottom: 10px !important;
                                        margin-top: 10px !important;
                                    }
                                    .article-content img[style*="float: right"],
                                    .article-content img[style*="text-align: right"] {
                                        float: right !important;
                                        margin-left: 15px !important;
                                        margin-bottom: 10px !important;
                                        margin-top: 10px !important;
                                    }
                                    .article-content img[style*="text-align: center"],
                                    .article-content img[style*="margin-left: auto"] {
                                        display: block !important;
                                        margin-left: auto !important;
                                        margin-right: auto !important;
                                        float: none !important;
                                    }
                                    .article-content iframe {
                                        max-width: 100%;
                                        height: auto;
                                    }
                                    .article-content::after {
                                        content: "";
                                        display: table;
                                        clear: both;
                                    }
                                    /* Fix for inline images that should be side by side */
                                    .article-content img:not([style*="float"]):not([style*="text-align"]) {
                                        display: inline-block;
                                        vertical-align: top;
                                        margin: 5px;
                                    }
                                </style>
                                {!! $news->content !!}
                            </div>

                            <div class="article-footer">
                                <div class="article-tags">
                                    <span>Tags:</span>
                                    @if($news->tags)
                                        @foreach(explode(',', $news->tags) as $tag)
                                            <a href="{{ route('berita.tag', trim($tag)) }}">{{ trim($tag) }}</a>@if(!$loop->last), @endif
                                        @endforeach
                                    @endif
                                </div>
                                <div class="article-share">
                                    <ul class="social">
                                        <li><span>Share:</span></li>
                                        <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" class="facebook" target="_blank"><i class="bx bxl-facebook"></i></a></li>
                                        <li><a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ $news->title }}" class="twitter" target="_blank"><i class="bx bxl-twitter"></i></a></li>
                                        <li><a href="https://www.linkedin.com/sharing/share-offsite/?url={{ url()->current() }}" class="linkedin" target="_blank"><i class="bx bxl-linkedin"></i></a></li>
                                        <li><a href="https://api.whatsapp.com/send?text={{ $news->title }} - {{ url()->current() }}" class="whatsapp" target="_blank"><i class="bx bxl-whatsapp"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="about-author d-flex p-5 bg-light">
                            <div class="bio align-self-md-center mr-5">
                                <img src="{{ $news->user->avatar_url ?? asset('frontend/assets/img/team/team-1.jpg') }}" alt="{{ $news->user->name }}" class="img-fluid mb-4">
                            </div>
                            <div class="desc align-self-md-center">
                                <h3>{{ $news->user->name }}</h3>
                                <p>{{ $news->user->description ?? 'Penulis berita di SMK PGRI Lawang' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="widget-area">
                        <div class="widget widget-search">
                            <h3 class="widget-title">Search</h3>
                            <form class="search-form" action="{{ route('berita.index') }}" method="GET">
                                <label>
                                    <span class="screen-reader-text">Search for:</span>
                                    <input type="search" class="search-field" name="search" placeholder="Search..." value="{{ request('search') }}">
                                </label>
                                <button type="submit"><i class='bx bx-search'></i></button>
                            </form>
                        </div>
                        
                        <div class="widget widget-catagories">
                            <h3 class="widget-title">Categories</h3>
                            <ul>
                                @if(isset($categories) && count($categories) > 0)
                                    @foreach($categories as $category)
                                        <li><h3><a href="{{ route('berita.category', $category->category) }}">{{ $category->category }}</a></h3> <span>({{ $category->total }})</span></li>
                                    @endforeach
                                @else
                                    <li><h3><a href="#">Belum ada kategori</a></h3> <span>(0)</span></li>
                                @endif
                            </ul>
                        </div>
                        
                        <div class="widget widget-tags">
                            <h3 class="widget-title">Popular Tags</h3>
                            <ul>
                                @if(isset($tagCloud) && count($tagCloud) > 0)
                                    @foreach($tagCloud as $tag)
                                        <li><a href="{{ route('berita.tag', $tag) }}">{{ $tag }}</a></li>
                                    @endforeach
                                @else
                                    <li><a href="#">Admission</a></li>
                                    <li><a href="#">Research</a></li>
                                    <li><a href="#">Student</a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection