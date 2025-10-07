@extends('home.layouts.app')

@section('content')
     <!-- ======= Hero Section ======= -->
  <section class="hero-section" id="hero">

    <div class="wave">

      <svg width="100%" height="355px" viewBox="0 0 1920 355" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
        <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
          <g id="Apple-TV" transform="translate(0.000000, -402.000000)" fill="#FFFFFF">
            <path d="M0,439.134243 C175.04074,464.89273 327.944386,477.771974 458.710937,477.771974 C654.860765,477.771974 870.645295,442.632362 1205.9828,410.192501 C1429.54114,388.565926 1667.54687,411.092417 1920,477.771974 L1920,757 L1017.15166,757 L0,757 L0,439.134243 Z" id="Path"></path>
          </g>
        </g>
      </svg>

    </div>

    <div class="container">
      <div class="row align-items-center">
        <div class="col-12 hero-text-image">
          <div class="row">
            <div class="col-lg-8 text-center text-lg-start">
              <h1 data-aos="fade-right">SMK PGRI LAWANG</h1>
              <p class="mb-5" data-aos="fade-right" data-aos-delay="100">Jl. DR. Wahidin No.17, Krajan, Kalirejo, Kec. Lawang, Kabupaten Malang</p>
              <p data-aos="fade-right" data-aos-delay="200" data-aos-offset="-500"><a href="{{ route('pendaftaran.index') }}" class="btn btn-outline-white">Daftar Sekarang</a></p>
            </div>
            <div class="col-lg-4 iphone-wrap">
              <img src="{{ asset('frontend/assets/img/phone_1.png') }}" alt="Modern smartphone displaying SMK PGRI Lawang app interface, screen shows school logo and navigation menu, device angled slightly to the left, set against a clean white background, conveys a welcoming and innovative atmosphere" class="phone-1" data-aos="fade-right">
              <img src="{{ asset('frontend/assets/img/phone-2.png') }}" alt="Modern smartphone displaying registration form for SMK PGRI Lawang app, form fields visible on screen, device angled to the right, placed beside another phone, background is bright and minimal, overall tone is friendly and inviting" class="phone-2" data-aos="fade-right" data-aos-delay="200">
            </div>
          </div>
        </div>
      </div>
    </div>

  </section>
  <!-- End Hero -->

  <main id="main">
    <!-- ======= Home Section ======= -->
    <section class="section">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <div class="step">
              <span class="number">01</span>
              <h3>Pendaftaran</h3>
              <p>Ayo daftar sekarang di SMK PGRI Lawang</p>
              <p><a href="{{ route('pendaftaran.index') }}" class="btn btn-primary">Info Selengkapnya</a></p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="step">
              <span class="number">02</span>
              <h3>Fasillitas Sekolah</h3>
              <p>
                Fasilitas yang tersedia di SMK PGRI Lawang</p>
              <p><a href="#" class="btn btn-primary">Info Selengkapnya</a></p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="step">
              <span class="number">03</span>
              <h3>Enjoy the app</h3>
              <p>
                Prestasi yang tersedia di SMK PGRI Lawang</p>
              <p><a href="#" class="btn btn-primary">Info Selengkapnya</a></p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ======= CTA Section ======= -->
    <section class="section cta-section">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-md-7 me-auto">
            <h2 class="mb-4">Sambutan Kepala Sekolah</h2>
            <p class="mb-4  justify-text" text-align="justify">Selamat datang di website SMK PGRI Lawang semoga bermanfaat untuk seluruh unsur pimpinan, guru, karyawan dan siswa serta khalayak umum untuk mendapatkan akses informasi tentang sekolah kami, mulai dari profil, aktifitas/kegiatan serta fasilitas sekolah kami.</p>
            <p class="mb-4  justify-text" text-align="justify">Tentunya dalam penyajian informasi masih banyak kekurangan, oleh karena itu kepada seluruh civitas akademika dan masyarakat umum dapat memberika saran dan kritikan yang membangun demi kemajuan website ini lebih lanjut.</p>
          </div>
          <div class="col-md-4" data-aos="fade-left">
            <img src="{{ asset('frontend/assets/img/undraw_svg_2.svg') }}" alt="Image" class="img-fluid">
          </div>
        </div>
      </div>
    </section>
    <!-- End CTA Section -->

    <!-- ======= Statistics Section ======= -->
    <section class="section">
      <div class="container">
        <div class="row justify-content-center text-center mb-5">
          <div class="col-md-6">
            <h2 class="section-heading">Statistik Website</h2>
            <p class="text-muted">Performa dan pencapaian website SMK PGRI Lawang</p>
          </div>
        </div>

        <div class="row justify-content-center">
          <div class="col-md-10 mx-auto">
            <div class="row text-center">
              <div class="col-md-3 mb-4">
                <div class="stat-item" style="background: #f8f9fa; border-radius: 15px; padding: 30px 20px; margin: 0 10px; border: 1px solid #e9ecef;">
                  <div style="font-size: 3rem; font-weight: 700; color: #2d71a1; margin-bottom: 10px;">{{ number_format(App\Models\Visitor::getMonthlyVisitors()) }}+</div>
                  <h5 style="color: #333; margin-bottom: 10px;">Total Pengunjung</h5>
                  <p style="color: #6c757d; font-size: 14px;">Bulan ini</p>
                </div>
              </div>
              <div class="col-md-3 mb-4">
                <div class="stat-item" style="background: #f8f9fa; border-radius: 15px; padding: 30px 20px; margin: 0 10px; border: 1px solid #e9ecef;">
                  <div style="font-size: 3rem; font-weight: 700; color: #1391a5; margin-bottom: 10px;">{{ number_format(App\Models\Visitor::getTodayVisitors()) }}+</div>
                  <h5 style="color: #333; margin-bottom: 10px;">Harian</h5>
                  <p style="color: #6c757d; font-size: 14px;">Pengunjung hari ini</p>
                </div>
              </div>
              <div class="col-md-3 mb-4">
                <div class="stat-item" style="background: #f8f9fa; border-radius: 15px; padding: 30px 20px; margin: 0 10px; border: 1px solid #e9ecef;">
                  <div style="font-size: 3rem; font-weight: 700; color: #28a745; margin-bottom: 10px;">{{ number_format(App\Models\Visitor::getWeeklyVisitors()) }}+</div>
                  <h5 style="color: #333; margin-bottom: 10px;">Mingguan</h5>
                  <p style="color: #6c757d; font-size: 14px;">Pengunjung minggu ini</p>
                </div>
              </div>
              <div class="col-md-3 mb-4">
                <div class="stat-item" style="background: #f8f9fa; border-radius: 15px; padding: 30px 20px; margin: 0 10px; border: 1px solid #e9ecef;">
                  <div style="font-size: 3rem; font-weight: 700; color: #ffc107; margin-bottom: 10px;">50+</div>
                  <h5 style="color: #333; margin-bottom: 10px;">Artikel Edukasi</h5>
                  <p style="color: #6c757d; font-size: 14px;">Konten bermanfaat</p>
                </div>
              </div>
            </div>

            <div class="row justify-content-center mt-4">
              <div class="col-md-8">
                <div style="background: #f8f9fa; border-radius: 15px; padding: 30px; text-align: center; border: 1px solid #e9ecef;">
                  <h4 style="color: #333; margin-bottom: 20px;">Performa Website</h4>
                  <div class="row text-center">
                    <div class="col-md-4">
                      <div style="margin-bottom: 15px;">
                        @php
                          $performance = app(\App\Services\PerformanceMonitor::class);
                          $metrics = $performance->getPerformanceMetrics();
                        @endphp
                        <div style="font-size: 2rem; font-weight: 700; color: #2d71a1;">{{ $metrics['loading_time'] }}</div>
                        <p style="color: #6c757d; font-size: 14px; margin: 0;">Loading Time</p>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div style="margin-bottom: 15px;">
                        <div style="font-size: 2rem; font-weight: 700; color: #1391a5;">{{ $metrics['uptime'] }}%</div>
                        <p style="color: #6c757d; font-size: 14px; margin: 0;">Uptime</p>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div style="margin-bottom: 15px;">
                        <div style="font-size: 2rem; font-weight: 700; color: #28a745;">{{ $metrics['response_time'] }}</div>
                        <p style="color: #6c757d; font-size: 14px; margin: 0;">Response Time</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End Statistics Section -->

    <!-- ======= Registration Section ======= -->
    <section class="section" style="background: linear-gradient(135deg, #274685 0%, #1391a5 100%); color: white;">
      <div class="container">
        <div class="row justify-content-center text-center mb-5">
          <div class="col-md-6">
             <h2 class="section-heading" style="color: white !important; background: none !important; -webkit-text-fill-color: white !important;">Pendaftaran Siswa Baru</h2>
            <p style="color: rgba(255,255,255,0.9);">Bergabunglah dengan SMK PGRI Lawang dan wujudkan masa depan yang lebih baik</p>
          </div>
        </div>

        <div class="row justify-content-center">
          <div class="col-md-10 mx-auto">
            <div class="registration-content" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border-radius: 20px; padding: 50px 30px; border: 1px solid rgba(255,255,255,0.2);">
              <div class="row align-items-center">
                <div class="col-lg-7 col-md-12 mb-4 mb-lg-0">
                  <h3 style="color: white; margin-bottom: 25px;"><i class="bx bx-info-circle me-2"></i>Informasi Pendaftaran</h3>

                  <div class="registration-info" style="color: rgba(255,255,255,0.9); font-size: 18px; line-height: 1.8;">
                    <p class="mb-3"><strong>Tahun Akademik 2024/2025</strong></p>
                    <p class="mb-3">Pendaftaran siswa baru SMK PGRI Lawang telah dibuka untuk memberikan kesempatan kepada putra-putri terbaik bangsa untuk bergabung dengan kami.</p>
                    <p class="mb-4">Dapatkan pendidikan berkualitas dengan fasilitas modern dan tenaga pengajar profesional yang siap membimbing Anda meraih masa depan gemilang.</p>

                    <div class="row text-center">
                      <div class="col-md-4 col-sm-4 col-4">
                        <div style="background: rgba(255,255,255,0.1); border-radius: 10px; padding: 15px;">
                          <i class="bx bx-calendar-check bx-lg mb-2" style="color: #3db3c5;"></i>
                          <p style="margin: 0; font-size: 14px;"><strong>Online 24/7</strong><br>Pendaftaran Daring</p>
                        </div>
                      </div>
                      <div class="col-md-4 col-sm-4 col-4">
                        <div style="background: rgba(255,255,255,0.1); border-radius: 10px; padding: 15px;">
                          <i class="bx bx-support bx-lg mb-2" style="color: #2d71a1;"></i>
                          <p style="margin: 0; font-size: 14px;"><strong>Bantuan</strong><br>CS Terlatih</p>
                        </div>
                      </div>
                      <div class="col-md-4 col-sm-4 col-4">
                        <div style="background: rgba(255,255,255,0.1); border-radius: 10px; padding: 15px;">
                          <i class="bx bx-shield-check bx-lg mb-2" style="color: #1391a5;"></i>
                          <p style="margin: 0; font-size: 14px;"><strong>Terjamin</strong><br>Kualitas Terbaik</p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-lg-5 col-md-12 text-center">
                  <div class="registration-form-section" style="background: rgba(255,255,255,0.15); border-radius: 15px; padding: 40px 20px;">
                    <h4 style="color: white; margin-bottom: 20px;"><i class="bx bx-edit-alt me-2"></i>Daftar Sekarang</h4>

                    <p style="color: rgba(255,255,255,0.8); margin-bottom: 30px;">Lengkapi formulir pendaftaran dan bergabunglah dengan ribuan siswa berprestasi lainnya.</p>

                    <a href="{{ route('pendaftaran.index') }}" class="btn btn-light btn-lg w-100 mb-3" style="padding: 15px 20px; font-weight: 600; font-size: 16px;">
                      <i class="bx bx-mouse me-2"></i>Klik untuk Daftar
                    </a>

                    <a href="#" class="btn btn-outline-light btn-lg w-100 mb-3" style="padding: 12px 20px; font-weight: 600; border-width: 2px; font-size: 16px;">
                      <i class="bx bx-download me-2"></i>Download Brosur
                    </a>

                    <div class="quick-contact" style="text-align: center; margin-top: 30px;">
                      <p style="color: white; font-size: 14px; margin-bottom: 10px;"><strong>Kontak Kami:</strong></p>
                      <p style="color: rgba(255,255,255,0.8); font-size: 14px; margin-bottom: 5px;">
                        <i class="bx bx-phone me-2"></i>(021) 1234-5678
                      </p>
                      <p style="color: rgba(255,255,255,0.8); font-size: 14px; margin-bottom: 0;">
                        <i class="bx bx-envelope me-2"></i>info@smkxyz.sch.id
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- End Registration Section -->

    

  </main><!-- End #main -->
@endsection

@push('styles')
  <style>
    .step {
      background-color: #ffffff;
      border-radius: 8px;
      padding: 30px 20px;
      box-shadow: 0 2px 10px rgba(78, 78, 78, 0.194);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .step:hover {
      transform: translateY(-5px);
      box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    }
  </style>
@endpush

