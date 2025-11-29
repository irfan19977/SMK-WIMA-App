@extends('home.layouts.app')

@section('content')
    <div class="hero-wrap hero-wrap-2" style="background-image: url('{{ asset('frontend/images/bg.png') }}'); background-attachment:fixed;">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-8 ftco-animate text-center">
            <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('/') }}">Home</a></span> <span>TKJ</span></p>
            <h1 class="mb-3 bread">Teknik Komputer dan Jaringan (TKJ)</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section bg-light">
      <div class="container">
        <div class="row justify-content-center mb-4 pb-2">
          <div class="col-md-10 heading-section ftco-animate text-center">
            <h2 class="mb-3">Profil Jurusan TKJ</h2>
            <p>Jurusan Teknik Komputer dan Jaringan (TKJ) SMK PGRI Lawang membekali siswa dengan kemampuan perakitan komputer, administrasi jaringan, instalasi jaringan kabel & nirkabel, pengelolaan server, hingga dasar keamanan jaringan.</p>
          </div>
        </div>

        <div class="row justify-content-center">
          <div class="col-md-10 ftco-animate">
            <div class="p-4 p-md-5 bg-white rounded shadow-sm">
              <h3 class="mb-3">Kompetensi yang Dipelajari</h3>
              <div class="row">
                <div class="col-md-6">
                  <ul class="mt-2">
                    <li>Perakitan, perawatan, dan troubleshooting komputer.</li>
                    <li>Instalasi dan konfigurasi sistem operasi client & server.</li>
                    <li>Instalasi jaringan LAN, WLAN, dan konfigurasi perangkat jaringan.</li>
                  </ul>
                </div>
                <div class="col-md-6">
                  <ul class="mt-2">
                    <li>Administrasi server (file server, web server, dan lainnya).</li>
                    <li>Dasar keamanan jaringan dan manajemen user.</li>
                    <li>Penerapan etika profesi dan kerja tim di lingkungan IT.</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-5 justify-content-center">
          <div class="col-md-10 heading-section ftco-animate text-center">
            <h3 class="mb-3">Prospek Karir Lulusan TKJ</h3>
            <p>Lulusan TKJ siap bekerja di berbagai bidang teknologi informasi, lembaga pendidikan, instansi pemerintahan, maupun membuka usaha sendiri.</p>
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-4 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services p-4 d-block text-center bg-white rounded shadow-sm">
              <div class="icon d-flex justify-content-center align-items-center mb-3"><span class="flaticon-computer"></span></div>
              <div class="media-body px-2">
                <h4 class="heading mb-2">Teknisi Komputer & Jaringan</h4>
                <p>Menangani perakitan, perawatan, dan perbaikan perangkat komputer serta instalasi jaringan di kantor, sekolah, maupun bisnis.</p>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services p-4 d-block text-center bg-white rounded shadow-sm">
              <div class="icon d-flex justify-content-center align-items-center mb-3"><span class="flaticon-network"></span></div>
              <div class="media-body px-2">
                <h4 class="heading mb-2">Network Administrator</h4>
                <p>Bertanggung jawab atas konfigurasi, pemantauan, dan keamanan jaringan di perusahaan, lembaga pendidikan, atau instansi pemerintahan.</p>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services p-4 d-block text-center bg-white rounded shadow-sm">
              <div class="icon d-flex justify-content-center align-items-center mb-3"><span class="flaticon-employee"></span></div>
              <div class="media-body px-2">
                <h4 class="heading mb-2">IT Support & Wirausaha</h4>
                <p>Melayani kebutuhan dukungan IT harian serta membuka jasa servis komputer, instalasi jaringan, dan solusi IT lainnya.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-freeTrial">
    	<div class="container">
    		<div class="row">
    			<div class="col-md-12">
    				<div class="d-flex flex-column align-items-center text-center">
							<div class="free-trial ftco-animate">
								<h3>Daftar Sekarang</h3>
								<p>Raih masa depan cemerlang! Daftarkan diri Anda dan bergabung dengan SMK PGRI Lawang untuk pendidikan berkualitas dan peluang karir yang luas.</p>
							</div>
							<div class="btn-join ftco-animate mt-3 mt-md-0 ml-md-4">
								<p><a href="#" class="btn btn-primary py-3 px-4">Daftar Sekarang!</a></p>
							</div>
						</div>
    			</div>
    		</div>
    	</div>
    </section>

    <section class="ftco-section">
      <div class="container">
        <div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-8 heading-section ftco-animate text-center">
            <h2 class="mb-4">Galeri Kegiatan TKJ</h2>
            <p>Berikut beberapa dokumentasi kegiatan praktik, kunjungan industri, dan lomba yang diikuti oleh siswa TKJ SMK PGRI Lawang.</p>
          </div>
        </div>
        <div class="row g-4">
            @foreach($galleries as $g)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ $g->image ? url('storage/' . $g->image) : url('assets/img/default-news.jpg') }}" 
                             class="card-img-top" 
                             alt="{{ $g->title }}"
                             style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $g->title }}</h5>
                            <p class="card-text">{{ $g->description }}</p>
                        </div>
                    </div>
                </div>
                @if($loop->iteration % 3 == 0 && !$loop->last)
                    </div><div class="row g-4">
                @endif
            @endforeach
        </div>
        <div class="row mt-4 justify-content-center">
          <div class="col-md-4 text-center">
            <a href="{{ route('gallery.tkj') }}" class="btn btn-primary py-3 px-4">Lihat Semua Galeri</a>
          </div>
        </div>
      </div>
    </section>
@endsection