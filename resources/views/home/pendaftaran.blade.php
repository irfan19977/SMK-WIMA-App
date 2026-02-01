@extends('home.layouts.app')

@section('content')
<!-- Start Section Banner Area -->
        <div class="section-banner bg-11">
            <div class="container">
                <div class="banner-spacing">
                    <div class="section-info">
                        <h2 data-aos="fade-up" data-aos-delay="100">Pendaftaran Siswa Baru</h2>
                        <p data-aos="fade-up" data-aos-delay="200">SMK PGRI Lawang membuka pendaftaran siswa baru untuk tahun ajaran {{ \App\Helpers\AcademicYearHelper::getCurrentAcademicYear() }}. Daftar sekarang untuk masa depan gemilang!</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Section Banner Area -->

        <!-- Start Academics Section Area -->
        <div class="academics-section ptb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="academics-left">
                            <div class="ac-category">
                                <ul>
                                    <li><a class="active" href="#" onclick="showTab('info'); return false;">Informasi Pendaftaran</a></li>
                                    <li><a href="#" onclick="showTab('form'); return false;">Formulir Pendaftaran</a></li>
                                    <li><a href="#" onclick="showTab('biaya'); return false;">Biaya Administrasi</a></li>
                                    <li><a href="#" onclick="showTab('panduan'); return false;">Panduan</a></li>
                                </ul>
                            </div>
                            <hr>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="ac-overview">
                            <!-- Informasi Pendaftaran Content -->
                            <div id="info-content" class="tab-content">
                                <div class="pera-title">
                                    <h2><span>4</span>Program Keahlian <br>Unggulan</h2>
                                </div>
                                <div class="pera-dec">
                                    <p>SMK PGRI Lawang menyediakan 4 program keahlian unggulan yang dirancang untuk mempersiapkan siswa menjadi tenaga terampil dan siap kerja. Setiap program dilengkapi dengan fasilitas modern dan kurikulum berbasis industri.</p>
                                    
                                   <div class="problem-sector">

                                    <div class="problem-list">
                                        <div class="title">
                                            <h3>Teknik Kimia Industri</h3>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-sm-6 col-md-6">
                                                <div class="problem-items">
                                                    <ul>
                                                        <li><a href="#">Proses Kimia Industri</a></li>
                                                        <li><a href="#">Teknologi Kimia</a></li>
                                                        <li><a href="#">Quality Control</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6 col-md-6">
                                                <div class="problem-items">
                                                    <ul>
                                                        <li><a href="#">Manajemen Industri</a></li>
                                                        <li><a href="#">Keselamatan Kerja</a></li>
                                                        <li><a href="#">Analisis Laboratorium</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="problem-list">
                                        <div class="title">
                                            <h3>Teknik Komputer dan Jaringan</h3>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-sm-6 col-md-6">
                                                <div class="problem-items">
                                                    <ul>
                                                        <li><a href="#">Teknik Komputer</a></li>
                                                        <li><a href="#">Jaringan Komputer</a></li>
                                                        <li><a href="#">Administrasi Sistem</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6 col-md-6">
                                                <div class="problem-items">
                                                    <ul>
                                                        <li><a href="#">Pemrograman Web</a></li>
                                                        <li><a href="#">Database Management</a></li>
                                                        <li><a href="#">Keamanan Jaringan</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="problem-list">
                                        <div class="title">
                                            <h3>Teknik Sepeda Motor</h3>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-sm-6 col-md-6">
                                                <div class="problem-items">
                                                    <ul>
                                                        <li><a href="#">Mesin Sepeda Motor</a></li>
                                                        <li><a href="#">Sistem Pengapian</a></li>
                                                        <li><a href="#">Sistem Bahan Bakar</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6 col-md-6">
                                                <div class="problem-items">
                                                    <ul>
                                                        <li><a href="#">Overhaul Mesin</a></li>
                                                        <li><a href="#">Tune Up Service</a></li>
                                                        <li><a href="#">Diagnostik Motor</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="problem-list">
                                        <div class="title">
                                            <h3>Teknik Kendaraan Ringan</h3>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-sm-6 col-md-6">
                                                <div class="problem-items">
                                                    <ul>
                                                        <li><a href="#">Mesin Kendaraan Ringan</a></li>
                                                        <li><a href="#">Sistem Transmisi</a></li>
                                                        <li><a href="#">Sistem Rem dan Suspensi</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6 col-md-6">
                                                <div class="problem-items">
                                                    <ul>
                                                        <li><a href="#">AC dan Kelistrikan</a></li>
                                                        <li><a href="#">Diagnostik Elektronik</a></li>
                                                        <li><a href="#">Body Repair</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="problem-list">
                                        <div class="title">
                                            <h3>Persyaratan Pendaftaran</h3>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-sm-6 col-md-6">
                                                <div class="problem-items">
                                                    <ul>
                                                        <li><a href="#">Usia Maksimal 21 Tahun</a></li>
                                                        <li><a href="#">Lulus SMP/MTs/Sederajat</a></li>
                                                        <li><a href="#">Akte Kelahiran</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-sm-6 col-md-6">
                                                <div class="problem-items">
                                                    <ul>
                                                        <li><a href="#">Kartu Keluarga</a></li>
                                                        <li><a href="#">Pas Foto 3x4 (4 Lembar)</a></li>
                                                        <li><a href="#">Surat Keterangan Sehat</a></li>
                                                        <li><a href="#">Biaya Pendaftaran Rp 50.000</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                   </div>

                                </div>
                            </div>

                            <!-- Biaya Administrasi Content -->
                            <div id="biaya-content" class="tab-content" style="display: none;">
                                <div class="pera-title">
                                    <h2>Biaya <br>Administrasi</h2>
                                </div>
                                <div class="pera-dec">
                                    <p>Berikut adalah rincian biaya administrasi untuk pendaftaran siswa baru SMK PGRI Lawang tahun ajaran {{ \App\Helpers\AcademicYearHelper::getCurrentAcademicYear() }}.</p>
                                    
                                    <div class="row mt-4">
                                        <div class="col-lg-12">
                                            <div class="problem-list">
                                                <div class="title">
                                                    <h3>Biaya Pendaftaran</h3>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Jenis Biaya</th>
                                                                <th>Nominal</th>
                                                                <th>Keterangan</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Formulir Pendaftaran</td>
                                                                <td>Rp 50.000</td>
                                                                <td>Biaya pendaftaran awal</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Uang Pangkal</td>
                                                                <td>Rp 1.500.000</td>
                                                                <td>Dibayar sekali saat penerimaan</td>
                                                            </tr>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <th>Total</th>
                                                                <th>Rp 1.550.000</th>
                                                                <th></th>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-4">
                                        <div class="col-lg-12">
                                            <div class="problem-list">
                                                <div class="title">
                                                    <h3>SPP Bulanan</h3>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Kelas</th>
                                                                <th>Nominal/Bulan</th>
                                                                <th>Keterangan</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Kelas X</td>
                                                                <td>Rp 150.000</td>
                                                                <td>perbulan</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Kelas XI</td>
                                                                <td>Rp 150.000</td>
                                                                <td>perbulan</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Kelas XII</td>
                                                                <td>Rp 150.000</td>
                                                                <td>perbulan</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-4">
                                        <div class="col-lg-12">
                                            <div class="alert alert-info">
                                                <h5><i class="bx bx-info-circle"></i> Informasi Pembayaran</h5>
                                                <ul class="mb-0">
                                                    <li>Pembayaran dapat dilakukan melalui transfer ke rekening BNI No. 1234567890 a.n. SMK PGRI Lawang</li>
                                                    <li>Pembayaran uang pangkal dan biaya administrasi lainnya dilakukan setelah dinyatakan diterima</li>
                                                    <li>SPP dibayarkan setiap bulan pada tanggal 1-10</li>
                                                    <li>Ada potongan 10% untuk pembayaran tahunan SPP</li>
                                                    <li>Bantuan biaya pendidikan tersedia untuk siswa berprestasi dan kurang mampu</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-4">
                                        <div class="col-lg-12">
                                            <div class="problem-list">
                                                <div class="title">
                                                    <h3>Beasiswa & Bantuan</h3>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="problem-items">
                                                            <ul>
                                                                <li><a href="#">Beasiswa Prestasi (Akademik)</a></li>
                                                                <li><a href="#">Beasiswa Non-Prestasi</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="problem-items">
                                                            <ul>
                                                                <li><a href="#">Juara (Lomba/Olahraga)</a></li>
                                                                <li><a href="#">Kurang Mampu</a></li>
                                                                <li><a href="#">Yatim/Yatim Piatu</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <!-- Panduan Content -->
                            <div id="panduan-content" class="tab-content" style="display: none;">
                                <div class="pera-title">
                                    <h2>Panduan <br>Pendaftaran</h2>
                                </div>
                                <div class="pera-dec">
                                    <p>Berikut adalah panduan lengkap untuk melakukan pendaftaran siswa baru di SMK PGRI Lawang. Pastikan untuk mengikuti setiap langkah dengan benar.</p>
                                    
                                    <div class="faq-content">
                                        <!-- Cara Pendaftaran -->
                                        <div class="faq-item">
                                            <div class="faq-question">Cara Pendaftaran</div>
                                            <div class="icon-container"><i class='bx bx-chevron-down'></i></div>
                                        </div>
                                        <div class="faq-answer">
                                            <div class="list-item-list">
                                                <h4>Langkah-langkah Pendaftaran</h4>
                                                <ol class="mb-4">
                                                    <li><strong>Pendaftaran Online</strong>: Kunjungi website resmi SMK PGRI Lawang dan isi formulir pendaftaran online</li>
                                                    <li><strong>Verifikasi Email</strong>: Periksa email Anda untuk verifikasi akun pendaftaran</li>
                                                    <li><strong>Lengkapi Data</strong>: Isi semua data pribadi dan data orang tua dengan lengkap dan benar</li>
                                                    <li><strong>Unggah Dokumen</strong>: Scan dan unggah dokumen yang diperlukan (Ijazah, KK, Akte Kelahiran, dll)</li>
                                                    <li><strong>Pembayaran</strong>: Lakukan pembayaran biaya pendaftaran sebesar Rp 250.000,- melalui bank/transfer/gerai</li>
                                                    <li><strong>Konfirmasi</strong>: Upload bukti pembayaran di halaman konfirmasi</li>
                                                    <li><strong>Tes Seleksi</strong>: Ikuti tes seleksi sesuai jadwal yang ditentukan</li>
                                                    <li><strong>Pengumuman</strong>: Cek pengumuman hasil seleksi di website</li>
                                                    <li><strong>Daftar Ulang</strong>: Lakukan daftar ulang bagi yang dinyatakan diterima</li>
                                                </ol>
                                                <div class="alert alert-info">
                                                    <h5><i class="bx bx-info-circle"></i> Persyaratan Umum</h5>
                                                    <ul class="mb-0">
                                                        <li>Usia maksimal 21 tahun pada saat pendaftaran</li>
                                                        <li>Fotokopi ijazah/surat keterangan lulus yang telah dilegalisir</li>
                                                        <li>Fotokopi akte kelahiran dan KK</li>
                                                        <li>Pas foto 3x4 (4 lembar, latar merah)</li>
                                                        <li>Surat keterangan sehat dari dokter</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Beasiswa -->
                                        <div class="faq-item">
                                            <div class="faq-question">Informasi Beasiswa</div>
                                            <div class="icon-container"><i class='bx bx-chevron-down'></i></div>
                                        </div>
                                        <div class="faq-answer">
                                            <div class="list-item-list">
                                                <h4>Jenis Beasiswa yang Tersedia</h4>
                                                <p>SMK PGRI Lawang menyediakan berbagai beasiswa untuk siswa berprestasi maupun yang membutuhkan:</p>
                                                
                                                <div class="row">
                                                    <div class="col-md-6 mb-4">
                                                        <div class="card h-100">
                                                            <div class="card-header bg-primary text-white">
                                                                <h5 class="mb-0">Beasiswa Prestasi (Akademik)</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <h6 class="card-subtitle mb-2 text-muted">Untuk siswa berprestasi akademik</h6>
                                                                <p class="card-text">
                                                                    <strong>Syarat:</strong>
                                                                    <ul>
                                                                        <li>Nilai rata-rata rapor minimal 85</li>
                                                                        <li>Juara kelas/tingkat sekolah</li>
                                                                    </ul>
                                                                    <strong>Benefit:</strong>
                                                                    <ul>
                                                                        <li>Potongan SPP 50-100%</li>
                                                                        <li>Bebas biaya praktik</li>
                                                                    </ul>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6 mb-4">
                                                        <div class="card h-100">
                                                            <div class="card-header bg-success text-white">
                                                                <h5 class="mb-0">Beasiswa Non-Prestasi</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <h6 class="card-subtitle mb-2 text-muted">Untuk siswa yang membutuhkan bantuan</h6>
                                                                <p class="card-text">
                                                                    <strong>Kategori:</strong>
                                                                    <ul>
                                                                        <li><strong>Juara (Lomba/Olahraga)</strong> - Prestasi di bidang non-akademik</li>
                                                                        <li><strong>Kurang Mampu</strong> - Surat keterangan tidak mampu</li>
                                                                        <li><strong>Yatim/Yatim Piatu</strong> - Dokumen yatim piatu</li>
                                                                    </ul>
                                                                    <strong>Benefit:</strong>
                                                                    <ul>
                                                                        <li>Potongan SPP 25-75%</li>
                                                                        <li>Bantuan biaya pendidikan</li>
                                                                        <li>Bantuan seragam dan buku</li>
                                                                    </ul>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="alert alert-info">
                                                    <h5><i class="bx bx-info-circle"></i> Cara Mengajukan Beasiswa</h5>
                                                    <ol class="mb-0">
                                                        <li>Mengisi formulir pengajuan beasiswa</li>
                                                        <li>Melengkapi dokumen pendukung</li>
                                                        <li>Wawancara dengan tim seleksi</li>
                                                        <li>Pengumuman hasil seleksi</li>
                                                    </ol>
                                                    <p class="mb-0 mt-2"><strong>Batas waktu pengajuan:</strong> 2 minggu setelah pengumuman kelulusan</p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Panduan NISN -->
                                        <div class="faq-item">
                                            <div class="faq-question">Panduan NISN</div>
                                            <div class="icon-container"><i class='bx bx-chevron-down'></i></div>
                                        </div>
                                        <div class="faq-answer">
                                            <div class="list-item-list">
                                                <h4>Informasi Penting Tentang NISN</h4>
                                                <p>NISN (Nomor Induk Siswa Nasional) adalah nomor identitas siswa yang berlaku secara nasional dan menjadi syarat wajib dalam proses pendaftaran.</p>
                                                
                                                <div class="alert alert-warning">
                                                    <h5><i class="bx bx-error"></i> Jika Belum Memiliki NISN</h5>
                                                    <p>Ikuti langkah-langkah berikut untuk mendapatkan NISN:</p>
                                                    <ol>
                                                        <li>Kunjungi website <a href="https://nisn.data.kemdikbud.go.id/" target="_blank">Verifikasi NISN</a></li>
                                                        <li>Klik menu "Pencarian Berdasarkan Nama"</li>
                                                        <li>Isi data lengkap sesuai dokumen (Nama, Tempat/Tanggal Lahir, Nama Ibu Kandung)</li>
                                                        <li>Jika NISN ditemukan, catat nomornya</li>
                                                        <li>Jika tidak ditemukan, Anda perlu mendaftar melalui sekolah asal</li>
                                                    </ol>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="card mb-4">
                                                            <div class="card-header bg-primary text-white">
                                                                <h5 class="mb-0">Cara Daftar NISN Baru</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <ol>
                                                                    <li>Mengajukan permohonan ke operator NISN di sekolah asal</li>
                                                                    <li>Membawa dokumen yang diperlukan:
                                                                        <ul>
                                                                            <li>Fotokopi akte kelahiran (legalisir)</li>
                                                                            <li>Fotokopi KK</li>
                                                                            <li>Surat pengantar dari sekolah asal</li>
                                                                            <li>Pas foto 3x4 (2 lembar)</li>
                                                                        </ul>
                                                                    </li>
                                                                    <li>Menunggu proses verifikasi dari operator sekolah</li>
                                                                    <li>NISN akan aktif dalam 3-7 hari kerja</li>
                                                                </ol>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="card mb-4">
                                                            <div class="card-header bg-success text-white">
                                                                <h5 class="mb-0">Jika NISN Tidak Ditemukan</h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <p>Jika NISN tidak ditemukan, kemungkinan karena:</p>
                                                                <ul>
                                                                    <li>Data belum terdaftar di database pusat</li>
                                                                    <li>Ada perbedaan data antara sekolah dan pusat</li>
                                                                    <li>Nama di ijazah tidak sesuai dengan akte kelahiran</li>
                                                                </ul>
                                                                <p><strong>Solusi:</strong> Segera hubungi operator NISN di sekolah asal untuk verifikasi dan perbaikan data.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="alert alert-info">
                                                    <h5><i class="bx bx-info-circle"></i> Bantuan NISN</h5>
                                                    <p>Untuk bantuan lebih lanjut terkait NISN, silakan hubungi:</p>
                                                    <ul class="mb-0">
                                                        <li>Call Center NISN: 1500-123</li>
                                                        <li>Email: nisn@kemdikbud.go.id</li>
                                                        <li>Datang langsung ke sekolah asal dengan membawa dokumen lengkap</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Informasi Kontak -->
                                        <div class="faq-item">
                                            <div class="faq-question">Kontak & Informasi</div>
                                            <div class="icon-container"><i class='bx bx-chevron-down'></i></div>
                                        </div>
                                        <div class="faq-answer">
                                            <div class="list-item-list">
                                                <h4>Hubungi Kami</h4>
                                                <p>Untuk informasi lebih lanjut, silakan hubungi:</p>
                                                
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="contact-info-box">
                                                            <div class="icon">
                                                                <i class="bx bx-map"></i>
                                                            </div>
                                                            <div class="content">
                                                                <h5>Alamat</h5>
                                                                <p>Jl. Veteran No. 12, Lawang</p>
                                                                <p>Malang, Jawa Timur 65211</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="contact-info-box">
                                                            <div class="icon">
                                                                <i class="bx bx-phone"></i>
                                                            </div>
                                                            <div class="content">
                                                                <h5>Telepon</h5>
                                                                <p>0341-426789 (Sekretariat)</p>
                                                                <p>0812-3456-7890 (WA Panitia PPDB)</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="contact-info-box">
                                                            <div class="icon">
                                                                <i class="bx bx-envelope"></i>
                                                            </div>
                                                            <div class="content">
                                                                <h5>Email</h5>
                                                                <p>info@smkpgrilawang.sch.id</p>
                                                                <p>ppdb@smkpgrilawang.sch.id</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-md-6">
                                                        <div class="contact-info-box">
                                                            <div class="icon">
                                                                <i class="bx bx-time"></i>
                                                            </div>
                                                            <div class="content">
                                                                <h5>Jam Operasional</h5>
                                                                <p>Senin - Jumat: 08.00 - 15.00 WIB</p>
                                                                <p>Sabtu: 08.00 - 12.00 WIB</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Panduan Content -->
                            
                            <!-- Formulir Pendaftaran Content -->
                            <div id="form-content" class="tab-content" style="display: none;">
                                <div class="pera-title">
                                    <h2>Formulir <br>Pendaftaran</h2>
                                </div>
                                <div class="pera-dec">
                                    <p>Isi formulir pendaftaran di bawah ini untuk mendaftar sebagai siswa baru SMK PGRI Lawang.</p>
                                    
                                                                        
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="font-weight-bold" id="step-indicator-text">Bagian 1 dari 3</span>
                                            <small class="text-muted">Progres pendaftaran</small>
                                        </div>
                                        <div class="progress" style="height: 8px;">
                                            <div id="step-progress-bar" class="progress-bar" role="progressbar" style="width: 33%; background-color: #2eca7f;" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    
                                    <div class="contact-form">
                                        <form id="registrationForm" method="POST" action="{{ route('pendaftaran.store') }}" enctype="multipart/form-data">
                                            @csrf
                                            
                                            <!-- Display Session Messages -->
                                            <!-- Step 1: Data Diri -->
                                            <div class="form-step" id="step-1">
                                                <h4 class="mb-3">Bagian 1: Data Diri</h4>
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="form-group">
                                                            <label for="name">Nama Lengkap</label>
                                                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" data-error="Masukkan nama lengkap" value="{{ old('name') }}" placeholder="" required>
                                                            <div class="help-block with-errors"></div>
                                                            @error('name')
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="form-group">
                                                            <label for="phone">Nomor WhatsApp</label>
                                                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" id="phone" data-error="Masukkan nomor WhatsApp" placeholder="" required>
                                                            <div class="help-block with-errors"></div>
                                                            @error('phone')
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="form-group">
                                                            <label for="nik">NIK</label>
                                                            <input type="number" name="nik" class="form-control @error('nik') is-invalid @enderror" id="nik" data-error="Masukkan NIK 16 digit" placeholder="16 Angka" required>
                                                            <div class="help-block with-errors"></div>
                                                            @error('nik')
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="form-group">
                                                            <label for="nisn">NISN</label>
                                                            <input type="tel" name="nisn" class="form-control @error('nisn') is-invalid @enderror" id="nisn" data-error="Masukkan NISN 10 digit" placeholder="" required>
                                                            <div class="help-block with-errors"></div>
                                                            @error('nisn')
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="form-group">
                                                            <label for="jurusan_utama">Jurusan Utama</label>
                                                            <select class="form-select @error('jurusan_utama') is-invalid @enderror" name="jurusan_utama" id="jurusan_utama" data-error="Pilih jurusan utama" required>
                                                                <option value="">-- Pilih Jurusan Utama --</option>
                                                                <option value="Teknik Kimia Industri">Teknik Kimia Industri (TKI)</option>
                                                                <option value="Teknik Komputer dan Jaringan">Teknik Komputer dan Jaringan (TKJ)</option>
                                                                <option value="Teknik Sepeda Motor">Teknik Sepeda Motor (TSM)</option>
                                                                <option value="Teknik Kendaraan Ringan">Teknik Kendaraan Ringan (TKR)</option>
                                                            </select>
                                                            <div class="help-block with-errors"></div>
                                                            @error('jurusan_utama')
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="form-group">
                                                            <label for="jurusan_cadangan">Jurusan Cadangan</label>
                                                            <select class="form-select @error('jurusan_cadangan') is-invalid @enderror" name="jurusan_cadangan" id="jurusan_cadangan" data-error="Pilih jurusan cadangan" required>
                                                                <option value="">-- Pilih Jurusan Cadangan --</option>
                                                                <option value="Teknik Kimia Industri">Teknik Kimia Industri (TKI)</option>
                                                                <option value="Teknik Komputer dan Jaringan">Teknik Komputer dan Jaringan (TKJ)</option>
                                                                <option value="Teknik Sepeda Motor">Teknik Sepeda Motor (TSM)</option>
                                                                <option value="Teknik Kendaraan Ringan">Teknik Kendaraan Ringan (TKR)</option>
                                                            </select>
                                                            <div class="help-block with-errors"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="form-group">
                                                            <label for="gender">Jenis Kelamin</label>
                                                            <select class="form-select @error('gender') is-invalid @enderror" name="gender" id="gender" data-error="Pilih jenis kelamin" required>
                                                                <option value="">-- Pilih Jenis Kelamin --</option>
                                                                <option value="laki-laki">Laki-laki</option>
                                                                <option value="perempuan">Perempuan</option>
                                                            </select>
                                                            <div class="help-block with-errors"></div>
                                                            @error('gender')
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="form-group">
                                                            <label for="birth_date">Tanggal Lahir</label>
                                                            <input type="date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" id="birth_date" data-error="Pilih tanggal lahir" required>
                                                            <div class="help-block with-errors"></div>
                                                            @error('birth_date')
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="form-group">
                                                            <label for="birth_place">Tempat Lahir</label>
                                                            <input type="text" name="birth_place" class="form-control @error('birth_place') is-invalid @enderror" id="birth_place" data-error="Masukkan tempat lahir" placeholder="" required>
                                                            <div class="help-block with-errors"></div>
                                                            @error('birth_place')
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="form-group">
                                                            <label for="religion">Agama</label>
                                                            <select class="form-select @error('religion') is-invalid @enderror" name="religion" id="religion" data-error="Pilih agama" required>
                                                                <option value="">-- Pilih Agama --</option>
                                                                <option value="Islam">Islam</option>
                                                                <option value="Kristen">Kristen</option>
                                                                <option value="Katolik">Katolik</option>
                                                                <option value="Hindu">Hindu</option>
                                                                <option value="Buddha">Buddha</option>
                                                                <option value="Konghucu">Konghucu</option>
                                                            </select>
                                                            <div class="help-block with-errors"></div>
                                                            @error('religion')
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12">
                                                        <div class="form-group">
                                                            <label for="address">Alamat Lengkap</label>
                                                            <textarea name="address" id="address" class="form-control @error('address') is-invalid @enderror" cols="30" rows="4" data-error="Masukkan alamat lengkap" placeholder="" required></textarea>
                                                            <div class="help-block with-errors"></div>
                                                            @error('address')
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12">
                                                        <div class="form-group">
                                                            <label for="asalSekolah">Asal Sekolah</label>
                                                            <input type="text" name="asalSekolah" class="form-control @error('asalSekolah') is-invalid @enderror" id="asalSekolah" placeholder="" required>
                                                            <div class="help-block with-errors"></div>
                                                            @error('asalSekolah')
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-end mt-4">
                                                    <button type="button" class="default-btn" id="btnNext1">Selanjutnya</button>
                                                </div>
                                            </div>

                                            <!-- Step 2: Upload Dokumen -->
                                            <div class="form-step d-none" id="step-2">
                                                <h4 class="mb-4">Bagian 2: Upload Dokumen</h4>
                                                <div class="row">
                                                    <div class="col-12 mb-4">
                                                        <p class="text-muted">Silakan upload dokumen-dokumen yang diperlukan untuk pendaftaran. Dokumen yang bertanda (*) wajib diupload. Maksimal ukuran file 200KB.</p>
                                                    </div>
                                                    
                                                    <!-- Foto 3x4 -->
                                                    <div class="col-12 mb-4">
                                                        <label class="form-label fw-bold">Foto 3x4 (Pas Foto) <small class="text-danger">*</small></label>
                                                        <div class="upload-area" id="photoUploadArea">
                                                            <input type="file" name="photo_path" class="d-none" id="photo_path" accept="image/*" required>
                                                            <div class="upload-icon">
                                                                <i class="ri-upload-cloud-2-line"></i>
                                                                <p class="mb-1">Upload File Disini</p>
                                                                <p class="file-info" id="photoFileInfo">Format JPG/PNG, maks. 200KB</p>
                                                            </div>
                                                        </div>
                                                        <div class="help-block with-errors text-danger"></div>
                                                    </div>

                                                    <!-- Ijazah/SKL -->
                                                    <div class="col-md-6 mb-4">
                                                        <label class="form-label fw-bold">Ijazah / SKL <small class="text-danger">*</small></label>
                                                        <div class="upload-area small" id="ijazahUploadArea">
                                                            <input type="file" name="ijazah" class="d-none" id="ijazah" accept=".jpg,.jpeg,.png,.pdf" required>
                                                            <div class="upload-icon">
                                                                <i class="ri-file-upload-line"></i>
                                                                <p class="file-info" id="ijazahFileInfo">Belum ada file ijazah yang dipilih</p>
                                                            </div>
                                                        </div>
                                                        <small class="text-muted d-block mt-1">Maksimal ukuran 200KB</small>
                                                        <div class="help-block with-errors text-danger"></div>
                                                    </div>

                                                    <!-- Kartu Keluarga -->
                                                    <div class="col-md-6 mb-4">
                                                        <label class="form-label fw-bold">Kartu Keluarga (KK) <small class="text-danger">*</small></label>
                                                        <div class="upload-area small" id="kkUploadArea">
                                                            <input type="file" name="kartu_keluarga" class="d-none" id="kartu_keluarga" accept=".jpg,.jpeg,.png,.pdf" required>
                                                            <div class="upload-icon">
                                                                <i class="ri-file-upload-line"></i>
                                                                <p class="file-info" id="kkFileInfo">Belum ada file KK yang dipilih</p>
                                                            </div>
                                                        </div>
                                                        <small class="text-muted d-block mt-1">Maksimal ukuran 200KB</small>
                                                        <div class="help-block with-errors text-danger"></div>
                                                    </div>

                                                    <!-- Akte Lahir -->
                                                    <div class="col-md-6 mb-4">
                                                        <label class="form-label fw-bold">Akte Lahir <small class="text-muted">(opsional)</small></label>
                                                        <div class="upload-area small" id="akteUploadArea">
                                                            <input type="file" name="akte_lahir" class="d-none" id="akte_lahir" accept=".jpg,.jpeg,.png,.pdf">
                                                            <div class="upload-icon">
                                                                <i class="ri-file-upload-line"></i>
                                                                <p class="file-info" id="akteFileInfo">Belum ada file akte lahir yang dipilih</p>
                                                            </div>
                                                        </div>
                                                        <small class="text-muted d-block mt-1">Maksimal ukuran 200KB</small>
                                                    </div>

                                                    <!-- KTP -->
                                                    <div class="col-md-6 mb-4">
                                                        <label class="form-label fw-bold">KTP <small class="text-danger">*</small></label>
                                                        <div class="upload-area small" id="ktpUploadArea">
                                                            <input type="file" name="ktp" class="d-none" id="ktp" accept=".jpg,.jpeg,.png,.pdf" required>
                                                            <div class="upload-icon">
                                                                <i class="ri-file-upload-line"></i>
                                                                <p class="file-info" id="ktpFileInfo">Belum ada file KTP yang dipilih</p>
                                                            </div>
                                                        </div>
                                                        <small class="text-muted d-block mt-1">Maksimal ukuran 200KB</small>
                                                        <div class="help-block with-errors text-danger"></div>
                                                    </div>

                                                    <!-- Sertifikat Pendukung -->
                                                    <div class="col-12 mb-4">
                                                        <label class="form-label fw-bold">Sertifikat Pendukung <small class="text-muted">(opsional)</small></label>
                                                        <div class="upload-area small" id="sertifikatUploadArea">
                                                            <input type="file" name="sertifikat" class="d-none" id="sertifikat" multiple accept=".jpg,.jpeg,.png,.pdf">
                                                            <div class="upload-icon">
                                                                <i class="ri-file-upload-line"></i>
                                                                <p class="file-info" id="sertifikatFileInfo">Belum ada file sertifikat yang dipilih</p>
                                                            </div>
                                                        </div>
                                                        <small class="text-muted d-block mt-1">Maksimal ukuran 200KB per file</small>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between mt-4">
                                                    <button type="button" class="default-btn" id="btnPrev2" style="background-color: #f8f9fa; color: #6c757d; border: 1px solid #dee2e6;">Kembali</button>
                                                    <button type="button" class="default-btn btn-style-2" id="btnNext2">Selanjutnya</button>
                                                </div>
                                            </div>

                                            <!-- Step 3: Akun -->
                                            <div class="form-step d-none" id="step-3">
                                                <h4 class="mb-3">Bagian 3: Pembuatan Akun</h4>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12">
                                                        <p class="text-muted mb-4">Buat akun untuk memantau status pendaftaran Anda.</p>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12">
                                                        <div class="form-group">
                                                            <label for="email">Email *</label>
                                                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" required data-error="Masukkan email yang valid" value="{{ old('email') }}" placeholder="">
                                                            <div class="help-block with-errors"></div>
                                                            @error('email')
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="form-group">
                                                            <label for="password">Password *</label>
                                                            <div class="password-wrapper" style="position: relative;">
                                                                <input type="password" name="password" class="form-control" id="password" data-error="Password minimal 8 karakter" style="padding-right: 2.5rem;" placeholder="" required>
                                                                <button type="button" class="password-toggle-btn" id="togglePassword" style="position: absolute; top: 50%; right: 0.75rem; transform: translateY(-50%); border: none; background: transparent; padding: 0; color: #6c757d; cursor: pointer;">
                                                                    <i class="bx bx-show"></i>
                                                                </button>
                                                            </div>
                                                            <div class="help-block with-errors"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6">
                                                        <div class="form-group">
                                                            <label for="password_confirmation">Konfirmasi Password *</label>
                                                            <div class="password-wrapper" style="position: relative;">
                                                                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" data-error="Konfirmasi password harus cocok" style="padding-right: 2.5rem;" placeholder="" required>
                                                                <button type="button" class="password-toggle-btn" id="togglePasswordConfirm" style="position: absolute; top: 50%; right: 0.75rem; transform: translateY(-50%); border: none; background: transparent; padding: 0; color: #6c757d; cursor: pointer;">
                                                                    <i class="bx bx-show"></i>
                                                                </button>
                                                            </div>
                                                            <div class="help-block with-errors"></div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-12 col-md-12">
                                                        <div class="form-group">
                                                            <div class="form-check">
                                                                <input class="form-check-input @error('setuju') is-invalid @enderror" type="checkbox" name="setuju" id="setuju" data-error="Anda harus menyetujui pernyataan" {{ old('setuju') ? 'checked' : '' }} required>
                                                                <label class="form-check-label" for="setuju">
                                                                    Saya menyatakan bahwa data yang diisi adalah benar dan bersedia mematuhi semua peraturan yang berlaku di SMK PGRI Lawang *
                                                                </label>
                                                            </div>
                                                            <div class="help-block with-errors"></div>
                                                            @error('setuju')
                                                                <div class="invalid-feedback d-block">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between mt-4">
                                                    <button type="button" class="default-btn darkbtn" id="btnPrev3">Kembali</button>
                                                    <button type="submit" class="default-btn" id="btnSubmit">Kirim Pendaftaran</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- GANTI LINK WHATSAPP DI BAWAH INI: https://chat.whatsapp.com/ExampleGroupLink -->
        <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-body text-center p-5">
                        <!-- Success Icon -->
                        <div class="success-icon-wrapper mb-4">
                            <div class="success-icon">
                                <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
                                    <circle cx="40" cy="40" r="40" fill="#10b981" opacity="0.1"/>
                                    <circle cx="40" cy="40" r="30" fill="#10b981" opacity="0.2"/>
                                    <circle cx="40" cy="40" r="20" fill="#10b981"/>
                                    <path d="M30 40L36 46L50 32" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Title -->
                        <h3 class="modal-title fw-bold mb-3" id="successModalLabel">Pendaftaran Berhasil!</h3>
                        
                        <!-- Message -->
                        <p class="text-muted mb-4">Terima kasih telah mendaftar di SMK PGRI Lawang. Data Anda telah kami terima dan akan segera diproses.</p>
                        
                        <!-- Success Details -->
                        <div class="success-details mb-4">
                            <div class="row g-3">
                                <div class="col-4">
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="fas fa-user-check text-success"></i>
                                        </div>
                                        <small class="text-muted d-block">Status</small>
                                        <span class="fw-semibold">Terverifikasi</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="fas fa-clock text-primary"></i>
                                        </div>
                                        <small class="text-muted d-block">Proses</small>
                                        <span class="fw-semibold">1-3 Hari</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="fas fa-bell text-warning"></i>
                                        </div>
                                        <small class="text-muted d-block">Notifikasi</small>
                                        <span class="fw-semibold">WhatsApp</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Countdown -->
                        <div class="countdown-wrapper mb-4">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-hourglass-half text-primary me-2"></i>
                                <span class="text-muted">Redirect dalam </span>
                                <span id="countdown" class="badge bg-primary ms-1 me-1">20</span>
                                <span class="text-muted">detik</span>
                            </div>
                            <div class="progress mt-2" style="height: 6px;">
                                <div id="countdown-progress" class="progress-bar bg-primary" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="actions-wrapper">
                            <div class="d-grid gap-2">
                                <a href="https://chat.whatsapp.com/HczMn0QnznG1Y3lihSpYtW" target="_blank" class="btn btn-success btn-lg" id="whatsappRedirectBtn">
                                    <i class="fab fa-whatsapp me-2"></i>
                                    Gabung Grup WhatsApp
                                </a>
                            </div>
                            <p class="text-muted small mt-3 mb-0">
                                <i class="fas fa-info-circle me-1"></i>
                                Klik tombol di atas untuk bergabung ke grup WhatsApp
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('style')
    <!-- Elegant Modal Styles -->
    <style>
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }
                                                
        .upload-area.small {
            padding: 1rem;
            min-height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
                                                
        .upload-area:hover {
            border-color: #0d6efd;
            background-color: #f1f8ff;
        }
                                                
        .upload-icon {
            color: #6c757d;
        }
                                                
        .upload-icon i {
            font-size: 24px;
            margin-bottom: 8px;
        }
                                                
        .file-info {
            margin: 0;
            font-size: 0.875rem;
            color: #6c757d;
        }
                                                
        .form-label {
            margin-bottom: 0.5rem;
        }
        
        /* Form Validation Styles */
        .form-control.is-invalid, .form-select.is-invalid {
            border-color: #dc3545;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
            padding-right: calc(1.5em + 0.75rem);
        }
        
        .invalid-feedback {
            display: block;
            width: 100%;
            margin-top: 0.25rem;
            margin-bottom: 0.5rem;
            font-size: 0.875em;
            color: #dc3545;
            font-weight: 500;
        }
        
        .invalid-feedback.d-block {
            display: block !important;
        }
        
        /* Disabled options styling */
        .form-select option:disabled {
            color: #6c757d;
            background-color: #f8f9fa;
            cursor: not-allowed;
        }
        
        .form-select option:disabled:hover {
            background-color: #e9ecef;
        }
        
        /* Ensure proper spacing between form groups */
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group .form-control,
        .form-group .form-select {
            margin-bottom: 0.25rem;
        }
        
        /* Modal Base */
        .modal-content {
            border-radius: 1rem;
            border: none;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .modal-body {
            padding: 3rem 2rem;
        }
        
        /* Success Icon */
        .success-icon-wrapper {
            position: relative;
        }
        
        .success-icon {
            display: inline-block;
            animation: successBounce 0.6s ease-out;
        }
        
        .success-icon svg {
            animation: successRotate 0.8s ease-out;
        }
        
        @keyframes successBounce {
            0% { transform: scale(0); opacity: 0; }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }
        
        @keyframes successRotate {
            0% { transform: rotate(-180deg) scale(0); }
            50% { transform: rotate(10deg) scale(1.1); }
            100% { transform: rotate(0deg) scale(1); }
        }
        
        /* Detail Items */
        .detail-item {
            text-align: center;
            padding: 1rem;
            border-radius: 0.75rem;
            background: #f8f9fa;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.25rem;
        }
        
        .detail-item:hover {
            background: #e9ecef;
            transform: translateY(-2px);
        }
        
        .detail-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        
        .detail-item small {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
            line-height: 1.2;
        }
        
        .detail-item span {
            font-size: 0.875rem;
            font-weight: 600;
            margin: 0;
        }
        
        /* Countdown */
        .countdown-wrapper {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 0.75rem;
            padding: 1rem;
            border: 1px solid #dee2e6;
        }
        
        .countdown-wrapper .badge {
            font-size: 1rem;
            padding: 0.5rem 0.75rem;
            animation: pulse 1s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        /* Progress Bar */
        .progress {
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .progress-bar {
            transition: width 1s linear;
            background: linear-gradient(90deg, #0d6efd, #0056b3);
        }
        
        /* Buttons */
        .btn {
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
        }
        
        .btn-success:hover {
            background: linear-gradient(135deg, #059669, #047857);
        }
        
        .btn-outline-primary:hover {
            background: #0d6efd;
            border-color: #0d6efd;
        }
        
        /* Responsive */
        @media (max-width: 576px) {
            .modal-body {
                padding: 2rem 1rem;
            }
            
            .detail-item {
                padding: 0.75rem 0.5rem;
            }
            
            .success-icon svg {
                width: 60px;
                height: 60px;
            }
        }
    </style>
@endpush

@push('scripts')
 <script>
            // Tab switching function
            function showTab(tabName) {
                // Hide all tab contents
                const allTabs = document.querySelectorAll('.tab-content');
                allTabs.forEach(tab => {
                    tab.style.display = 'none';
                });
                
                // Remove active class from all menu items
                const allMenuItems = document.querySelectorAll('.ac-category ul li a');
                allMenuItems.forEach(item => {
                    item.classList.remove('active');
                });
                
                // Show selected tab content
                const selectedTab = document.getElementById(tabName + '-content');
                if (selectedTab) {
                    selectedTab.style.display = 'block';
                    // Add fade-in effect
                    selectedTab.style.opacity = '0';
                    setTimeout(() => {
                        selectedTab.style.transition = 'opacity 0.3s ease-in-out';
                        selectedTab.style.opacity = '1';
                    }, 50);
                }
                
                // Add active class to the correct menu item
                const targetMenuItem = document.querySelector(`.ac-category ul li a[href="#"][onclick*="${tabName}"]`);
                if (targetMenuItem) {
                    targetMenuItem.classList.add('active');
                }
            }

            // Fungsi untuk menampilkan step yang aktif
            function showStep(step) {
                // Sembunyikan semua step
                document.querySelectorAll('.form-step').forEach(stepEl => {
                    stepEl.classList.add('d-none');
                });
                
                // Tampilkan step yang aktif
                const activeStep = document.getElementById(`step-${step}`);
                if (activeStep) {
                    activeStep.classList.remove('d-none');
                    
                    // Scroll ke bagian atas form dengan offset yang sangat besar
                    if (step === 3) {
                        // Khusus untuk step 3, scroll ke judul "Bagian 3: Pembuatan Akun" dengan offset sangat besar
                        const step3Title = activeStep.querySelector('h4');
                        if (step3Title) {
                            const yOffset = -100; // Menambahkan offset 100px ke atas
                            const y = step3Title.getBoundingClientRect().top + window.pageYOffset + yOffset;
                            window.scrollTo({top: y, behavior: 'smooth'});
                        } else {
                            const yOffset = -100; // Menambahkan offset 100px ke atas
                            const y = activeStep.getBoundingClientRect().top + window.pageYOffset + yOffset;
                            window.scrollTo({top: y, behavior: 'smooth'});
                        }
                    } else {
                        const yOffset = -100; // Menambahkan offset 100px ke atas untuk step lainnya
                        const y = activeStep.getBoundingClientRect().top + window.pageYOffset + yOffset;
                        window.scrollTo({top: y, behavior: 'smooth'});
                    }
                }
                
                // Update progress bar
                const progressBar = document.getElementById('step-progress-bar');
                if (progressBar) {
                    const progress = (step / 3) * 100;
                    progressBar.style.width = `${progress}%`;
                    progressBar.setAttribute('aria-valuenow', progress);
                }
                
                // Update teks indikator
                const indicatorText = document.getElementById('step-indicator-text');
                if (indicatorText) {
                    indicatorText.textContent = `Bagian ${step} dari 3`;
                }
            }
            
            // Fungsi untuk memvalidasi step
            function validateStep(step) {
                if (step === 2) {
                    // Validasi step 2 (upload dokumen)
                    let isValid = true;
                    const currentStepEl = document.getElementById(`step-${step}`);
                    
                    if (currentStepEl) {
                        // Validasi file input required
                        const requiredInputs = currentStepEl.querySelectorAll('input[required]');
                        requiredInputs.forEach(input => {
                            // Check apakah file ada atau tidak
                            const hasFile = input.files && input.files.length > 0;
                            
                            if (!hasFile) {
                                input.classList.add('is-invalid');
                                // Tambahkan error message untuk upload field
                                let uploadArea = input.closest('.upload-area');
                                if (uploadArea) {
                                    uploadArea.style.borderColor = '#dc3545';
                                    uploadArea.style.backgroundColor = '#f8d7da';
                                    
                                    let errorDiv = uploadArea.nextElementSibling;
                                    if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                                        errorDiv = document.createElement('div');
                                        errorDiv.className = 'invalid-feedback d-block';
                                        const fieldName = input.getAttribute('name');
                                        let errorMsg = 'File ini wajib diupload';
                                        
                                        // Custom error message berdasarkan field
                                        if (fieldName === 'photo_path') errorMsg = 'Foto 3x4 wajib diupload';
                                        else if (fieldName === 'ijazah') errorMsg = 'Ijazah/SKL wajib diupload';
                                        else if (fieldName === 'kartu_keluarga') errorMsg = 'Kartu Keluarga wajib diupload';
                                        else if (fieldName === 'ktp') errorMsg = 'KTP wajib diupload';
                                        
                                        errorDiv.textContent = errorMsg;
                                        uploadArea.insertAdjacentElement('afterend', errorDiv);
                                    }
                                }
                                isValid = false;
                            } else {
                                input.classList.remove('is-invalid');
                                // Hapus error message dan reset style jika ada
                                let uploadArea = input.closest('.upload-area');
                                if (uploadArea) {
                                    uploadArea.style.borderColor = '#dee2e6';
                                    uploadArea.style.backgroundColor = '#f8f9fa';
                                    
                                    let errorDiv = uploadArea.nextElementSibling;
                                    if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                                        errorDiv.remove();
                                    }
                                }
                            }
                        });
                    }
                    
                    return isValid;
                }
                
                let isValid = true;
                const currentStepEl = document.getElementById(`step-${step}`);
                
                if (currentStepEl) {
                    // Validasi input required
                    const requiredInputs = currentStepEl.querySelectorAll('[required]');
                    requiredInputs.forEach(input => {
                        if (!input.value.trim()) {
                            input.classList.add('is-invalid');
                            // Tambahkan error message tepat setelah input field
                            let errorDiv = input.nextElementSibling;
                            if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                                errorDiv = document.createElement('div');
                                errorDiv.className = 'invalid-feedback d-block';
                                const errorMsg = input.getAttribute('data-error') || 'Field ini wajib diisi';
                                errorDiv.textContent = errorMsg;
                                input.insertAdjacentElement('afterend', errorDiv);
                            }
                            isValid = false;
                        } else {
                            input.classList.remove('is-invalid');
                            // Hapus error message jika ada
                            const errorDiv = input.nextElementSibling;
                            if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                                errorDiv.remove();
                            }
                        }
                    });
                    
                    // Validasi select required
                    const requiredSelects = currentStepEl.querySelectorAll('select[required]');
                    requiredSelects.forEach(select => {
                        if (!select.value) {
                            select.classList.add('is-invalid');
                            // Tambahkan error message tepat setelah select field
                            let errorDiv = select.nextElementSibling;
                            if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                                errorDiv = document.createElement('div');
                                errorDiv.className = 'invalid-feedback d-block';
                                const errorMsg = select.getAttribute('data-error') || 'Pilih salah satu opsi';
                                errorDiv.textContent = errorMsg;
                                select.insertAdjacentElement('afterend', errorDiv);
                            }
                            isValid = false;
                        } else {
                            select.classList.remove('is-invalid');
                            // Hapus error message jika ada
                            const errorDiv = select.nextElementSibling;
                            if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                                errorDiv.remove();
                            }
                        }
                    });
                    
                    // Validasi khusus: jurusan cadangan harus berbeda dengan jurusan utama
                    const jurusanUtama = document.getElementById('jurusan_utama');
                    const jurusanCadangan = document.getElementById('jurusan_cadangan');
                    if (jurusanUtama && jurusanCadangan && jurusanUtama.value === jurusanCadangan.value) {
                        jurusanCadangan.classList.add('is-invalid');
                        // Tambahkan error message untuk jurusan cadangan
                        let errorDiv = jurusanCadangan.nextElementSibling;
                        if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                            errorDiv = document.createElement('div');
                            errorDiv.className = 'invalid-feedback d-block';
                            errorDiv.textContent = 'Jurusan cadangan harus berbeda dengan jurusan utama.';
                            jurusanCadangan.insertAdjacentElement('afterend', errorDiv);
                        }
                        isValid = false;
                    }
                }
                
                return isValid;
            }
            
            document.addEventListener('DOMContentLoaded', function() {
                // Check if there are server-side errors and show the appropriate step
                @if($errors->any())
                    // Wait a bit for the page to fully load
                    setTimeout(() => {
                        // Find the first field with an error and show that step
                        const errorFields = [@json(array_keys($errors->all()))];
                        const stepMapping = {
                            'name': 1, 'phone': 1, 'nik': 1, 'nisn': 1, 'jurusan_utama': 1, 'jurusan_cadangan': 1, 
                            'gender': 1, 'birth_date': 1, 'birth_place': 1, 'religion': 1, 'address': 1, 'asalSekolah': 1,
                            'photo_path': 2, 'ijazah': 2, 'kartu_keluarga': 2, 'akte_lahir': 2, 'ktp': 2, 'sertifikat': 2,
                            'email': 3, 'password': 3, 'password_confirmation': 3, 'setuju': 3
                        };
                        
                        // Find the step with the first error
                        let errorStep = 1;
                        for (let field of errorFields) {
                            if (stepMapping[field]) {
                                errorStep = stepMapping[field];
                                break;
                            }
                        }
                        
                        // Show the form tab and the step with errors
                        showTab('form');
                        currentStep = errorStep;
                        showStep(currentStep);
                    }, 100);
                @endif
                
                // Function to update jurusan options (both ways)
                function updateJurusanOptions() {
                    const jurusanUtama = document.getElementById('jurusan_utama');
                    const jurusanCadangan = document.getElementById('jurusan_cadangan');
                    
                    if (!jurusanUtama || !jurusanCadangan) return;
                    
                    // Get all options in both selects
                    const optionsUtama = jurusanUtama.querySelectorAll('option');
                    const optionsCadangan = jurusanCadangan.querySelectorAll('option');
                    
                    // Enable all options first
                    optionsUtama.forEach(option => {
                        option.disabled = false;
                    });
                    optionsCadangan.forEach(option => {
                        option.disabled = false;
                    });
                    
                    // Disable selected jurusan cadangan in jurusan utama
                    if (jurusanCadangan.value) {
                        const selectedInUtama = jurusanUtama.querySelector(`option[value="${jurusanCadangan.value}"]`);
                        if (selectedInUtama) {
                            selectedInUtama.disabled = true;
                        }
                        
                        // If jurusan utama is the same as jurusan cadangan, clear it
                        if (jurusanUtama.value === jurusanCadangan.value) {
                            jurusanUtama.value = '';
                        }
                    }
                    
                    // Disable selected jurusan utama in jurusan cadangan
                    if (jurusanUtama.value) {
                        const selectedInCadangan = jurusanCadangan.querySelector(`option[value="${jurusanUtama.value}"]`);
                        if (selectedInCadangan) {
                            selectedInCadangan.disabled = true;
                        }
                        
                        // If jurusan cadangan is the same as jurusan utama, clear it
                        if (jurusanCadangan.value === jurusanUtama.value) {
                            jurusanCadangan.value = '';
                        }
                    }
                }
                
                // Add event listeners for both jurusan selects
                const jurusanUtama = document.getElementById('jurusan_utama');
                const jurusanCadangan = document.getElementById('jurusan_cadangan');
                
                if (jurusanUtama) {
                    jurusanUtama.addEventListener('change', updateJurusanOptions);
                }
                
                if (jurusanCadangan) {
                    jurusanCadangan.addEventListener('change', updateJurusanOptions);
                }
                
                // Initialize on page load
                updateJurusanOptions();
                
                // Check if there's a success message and show form tab
                @if(session('success'))
                    // Wait a bit for the page to fully load
                    setTimeout(() => {
                        showTab('form');
                        // Reset form to show first step
                        currentStep = 1;
                        showStep(currentStep);
                        
                        // Show success modal
                        const successModal = new bootstrap.Modal(document.getElementById('successModal'), {
                            backdrop: 'static',
                            keyboard: false
                        });
                        successModal.show();
                        
                        // Add click handler for WhatsApp button
                        document.getElementById('whatsappRedirectBtn').addEventListener('click', function() {
                            // Close modal immediately when WhatsApp is clicked
                            const modalInstance = bootstrap.Modal.getInstance(document.getElementById('successModal'));
                            if (modalInstance) {
                                modalInstance.hide();
                            }
                        });
                        
                        // Start countdown timer
                        let countdown = 20;
                        const countdownElement = document.getElementById('countdown');
                        const countdownProgress = document.getElementById('countdown-progress');
                        let hasRedirected = false; // Flag to prevent double redirect
                        
                        const countdownInterval = setInterval(() => {
                            countdown--;
                            countdownElement.textContent = countdown;
                            
                            // Update progress bar
                            const progressPercentage = (countdown / 20) * 100;
                            countdownProgress.style.width = progressPercentage + '%';
                            countdownProgress.setAttribute('aria-valuenow', progressPercentage);
                            
                            // Change color as time runs out
                            if (countdown <= 5) {
                                countdownProgress.classList.remove('bg-primary');
                                countdownProgress.classList.add('bg-warning');
                                countdownElement.classList.remove('bg-primary');
                                countdownElement.classList.add('bg-warning');
                            }
                            if (countdown <= 2) {
                                countdownProgress.classList.remove('bg-warning');
                                countdownProgress.classList.add('bg-danger');
                                countdownElement.classList.remove('bg-warning');
                                countdownElement.classList.add('bg-danger');
                            }
                            
                            // Auto redirect when countdown reaches 0
                            if (countdown <= 0 && !hasRedirected) {
                                clearInterval(countdownInterval);
                                hasRedirected = true;
                                
                                // Auto redirect to WhatsApp group
                                window.open('https://chat.whatsapp.com/HczMn0QnznG1Y3lihSpYtW', '_blank');
                                
                                // Close modal after redirect
                                const successModal = bootstrap.Modal.getInstance(document.getElementById('successModal'));
                                successModal.hide();
                            }
                        }, 1000);
                    }, 100);
                @endif
                
                // Multi-step form variables
                let currentStep = 1;
                const totalSteps = 3;
                const btnNext1 = document.getElementById('btnNext1');
                const btnNext2 = document.getElementById('btnNext2');
                const btnPrev2 = document.getElementById('btnPrev2');
                const btnSubmit = document.getElementById('btnSubmit');
                const btnPrev3 = document.getElementById('btnPrev3');

                // Previous button click for step 3
                if (btnPrev3) {
                    btnPrev3.addEventListener('click', function() {
                        currentStep = 2;
                        showStep(currentStep);
                    });
                }

                // Next button click for step 1
                if (btnNext1) {
                    btnNext1.addEventListener('click', function() {
                        if (validateStep(1)) {
                            currentStep = 2;
                            showStep(currentStep);
                        } else {
                            // Show inline errors dengan pesan yang jelas
                            const requiredFields = document.querySelectorAll('#step-1 [required]');
                            requiredFields.forEach(field => {
                                if (!field.value.trim()) {
                                    field.classList.add('is-invalid');
                                    // Tambahkan error message tepat setelah field
                                    let errorDiv = field.nextElementSibling;
                                    if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                                        errorDiv = document.createElement('div');
                                        errorDiv.className = 'invalid-feedback d-block';
                                        const errorMsg = field.getAttribute('data-error') || 'Field ini wajib diisi';
                                        errorDiv.textContent = errorMsg;
                                        field.insertAdjacentElement('afterend', errorDiv);
                                    }
                                }
                            });
                        }
                    });
                }

                // Next button click for step 2
                if (btnNext2) {
                    btnNext2.addEventListener('click', function() {
                        if (validateStep(2)) {
                            currentStep = 3;
                            showStep(currentStep);
                        } else {
                            // Show inline errors untuk file upload yang kosong
                            const requiredFiles = document.querySelectorAll('#step-2 input[required]');
                            requiredFiles.forEach(fileInput => {
                                if (!fileInput.files || fileInput.files.length === 0) {
                                    fileInput.classList.add('is-invalid');
                                    // Tambahkan error message untuk upload field
                                    let uploadArea = fileInput.closest('.upload-area');
                                    if (uploadArea) {
                                        uploadArea.style.borderColor = '#dc3545';
                                        uploadArea.style.backgroundColor = '#f8d7da';
                                        
                                        let errorDiv = uploadArea.nextElementSibling;
                                        if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                                            errorDiv = document.createElement('div');
                                            errorDiv.className = 'invalid-feedback d-block';
                                            const fieldName = fileInput.getAttribute('name');
                                            let errorMsg = 'File ini wajib diupload';
                                            
                                            // Custom error message berdasarkan field
                                            if (fieldName === 'photo_path') errorMsg = 'Foto 3x4 wajib diupload';
                                            else if (fieldName === 'ijazah') errorMsg = 'Ijazah/SKL wajib diupload';
                                            else if (fieldName === 'kartu_keluarga') errorMsg = 'Kartu Keluarga wajib diupload';
                                            else if (fieldName === 'ktp') errorMsg = 'KTP wajib diupload';
                                            
                                            errorDiv.textContent = errorMsg;
                                            uploadArea.insertAdjacentElement('afterend', errorDiv);
                                        }
                                    }
                                }
                            });
                        }
                    });
                }

                // Previous button click for step 2
                if (btnPrev2) {
                    btnPrev2.addEventListener('click', function() {
                        currentStep = 1;
                        showStep(currentStep);
                    });
                }

                // Form submission handler
                const registrationForm = document.getElementById('registrationForm');
                if (registrationForm) {
                    registrationForm.addEventListener('submit', function(e) {
                        // Don't prevent default - let the form submit normally
                        // This allows server-side validation errors to be displayed
                        
                        // Validate all steps for client-side feedback only
                        let isValid = true;
                        for (let i = 1; i <= totalSteps; i++) {
                            if (!validateStep(i)) {
                                isValid = false;
                                currentStep = i;
                                showStep(currentStep);
                                // Show inline errors dengan pesan yang jelas
                                const stepFields = document.getElementById('step-' + i).querySelectorAll('[required]');
                                stepFields.forEach(field => {
                                    if (!field.value.trim()) {
                                        field.classList.add('is-invalid');
                                        // Tambahkan error message tepat setelah field
                                        let errorDiv = field.nextElementSibling;
                                        if (!errorDiv || !errorDiv.classList.contains('invalid-feedback')) {
                                            errorDiv = document.createElement('div');
                                            errorDiv.className = 'invalid-feedback d-block';
                                            const errorMsg = field.getAttribute('data-error') || 'Field ini wajib diisi';
                                            errorDiv.textContent = errorMsg;
                                            field.insertAdjacentElement('afterend', errorDiv);
                                        }
                                    }
                                });
                                e.preventDefault(); // Only prevent if client validation fails
                                break;
                            }
                        }

                        if (!isValid) return;

                        // Additional validations
                        const nikField = document.getElementById('nik');
                        if (nikField && nikField.value && !/^\d{16}$/.test(nikField.value)) {
                            isValid = false;
                            nikField.classList.add('is-invalid');
                            // Add error message below NIK field
                            let nikError = nikField.parentElement.querySelector('.invalid-feedback');
                            if (!nikError) {
                                nikError = document.createElement('div');
                                nikError.className = 'invalid-feedback d-block';
                                nikField.parentElement.appendChild(nikError);
                            }
                            nikError.textContent = 'NIK harus 16 digit angka.';
                            currentStep = 1;
                            showStep(currentStep);
                            e.preventDefault();
                            return;
                        }
                        
                        // NISN validation (10 digits)
                        const nisnField = document.getElementById('nisn');
                        if (nisnField && nisnField.value && !/^\d{10}$/.test(nisnField.value)) {
                            isValid = false;
                            nisnField.classList.add('is-invalid');
                            // Add error message below NISN field
                            let nisnError = nisnField.parentElement.querySelector('.invalid-feedback');
                            if (!nisnError) {
                                nisnError = document.createElement('div');
                                nisnError.className = 'invalid-feedback d-block';
                                nisnField.parentElement.appendChild(nisnError);
                            }
                            nisnError.textContent = 'NISN harus 10 digit angka.';
                            e.preventDefault();
                        }
                        
                        // Phone validation (10-15 digits)
                        const phoneField = document.getElementById('phone');
                        if (phoneField && phoneField.value && !/^\d{10,15}$/.test(phoneField.value)) {
                            isValid = false;
                            phoneField.classList.add('is-invalid');
                            // Add error message below phone field
                            let phoneError = phoneField.parentElement.querySelector('.invalid-feedback');
                            if (!phoneError) {
                                phoneError = document.createElement('div');
                                phoneError.className = 'invalid-feedback d-block';
                                phoneField.parentElement.appendChild(phoneError);
                            }
                            phoneError.textContent = 'Nomor WhatsApp harus 10-15 digit angka.';
                            e.preventDefault();
                        }
                        
                        // Email validation
                        const emailField = document.getElementById('email');
                        if (emailField && emailField.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailField.value)) {
                            isValid = false;
                            emailField.classList.add('is-invalid');
                            // Add error message below email field
                            let emailError = emailField.parentElement.querySelector('.invalid-feedback');
                            if (!emailError) {
                                emailError = document.createElement('div');
                                emailError.className = 'invalid-feedback d-block';
                                emailField.parentElement.appendChild(emailError);
                            }
                            emailError.textContent = 'Format email tidak valid.';
                            e.preventDefault();
                        }
                        
                        // Password validation (minimum 8 characters)
                        const passwordField = document.getElementById('password');
                        if (passwordField && passwordField.value && passwordField.value.length < 8) {
                            isValid = false;
                            passwordField.classList.add('is-invalid');
                            // Add error message below password field
                            let passwordError = passwordField.parentElement.parentElement.querySelector('.invalid-feedback');
                            if (!passwordError) {
                                passwordError = document.createElement('div');
                                passwordError.className = 'invalid-feedback d-block';
                                passwordField.parentElement.parentElement.appendChild(passwordError);
                            }
                            passwordError.textContent = 'Password minimal 8 karakter.';
                            e.preventDefault();
                        }
                        
                        // Password confirmation validation
                        const passwordConfirmField = document.getElementById('password_confirmation');
                        if (passwordField && passwordConfirmField && passwordField.value !== passwordConfirmField.value) {
                            isValid = false;
                            passwordConfirmField.classList.add('is-invalid');
                            // Add error message below confirm password field
                            let confirmError = passwordConfirmField.parentElement.parentElement.querySelector('.invalid-feedback');
                            if (!confirmError) {
                                confirmError = document.createElement('div');
                                confirmError.className = 'invalid-feedback d-block';
                                passwordConfirmField.parentElement.parentElement.appendChild(confirmError);
                            }
                            confirmError.textContent = 'Konfirmasi password tidak cocok.';
                            e.preventDefault();
                        }
                        
                        // Jurusan validation (must be different)
                        const jurusanUtama = document.getElementById('jurusan_utama');
                        const jurusanCadangan = document.getElementById('jurusan_cadangan');
                        if (jurusanUtama && jurusanCadangan) {
                            // Check if jurusan cadangan is empty (required)
                            if (!jurusanCadangan.value) {
                                isValid = false;
                                jurusanCadangan.classList.add('is-invalid');
                                // Add error message below jurusan cadangan field
                                let jurusanError = jurusanCadangan.nextElementSibling;
                                if (!jurusanError || !jurusanError.classList.contains('invalid-feedback')) {
                                    jurusanError = document.createElement('div');
                                    jurusanError.className = 'invalid-feedback d-block';
                                    jurusanError.textContent = 'Jurusan cadangan wajib dipilih.';
                                    jurusanCadangan.insertAdjacentElement('afterend', jurusanError);
                                }
                                e.preventDefault();
                            } else if (jurusanUtama.value === jurusanCadangan.value) {
                                isValid = false;
                                jurusanCadangan.classList.add('is-invalid');
                                // Add error message below jurusan cadangan field
                                let jurusanError = jurusanCadangan.nextElementSibling;
                                if (!jurusanError || !jurusanError.classList.contains('invalid-feedback')) {
                                    jurusanError = document.createElement('div');
                                    jurusanError.className = 'invalid-feedback d-block';
                                    jurusanError.textContent = 'Jurusan cadangan harus berbeda dengan jurusan utama.';
                                    jurusanCadangan.insertAdjacentElement('afterend', jurusanError);
                                }
                                e.preventDefault();
                            }
                        }
                        
                        // Checkbox validation
                        const agreementCheckbox = document.getElementById('setuju');
                        if (!agreementCheckbox.checked) {
                            isValid = false;
                            agreementCheckbox.classList.add('is-invalid');
                            // Add error message below checkbox
                            let agreementError = agreementCheckbox.parentElement.parentElement.querySelector('.invalid-feedback');
                            if (!agreementError) {
                                agreementError = document.createElement('div');
                                agreementError.className = 'invalid-feedback d-block';
                                agreementCheckbox.parentElement.parentElement.appendChild(agreementError);
                            }
                            agreementError.textContent = 'Anda harus menyetujui pernyataan untuk melanjutkan.';
                            e.preventDefault();
                        }
                        
                        // If all client-side validation passes, let the form submit normally
                        // so server-side validation can handle business logic and show errors
                    });
                }
                
                // Add visual feedback for form fields
                const formInputs = document.querySelectorAll('.form-control, .form-select');
                formInputs.forEach(input => {
                    input.addEventListener('focus', function() {
                        this.classList.remove('is-invalid');
                    });
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Fungsi untuk menangani upload area
                function setupUploadArea(uploadAreaId, fileInputId, fileInfoId) {
                    const uploadArea = document.getElementById(uploadAreaId);
                    const fileInput = document.getElementById(fileInputId);
                    const fileInfo = document.getElementById(fileInfoId);
                    
                    uploadArea.addEventListener('click', () => fileInput.click());
                    
                    fileInput.addEventListener('change', function() {
                        if (this.files.length > 0) {
                            const fileName = this.files[0].name;
                            fileInfo.textContent = fileName;
                            fileInfo.classList.add('text-primary', 'fw-medium');
                            
                            // Reset validation state untuk field ini
                            this.classList.remove('is-invalid');
                            const uploadArea = this.closest('.upload-area');
                            if (uploadArea) {
                                uploadArea.style.borderColor = '#dee2e6';
                                uploadArea.style.backgroundColor = '#f8f9fa';
                                
                                // Hapus error message jika ada
                                let errorDiv = uploadArea.nextElementSibling;
                                if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                                    errorDiv.remove();
                                }
                            }
                        } else {
                            // Jika file dihapus, reset ke state semula
                            fileInfo.textContent = 'Format JPG/PNG, maks. 200KB';
                            fileInfo.classList.remove('text-primary', 'fw-medium');
                            
                            // Reset validation state
                            this.classList.remove('is-invalid');
                            const uploadArea = this.closest('.upload-area');
                            if (uploadArea) {
                                uploadArea.style.borderColor = '#dee2e6';
                                uploadArea.style.backgroundColor = '#f8f9fa';
                                
                                // Hapus error message jika ada
                                let errorDiv = uploadArea.nextElementSibling;
                                if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                                    errorDiv.remove();
                                }
                            }
                        }
                    });
                    
                    // Drag and drop functionality
                    uploadArea.addEventListener('dragover', (e) => {
                        e.preventDefault();
                        uploadArea.style.borderColor = '#0d6efd';
                        uploadArea.style.backgroundColor = '#f1f8ff';
                    });
                    
                    uploadArea.addEventListener('dragleave', () => {
                        uploadArea.style.borderColor = '#dee2e6';
                        uploadArea.style.backgroundColor = '#f8f9fa';
                    });
                    
                    uploadArea.addEventListener('drop', (e) => {
                        e.preventDefault();
                        uploadArea.style.borderColor = '#dee2e6';
                        uploadArea.style.backgroundColor = '#f8f9fa';
                        
                        if (e.dataTransfer.files.length) {
                            fileInput.files = e.dataTransfer.files;
                            const fileName = e.dataTransfer.files[0].name;
                            fileInfo.textContent = fileName;
                            fileInfo.classList.add('text-primary', 'fw-medium');
                            
                            // Reset validation state untuk field ini
                            fileInput.classList.remove('is-invalid');
                            uploadArea.style.borderColor = '#dee2e6';
                            uploadArea.style.backgroundColor = '#f8f9fa';
                            
                            // Hapus error message jika ada
                            let errorDiv = uploadArea.nextElementSibling;
                            if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                                errorDiv.remove();
                            }
                            
                            // Trigger change event
                            const event = new Event('change');
                            fileInput.dispatchEvent(event);
                        } else {
                            // Jika tidak ada file yang di-drop
                            fileInfo.textContent = 'Format JPG/PNG, maks. 200KB';
                            fileInfo.classList.remove('text-primary', 'fw-medium');
                        }
                    });
                }
                
                // Setup semua upload area
                setupUploadArea('photoUploadArea', 'photo_path', 'photoFileInfo');
                setupUploadArea('ijazahUploadArea', 'ijazah', 'ijazahFileInfo');
                setupUploadArea('kkUploadArea', 'kartu_keluarga', 'kkFileInfo');
                setupUploadArea('akteUploadArea', 'akte_lahir', 'akteFileInfo');
                setupUploadArea('ktpUploadArea', 'ktp', 'ktpFileInfo');
                setupUploadArea('sertifikatUploadArea', 'sertifikat', 'sertifikatFileInfo');
                
                // Handle multiple files for sertifikat
                const sertifikatInput = document.getElementById('sertifikat');
                const sertifikatInfo = document.getElementById('sertifikatFileInfo');
                
                sertifikatInput.addEventListener('change', function() {
                    if (this.files.length > 0) {
                        if (this.files.length === 1) {
                            sertifikatInfo.textContent = this.files[0].name;
                        } else {
                            sertifikatInfo.textContent = `${this.files.length} file dipilih`;
                        }
                        sertifikatInfo.classList.add('text-primary', 'fw-medium');
                    }
                });
            });
        </script>
@endpush