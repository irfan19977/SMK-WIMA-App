@extends('home.layouts.app')

@section('content')
    <!-- Start Clgun Banner 2 Area -->
        <div class="banner-area-2 big-bg-2">
            <div class="container">
                <div class="banner-content-2">
                    <div class="content">
                        <span data-aos="fade-zoom-in" data-aos-delay="300">Sekolah Menengah Kejuruan Unggulan di Lawang</span>
                        <h1 data-aos="fade-up" data-aos-delay="200">SMK PGRI Lawang</h1>
                        <p data-aos="fade-up" data-aos-delay="300">Berkomitmen untuk memberikan pendidikan vokasi berkualitas tinggi yang mempersiapkan siswa untuk menjadi tenaga terampil siap kerja.</p>
                        <div class="buttons-action" data-aos="fade-up" data-aos-delay="100">
                            <a class="default-btn" href="{{ route('pendaftaran.index') }}">Daftar Sekarang</a>
                            <a class="default-btn btn-style-2" href="{{ route('contact.index') }}">Hubungi Kami</a>
                        </div>

                        <div class="scroll-down" data-aos="fade-down" data-aos-delay="100">
                           <a href="#about"><i class='bx bx-chevron-down'></i></a> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Clgun Banner 2 Area -->

        <!-- Start About Us Area 2 -->
        <div id="about" class="about-us-area-2 ptb-100">
            <div class="container">
                <div class="section-title" data-aos="fade-up" data-aos-delay="100">
                    <h2>Tentang SMK PGRI Lawang</h2>
                    <p>SMK PGRI Lawang adalah lembaga pendidikan vokasional yang berdedikasi untuk menghasilkan lulusan berkualitas, siap kerja, dan berdaya saing global.</p>
                </div>
                <div class="about-content-courser owl-carousel owl-theme">
                    <div class="content-items" data-dot="<button>01</button>">
                        <div class="image ct-bg-1" data-aos="fade-zoom-in" data-aos-delay="100">
                        </div>
                        <div class="content" data-aos="fade-up" data-aos-delay="200">
                            <span>Visi & Misi</span>
                            <h2>Mencetak Lulusan Berkualitas</h2>
                            <p>Visi kami menjadi sekolah vokasional unggulan yang menghasilkan lulusan berkarakter, kompeten, dan siap bersaing di era global.</p>
                            <a class="default-btn" href="{{ route('contact.index') }}">Jadwalkan Kunjungan</a>
                        </div>
                    </div>
                    <div class="content-items" data-dot="<button>02</button>">
                        <div class="image ct-bg-2" data-aos="fade-zoom-in" data-aos-delay="100">
                        </div>
                        <div class="content" data-aos="fade-up" data-aos-delay="200">
                            <span>Program Unggulan</span>
                            <h2>Berbagai Jurusan Pilihan</h2>
                            <p>Tersedia 4 jurusan unggulan: Teknik Kimia Industri, Teknik Komputer dan Jaringan, Teknik Sepeda Motor, dan Teknik Kendaraan Ringan.</p>
                            <a class="default-btn" href="{{ route('contact.index') }}">Jadwalkan Kunjungan</a>
                        </div>
                    </div>
                    <div class="content-items" data-dot="<button>03</button>">
                        <div class="image ct-bg-3" data-aos="fade-zoom-in" data-aos-delay="100">
                        </div>
                        <div class="content" data-aos="fade-up" data-aos-delay="200">
                            <span>Fasilitas Modern</span>
                            <h2>Lingkungan Belajar Digital</h2>
                            <p>Dilengkapi laboratorium komputer, bengkel praktik, laboratorium kimia, ruang kelas, akses internet, dan fasilitas lainnya untuk mendukung pembelajaran modern.</p>
                            <a class="default-btn" href="{{ route('contact.index') }}">Jadwalkan Kunjungan</a>
                        </div>
                    </div>
                    <div class="content-items" data-dot="<button>04</button>">
                        <div class="image ct-bg-2" data-aos="fade-zoom-in" data-aos-delay="100">
                        </div>
                        <div class="content" data-aos="fade-up" data-aos-delay="200">
                            <span>Prestasi & Alumni</span>
                            <h2>Membangun Karir Sukses</h2>
                            <p>Alumni kami tersebar di berbagai industri dan banyak yang telah menjadi wirausaha sukses di bidangnya masing-masing.</p>
                            <a class="default-btn" href="{{ route('contact.index') }}">Jadwalkan Kunjungan</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- End About Us Area 2 -->

        <!-- Start Features Area 2 -->
        <div class="features-area-2">
            <div class="features-content-2 ptb-100">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                            <div class="sub-title">
                                <p>Program Unggulan Kami</p>
                            </div>
                            <div class="content">
                                <h2>Siswa kami menciptakan lingkungan yang inklusif dan berprestasi</h2>
                                <div class="item">
                                    <div class="item-content">
                                        <div class="icon">
                                            <img src="{{ asset('frontend/assets/img/icon/features-icon-2.png') }}" alt="image">
                                        </div>
                                        <h3>Pendidikan Vokasional</h3>
                                        <p>Program keahlian komprehensif yang disesuaikan dengan kebutuhan industri dan dunia kerja modern.</p>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="item-content">
                                        <div class="icon">
                                            <img src="{{ asset('frontend/assets/img/icon/features-icon-1.png') }}" alt="image">
                                        </div>
                                        <h3>Jurusan Unggulan</h3>
                                        <p>Teknik Kimia Industri, Teknik Komputer dan Jaringan, Teknik Sepeda Motor, dan Teknik Kendaraan Ringan.</p>
                                    </div>
                                </div>
                                <a class="default-btn" href="{{ route('pendaftaran.index') }}">Info Pendaftaran</a>

                                <div class="arrow-icon">
                                    <img src="{{ asset('frontend/assets/img/icon/shape-1.png') }}" alt="image">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="academic-content">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-6 col-md-6 pt-25">
                                        <div class="academic-item" data-aos="fade-up" data-aos-delay="100">
                                            <div class="image">
                                                <img src="{{ asset('frontend/assets/img/all-img/kimia.jpeg') }}" alt="image">
                                                <div class="number">
                                                    <h3>01</h3>
                                                </div>
                                            </div>
                                            <div class="content">
                                                <h4>Teknik Kimia Industri</h4>
                                                <a class="btn" href="{{ route('kimia.index') }}">Pelajari Lebih Lanjut <i class='bx bx-right-arrow-alt'></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-6 col-md-6">
                                        <div class="academic-item" data-aos="fade-up" data-aos-delay="200">
                                            <div class="image">
                                                <img src="{{ asset('frontend/assets/img/all-img/tkj.jpeg') }}" alt="image">
                                                <div class="number">
                                                    <h3>02</h3>
                                                </div>
                                            </div>
                                            <div class="content">
                                                <h4>Teknik Komputer & Jaringan</h4>
                                                <a class="btn" href="{{ route('tkj.index') }}">Pelajari Lebih Lanjut <i class='bx bx-right-arrow-alt'></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-6 col-md-6 pt-25">
                                        <div class="academic-item" data-aos="fade-up" data-aos-delay="300">
                                            <div class="image">
                                                <img src="{{ asset('frontend/assets/img/all-img/tsm.jpg') }}" alt="image">
                                                <div class="number">
                                                    <h3>03</h3>
                                                </div>
                                            </div>
                                            <div class="content">
                                                <h4>Teknik Sepeda Motor</h4>
                                                <a class="btn" href="{{ route('tbsm.index') }}">Pelajari Lebih Lanjut <i class='bx bx-right-arrow-alt'></i></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-6 col-md-6">
                                        <div class="academic-item" data-aos="fade-up" data-aos-delay="400">
                                            <div class="image">
                                                <img src="{{ asset('frontend/assets/img/all-img/tkr.jpg') }}" alt="image">
                                                <div class="number">
                                                    <h3>04</h3>
                                                </div>
                                            </div>
                                            <div class="content">
                                                <h4>Teknik Kendaraan Ringan</h4>
                                                <a class="btn" href="{{ route('tkr.index') }}">Pelajari Lebih Lanjut <i class='bx bx-right-arrow-alt'></i></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Features Area 2 -->

        <!-- Start Video Area  -->
        <div class="video-area">
            <div class="container">
                <div class="video-play-btn" data-aos="fade-zoom-in" data-aos-delay="100">
                    <a class="popup-youtube" href="https://youtu.be/rh721JJV_ZA?si=SbZusAUqE2TVW0_i" data-mfp-src="https://www.youtube.com/watch?v=rh721JJV_ZA">Play</a>
                </div>
            </div>
        </div>
        <!-- End Video Area  -->

        <!-- Start News Area 2 -->
        <div class="news-area ptb-100">
            <div class="container">
                <div class="section-title section-title-2" data-aos="fade-up" data-aos-delay="100">
                    <div class="sub-title">
                        <p>Berita Sekolah</p>
                    </div>
                    <h2>Kisah Tentang Siswa, Prestasi, dan Inovasi di SMK PGRI Lawang</h2>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="news-content">
                            <ul>
                                @forelse($homepageNews->take(2) as $index => $news)
                                <li class="news-item" data-aos="fade-up" data-aos-delay="{{ 100 + ($index * 100) }}">
                                    <div class="image">
                                        @if($news->image)
                                            <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}">
                                        @else
                                            <img src="{{ asset('frontend/assets/img/all-img/news-image-1.png') }}" alt="image">
                                        @endif
                                    </div>
                                    <div class="content">
                                        <div class="sub-title">
                                            <i class='bx bxs-graduation'></i> <p>{{ $news->category ?? 'Berita Sekolah' }}</p>
                                        </div>
                                        <h2><a href="{{ route('berita.detail', $news->slug) }}">{{ $news->title }}</a></h2>
                                        <p>{{ Str::limit(strip_tags($news->content), 100) }}</p>
                                        <a class="btn" href="{{ route('berita.detail', $news->slug) }}">Baca Selengkapnya...</a>
                                    </div>
                                </li>
                                @empty
                                <li class="news-item" data-aos="fade-up" data-aos-delay="100">
                                    <div class="image">
                                        <img src="{{ asset('frontend/assets/img/all-img/news-image-1.png') }}" alt="image">
                                    </div>
                                    <div class="content">
                                        <div class="sub-title">
                                            <i class='bx bxs-graduation'></i> <p>Teknologi dan Inovasi</p>
                                        </div>
                                        <h2><a href="blog-details.html">Siswa SMK PGRI Lawang Ciptakan Robot Pembersih Ruangan</a></h2>
                                        <p>Para siswa jurusan Teknik Elektronika berhasil menciptakan robot inovatif untuk membantu kebersihan ruangan kelas.</p>
                                        <a class="btn" href="blog-details.html">Baca Selengkapnya...</a>
                                    </div>
                                </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="news-content-right" data-aos="fade-up" data-aos-delay="100">
                            @if($homepageNews->skip(2)->first())
                            <div class="content-box">
                                @if($homepageNews[2]->image)
                                    <img src="{{ asset('storage/' . $homepageNews[2]->image) }}" alt="{{ $homepageNews[2]->title }}">
                                @else
                                    <img src="{{ asset('frontend/assets/img/all-img/news-image-3.png') }}" alt="image">
                                @endif
                                <div class="content">
                                    <h3><a href="{{ route('berita.detail', $homepageNews[2]->slug) }}">{{ $homepageNews[2]->title }}</a></h3>
                                    <p>{{ Str::limit(strip_tags($homepageNews[2]->content), 80) }}</p>
                                    <a class="btn" href="{{ route('berita.detail', $homepageNews[2]->slug) }}">Baca Selengkapnya...</a>
                                </div>
                            </div>
                            @else
                            <div class="content-box">
                                <img src="{{ asset('frontend/assets/img/all-img/news-image-3.png') }}" alt="iamge">
                                <div class="content">
                                    <h3><a href="blog-details.html">Gender inequality in higher education persists</a></h3>
                                    <p>Lorem ipsum dolor sit amet conse sed do eiusm tem incid idunt ut labore.</p>
                                    <a class="btn" href="blog-details.html">Continue Reading...</a>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="news-content-item" data-aos="fade-up" data-aos-delay="100">
                            @forelse($homepageNews->skip(3)->take(2) as $index => $news)
                            <div class="content-box">
                                <div class="image">
                                    @if($news->image)
                                        <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}">
                                    @else
                                        <img src="{{ asset('frontend/assets/img/all-img/news-image-4.png') }}" alt="image">
                                    @endif
                                </div>
                                <div class="content">
                                    <div class="sub-title">
                                        <i class='bx bxs-graduation'></i> <p>{{ $news->category ?? 'Berita' }}</p>
                                    </div>
                                    <h3><a href="{{ route('berita.detail', $news->slug) }}">{{ $news->title }}</a></h3>
                                </div>
                            </div>
                            @empty
                            <div class="content-box">
                                <div class="image">
                                    <img src="{{ asset('frontend/assets/img/all-img/news-image-4.png') }}" alt="image">
                                </div>
                                <div class="content">
                                    <div class="sub-title">
                                        <i class='bx bxs-graduation'></i> <p>Medicine</p>
                                    </div>
                                    <h3><a href="blog-details.html">Empowering Health, One Patient at a Time.</a></h3>
                                </div>
                            </div>
                            <div class="content-box">
                                <div class="image">
                                    <img src="{{ asset('frontend/assets/img/all-img/news-image-5.png') }}" alt="image">
                                </div>
                                <div class="content">
                                    <div class="sub-title">
                                        <i class='bx bxs-graduation'></i> <p>Student Life</p>
                                    </div>
                                    <h3><a href="blog-details.html">Every Student, Every Dream, Every Success.</a></h3>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="section-btn text-center" data-aos="fade-zoom-in" data-aos-delay="100">
                    <p>Where Dreams Take Flight. <a href="news-and-blog.html">More Campus News <i class='bx bx-right-arrow-alt'></i></a></p>
                </div>
            </div>
        </div>
        <!-- End News Area 2 -->

        <!-- Start Faculty Area 2 -->
        <div class="faculty-area-2 ptb-100">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-3">
                        <div class="heading" data-aos="fade-up" data-aos-delay="100">
                            <h2>Program Beasiswa</h2>
                        </div>
                    </div>
                    <div class="col-lg-7" data-aos="fade-up" data-aos-delay="200">
                        <div class="content">
                            <p>SMK PGRI Lawang menyediakan berbagai program beasiswa untuk siswa berprestasi dan kurang mampu, demi mendukung pendidikan yang berkualitas dan merata.</p>
                        </div>
                    </div>
                    <div class="col-lg-2" data-aos="fade-up" data-aos-delay="300">
                        <div class="button">
                            <a class="default-btn" href="{{ route('pendaftaran.index') }}">Bantuan Keuangan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Faculty Area 2 -->

        <!-- Start Quick Search Area -->
        <div class="quick-search style-2 ptb-100">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="course-search-box" data-aos="fade-right">
                            <h4>Cari Program Keahlian</h4>
                            <div class="search-key">
                                <input class="form-control" placeholder="Kata Kunci" type="search" value="" id="searchKey1">
                                <input class="form-control" placeholder="Kode Program" type="search" value="" id="searchKey">
                                
                                <select class="form-select" aria-label="Default select example" id="searchKey2">
                                    <option selected>Jurusan</option>
                                    <option value="1">Teknik Kimia Industri</option>
                                    <option value="2">Teknik Komputer dan Jaringan</option>
                                    <option value="3">Teknik Sepeda Motor</option>
                                    <option value="4">Teknik Kendaraan Ringan</option>
                                </select>

                                <select class="form-select" aria-label="Default select example" id="searchKey3">
                                    <option selected>Lokasi</option>
                                    <option value="1">Kampus Utama</option>
                                    <option value="2">Kampus 2</option>
                                    <option value="3">Workshop</option>
                                </select>

                                <select class="form-select" aria-label="Default select example" id="searchKey4">
                                    <option selected>Tingkat</option>
                                    <option value="1">X</option>
                                    <option value="2">XI</option>
                                    <option value="3">XII</option>
                                </select>

                                <select class="form-select" aria-label="Default select example" id="searchKey5">
                                    <option selected>Guru</option>
                                    <option value="1">Pak Budi</option>
                                    <option value="2">Bu Siti</option>
                                    <option value="3">Pak Ahmad</option>
                                </select>

                                <select class="form-select" aria-label="Default select example" id="searchKey6">
                                    <option selected>Semester</option>
                                    <option value="1">Ganjil</option>
                                    <option value="2">Genap</option>
                                    <option value="3">Pendek</option>
                                </select>

                                <select class="form-select" aria-label="Default select example" id="searchKey7">
                                    <option selected>SKS</option>
                                    <option value="1">2</option>
                                    <option value="3">4</option>
                                    <option value="6">6</option>
                                </select>

                                <a class="default-btn" href="#">Cari Program</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="quick-content" data-aos="fade-up" data-aos-delay="200">
                            <div class="sub-title">
                                <p>Berita Sekolah</p>
                            </div>
                            <h2>Mulai Karirmu & Kejar Passionmu</h2>
                            <p>SMK PGRI Lawang memberikan pendidikan vokasional yang relevan dengan kebutuhan industri, mempersiapkan siswa untuk menjadi tenaga terampil yang siap kerja dan berdaya saing.</p>

                            <div class="list">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-6 col-md-6">
                                        <div class="list-items">
                                            <ul>
                                                <li><i class='bx bx-right-arrow-circle'></i> Alumni & Donatur</li>
                                                <li><i class='bx bx-right-arrow-circle'></i> Kalender Akademik</li>
                                                <li><i class='bx bx-right-arrow-circle'></i> Semua Acara Sekolah</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-6 col-md-6">
                                        <div class="list-items">
                                            <ul>
                                                <li><i class='bx bx-right-arrow-circle'></i> Kemitraan & Kerjasama</li>
                                                <li><i class='bx bx-right-arrow-circle'></i> Program Akademik</li>
                                                <li><i class='bx bx-right-arrow-circle'></i> Biaya Pendidikan</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            
                            </div>

                            <div class="apply-banner">
                                <div class="row align-items-center">
                                    <div class="col-lg-6 col-sm-6 col-md-6">
                                        <p>Daftar Sekarang</p>
                                    </div>
                                    <div class="col-lg-6 col-sm-6 col-md-6 text-end">
                                        <a class="default-btn" href="{{ route('pendaftaran.index') }}">Daftar Sekarang</a>
                                    </div>
                                </div>
                            </div>

                            <div class="user-exprience">
                                <div class="row align-items-center">
                                    <div class="col-lg-6 col-sm-6 col-md-6">
                                        <div class="user-info">
                                            <div class="image">
                                                <img src="assets/img/all-img/admin-image.png" alt="image">
                                            </div>
                                            <div class="text">
                                                <h4>Kepala Sekolah</h4>
                                                <p>Drs. Budi Santoso, M.Pd.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-6 col-md-6">
                                        <div class="exprience">
                                            <div class="icon">
                                                <img src="assets/img/icon/trophy-star.png" alt="image">
                                            </div>
                                            <div class="text">
                                                <h4>25</h4>
                                                <p>Tahun <br> Pengalaman</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Quick Search Area -->

        <!-- Start Success Area 2 -->
        <div class="success-area success-area-2 ptb-100">
            <div class="container">
                <div class="section-title section-title-2" data-aos="fade-up" data-aos-delay="100">
                    <div class="sub-title">
                        <p>Prestasi Siswa, Guru, dan Alumni</p>
                    </div>
                    <h2>Merayakan Warisan, Merangkul Masa Depan</h2>
                </div>
    
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-sm-6 col-md-6">
                        <div class="success-card" data-aos="fade-up" data-aos-delay="100">
                            <div class="image">
                                <img src="{{ asset('frontend/assets/img/all-img/success-image-1.png') }}" alt="image">
                            </div>
                            <div class="content">
                                <div class="play">
                                    <a class="popup-youtube" href="https://www.youtube.com/watch?v=LlCwHnp3kL4"><i class='bx bx-play'></i></a>
                                </div>
                                <ul>
                                    <li><a href="university-life.html"><h3>Amelia Sari '23 (Teknik Kimia Industri)</h3></a></li>
                                    <li class="link"><a href="university-life.html"><i class='bx bx-link-external'></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-md-6">
                        <div class="success-card" data-aos="fade-up" data-aos-delay="200">
                            <div class="image">
                                <img src="{{ asset('frontend/assets/img/all-img/success-image-2.png') }}" alt="image">
                            </div>
                            <div class="content">
                                <div class="play">
                                    <a class="popup-youtube" href="https://www.youtube.com/watch?v=LlCwHnp3kL4"><i class='bx bx-play'></i></a>
                                </div>
                                <ul>
                                    <li><a href="university-life.html"><h3>Oliver Pratama '23 (Teknik Komputer & Jaringan)</h3></a></li>
                                    <li class="link"><a href="university-life.html"><i class='bx bx-link-external'></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-md-6">
                        <div class="success-card" data-aos="fade-up" data-aos-delay="300">
                            <div class="image">
                                <img src="{{ asset('frontend/assets/img/all-img/success-image-3.png') }}" alt="image">
                            </div>
                            <div class="content">
                                <div class="play">
                                    <a class="popup-youtube" href="https://www.youtube.com/watch?v=LlCwHnp3kL4"><i class='bx bx-play'></i></a>
                                </div>
                                <ul>
                                    <li><a href="university-life.html"><h3>Sofia Putri '15 (Teknik Sepeda Motor)</h3></a></li>
                                    <li class="link"><a href="university-life.html"><i class='bx bx-link-external'></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-btn text-center" data-aos="fade-zoom-in" data-aos-delay="100">
                    <p>Inspirasi untuk Masa Depan. <a href="undergraduate.html">Pelajari Tentang Program Unggulan <i class='bx bx-right-arrow-alt'></i></a></p>
                </div>
            </div>
        </div>
        <!-- End Success Area 2 -->

        <!-- Start Program Info Area 2 -->
        <div class="subscribe-area subscribe-area-2" style="background-image: url('{{ asset('frontend/assets/img/all-img/luar.jpeg') }}'); background-size: cover; background-repeat: no-repeat; background-position: center; position: relative;">
            <div class="container">
                <div class="section-title section-title-2" data-aos="fade-up" data-aos-delay="100">
                    <div class="sub-title">
                        <p>Informasi Pendaftaran</p>
                    </div>
                    <h2>Bergabung dengan SMK PGRI Lawang untuk Masa Depan yang Cerah</h2>
                </div>

                <div class="subscribe-btn text-center" data-aos="fade-up" data-aos-delay="200">
                    <a class="default-btn" href="application-form.html">Daftar Sekarang</a>
                </div>
            </div>
        </div> 
        <!-- End Program Info Area 2 -->

@endsection