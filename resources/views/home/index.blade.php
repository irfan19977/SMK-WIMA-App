@extends('home.layouts.app')

@section('content')
        <div class="hero-wrap" style="background-image: url('{{ asset('frontend/images/bg.png') }}'); background-attachment:fixed;">
      <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center" data-scrollax-parent="true">
          <div class="col-md-8 ftco-animate text-center">
            <h1 class="mb-4">SMK PGRI LAWANG</h1>
            <p>SMK PGRI LAWANG adalah Sekolah Menengah Kejuruan yang berlokasi di Jl. DR. Wahidin No.17, Krajan, Kalirejo, Kec. Lawang, Kabupaten Malang.</p>
            <p><a href="#" class="btn btn-primary px-4 py-3">Daftar Sekarang</a> <a href="#" class="btn btn-secondary px-4 py-3">Tentang Kami</a></p>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-search-course">
    	<div class="container">
    		<div class="row">
    			<div class="col-md-12">
    				<div class="courseSearch-wrap d-md-flex flex-column-reverse">
    					<div class="full-wrap ftco-animate">
    						<div class="one-half">
    							@isset($featuredNews)
    								<div class="featured-blog d-md-flex mb-3">
    									<div class="image d-flex order-last">
    										<a href="{{ route('berita.detail', $featuredNews->slug) }}" class="img" style="background: url({{ $featuredNews->thumbnail_url }}); min-height: 200px; background-size: cover; background-position: center;"></a>
    									</div>
    									<div class="text order-first">
    										<span class="date">{{ $featuredNews->published_at->format('d M Y') }}</span>
    										<h3><a href="{{ route('berita.detail', $featuredNews->slug) }}">{{ $featuredNews->title }}</a></h3>
    										<p class="mt-3">{{ Str::limit($featuredNews->excerpt, 150) }}</p>
    									</div>
    								</div>
    							@endisset
    						</div>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
    </section>

    <section class="ftco-section">
    	<div class="container">
    		<div class="row">
          <div class="col-md-4 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services p-3 py-4 d-block text-center">
              <div class="icon d-flex justify-content-center align-items-center mb-3"><span class="flaticon-exam"></span></div>
              <div class="media-body px-3">
                <h3 class="heading">Laboratorium Lengkap</h3>
                <p>Lab praktik tiap jurusan dilengkapi peralatan standar industri untuk mengasah keterampilan siswa secara profesional.</p>
              </div>
            </div>      
          </div>
          <div class="col-md-4 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services p-3 py-4 d-block text-center">
              <div class="icon d-flex justify-content-center align-items-center mb-3"><span class="flaticon-blackboard"></span></div>
              <div class="media-body px-3">
                <h3 class="heading">Wifi Gratis & Beasiswa</h3>
                <p>WiFi gratis di seluruh area sekolah dan program beasiswa untuk siswa berprestasi maupun kurang mampu.</p>
              </div>
            </div>      
          </div>
          <div class="col-md-4 d-flex align-self-stretch ftco-animate">
            <div class="media block-6 services p-3 py-4 d-block text-center">
              <div class="icon d-flex justify-content-center align-items-center mb-3"><span class="flaticon-books"></span></div>
              <div class="media-body px-3">
                <h3 class="heading">Perpustakaan</h3>
                <p>Perpustakaan lengkap dengan koleksi buku dan sumber belajar yang mendukung proses pembelajaran siswa.</p>
              </div>
            </div>    
          </div>
        </div>
    	</div>
    </section>


    <section class="ftco-section-3 img" style="background-image: url({{ asset('frontend/images/bg.png') }});">
    	<div class="overlay"></div>
    	<div class="container">
    		<div class="row d-md-flex justify-content-center">
    			<div class="col-md-9 about-video text-center">
    				<h2 class="ftco-animate">SMK PGRI Lawang - Mencetak Lulusan Berkualitas dan Siap Kerja</h2>
    				<div class="video d-flex justify-content-center">
    					<a href="https://vimeo.com/45830194" class="button popup-vimeo d-flex justify-content-center align-items-center"><span class="ion-ios-play"></span></a>
    				</div>
    			</div>
    		</div>
    	</div>
    </section>
    <section class="ftco-counter bg-light" id="section-counter">
    	<div class="container">
    		<div class="row justify-content-center">
    			<div class="col-md-10">
		    		<div class="row">
		          <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
		            <div class="block-18 text-center">
		              <div class="text">
		                <strong class="number" data-number="{{ number_format(App\Models\Visitor::getMonthlyVisitors()) }}">0</strong>
		                <span>Pengunjung Bulan ini</span>
		              </div>
		            </div>
		          </div>
		          <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
		            <div class="block-18 text-center">
		              <div class="text">
		                <strong class="number" data-number="{{ number_format(App\Models\Visitor::getTodayVisitors()) }}">0</strong>
		                <span>Pengunjung Hari ini</span>
		              </div>
		            </div>
		          </div>
		          <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
		            <div class="block-18 text-center">
		              <div class="text">
		                <strong class="number" data-number="{{ number_format(App\Models\Visitor::getWeeklyVisitors()) }}">0</strong>
		                <span>Pengunjung Minggu ini</span>
		              </div>
		            </div>
		          </div>
		          <div class="col-md-3 d-flex justify-content-center counter-wrap ftco-animate">
		            <div class="block-18 text-center">
		              <div class="text">
		                <strong class="number" data-number="200">0</strong>
		                <span>Artikel Edukasi</span>
		              </div>
		            </div>
		          </div>
		        </div>
	        </div>
        </div>
    	</div>
    </section>

    <section class="ftco-section testimony-section">
      <div class="container">
      	<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
            <h2 class="mb-4">Kata Alumni Kami</h2>
          </div>
        </div>
        <div class="row">
        	<div class="col-md-12 ftco-animate">
            <div class="carousel-testimony owl-carousel">
              <div class="item">
                <div class="testimony-wrap text-center">
                  <div class="user-img mb-5" style="background-image: url(images/person_1.jpg)">
                    <span class="quote d-flex align-items-center justify-content-center">
                      <i class="icon-quote-left"></i>
                    </span>
                  </div>
                  <div class="text">
                    <p class="mb-5">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
                    <p class="name">Dennis Green</p>
                    <span class="position">CSE Student</span>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="testimony-wrap text-center">
                  <div class="user-img mb-5" style="background-image: url(images/person_2.jpg)">
                    <span class="quote d-flex align-items-center justify-content-center">
                      <i class="icon-quote-left"></i>
                    </span>
                  </div>
                  <div class="text">
                    <p class="mb-5">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
                    <p class="name">Dennis Green</p>
                    <span class="position">Math Student</span>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="testimony-wrap text-center">
                  <div class="user-img mb-5" style="background-image: url(images/person_3.jpg)">
                    <span class="quote d-flex align-items-center justify-content-center">
                      <i class="icon-quote-left"></i>
                    </span>
                  </div>
                  <div class="text">
                    <p class="mb-5">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
                    <p class="name">Dennis Green</p>
                    <span class="position">Science Students</span>
                  </div>
                </div>
              </div>
              <div class="item">
                <div class="testimony-wrap text-center">
                  <div class="user-img mb-5" style="background-image: url(images/person_3.jpg)">
                    <span class="quote d-flex align-items-center justify-content-center">
                      <i class="icon-quote-left"></i>
                    </span>
                  </div>
                  <div class="text">
                    <p class="mb-5">Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.</p>
                    <p class="name">Dennis Green</p>
                    <span class="position">English Student</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="ftco-section bg-light">
      <div class="container">
      	<div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
            <h2 class="mb-4"><u>Mitra Industri</u></h2>
            <p>SMK PGRI Lawang bekerja sama dengan berbagai perusahaan dan institusi terkemuka untuk memberikan pengalaman praktik kerja dan peluang karir bagi siswa.</p>
          </div>
        </div>
        <div class="row">
        	<div class="col-lg-4 mb-sm-4 ftco-animate">
        		<div class="staff">
        			<div class="d-flex mb-4">
        				<div class="img" style="background-image: url(images/person_1.jpg);"></div>
        				<div class="info ml-4">
        					<h3><a href="teacher-single.html">Ivan Jacobson</a></h3>
        					<span class="position">CSE Teacher</span>
        					<p class="ftco-social d-flex">
		                <a href="#" class="d-flex justify-content-center align-items-center"><span class="icon-twitter"></span></a>
		                <a href="#" class="d-flex justify-content-center align-items-center"><span class="icon-facebook"></span></a>
		                <a href="#" class="d-flex justify-content-center align-items-center"><span class="icon-instagram"></span></a>
		              </p>
        				</div>
        			</div>
        			<div class="text">
        				<p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name</p>
        			</div>
        		</div>
        	</div>
        	<div class="col-lg-4 mb-sm-4 ftco-animate">
        		<div class="staff">
        			<div class="d-flex mb-4">
        				<div class="img" style="background-image: url(images/person_2.jpg);"></div>
        				<div class="info ml-4">
        					<h3><a href="teacher-single.html">Ivan Jacobson</a></h3>
        					<span class="position">CSE Teacher</span>
        					<p class="ftco-social d-flex">
		                <a href="#" class="d-flex justify-content-center align-items-center"><span class="icon-twitter"></span></a>
		                <a href="#" class="d-flex justify-content-center align-items-center"><span class="icon-facebook"></span></a>
		                <a href="#" class="d-flex justify-content-center align-items-center"><span class="icon-instagram"></span></a>
		              </p>
        				</div>
        			</div>
        			<div class="text">
        				<p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name</p>
        			</div>
        		</div>
        	</div>
        	<div class="col-lg-4 mb-sm-4 ftco-animate">
        		<div class="staff">
        			<div class="d-flex mb-4">
        				<div class="img" style="background-image: url(images/person_3.jpg);"></div>
        				<div class="info ml-4">
        					<h3><a href="teacher-single.html">Ivan Jacobson</a></h3>
        					<span class="position">CSE Teacher</span>
        					<p class="ftco-social d-flex">
		                <a href="#" class="d-flex justify-content-center align-items-center"><span class="icon-twitter"></span></a>
		                <a href="#" class="d-flex justify-content-center align-items-center"><span class="icon-facebook"></span></a>
		                <a href="#" class="d-flex justify-content-center align-items-center"><span class="icon-instagram"></span></a>
		              </p>
        				</div>
        			</div>
        			<div class="text">
        				<p>Even the all-powerful Pointing has no control about the blind texts it is an almost unorthographic life One day however a small line of blind text by the name</p>
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
    					<div class="btn-join ftco-animate mt-3 mt-md-0">
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
			  <div class="col-md-7 heading-section ftco-animate text-center">
				<h2 class="mb-4">Program Keahlian</h2>
			  </div>
			</div>
			<div class="row">
				<div class="col-md-6 d-flex ftco-animate">
					<div class="course align-self-stretch">
						<a href="#" class="img" style="background-image: url(images/course-1.jpg)"></a>
						<div class="text p-4">
    						<p class="category"><span>Jurusan</span></p>
    						<h3 class="mb-3"><a href="#">Teknik Kendaraan Ringan</a></h3>
    						<p>Jurusan Teknik Kendaraan Ringan (TKR) merupakan program keahlian unggulan yang fokus pada pengembangan kompetensi di bidang otomotif, perawatan, dan perbaikan kendaraan bermotor.</p>
    						<p><a href="#" class="btn btn-primary">Lihat Selengkapnya!</a></p>
    						</div>
					</div>
				</div>
				<div class="col-md-6 d-flex ftco-animate">
					<div class="course align-self-stretch">
						<a href="#" class="img" style="background-image: url(images/course-2.jpg)"></a>
						<div class="text p-4">
    						<p class="category"><span>Jurusan</span></p>
    						<h3 class="mb-3"><a href="#">Teknik Bisnis Sepeda Motor</a></h3>
    						<p>Jurusan Teknik Bisnis Sepeda Motor (TBSM) merupakan program keahlian yang memadukan kompetensi teknik sepeda motor dengan prinsip-prinsip bisnis dan entrepreneurship modern.</p>
    						<p><a href="#" class="btn btn-primary">Lihat Selengkapnya!</a></p>
    						</div>
					</div>
				</div>
				<div class="col-md-6 d-flex ftco-animate">
					<div class="course align-self-stretch">
						<a href="#" class="img" style="background-image: url(images/course-3.jpg)"></a>
						<div class="text p-4">
    						<p class="category"><span>Jurusan</span></p>
    						<h3 class="mb-3"><a href="#">Teknik Kimia Industri</a></h3>
    						<p>Jurusan Teknik Kimia Industri merupakan program keahlian yang mempelajari proses kimia untuk menghasilkan produk industri, pengolahan bahan baku, dan kontrol kualitas produk kimia.</p>
    							<p><a href="#" class="btn btn-primary">Lihat Selengkapnya!</a></p>
						</div>
					</div>
				</div>
				<div class="col-md-6 d-flex ftco-animate">
					<div class="course align-self-stretch">
						<a href="#" class="img" style="background-image: url(images/course-1.jpg)"></a>
						<div class="text p-4">
							<p class="category"><span>Jurusan</span></p>
							<h3 class="mb-3"><a href="#">Teknik Komputer dan Jaringan</a></h3>
							<p>Jurusan Teknik Komputer dan Jaringan (TKJ) merupakan salah satu program keahlian unggulan yang fokus pada pengembangan kompetensi di bidang teknologi informasi dan komunikasi.</p>
							<p><a href="#" class="btn btn-primary">Lihat Selengkapnya!</a></p>
						</div>
					</div>
				</div>
			</div>
    	</div>
    </section>

    <section class="ftco-section bg-light">
      <div class="container">
        <div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
            <h2 class="mb-4">Informasi Terbaru</h2>
          </div>
        </div>
        <div class="row d-flex">
          @foreach($latestNews as $news)
          <div class="col-md-4 d-flex ftco-animate">
            <div class="blog-entry align-self-stretch" style="width: 100%; height: 100%;">
              <a href="{{ route('berita.detail', $news->slug) }}" class="block-20" style="background-image: url('{{ $news->thumbnail_url }}'); height: 200px; display: block; background-size: cover; background-position: center;">
              </a>
              <div class="text p-4 d-block">
                <div class="meta mb-3">
                  <div><a href="#">{{ $news->published_at->format('F d, Y') }}</a></div>
                  <div><a href="#">Admin</a></div>
                  <div><a href="#" class="meta-chat"><span class="icon-eye"></span> {{ $news->view_count ?? 0 }}</a></div>
                </div>
                <h3 class="heading mt-3" style="flex-grow: 1;"><a href="{{ route('berita.detail', $news->slug) }}" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-overflow: ellipsis; min-height: 3em;">{{ $news->title }}</a></h3>
                <p style="margin-bottom: 0;">{{ Str::limit($news->excerpt, 100) }}</p>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
    </section>

    <section class="ftco-section">
      <div class="container">
        <div class="row justify-content-center mb-5 pb-3">
          <div class="col-md-7 heading-section ftco-animate text-center">
            <h2 class="mb-4">Kegiatan Sekolah</h2>
          </div>
        </div>
        <div class="row">
          @foreach($categoriesNews as $index => $news)
          <div class="col-md-4 d-flex ftco-animate">
            @if($index !== 1)
              <!-- Layout untuk card 1 dan 3: Gambar di atas, deskripsi di bawah -->
              <div class="blog-entry align-self-stretch">
                <a href="{{ route('berita.detail', $news->slug) }}" class="block-20" style="background-image: url('{{ $news->thumbnail_url }}'); height: 200px;">
                </a>
                <div class="text p-4 d-block">
                  <div class="meta mb-3">
                    <div><a href="#">{{ $news->published_at->format('d M Y') }}</a></div>
                    <div><a href="#">{{ $news->author->name ?? 'Admin' }}</a></div>
                    <div><a href="#" class="meta-chat"><span class="icon-eye"></span> {{ $news->view_count ?? 0 }}</a></div>
                  </div>
                  <h3 class="heading"><a href="{{ route('berita.detail', $news->slug) }}">{{ $news->title }}</a></h3>
                  <p>{{ Str::limit($news->excerpt, 100) }}</p>
                  <p><a href="{{ route('berita.detail', $news->slug) }}">Baca Selengkapnya <i class="ion-ios-arrow-forward"></i></a></p>
                </div>
              </div>
            @else
              <!-- Layout untuk card 2: Deskripsi di atas, gambar di bawah -->
              <div class="blog-entry d-flex align-self-stretch flex-column">
                <div class="text p-4 d-block">
                  <div class="meta mb-3">
                    <div><a href="#">{{ $news->published_at->format('d M Y') }}</a></div>
                    <div><a href="#">{{ $news->author->name ?? 'Admin' }}</a></div>
                    <div><a href="#" class="meta-chat"><span class="icon-eye"></span> {{ $news->views ?? 0 }}</a></div>
                  </div>
                  <h3 class="heading"><a href="{{ route('berita.detail', $news->slug) }}">{{ $news->title }}</a></h3>
                  <p>{{ Str::limit($news->excerpt, 100) }}</p>
                  <p><a href="{{ route('berita.detail', $news->slug) }}">Baca Selengkapnya <i class="ion-ios-arrow-forward"></i></a></p>
                </div>
                <a href="{{ route('berita.detail', $news->slug) }}" class="block-20" style="background-image: url('{{ $news->thumbnail_url }}'); height: 200px;">
                </a>
              </div>
            @endif
          </div>
          @endforeach
        </div>
      </div>
    </section>
@endsection