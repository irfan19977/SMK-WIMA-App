@extends('home.layouts.app')

@section('content')
 <!-- Start Section Banner Area -->
        <div class="section-banner bg-1">
            <div class="container">
                <div class="banner-spacing">
                    <div class="section-info">
                        <h2 data-aos="fade-up" data-aos-delay="100">Teknik Bisnis Sepeda Motor</h2>
                        <p data-aos="fade-up" data-aos-delay="200">Program keahlian TBSM SMK PGRI Lawang dirancang untuk mempersiapkan siswa menjadi ahli dalam perbaikan sepeda motor, manajemen bengkel, dan bisnis otomotif sepeda motor.</p>
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
                                <i class='bx bxs-graduation'></i> <p>Tentang Jurusan TBSM</p>
                            </div>
                             <h2 class="title-anim">Ahli Sepeda Motor</h2>
                            <p class="title-anim">Program TBSM SMK PGRI Lawang membekali siswa dengan kemampuan perbaikan sepeda motor, manajemen bengkel, pelayanan pelanggan, dan kewirausahaan otomotif.</p>
                            <a class="default-btn" href="{{ route('pendaftaran.index') }}">Daftar Sekarang</a>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="row justify-content-end">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <div class="content" data-aos="fade-right" data-aos-delay="100">
                                <img src="{{ asset('frontend/assets/img/all-img/tsm.jpg') }}" alt="TBSM Workshop">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <div class="notice-content" data-aos="fade-right" data-aos-delay="200">
                                    <i class='bx bxs-quote-left'></i>
                                    <h4 class="title-anim">Pembelajaran Praktik</h4>
                                    <p class="title-anim">Kami menyediakan bengkel sepeda motor lengkap dengan area praktik dan simulasi bisnis untuk mendukung pembelajaran.</p>
                                    <div class="author-info">
                                        <span>Kepala Bengkel TBSM</span>
                                        <h5>Susilo Wibowo, S.Pd., M.T.</h5>
                                        <p>Koordinator Program TBSM</p>
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
                        <i class='bx bxs-graduation'></i> <p>Kompetensi TBSM</p>
                    </div>
                    <h2>Kompetensi yang Dipelajari</h2>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="100">
                            <img src="{{ asset('frontend/assets/img/all-img/motorcycle-engine.jpg') }}" alt="icon">
                            <h4>Mesin Sepeda Motor</h4>
                            <p>Perbaikan mesin 2-tak dan 4-tak, sistem pendingin, karburator, dan injeksi bahan bakar sepeda motor.</p>
                            <a href="#">Learn More <i class='bx bx-right-arrow-alt'></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="200">
                            <img src="{{ asset('frontend/assets/img/all-img/chassis-system.jpg') }}" alt="icon">
                            <h4>Sasis & Rangka</h4>
                            <p>Perbaikan rangka, suspensi, rem, ban, dan sistem kemudi sepeda motor.</p>
                            <a href="#">Learn More <i class='bx bx-right-arrow-alt'></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="300">
                            <img src="{{ asset('frontend/assets/img/all-img/electrical-motorcycle.jpg') }}" alt="icon">
                            <h4>Kelistrikan</h4>
                            <p>Diagnosa dan perbaikan sistem pengapian, lampu, kelistrikan body, dan komponen elektrik.</p>
                            <a href="#">Learn More <i class='bx bx-right-arrow-alt'></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="400">
                            <img src="{{ asset('frontend/assets/img/all-img/business-management.jpg') }}" alt="icon">
                            <h4>Manajemen Bengkel</h4>
                            <p>Pengelolaan bengkel, pelayanan pelanggan, manajemen stok, dan kewirausahaan otomotif.</p>
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
                                <i class='bx bxs-graduation'></i> <p>Fasilitas TBSM</p>
                            </div>
                            <h2>Bengkel Sepeda Motor</h2>

                            <p>Bengkel TBSM dilengkapi dengan area praktik, alat perbaikan, area servis, dan simulasi bisnis otomotif.</p>
                            <p>Siswa mendapatkan pengalaman langsung dengan berbagai merek sepeda motor dan simulasi usaha bengkel.</p>
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
                            <h2>Peluang Karir Lulusan TBSM</h2>

                            <p>Lulusan TBSM siap bekerja sebagai Motorcycle Technician, Service Advisor, Parts Manager, dan Entrepreneur Otomotif.</p>
                            <p>Banyak lulusan kami yang sukses membuka bengkel sendiri atau bekerja di dealer resmi dan bengkel ternama.</p>
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

                            <p><strong>Sertifikasi Nasional:</strong> BNSP (Badan Nasional Sertifikasi Profesi) untuk Teknisi Sepeda Motor.</p>
                            <p><strong>Sertifikasi Merek:</strong> Yamaha Technical Academy, Honda Technical Training, Suzuki Technical Education.</p>
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
                    <h2>Daftar Sekarang di Jurusan TBSM</h2>
                </div>

                <div class="subscribe-btn text-center" data-aos="fade-up" data-aos-delay="100">
                    <a class="default-btn" href="{{ route('pendaftaran.index') }}">Daftar Sekarang</a>
                </div>
            </div>
        </div> 
        <!-- End Subscribe Area -->
@endsection