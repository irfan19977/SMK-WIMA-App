@extends('home.layouts.app')

@section('content')
    <div class="hero-wrap hero-wrap-2" style="background-image: url('{{ asset('frontend/images/bg.png') }}'); background-attachment:fixed;">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-8 ftco-animate text-center">
            <p class="breadcrumbs"><span class="mr-2"><a href="{{ route('/') }}">Home</a></span> <span>TBSM</span></p>
            <h1 class="mb-3 bread">Teknik Bisnis Sepeda Motor (TBSM)</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section bg-light">
      <div class="container">
        <div class="row justify-content-center mb-4 pb-2">
          <div class="col-md-10 heading-section ftco-animate text-center">
            <h2 class="mb-3">Profil Jurusan TBSM</h2>
            <p>Jurusan Teknik Bisnis Sepeda Motor (TBSM) SMK PGRI Lawang membekali siswa dengan kemampuan perbaikan dan perawatan sepeda motor, manajemen penjualan dan layanan purna jual, keterampilan interpersonal pelanggan, serta pengetahuan bisnis dan kewirausahaan di industri otomotif roda dua.</p>
          </div>
        </div>

        <div class="row justify-content-center">
          <div class="col-md-10 ftco-animate">
            <div class="p-4 p-md-5 bg-white rounded shadow-sm">
              <h3 class="mb-3">Kompetensi yang Dipelajari</h3>
              <div class="row">
                <div class="col-md-6">
                  <ul class="mt-2">
                    <li>Perbaikan dan pemeliharaan mesin sepeda motor.</li>
                    <li>Sistem kelistrikan dan pengisian daya sepeda motor.</li>
                    <li>Layanan purna jual dan customer relationship management (CRM).</li>
                  </ul>
                </div>
                <div class="col-md-6">
                  <ul class="mt-2">
                    <li>Manajemen bengkel dan penjualan suku cadang.</li>
                    <li>Teknik penjualan dan negosiasi dengan pelanggan.</li>
                    <li>Kewirausahaan dan pengelolaan bisnis otomotif roda dua.</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-5 justify-content-center">
          <div class="col-md-10 heading-section ftco-animate text-center">
            <h3 class="mb-3">Prospek Karir Lulusan TBSM</h3>
            <p>Lulusan TBSM siap bekerja di bengkel resmi, dealer sepeda motor, showroom, atau membuka usaha bengkel dan layanan jasa perawatan sepeda motor sendiri dengan skill bisnis yang kompetitif.</p>
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-md-4 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services p-4 d-block text-center bg-white rounded shadow-sm">
              <div class="icon d-flex justify-content-center align-items-center mb-3"><span class="flaticon-mechanic"></span></div>
              <div class="media-body px-2">
                <h4 class="heading mb-2">Mekanik Sepeda Motor</h4>
                <p>Melakukan perbaikan, pemeliharaan berkala, dan troubleshooting sepeda motor dengan standar kualitas tinggi sesuai spesifikasi pabrikan.</p>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services p-4 d-block text-center bg-white rounded shadow-sm">
              <div class="icon d-flex justify-content-center align-items-center mb-3"><span class="flaticon-customer-service"></span></div>
              <div class="media-body px-2">
                <h4 class="heading mb-2">Service Advisor & Sales</h4>
                <p>Menangani layanan purna jual, konsultasi perbaikan, penjualan suku cadang, dan membangun hubungan baik dengan pelanggan.</p>
              </div>
            </div>
          </div>
          <div class="col-md-4 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services p-4 d-block text-center bg-white rounded shadow-sm">
              <div class="icon d-flex justify-content-center align-items-center mb-3"><span class="flaticon-business"></span></div>
              <div class="media-body px-2">
                <h4 class="heading mb-2">Pengusaha Bengkel & Dealer</h4>
                <p>Membuka dan mengelola usaha bengkel, dealer sepeda motor, atau pusat servis dengan strategi bisnis dan customer service yang efektif.</p>
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
            <h2 class="mb-4">Galeri Kegiatan TBSM</h2>
            <p>Berikut beberapa dokumentasi kegiatan praktik bengkel, pelatihan penjualan, kunjungan dealer, dan kompetisi yang diikuti oleh siswa TBSM SMK PGRI Lawang.</p>
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
            <a href="{{ route('gallery.tbsm') }}" class="btn btn-primary py-3 px-4">Lihat Semua Galeri</a>
          </div>
        </div>
      </div>
    </section>
@endsection