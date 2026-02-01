@extends('home.layouts.app')

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
                                    display: block;
                                    margin: 15px 0;
                                }
                                .article-content iframe {
                                    max-width: 100%;
                                    height: auto;
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