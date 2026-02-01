@extends('home.layouts.app')

@section('content')
      <!-- Start Section Banner Area -->
        <div class="section-banner bg-1">
            <div class="container">
                <div class="banner-spacing">
                    <div class="section-info">
                        <h2 data-aos="fade-up" data-aos-delay="100">Tentang SMK PGRI Lawang</h2>
                        <p data-aos="fade-up" data-aos-delay="200">SMK PGRI Lawang adalah lembaga pendidikan vokasional yang berdedikasi untuk menghasilkan lulusan berkualitas, siap kerja, dan berdaya saing global.</p>
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
                                <i class='bx bxs-graduation'></i> <p>Tentang SMK PGRI Lawang</p>
                            </div>
                             <h2 class="title-anim">Mencetak Lulusan Berkualitas</h2>
                            <p class="title-anim">SMK PGRI Lawang berkomitmen untuk menghasilkan lulusan yang siap kerja, berkarakter, dan berdaya saing global. Dengan pengalaman lebih dari 17 tahun, kami terus berinovasi dalam pendidikan vokasional.</p>
                            <a class="default-btn" href="#">Kunjungi Sekolah</a>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="row justify-content-end">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <div class="content" data-aos="fade-right" data-aos-delay="100">
                                <img src="{{ asset('frontend/assets/img/all-img/tentara.jpeg') }}" alt="image">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <div class="notice-content" data-aos="fade-right" data-aos-delay="200">
                                    <i class='bx bxs-quote-left'></i>
                                    <h4 class="title-anim">Pendidikan Berkualitas</h4>
                                    <p class="title-anim">Kami menyediakan lingkungan belajar yang kondusif dengan kurikulum berbasis industri dan tenaga pengajar profesional.</p>
                                    <div class="author-info">
                                        <span>Kepala Sekolah</span>
                                        <h5>Dr. Budi Santoso, S.Pd., M.Pd.</h5>
                                        <p>Kepala Sekolah SMK PGRI Lawang</p>
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
                        <i class='bx bxs-graduation'></i> <p>Program Keahlian</p>
                    </div>
                    <h2>Program Keahlian Unggulan</h2>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="100">
                            <img src="{{ asset('frontend/assets/img/all-img/kimia.jpeg') }}" alt="icon">
                            <h4>Teknik Kimia Industri</h4>
                            <p>Program keahlian yang fokus pada proses kimia industri dan pengembangan produk kimia.</p>
                            <a href="#">Learn More <i class='bx bx-right-arrow-alt'></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="200">
                            <img src="{{ asset('frontend/assets/img/all-img/tkj.jpeg') }}" alt="icon">
                            <h4>Teknik Komputer & Jaringan</h4>
                            <p>Program keahlian yang mempelajari instalasi jaringan komputer dan administrasi sistem.</p>
                            <a href="#">Learn More <i class='bx bx-right-arrow-alt'></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="300">
                            <img src="{{ asset('frontend/assets/img/all-img/tsm.jpg') }}" alt="icon">
                            <h4>Teknik Sepeda Motor</h4>
                            <p>Program keahlian yang fokus pada perbaikan dan perawatan sepeda motor.</p>
                            <a href="#">Learn More <i class='bx bx-right-arrow-alt'></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="400">
                            <img src="{{ asset('frontend/assets/img/all-img/tkr.jpg') }}" alt="icon">
                            <h4>Teknik Kendaraan Ringan</h4>
                            <p>Program keahlian yang fokus pada perbaikan sistem kendaraan ringan mobil.</p>
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
                                <i class='bx bxs-graduation'></i> <p>Fasilitas Sekolah</p>
                            </div>
                            <h2>Sarana & Prasarana SMK PGRI Lawang</h2>

                            <p>SMK PGRI Lawang dilengkapi dengan fasilitas modern untuk mendukung pembelajaran praktik dan teori.</p>
                            <p>Kami memiliki laboratorium komputer, bengkel praktik, ruang kelas ber-AC, perpustakaan, dan area olahraga.</p>
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
                                <i class='bx bxs-graduation'></i> <p>Prestasi</p>
                            </div>
                            <h2>Kelulusan Unggulan</h2>

                            <p>SMK PGRI Lawang bangga dengan kelulusan yang berkualitas dan siap kerja.</p>
                            <p>Setiap tahun kami meluluskan siswa-siswi berprestasi yang diterima di berbagai industri ternama.</p>
                        </div>
                    </div>
                </div>
                <div class="row g-0 align-items-center flex-column-reverse flex-lg-row">
                    <div class="col-lg-6">
                        <div class="content" data-aos="fade-up" data-aos-delay="100">
                            <div class="sub-title">
                                <i class='bx bxs-graduation'></i> <p>Nilai-Nilai</p>
                            </div>
                            <h2>Visi & Misi</h2>

                            <p><strong>Visi:</strong> Menjadi sekolah vokasional unggulan yang menghasilkan lulusan berkarakter, kompeten, dan siap bersaing.</p>
                            <p><strong>Misi:</strong> Memberikan pendidikan berkualitas dengan mengembangkan potensi siswa melalui pembelajaran terpadu.</p>
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
                    <h2>Daftar Sekarang di SMK PGRI Lawang</h2>
                </div>

                <div class="subscribe-btn text-center" data-aos="fade-up" data-aos-delay="100">
                    <a class="default-btn" href="application-form.html">Daftar Sekarang</a>
                </div>
            </div>
        </div> 
        <!-- End Subscribe Area -->
@endsection