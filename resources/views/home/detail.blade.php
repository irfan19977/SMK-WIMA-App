@extends('home.layouts.app')

@section('content')
    <div class="hero-wrap hero-wrap-2" style="background-image: url('{{ asset('frontend/images/bg.png') }}'); background-attachment:fixed;">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-10 col-sm-12 ftco-animate text-center">
            <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('/') }}">Home</a></span> <span class="mr-2"><a href="{{ route('berita.index') }}">Berita</a></span> <span>Detail Berita</span></p>
            <h1 class="mb-3 bread">{{ $news->title }}</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section ftco-degree-bg">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-8 col-md-10 ftco-animate">
            <div class="news-content" style="overflow: hidden; max-width: 100%;">
              <style>
                .news-content img {
                  max-width: 100%;
                  height: auto !important;
                  display: block;
                  margin: 15px 0;
                }
                .news-content iframe {
                  max-width: 100%;
                  height: auto;
                }
              </style>
              {!! $news->content !!}
            </div>
            
            <div class="about-author d-flex p-5 bg-light">
              <div class="bio align-self-md-center mr-5">
                <img src="images/person_1.jpg" alt="Image placeholder" class="img-fluid mb-4">
              </div>
              <div class="desc align-self-md-center">
                <h3>{{ $news->user->name }}</h3>
                <p>{{ $news->user->description }}</p>
              </div>
            </div>

          </div> <!-- .col-lg-8 -->
          <div class="col-lg-4 col-md-10 sidebar ftco-animate">
            <div class="sidebar-box">
              <form action="#" class="search-form">
                <div class="form-group">
                  <span class="icon fa fa-search"></span>
                  <input type="text" class="form-control" placeholder="Type a keyword and hit enter">
                </div>
              </form>
            </div>
            <div class="sidebar-box ftco-animate">
              <div class="categories">
                <h3>Categories</h3>
                @forelse($categories as $category)
                  <li>
                    <a href="{{ route('berita.category', $category->category) }}">
                      {{ $category->category }} <span>({{ $category->total }})</span>
                    </a>
                  </li>
                @empty
                  <p>Belum ada kategori.</p>
                @endforelse
              </div>
            </div>

            <div class="sidebar-box ftco-animate">
              <h3>Recent Blog</h3>
              @forelse($recentNews as $item)
                <div class="block-21 mb-4 d-flex">
                  <a class="blog-img mr-4" style="background-image: url({{ $item->thumbnail_url }});"></a>
                  <div class="text">
                    <h3 class="heading">
                      <a href="{{ route('berita.detail', $item->slug) }}">{{ $item->title }}</a>
                    </h3>
                    <div class="meta">
                      <div>
                        <a href="#"><span class="icon-calendar"></span> {{ optional($item->published_at)->format('d M Y') }}</a>
                      </div>
                      <div>
                        <a href="#"><span class="icon-person"></span> {{ optional($item->user)->name ?? 'Admin' }}</a>
                      </div>
                      <div>
                        <a href="#"><span class="icon-chat"></span> {{ $item->view_count ?? 0 }}</a>
                      </div>
                    </div>
                  </div>
                </div>
              @empty
                <p>Belum ada berita terbaru.</p>
              @endforelse
            </div>

            <div class="sidebar-box ftco-animate">
              <h3>Tag Cloud</h3>
              <div class="tagcloud">
                @forelse($tagCloud as $tag)
                  <a href="{{ route('berita.tag', ['tag' => $tag]) }}" class="tag-cloud-link">{{ $tag }}</a>
                @empty
                  <span>Belum ada tag.</span>
                @endforelse
              </div>
            </div>
          </div>

        </div>
      </div>
    </section> <!-- .section -->
		
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