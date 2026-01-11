@extends('home.layouts.app')

@section('content')
    <div class="hero-wrap hero-wrap-2" style="background-image: url('{{ asset('frontend/images/bg.png') }}'); background-attachment:fixed;">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-8 ftco-animate text-center">
            <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('/') }}">Home</a></span> <span>TKR</span></p>
            <h1 class="mb-3 bread">Teknik Kendaraan Ringan (TKR)</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section bg-light">
      <div class="container">
        <div class="row justify-content-center mb-4 pb-2">
          <div class="col-md-10 heading-section ftco-animate text-center">
            <h2 class="mb-3">Profil Jurusan TKR</h2>
            <p>Jurusan Teknik Kendaraan Ringan (TKR) SMK PGRI Lawang membekali siswa dengan kemampuan diagnosis dan perbaikan mesin, sistem kelistrikan, pemeliharaan berkala, modifikasi kendaraan, serta menerapkan standar keselamatan kerja di industri otomotif.</p>
          </div>
        </div>

        <div class="row justify-content-center">
          <div class="col-md-10 ftco-animate">
            <div class="p-4 p-md-5 bg-white rounded shadow-sm">
              <h3 class="mb-3">Kompetensi yang Dipelajari</h3>
              <div class="row">
                <div class="col-md-6">
                  <ul class="mt-2">
                    <li>Pemeliharaan dan perbaikan mesin kendaraan ringan.</li>
                    <li>Diagnosis dan troubleshooting sistem kelistrikan otomotif.</li>
                    <li>Perawatan sistem suspensi, rem, dan transmisi.</li>
                  </ul>
                </div>
                <div class="col-md-6">
                  <ul class="mt-2">
                    <li>Pemeriksaan dan penyesuaian sistem pembakaran dan bahan bakar.</li>
                    <li>Penerapan standar keselamatan kerja dan K3 di bengkel.</li>
                    <li>Modifikasi dan customisasi kendaraan sesuai permintaan pelanggan.</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-5 justify-content-center">
          <div class="col-md-10 heading-section ftco-animate text-center">
            <h3 class="mb-3">Prospek Karir Lulusan TKR</h3>
            <p>Lulusan TKR siap bekerja di bengkel resmi, showroom otomotif, industri manufaktur kendaraan, atau membuka usaha bengkel dan jasa servis otomotif sendiri.</p>
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-4 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services p-4 d-block text-center bg-white rounded shadow-sm">
              <div class="icon d-flex justify-content-center align-items-center mb-3"><span class="flaticon-car-repair"></span></div>
              <div class="media-body px-2">
                <h4 class="heading mb-2">Mekanik Kendaraan Ringan</h4>
                <p>Melakukan perbaikan, pemeliharaan, dan penyetelan mesin kendaraan dengan menggunakan peralatan dan teknik perbaikan yang tepat.</p>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services p-4 d-block text-center bg-white rounded shadow-sm">
              <div class="icon d-flex justify-content-center align-items-center mb-3"><span class="flaticon-technician"></span></div>
              <div class="media-body px-2">
                <h4 class="heading mb-2">Teknisi Listrik Otomotif</h4>
                <p>Menangani diagnosis dan perbaikan sistem kelistrikan, pengisian daya, dan sistem pencahayaan kendaraan.</p>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services p-4 d-block text-center bg-white rounded shadow-sm">
              <div class="icon d-flex justify-content-center align-items-center mb-3"><span class="flaticon-service"></span></div>
              <div class="media-body px-2">
                <h4 class="heading mb-2">Service Advisor & Wirausaha</h4>
                <p>Melayani konsultasi perbaikan kendaraan kepada pelanggan atau membuka bengkel dan layanan servis otomotif independen.</p>
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
								<p><a href="{{ route('pendaftaran.index') }}" class="btn btn-primary py-3 px-4">Daftar Sekarang!</a></p>
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
            <h2 class="mb-4">Galeri Kegiatan TKR</h2>
            <p>Berikut beberapa dokumentasi kegiatan praktik bengkel, kunjungan industri otomotif, dan kompetisi yang diikuti oleh siswa TKR SMK PGRI Lawang.</p>
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
            <a href="{{ route('gallery.tkr') }}" class="btn btn-primary py-3 px-4">Lihat Semua Galeri</a>
          </div>
        </div>
      </div>
    </section>
@endsection