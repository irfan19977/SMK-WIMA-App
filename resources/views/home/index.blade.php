@extends('home.layouts.app')

@section('content')
    <!-- Start Clgun Banner 2 Area -->
        <div class="banner-area-2 big-bg-2">
            <div class="container">
                <div class="banner-content-2">
                    <div class="content">
                        <span data-aos="fade-zoom-in" data-aos-delay="300">{{ __('home.banner_subtitle') }}</span>
                        <h1 data-aos="fade-up" data-aos-delay="200">{{ __('home.banner_title') }}</h1>
                        <p data-aos="fade-up" data-aos-delay="300">{{ __('home.banner_description') }}</p>
                        <div class="buttons-action" data-aos="fade-up" data-aos-delay="100">
                            <a class="default-btn" href="{{ route('pendaftaran.index') }}">{{ __('home.register_now') }}</a>
                            <a class="default-btn btn-style-2" href="{{ route('contact.index') }}">{{ __('home.contact_us') }}</a>
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
                    <h2>{{ __('home.about_title') }}</h2>
                    <p>{{ __('home.about_description') }}</p>
                </div>
                <div class="about-content-courser owl-carousel owl-theme">
                    <div class="content-items" data-dot="<button>01</button>">
                        <div class="image ct-bg-1" data-aos="fade-zoom-in" data-aos-delay="100">
                        </div>
                        <div class="content" data-aos="fade-up" data-aos-delay="200">
                            <span>{{ __('home.vision_mission') }}</span>
                            <h2>{{ __('home.vision_mission_title') }}</h2>
                            <p>{{ __('home.vision_mission_description') }}</p>
                            <a class="default-btn" href="{{ route('contact.index') }}">{{ __('home.schedule_visit') }}</a>
                        </div>
                    </div>
                    <div class="content-items" data-dot="<button>02</button>">
                        <div class="image ct-bg-2" data-aos="fade-zoom-in" data-aos-delay="100">
                        </div>
                        <div class="content" data-aos="fade-up" data-aos-delay="200">
                            <span>{{ __('home.featured_programs') }}</span>
                            <h2>{{ __('home.featured_programs_title') }}</h2>
                            <p>{{ __('home.featured_programs_description') }}</p>
                            <a class="default-btn" href="{{ route('contact.index') }}">{{ __('home.schedule_visit') }}</a>
                        </div>
                    </div>
                    <div class="content-items" data-dot="<button>03</button>">
                        <div class="image ct-bg-3" data-aos="fade-zoom-in" data-aos-delay="100">
                        </div>
                        <div class="content" data-aos="fade-up" data-aos-delay="200">
                            <span>{{ __('home.modern_facilities') }}</span>
                            <h2>{{ __('home.modern_facilities_title') }}</h2>
                            <p>{{ __('home.modern_facilities_description') }}</p>
                            <a class="default-btn" href="{{ route('contact.index') }}">{{ __('home.schedule_visit') }}</a>
                        </div>
                    </div>
                    <div class="content-items" data-dot="<button>04</button>">
                        <div class="image ct-bg-2" data-aos="fade-zoom-in" data-aos-delay="100">
                        </div>
                        <div class="content" data-aos="fade-up" data-aos-delay="200">
                            <span>{{ __('home.achievements_alumni') }}</span>
                            <h2>{{ __('home.achievements_alumni_title') }}</h2>
                            <p>{{ __('home.achievements_alumni_description') }}</p>
                            <a class="default-btn" href="{{ route('contact.index') }}">{{ __('home.schedule_visit') }}</a>
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
                                <p>{{ __('home.our_featured_programs') }}</p>
                            </div>
                            <div class="content">
                                <h2>{{ __('home.students_create_inclusive_environment') }}</h2>
                                <div class="item">
                                    <div class="item-content">
                                        <div class="icon">
                                            <img src="{{ asset('frontend/assets/img/icon/features-icon-2.png') }}" alt="image">
                                        </div>
                                        <h3>{{ __('home.vocational_education') }}</h3>
                                        <p>{{ __('home.vocational_education_description') }}</p>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="item-content">
                                        <div class="icon">
                                            <img src="{{ asset('frontend/assets/img/icon/features-icon-1.png') }}" alt="image">
                                        </div>
                                        <h3>{{ __('home.featured_majors') }}</h3>
                                        <p>{{ __('home.featured_majors_description') }}</p>
                                    </div>
                                </div>
                                <a class="default-btn" href="{{ route('pendaftaran.index') }}">{{ __('home.registration_info') }}</a>

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
                                                <h4>{{ __('home.industrial_chemistry_engineering') }}</h4>
                                                <a class="btn" href="{{ route('kimia.index') }}">{{ __('home.learn_more') }} <i class='bx bx-right-arrow-alt'></i></a>
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
                                                <h4>{{ __('home.computer_network_engineering') }}</h4>
                                                <a class="btn" href="{{ route('tkj.index') }}">{{ __('home.learn_more') }} <i class='bx bx-right-arrow-alt'></i></a>
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
                                                <h4>{{ __('home.motorcycle_engineering') }}</h4>
                                                <a class="btn" href="{{ route('tbsm.index') }}">{{ __('home.learn_more') }} <i class='bx bx-right-arrow-alt'></i></a>
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
                                                <h4>{{ __('home.light_vehicle_engineering') }}</h4>
                                                <a class="btn" href="{{ route('tkr.index') }}">{{ __('home.learn_more') }} <i class='bx bx-right-arrow-alt'></i></a>
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
                        <p>{{ __('home.school_news') }}</p>
                    </div>
                    <h2>{{ __('home.news_title') }}</h2>
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
                                            <i class='bx bxs-graduation'></i> <p>{{ $news->category ?? __('home.technology_innovation') }}</p>
                                        </div>
                                        <h2><a href="{{ route('berita.detail', $news->slug) }}">{{ $news->title }}</a></h2>
                                        <p>{{ Str::limit(strip_tags($news->content), 100) }}</p>
                                        <a class="btn" href="{{ route('berita.detail', $news->slug) }}">{{ __('home.read_more') }}</a>
                                    </div>
                                </li>
                                @empty
                                <li class="news-item" data-aos="fade-up" data-aos-delay="100">
                                    <div class="image">
                                        <img src="{{ asset('frontend/assets/img/all-img/news-image-1.png') }}" alt="image">
                                    </div>
                                    <div class="content">
                                        <div class="sub-title">
                                            <i class='bx bxs-graduation'></i> <p>{{ __('home.technology_innovation') }}</p>
                                        </div>
                                        <h2><a href="blog-details.html">{{ __('home.student_robot_news_title') }}</a></h2>
                                        <p>{{ __('home.student_robot_news_description') }}</p>
                                        <a class="btn" href="blog-details.html">{{ __('home.read_more') }}</a>
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
                                    <a class="btn" href="{{ route('berita.detail', $homepageNews[2]->slug) }}">{{ __('home.read_more') }}</a>
                                </div>
                            </div>
                            @else
                            <div class="content-box">
                                <img src="{{ asset('frontend/assets/img/all-img/news-image-3.png') }}" alt="iamge">
                                <div class="content">
                                    <h3><a href="blog-details.html">{{ __('home.gender_inequality_title') }}</a></h3>
                                    <p>{{ __('home.gender_inequality_description') }}</p>
                                    <a class="btn" href="blog-details.html">{{ __('home.continue_reading') }}</a>
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
                                        <i class='bx bxs-graduation'></i> <p>{{ $news->category ?? __('home.medicine') }}</p>
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
                                    <h3><a href="blog-details.html">{{ __('home.empowering_health') }}</a></h3>
                                </div>
                            </div>
                            <div class="content-box">
                                <div class="image">
                                    <img src="{{ asset('frontend/assets/img/all-img/news-image-5.png') }}" alt="image">
                                </div>
                                <div class="content">
                                    <div class="sub-title">
                                        <i class='bx bxs-graduation'></i> <p>{{ __('home.student_life') }}</p>
                                    </div>
                                    <h3><a href="blog-details.html">{{ __('home.every_student_dream_success') }}</a></h3>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="section-btn text-center" data-aos="fade-zoom-in" data-aos-delay="100">
                    <p>{{ __('home.where_dreams_take_flight') }}. <a href="news-and-blog.html">{{ __('home.more_campus_news') }} <i class='bx bx-right-arrow-alt'></i></a></p>
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
                            <h2>{{ __('home.scholarship_programs') }}</h2>
                        </div>
                    </div>
                    <div class="col-lg-7" data-aos="fade-up" data-aos-delay="200">
                        <div class="content">
                            <p>{{ __('home.scholarship_description') }}</p>
                        </div>
                    </div>
                    <div class="col-lg-2" data-aos="fade-up" data-aos-delay="300">
                        <div class="button">
                            <a class="default-btn" href="{{ route('pendaftaran.index') }}">{{ __('home.financial_aid') }}</a>
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
                            <h4>{{ __('home.search_vocational_program') }}</h4>
                            <div class="search-key">
                                <input class="form-control" placeholder="{{ __('home.keyword') }}" type="search" value="" id="searchKey1">
                                <input class="form-control" placeholder="{{ __('home.program_code') }}" type="search" value="" id="searchKey">
                                
                                <select class="form-select" aria-label="Default select example" id="searchKey2">
                                    <option selected>{{ __('home.major') }}</option>
                                    <option value="1">{{ __('home.industrial_chemistry_engineering') }}</option>
                                    <option value="2">{{ __('home.computer_network_engineering') }}</option>
                                    <option value="3">{{ __('home.motorcycle_engineering') }}</option>
                                    <option value="4">{{ __('home.light_vehicle_engineering') }}</option>
                                </select>

                                <select class="form-select" aria-label="Default select example" id="searchKey3">
                                    <option selected>{{ __('home.location') }}</option>
                                    <option value="1">{{ __('home.main_campus') }}</option>
                                    <option value="2">{{ __('home.campus_2') }}</option>
                                    <option value="3">{{ __('home.workshop') }}</option>
                                </select>

                                <select class="form-select" aria-label="Default select example" id="searchKey4">
                                    <option selected>{{ __('home.level') }}</option>
                                    <option value="1">X</option>
                                    <option value="2">XI</option>
                                    <option value="3">XII</option>
                                </select>

                                <select class="form-select" aria-label="Default select example" id="searchKey5">
                                    <option selected>{{ __('home.teacher') }}</option>
                                    <option value="1">{{ __('home.mr_budi') }}</option>
                                    <option value="2">{{ __('home.mrs_siti') }}</option>
                                    <option value="3">{{ __('home.mr_ahmad') }}</option>
                                </select>

                                <select class="form-select" aria-label="Default select example" id="searchKey6">
                                    <option selected>{{ __('home.semester') }}</option>
                                    <option value="1">{{ __('home.odd_semester') }}</option>
                                    <option value="2">{{ __('home.even_semester') }}</option>
                                    <option value="3">{{ __('home.short_semester') }}</option>
                                </select>

                                <select class="form-select" aria-label="Default select example" id="searchKey7">
                                    <option selected>{{ __('home.credits') }}</option>
                                    <option value="1">2</option>
                                    <option value="3">4</option>
                                    <option value="6">6</option>
                                </select>

                                <a class="default-btn" href="#">{{ __('home.search_program') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="quick-content" data-aos="fade-up" data-aos-delay="200">
                            <div class="sub-title">
                                <p>{{ __('home.school_news') }}</p>
                            </div>
                            <h2>{{ __('home.start_your_career') }}</h2>
                            <p>{{ __('home.career_description') }}</p>

                            <div class="list">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-6 col-md-6">
                                        <div class="list-items">
                                            <ul>
                                                <li><i class='bx bx-right-arrow-circle'></i> {{ __('home.alumni_donors') }}</li>
                                                <li><i class='bx bx-right-arrow-circle'></i> {{ __('home.academic_calendar') }}</li>
                                                <li><i class='bx bx-right-arrow-circle'></i> {{ __('home.all_school_events') }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-6 col-md-6">
                                        <div class="list-items">
                                            <ul>
                                                <li><i class='bx bx-right-arrow-circle'></i> {{ __('home.partnerships_collaboration') }}</li>
                                                <li><i class='bx bx-right-arrow-circle'></i> {{ __('home.academic_programs') }}</li>
                                                <li><i class='bx bx-right-arrow-circle'></i> {{ __('home.education_costs') }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            
                            </div>

                            <div class="apply-banner">
                                <div class="row align-items-center">
                                    <div class="col-lg-6 col-sm-6 col-md-6">
                                        <p>{{ __('home.register_now_short') }}</p>
                                    </div>
                                    <div class="col-lg-6 col-sm-6 col-md-6 text-end">
                                        <a class="default-btn" href="{{ route('pendaftaran.index') }}">{{ __('home.register_now_short') }}</a>
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
                                                <h4>{{ __('home.principal') }}</h4>
                                                <p>{{ __('home.principal_name') }}</p>
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
                                                <p>{{ __('home.years') }} <br> {{ __('home.experience') }}</p>
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
                        <p>{{ __('home.student_teacher_alumni_achievements') }}</p>
                    </div>
                    <h2>{{ __('home.celebrating_legacy_embracing_future') }}</h2>
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
                                    <li><a href="university-life.html"><h3>{{ __('home.amelia_sari') }}</h3></a></li>
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
                                    <li><a href="university-life.html"><h3>{{ __('home.oliver_pratama') }}</h3></a></li>
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
                                    <li><a href="university-life.html"><h3>{{ __('home.sofia_putri') }}</h3></a></li>
                                    <li class="link"><a href="university-life.html"><i class='bx bx-link-external'></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-btn text-center" data-aos="fade-zoom-in" data-aos-delay="100">
                    <p>{{ __('home.inspiration_for_future') }}. <a href="undergraduate.html">{{ __('home.learn_about_featured_programs') }} <i class='bx bx-right-arrow-alt'></i></a></p>
                </div>
            </div>
        </div>
        <!-- End Success Area 2 -->

        <!-- Start Program Info Area 2 -->
        <div class="subscribe-area subscribe-area-2" style="background-image: url('{{ asset('frontend/assets/img/all-img/luar.jpeg') }}'); background-size: cover; background-repeat: no-repeat; background-position: center; position: relative;">
            <div class="container">
                <div class="section-title section-title-2" data-aos="fade-up" data-aos-delay="100">
                    <div class="sub-title">
                        <p>{{ __('home.registration_information') }}</p>
                    </div>
                    <h2>{{ __('home.join_bright_future') }}</h2>
                </div>

                <div class="subscribe-btn text-center" data-aos="fade-up" data-aos-delay="200">
                    <a class="default-btn" href="application-form.html">{{ __('home.register_now_button') }}</a>
                </div>
            </div>
        </div> 
        <!-- End Program Info Area 2 -->

@endsection