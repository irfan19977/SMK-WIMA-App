@extends('home.layouts.app')

@section('content')
 <!-- Start Section Banner Area -->
        <div class="section-banner bg-1">
            <div class="container">
                <div class="banner-spacing">
                    <div class="section-info">
                        <h2 data-aos="fade-up" data-aos-delay="100">Teknik Kimia Industri</h2>
                        <p data-aos="fade-up" data-aos-delay="200">Program keahlian TKI SMK PGRI Lawang dirancang untuk mempersiapkan siswa menjadi ahli dalam proses kimia industri, pengembangan produk, dan kontrol kualitas industri kimia.</p>
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
                                <i class='bx bxs-graduation'></i> <p>Tentang Jurusan TKI</p>
                            </div>
                             <h2 class="title-anim">Ahli Kimia Industri</h2>
                            <p class="title-anim">Program TKI SMK PGRI Lawang membekali siswa dengan kemampuan proses kimia industri, analisis kualitas, pengembangan produk, dan manajemen produksi industri kimia.</p>
                            <a class="default-btn" href="{{ route('pendaftaran.index') }}">Daftar Sekarang</a>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="row justify-content-end">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <div class="content" data-aos="fade-right" data-aos-delay="100">
                                <img src="{{ asset('frontend/assets/img/all-img/kimia.jpeg') }}" alt="TKI Lab">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <div class="notice-content" data-aos="fade-right" data-aos-delay="200">
                                    <i class='bx bxs-quote-left'></i>
                                    <h4 class="title-anim">Pembelajaran Praktik</h4>
                                    <p class="title-anim">Kami menyediakan laboratorium kimia modern dengan peralatan analisis dan proses industri untuk mendukung pembelajaran praktik.</p>
                                    <div class="author-info">
                                        <span>Kepala Lab TKI</span>
                                        <h5>Drs. Hendra Wijaya, M.Si.</h5>
                                        <p>Koordinator Program TKI</p>
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
                        <i class='bx bxs-graduation'></i> <p>Kompetensi TKI</p>
                    </div>
                    <h2>Kompetensi yang Dipelajari</h2>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="100">
                            <img src="{{ asset('frontend/assets/img/all-img/chemical-process.jpg') }}" alt="icon">
                            <h4>Proses Kimia</h4>
                            <p>Operasi unit proses kimia, reaktor, distilasi, dan pemisahan dalam industri kimia.</p>
                            <a href="#">Learn More <i class='bx bx-right-arrow-alt'></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="200">
                            <img src="{{ asset('frontend/assets/img/all-img/quality-control.jpg') }}" alt="icon">
                            <h4>Kontrol Kualitas</h4>
                            <p>Analisis kualitas produk, pengujian laboratorium, dan standar QC industri.</p>
                            <a href="#">Learn More <i class='bx bx-right-arrow-alt'></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="300">
                            <img src="{{ asset('frontend/assets/img/all-img/product-development.jpg') }}" alt="icon">
                            <h4>Pengembangan Produk</h4>
                            <p>Formulasi produk, uji stabilitas, dan inovasi produk kimia industri.</p>
                            <a href="#">Learn More <i class='bx bx-right-arrow-alt'></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="400">
                            <img src="{{ asset('frontend/assets/img/all-img/safety-management.jpg') }}" alt="icon">
                            <h4>K3 & Lingkungan</h4>
                            <p>Manajemen keselamatan kerja, penanganan limbah, dan pengelolaan lingkungan industri.</p>
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
                                <i class='bx bxs-graduation'></i> <p>Fasilitas TKI</p>
                            </div>
                            <h2>Laboratorium Kimia Industri</h2>

                            <p>Laboratorium TKI dilengkapi dengan reaktor, alat distilasi, peralatan analisis kualitas, dan peralatan K3 untuk praktik industri.</p>
                            <p>Siswa mendapatkan pengalaman langsung dengan proses kimia industri dan simulasi lingkungan pabrik kimia.</p>
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
                            <h2>Peluang Karir Lulusan TKI</h2>

                            <p>Lulusan TKI siap bekerja sebagai Chemical Operator, QC Analyst, Production Supervisor, dan Lab Analyst.</p>
                            <p>Banyak lulusan kami yang diterima di industri farmasi, makanan & minuman, kosmetik, dan petrokimia.</p>
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

                            <p><strong>Sertifikasi Nasional:</strong> BNSP (Badan Nasional Sertifikasi Profesi) untuk Teknisi Kimia Industri.</p>
                            <p><strong>Sertifikasi Internasional:</strong> ISO 9001 Quality Management, OHSAS 18001 Safety Management, dan ISO 14001 Environmental Management.</p>
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
                    <h2>Daftar Sekarang di Jurusan TKI</h2>
                </div>

                <div class="subscribe-btn text-center" data-aos="fade-up" data-aos-delay="100">
                    <a class="default-btn" href="{{ route('pendaftaran.index') }}">Daftar Sekarang</a>
                </div>
            </div>
        </div> 
        <!-- End Subscribe Area -->
@endsection