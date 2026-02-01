@extends('home.layouts.app')

@section('content')
 <!-- Start Section Banner Area -->
        <div class="section-banner bg-1">
            <div class="container">
                <div class="banner-spacing">
                    <div class="section-info">
                        <h2 data-aos="fade-up" data-aos-delay="100">Teknik Kendaraan Ringan</h2>
                        <p data-aos="fade-up" data-aos-delay="200">Program keahlian TKR SMK PGRI Lawang dirancang untuk mempersiapkan siswa menjadi ahli dalam perbaikan sistem kendaraan ringan mobil, diagnosa masalah mesin, dan maintenance kendaraan modern.</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Section Banner Area -->

        <!-- Start About Us Area -->
        <div class="about-us-area ptb-100">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-5 col-md-12">
                        <div class="text-content" data-aos="fade-up" data-aos-delay="100">
                            <div class="sub-title">
                                <i class='bx bxs-graduation'></i> <p>Tentang Jurusan TKR</p>
                            </div>
                             <h2 class="title-anim">Ahli Kendaraan Ringan</h2>
                            <p class="title-anim">Program TKR SMK PGRI Lawang membekali siswa dengan kemampuan perbaikan sistem kendaraan ringan, diagnosa mesin, kelistrikan, dan sistem transmisi mobil modern.</p>
                            <a class="default-btn" href="{{ route('pendaftaran.index') }}">Daftar Sekarang</a>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="row justify-content-end">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <div class="content" data-aos="fade-right" data-aos-delay="100">
                                <img src="{{ asset('frontend/assets/img/all-img/tkr.jpg') }}" alt="TKR Workshop">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <div class="notice-content" data-aos="fade-right" data-aos-delay="200">
                                    <i class='bx bxs-quote-left'></i>
                                    <h4 class="title-anim">Pembelajaran Praktik</h4>
                                    <p class="title-anim">Kami menyediakan bengkel praktik modern dengan peralatan diagnostik terkini untuk mendukung pembelajaran perbaikan kendaraan.</p>
                                    <div class="author-info">
                                        <span>Kepala Bengkel TKR</span>
                                        <h5>Bambang Sutrisno, S.T.</h5>
                                        <p>Koordinator Program TKR</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End About Us Area -->

        <!-- Start Academics Area -->
        <div class="academics-area bg-color-1 ptb-100" >
            <div class="container">
                <div class="section-title" data-aos="fade-up" data-aos-delay="100">
                    <div class="sub-title">
                        <i class='bx bxs-graduation'></i> <p>Kompetensi TKR</p>
                    </div>
                    <h2>Kompetensi yang Dipelajari</h2>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="100">
                            <img src="{{ asset('frontend/assets/img/all-img/engine-repair.jpg') }}" alt="icon">
                            <h4>Mesin Kendaraan</h4>
                            <p>Perbaikan mesin bensin, diesel, sistem pendingin, dan komponen mesin kendaraan ringan.</p>
                            <a href="#">Learn More <i class='bx bx-right-arrow-alt'></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="200">
                            <img src="{{ asset('frontend/assets/img/all-img/transmission.jpg') }}" alt="icon">
                            <h4>Sistem Transmisi</h4>
                            <p>Perbaikan transmisi manual, otomatis, kopling, dan sistem penggerak roda kendaraan.</p>
                            <a href="#">Learn More <i class='bx bx-right-arrow-alt'></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="300">
                            <img src="{{ asset('frontend/assets/img/all-img/electrical-system.jpg') }}" alt="icon">
                            <h4>Kelistrikan</h4>
                            <p>Diagnosa dan perbaikan sistem kelistrikan, wiring, electronic control unit, dan komponen elektrik.</p>
                            <a href="#">Learn More <i class='bx bx-right-arrow-alt'></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="400">
                            <img src="{{ asset('frontend/assets/img/all-img/diagnostics.jpg') }}" alt="icon">
                            <h4>Diagnostik</h4>
                            <p>Penggunaan alat diagnostik modern, scanning ECU, dan analisis masalah kendaraan.</p>
                            <a href="#">Learn More <i class='bx bx-right-arrow-alt'></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Academics Area -->
       
        <!-- Start Campus Tour Area -->
        <div class="campus-tour">
            <div class="container-fluid p-0">
                <div class="row g-0 align-items-center flex-column-reverse flex-lg-row">
                    <div class="col-lg-6">
                        <div class="content" data-aos="fade-up" data-aos-delay="100">
                            <div class="sub-title">
                                <i class='bx bxs-graduation'></i> <p>Fasilitas TKR</p>
                            </div>
                            <h2>Bengkel Kendaraan Ringan</h2>

                            <p>Bengkel TKR dilengkapi dengan lift mobil, alat diagnostik, peralatan perbaikan mesin, dan sistem scanning modern.</p>
                            <p>Siswa mendapatkan pengalaman langsung dengan kendaraan nyata dan simulasi perbaikan industri.</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="image cp-bg-1" data-aos="fade-zoom-in" data-aos-delay="100">
                        </div>
                    </div>
                </div>
                <div class="row g-0 align-items-center">
                    
                    <div class="col-lg-6">
                        <div class="image cp-bg-2" data-aos="fade-zoom-in" data-aos-delay="100">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="content" data-aos="fade-up" data-aos-delay="100">
                            <div class="sub-title">
                                <i class='bx bxs-graduation'></i> <p>Prospek Karir</p>
                            </div>
                            <h2>Peluang Karir Lulusan TKR</h2>

                            <p>Lulusan TKR siap bekerja sebagai Automotive Technician, Service Advisor, Parts Manager, dan Workshop Supervisor.</p>
                            <p>Banyak lulusan kami yang diterima di dealer resmi, bengkel independen, dan industri otomotif.</p>
                        </div>
                    </div>
                </div>
                <div class="row g-0 align-items-center flex-column-reverse flex-lg-row">
                    <div class="col-lg-6">
                        <div class="content" data-aos="fade-up" data-aos-delay="100">
                            <div class="sub-title">
                                <i class='bx bxs-graduation'></i> <p>Sertifikasi</p>
                            </div>
                            <h2>Sertifikasi Kompetensi</h2>

                            <p><strong>Sertifikasi Nasional:</strong> BNSP (Badan Nasional Sertifikasi Profesi) untuk Teknisi Kendaraan Ringan.</p>
                            <p><strong>Sertifikasi Internasional:</strong> ASEAN Automotive Technician, Toyota Technical Education, dan Honda Technical Training.</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="image cp-bg-3" data-aos="fade-zoom-in" data-aos-delay="100">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Campus Tour Area -->

        <!-- Start Subscribe Area -->
        <div class="subscribe-area ptb-100">
            <div class="container">
                <div class="section-title" data-aos="fade-up" data-aos-delay="100">
                    <div class="sub-title">
                        <i class='bx bxs-graduation'></i> <p>Informasi Pendaftaran</p>
                    </div>
                    <h2>Daftar Sekarang di Jurusan TKR</h2>
                </div>

                <div class="subscribe-btn text-center" data-aos="fade-up" data-aos-delay="100">
                    <a class="default-btn" href="{{ route('pendaftaran.index') }}">Daftar Sekarang</a>
                </div>
            </div>
        </div> 
        <!-- End Subscribe Area -->
@endsection