@extends('home.layouts.app')

@section('content')
    <div class="hero-wrap hero-wrap-2" style="background-image: url('{{ asset('frontend/images/bg.png') }}'); background-attachment:fixed;">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-8 ftco-animate text-center">
            <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('/') }}">Home</a></span> <span>Berita</span></p>
            <h1 class="mb-3 bread">Berita</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section">
    	<div class="container">
    		<div class="row d-flex">
                @foreach ($featuredNews as $news)
                    <div class="col-md-4 d-flex ftco-animate">
                        <div class="blog-entry align-self-stretch d-flex flex-column" style="width: 100%;">
                            <a href="{{ route('berita.detail', $news->slug) }}" class="block-20" style="background-image: url('{{ $news->thumbnail_url }}'); flex-shrink: 0; height: 250px; position: relative;">
                                <span class="badge badge-primary" style="position: absolute; top: 10px; left: 10px;">{{ $news->category }}</span>
                            </a>
                            <div class="text p-4 d-block" style="flex: 1; display: flex; flex-direction: column;">
                                <div class="meta mb-3">
                                    <div><a href="">{{ $news->published_at->translatedFormat('d F Y') }}</a></div>
                                    <div><a href="">{{ $news->user->name }}</a></div>
                                    <div><a href="" class="meta-chat"><span class="icon-eye"></span>{{ $news->view_count }}</a></div>
                                </div>
                                <h3 class="heading mt-3" style="height: 3.6em; overflow: hidden; line-height: 1.2em; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                    <a href="{{ route('berita.detail', $news->slug) }}">{{ $news->title }}</a>
                                </h3>
                                <p style="height: 4.5em; overflow: hidden; line-height: 1.5em; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; margin-top: auto;">
                                    {{ Str::limit($news->excerpt, 100) }}
                                </p>
                            </div>
                        </div>
                    </div> 
                @endforeach
            </div>
            <div class="row mt-5">
                <div class="col text-center">
                    <div class="block-27">
                        <ul>
                            {{-- Tombol Previous --}}
                            @if ($featuredNews->onFirstPage())
                                <li class="disabled"><span>&lt;</span></li>
                            @else
                                <li><a href="{{ $featuredNews->previousPageUrl() }}">&lt;</a></li>
                            @endif

                            {{-- Number Pages --}}
                            @foreach ($featuredNews->getUrlRange(1, $featuredNews->lastPage()) as $page => $url)
                                @if ($page == $featuredNews->currentPage())
                                    <li class="active"><span>{{ $page }}</span></li>
                                @else
                                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach

                            {{-- Tombol Next --}}
                            @if ($featuredNews->hasMorePages())
                                <li><a href="{{ $featuredNews->nextPageUrl() }}">&gt;</a></li>
                            @else
                                <li class="disabled"><span>&gt;</span></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
		
		<section class="ftco-section-parallax">
      <div class="parallax-img d-flex align-items-center">
        <div class="container">
          <div class="row d-flex justify-content-center">
            <div class="col-md-7 text-center heading-section heading-section-white ftco-animate">
              <h2>Subcribe to our Newsletter</h2>
              <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in</p>
              <div class="row d-flex justify-content-center mt-5">
                <div class="col-md-8">
                  <form action="#" class="subscribe-form">
                    <div class="form-group d-flex">
                      <input type="text" class="form-control" placeholder="Enter email address">
                      <input type="submit" value="Subscribe" class="submit px-3">
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
@endsection