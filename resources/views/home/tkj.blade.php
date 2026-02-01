@extends('home.layouts.app')

@section('content')
 <!-- Start Section Banner Area -->
        <div class="section-banner bg-1">
            <div class="container">
                <div class="banner-spacing">
                    <div class="section-info">
                        <h2 data-aos="fade-up" data-aos-delay="100">Teknik Komputer dan Jaringan</h2>
                        <p data-aos="fade-up" data-aos-delay="200">Program keahlian TKJ SMK PGRI Lawang dirancang untuk mempersiapkan siswa menjadi ahli dalam instalasi, konfigurasi, dan maintenance jaringan komputer serta sistem keamanan jaringan.</p>
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
                                <i class='bx bxs-graduation'></i> <p>Tentang Jurusan TKJ</p>
                            </div>
                             <h2 class="title-anim">Ahli Jaringan Komputer</h2>
                            <p class="title-anim">Program TKJ SMK PGRI Lawang membekali siswa dengan kemampuan perakitan komputer, administrasi jaringan, instalasi jaringan kabel & nirkabel, pengelolaan server, hingga dasar keamanan jaringan.</p>
                            <a class="default-btn" href="{{ route('pendaftaran.index') }}">Daftar Sekarang</a>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="row justify-content-end">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <div class="content" data-aos="fade-right" data-aos-delay="100">
                                <img src="{{ asset('frontend/assets/img/all-img/tkj.jpeg') }}" alt="TKJ Lab">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <div class="notice-content" data-aos="fade-right" data-aos-delay="200">
                                    <i class='bx bxs-quote-left'></i>
                                    <h4 class="title-anim">Pembelajaran Praktik</h4>
                                    <p class="title-anim">Kami menyediakan laboratorium modern dengan perangkat keras dan lunak terkini untuk mendukung pembelajaran praktik jaringan komputer.</p>
                                    <div class="author-info">
                                        <span>Kepala Lab TKJ</span>
                                        <h5>Ahmad Fauzi, S.Kom., M.T.</h5>
                                        <p>Koordinator Program TKJ</p>
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
                        <i class='bx bxs-graduation'></i> <p>Kompetensi TKJ</p>
                    </div>
                    <h2>Kompetensi yang Dipelajari</h2>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="100">
                            <img src="{{ asset('frontend/assets/img/all-img/networking.jpg') }}" alt="icon">
                            <h4>Jaringan Komputer</h4>
                            <p>Instalasi dan konfigurasi jaringan LAN, WLAN, serta perangkat jaringan seperti router dan switch.</p>
                            <a href="#">Learn More <i class='bx bx-right-arrow-alt'></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="200">
                            <img src="{{ asset('frontend/assets/img/all-img/server.jpg') }}" alt="icon">
                            <h4>Administrasi Server</h4>
                            <p>Pengelolaan server, instalasi sistem operasi, dan konfigurasi layanan jaringan.</p>
                            <a href="#">Learn More <i class='bx bx-right-arrow-alt'></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="300">
                            <img src="{{ asset('frontend/assets/img/all-img/security.jpg') }}" alt="icon">
                            <h4>Keamanan Jaringan</h4>
                            <p>Implementasi keamanan jaringan, firewall, dan proteksi sistem dari ancaman cyber.</p>
                            <a href="#">Learn More <i class='bx bx-right-arrow-alt'></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="400">
                            <img src="{{ asset('frontend/assets/img/all-img/troubleshooting.jpg') }}" alt="icon">
                            <h4>Troubleshooting</h4>
                            <p>Diagnosis dan perbaikan masalah jaringan, hardware, dan sistem komputer.</p>
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
                                <i class='bx bxs-graduation'></i> <p>Fasilitas TKJ</p>
                            </div>
                            <h2>Laboratorium Jaringan Komputer</h2>

                            <p>Laboratorium TKJ dilengkapi dengan router, switch, access point, firewall, dan server untuk praktik jaringan.</p>
                            <p>Siswa mendapatkan pengalaman langsung dengan perangkat jaringan industri dan simulasi lingkungan kerja IT.</p>
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
                            <h2>Peluang Karir Lulusan TKJ</h2>

                            <p>Lulusan TKJ siap bekerja sebagai Network Administrator, IT Support, Teknisi Jaringan, dan System Administrator.</p>
                            <p>Banyak lulusan kami yang diterima di perusahaan teknologi, lembaga pendidikan, dan instansi pemerintahan.</p>
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

                            <p><strong>Sertifikasi Nasional:</strong> BNSP (Badan Nasional Sertifikasi Profesi) untuk Teknisi Jaringan.</p>
                            <p><strong>Sertifikasi Internasional:</strong> Cisco CCNA, CompTIA Network+, dan Microsoft Certified Professional.</p>
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
                    <h2>Daftar Sekarang di Jurusan TKJ</h2>
                </div>

                <div class="subscribe-btn text-center" data-aos="fade-up" data-aos-delay="100">
                    <a class="default-btn" href="{{ route('pendaftaran.index') }}">Daftar Sekarang</a>
                </div>
            </div>
        </div> 
        <!-- End Subscribe Area -->
@endsection