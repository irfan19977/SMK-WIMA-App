<!DOCTYPE html>
<html lang="en">
  <head>
    <title>SMK PGRI LAWANG</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Favicons -->
    <link rel="icon" type="image/png" href="{{ asset('frontend/images/logo 1.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('frontend/images/logo 1.png') }}">
    
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('frontend/css/open-iconic-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/animate.css') }}">
    
    <link rel="stylesheet" href="{{ asset('frontend/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/magnific-popup.css') }}">

    <link rel="stylesheet" href="{{ asset('frontend/css/aos.css') }}">

    <link rel="stylesheet" href="{{ asset('frontend/css/ionicons.min.css') }}">

    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/jquery.timepicker.css') }}">

    
    <link rel="stylesheet" href="{{ asset('frontend/css/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/icomoon.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">

    @stack('styles')

  </head>
  <body>
    
  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
      <a class="navbar-brand d-flex align-items-center" href="index.html">
        <img src="{{ asset('frontend/images/logo 1.png') }}" alt="" style="height:40px; width:40px; margin-right:10px;">
        <div>
          <strong>SMK PGRI LAWANG</strong><br>
          <small>Sekolah Masadepan</small>
        </div>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="oi oi-menu"></span> 
      </button>

      <div class="collapse navbar-collapse" id="ftco-nav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item {{ request()->routeIs('/') ? 'active' : '' }}"><a href="{{ route('/') }}" class="nav-link">Home</a></li>
          <li class="nav-item {{ request()->routeIs('profile-sekolah.*') ? 'active' : '' }}"><a href="{{ route('profile-sekolah.index') }}" class="nav-link">Profile</a></li>
          <li class="nav-item {{ request()->routeIs('pendaftaran.*') ? 'active' : '' }}"><a href="{{ route('pendaftaran.index') }}" class="nav-link">Pendaftaran</a></li>
          @php
              $isJurusanPage = request()->is('teknik-kendaraan-ringan*') || 
                              request()->is('teknik-bisnis-sepeda-motor*') || 
                              request()->is('kimia-industri*') || 
                              request()->is('teknik-komputer-jaringan*') ||
                              request()->is('galeri/teknik-kendaraan-ringan*') ||
                              request()->is('galeri/teknik-bisnis-sepeda-motor*') ||
                              request()->is('galeri/kimia-industri*') ||
                              request()->is('galeri/teknik-komputer-jaringan*');
          @endphp

          <li class="nav-item dropdown {{ $isJurusanPage ? 'active' : '' }}">
              <a class="nav-link dropdown-toggle" 
                href="#" id="jurusanDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Jurusan
              </a>
              <div class="dropdown-menu" aria-labelledby="jurusanDropdown">
                  <a class="dropdown-item {{ request()->is('teknik-kendaraan-ringan*') || request()->is('galeri/teknik-kendaraan-ringan*') ? 'active' : '' }}" 
                    href="{{ route('tkr.index') }}">Teknik Kendaraan Ringan</a>
                  
                  <a class="dropdown-item {{ request()->is('teknik-bisnis-sepeda-motor*') || request()->is('galeri/teknik-bisnis-sepeda-motor*') ? 'active' : '' }}" 
                    href="{{ route('tbsm.index') }}">Teknik Bisnis Sepeda Motor</a>
                  
                  <a class="dropdown-item {{ request()->is('kimia-industri*') || request()->is('galeri/kimia-industri*') ? 'active' : '' }}" 
                    href="{{ route('kimia.index') }}">Teknik Kimia Industri</a>
                  
                  <a class="dropdown-item {{ request()->is('teknik-komputer-jaringan*') || request()->is('galeri/teknik-komputer-jaringan*') ? 'active' : '' }}" 
                    href="{{ route('tkj.index') }}">Teknik Komputer dan Jaringan</a>
              </div>
          </li>
          <li class="nav-item {{ request()->routeIs('berita.*') ? 'active' : '' }}"><a href="{{ route('berita.index') }}" class="nav-link">Berita</a></li>
          <li class="nav-item {{ request()->routeIs('contact.*') ? 'active' : '' }}"><a href="{{ route('contact.index') }}" class="nav-link">Kontak</a></li>
          <li class="nav-item cta"><a href="{{ route('pendaftaran.index') }}" class="nav-link"><span>Daftar Sekarang!</span></a></li>
        </ul>
      </div>
    </div>
  </nav>
    <!-- END nav -->
    
    @yield('content')

    <footer class="ftco-footer ftco-bg-dark ftco-section img" style="background-image: url(images/bg_2.jpg); background-attachment:fixed;">
    	<div class="overlay"></div>
      <div class="container">
        <div class="row mb-5">
          <div class="col-md-3">
            <div class="ftco-footer-widget mb-4">
              <h2>
                <a class="navbar-brand d-flex align-items-center" href="index.html">
                  <img src="{{ asset('frontend/images/logo 1.png') }}" alt="SMK PGRI LAWANG" style="height:40px; width:40px; margin-right:10px;">
                  <div>
                    <strong>SMK PGRI LAWANG</strong><br>
                    <small>Sekolah Masadepan</small>
                  </div>
                </a>
              </h2>
              <p>SMK PGRI Lawang mencetak lulusan terampil dan siap kerja dengan fasilitas lengkap serta tenaga pengajar profesional.</p>
              <ul class="ftco-footer-social list-unstyled float-md-left float-lft mt-5">
                <li class="ftco-animate"><a href="#"><span class="icon-twitter"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-facebook"></span></a></li>
                <li class="ftco-animate"><a href="#"><span class="icon-instagram"></span></a></li>
              </ul>
            </div>
          </div>
          <div class="col-md-4">
            <div class="ftco-footer-widget mb-4">
              <h2 class="ftco-heading-2">Info Terbaru</h2>
                @php
                    $footerNews = \App\Models\News::latest('published_at')
                        ->take(2)
                        ->get();
                @endphp

                @if($footerNews->count() > 0)
                    @foreach($footerNews as $news)
                    <div class="block-21 mb-4 d-flex">
                        @if($news->thumbnail_url)
                        <a href="{{ route('berita.detail', $news->slug) }}" class="blog-img mr-4" style="background-image: url({{ $news->thumbnail_url }});"></a>
                        @endif
                        <div class="text">
                            <h3 class="heading"><a href="{{ route('berita.detail', $news->slug) }}">{{ $news->title }}</a></h3>
                            <div class="meta">
                                <div><a href="#"><span class="icon-calendar"></span> {{ $news->published_at->format('d M Y') }}</a></div>
                                <div><a href="#"><span class="icon-person"></span> {{ $news->author->name ?? 'Admin' }}</a></div>
                                <div><a href="#"><span class="icon-eye"></span> {{ $news->view_count ?? 0 }}</a></div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p>Tidak ada berita terbaru.</p>
                @endif
            </div>
          </div>
          <div class="col-md-2">
             <div class="ftco-footer-widget mb-4 ml-md-4">
              <h2 class="ftco-heading-2">Site Links</h2>
              <ul class="list-unstyled">
                <li><a href="#" class="py-2 d-block">Home</a></li>
                <li><a href="#" class="py-2 d-block">About</a></li>
                <li><a href="#" class="py-2 d-block">Courses</a></li>
                <li><a href="#" class="py-2 d-block">Students</a></li>
                <li><a href="#" class="py-2 d-block">Video</a></li>
              </ul>
            </div>
          </div>
          <div class="col-md-3">
            <div class="ftco-footer-widget mb-4">
            	<h2 class="ftco-heading-2">Have a Questions?</h2>
            	<div class="block-23 mb-3">
	              <ul>
	                <li><span class="icon icon-map-marker"></span><span class="text">Jl. DR. Wahidin No.17, Krajan, Kalirejo, Kec. Lawang, Kabupaten Malang</span></li>
	                <li><a href="#"><span class="icon icon-phone"></span><span class="text">(0341) 4395005</span></a></li>
	                <li><a href="#"><span class="icon icon-envelope"></span><span class="text">info@smkpgrilawang.sch.id</span></a></li>
	              </ul>
	            </div>
            </div>
          </div>
        </div>
      </div>
    </footer>
    
  

  <!-- loader -->
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>


  <script src="{{ asset('frontend/js/jquery.min.js') }}"></script>
  <script src="{{ asset('frontend/js/jquery-migrate-3.0.1.min.js') }}"></script>
  <script src="{{ asset('frontend/js/popper.min.js') }}"></script>
  <script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('frontend/js/jquery.easing.1.3.js') }}"></script>
  <script src="{{ asset('frontend/js/jquery.waypoints.min.js') }}"></script>
  <script src="{{ asset('frontend/js/jquery.stellar.min.js') }}"></script>
  <script src="{{ asset('frontend/js/owl.carousel.min.js') }}"></script>
  <script src="{{ asset('frontend/js/jquery.magnific-popup.min.js') }}"></script>
  <script src="{{ asset('frontend/js/aos.js') }}"></script>
  <script src="{{ asset('frontend/js/jquery.animateNumber.min.js') }}"></script>
  <script src="{{ asset('frontend/js/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('frontend/js/jquery.timepicker.min.js') }}"></script>
  <script src="{{ asset('frontend/js/scrollax.min.js') }}"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBVWaKrjvy3MaE7SQ74_uJiULgl1JY0H2s&sensor=false"></script>
  <script src="{{ asset('frontend/js/google-map.js') }}"></script>
  <script src="{{ asset('frontend/js/main.js') }}"></script>

  @stack('scripts')
    
  </body>
</html>