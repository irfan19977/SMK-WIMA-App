<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>SMK PGRI LAWANG</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
<link href="{{ asset('frontend/assets/img/logo 1.png') }}" rel="icon">
<link href="{{ asset('frontend/assets/img/logo 1.png') }}" rel="apple-touch-icon">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

<!-- Vendor CSS Files -->
<link href="{{ asset('frontend/assets/vendor/aos/aos.css') }}" rel="stylesheet">
<link href="{{ asset('frontend/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('frontend/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
<link href="{{ asset('frontend/assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
<link href="{{ asset('frontend/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

<!-- Template Main CSS File -->
<link href="{{ asset('frontend/assets/css/style.css') }}" rel="stylesheet">
@stack('styles')
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top d-flex align-items-center">
    <div class="container d-flex justify-content-between align-items-center">

      <div class="logo d-flex align-items-center">
        <!-- Uncomment below if you prefer to use an image logo -->
        <a href="index.html" class="me-3"><img src="{{ asset('backend/assets/img/logo 1.png') }}" alt="" class="img-fluid" style="height: 50px;"></a>
        <h1 class="mb-0"><a href="index.html">SMK PGRI LAWANG</a></h1>
      </div>

      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="{{ request()->routeIs('/') ? 'active' : '' }}" href="{{ route('/') }}">Home</a></li>
          <li><a class="{{ request()->routeIs('profile-sekolah.*') ? 'active' : '' }}" href="{{ route('profile-sekolah.index') }}">Profile</a></li>
          <li class="dropdown">
            <a href="#" data-bs-toggle="dropdown">
              Jurusan <i class="bi bi-chevron-down"></i>
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{ route('tkr.index') }}">Teknik Kendaraan Ringan</a></li>
              <li><a class="dropdown-item" href="{{ route('tbsm.index') }}">Teknik Bisnis Sepeda Motor</a></li>
              <li><a class="dropdown-item" href="{{ route('kimia.index') }}">Teknik Kimia Industri</a></li>
              <li><a class="dropdown-item" href="{{ route('tkj.index') }}">Teknik Komputer & Jaringan</a></li>
            </ul>
          </li>
          <li><a class="{{ request()->routeIs('pendaftaran.*') ? 'active' : '' }}" href="{{ route('pendaftaran.index') }}">Pendaftaran</a></li>
          <li><a class="{{ request()->routeIs('berita.*') ? 'active' : '' }}" href="{{ route('berita.index') }}">Berita</a></li>
          <li><a class="{{ request()->is('contact.*') ? 'active' : '' }}" href="{{ route('contact.index') }}">Kontak</a></li>
          <li><a class="{{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">Login</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

    @yield('content')

  <!-- ======= Footer ======= -->
  <footer class="footer" role="contentinfo">
    <div class="container">
      <div class="row">
        <div class="col-md-4 mb-4 mb-md-0">
          <h3>Tentang SMK PGRI Lawang</h3>
          <p>SMK PGRI Lawang adalah lembaga pendidikan vokasi yang berkomitmen untuk mencetak generasi muda yang terampil dan berdaya saing tinggi di era digital.</p>
          <p class="social">
            <a href="#"><span class="bi bi-twitter"></span></a>
            <a href="#"><span class="bi bi-facebook"></span></a>
            <a href="#"><span class="bi bi-instagram"></span></a>
            <a href="#"><span class="bi bi-linkedin"></span></a>
          </p>
        </div>
        <div class="col-md-7 ms-auto">
          <div class="row site-section pt-0">
            <div class="col-md-4 mb-4 mb-md-0">
              <h3>Profil Sekolah</h3>
              <ul class="list-unstyled">
                <li><a href="#">Sejarah Sekolah</a></li>
                <li><a href="#">Visi & Misi</a></li>
                <li><a href="#">Fasilitas</a></li>
                <li><a href="#">Tenaga Pendidik</a></li>
              </ul>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
              <h3>Akademik</h3>
              <ul class="list-unstyled">
                <li><a href="#">Program Keahlian</a></li>
                <li><a href="#">Kurikulum</a></li>
                <li><a href="#">Kegiatan Siswa</a></li>
                <li><a href="#">Prestasi</a></li>
              </ul>
            </div>
            <div class="col-md-4 mb-4 mb-md-0">
              <h3>Jurusan</h3>
              <ul class="list-unstyled">
                <li><a href="#">Teknik Kimia Industri</a></li>
                <li><a href="#">Teknik Komputer & Jaringan</a></li>
                <li><a href="#">Teknik Kendaraan Ringan</a></li>
                <li><a href="#">Teknik Bisnis Sepeda Motor</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="row justify-content-center text-center">
        <div class="col-md-7">
          <p class="copyright">&copy; {{ date('Y') }} SMK PGRI Lawang. All Rights Reserved</p>
          <div class="credits">
            Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
          </div>
        </div>
      </div>

    </div>
  </footer>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="{{ asset('frontend/assets/vendor/aos/aos.js') }}"></script>
<script src="{{ asset('frontend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('frontend/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('frontend/assets/vendor/php-email-form/validate.js') }}"></script>

<!-- Template Main JS File -->
<script src="{{ asset('frontend/assets/js/main.js') }}"></script>

@stack('scripts')

</body>

</html>