@extends('home.layouts.app')

@section('content')
 <!-- Start Section Banner Area -->
        <div class="section-banner bg-1">
            <div class="container">
                <div class="banner-spacing">
                    <div class="section-info">
                        <h2 data-aos="fade-up" data-aos-delay="100">{{ __('home.tkj_banner_title') }}</h2>
                        <p data-aos="fade-up" data-aos-delay="200">{{ __('home.tkj_banner_description') }}</p>
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
                                <i class='bx bxs-graduation'></i> <p>{{ __('home.tkj_about_title') }}</p>
                            </div>
                             <h2 class="title-anim">{{ __('home.tkj_about_subtitle') }}</h2>
                            <p class="title-anim">{{ __('home.tkj_about_description') }}</p>
                            <a class="default-btn" href="{{ route('pendaftaran.index') }}">{{ __('home.tkj_register_now') }}</a>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="row justify-content-end">
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <div class="content" data-aos="fade-right" data-aos-delay="100">
                                <img src="{{ asset('frontend/assets/img/all-img/tkj.jpeg') }}" alt="TKJ Lab">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-md-6">
                                <div class="notice-content" data-aos="fade-right" data-aos-delay="200">
                                    <i class='bx bxs-quote-left'></i>
                                    <h4 class="title-anim">{{ __('home.tkj_lab_quote') }}</h4>
                                    <p class="title-anim">{{ __('home.tkj_lab_description') }}</p>
                                    <div class="author-info">
                                        <span>{{ __('home.tkj_lab_coordinator') }}</span>
                                        <h5>{{ __('home.tkj_lab_name') }}</h5>
                                        <p>{{ __('home.tkj_lab_position') }}</p>
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
                        <i class='bx bxs-graduation'></i> <p>{{ __('home.tkj_competencies_title') }}</p>
                    </div>
                    <h2>{{ __('home.tkj_competencies_subtitle') }}</h2>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="100">
                            <img src="{{ asset('frontend/assets/img/all-img/networking.jpg') }}" alt="icon">
                            <h4>{{ __('home.tkj_networking') }}</h4>
                            <p>{{ __('home.tkj_networking_description') }}</p>
                            <a href="#">{{ __('home.tkj_learn_more') }} <i class='bx bx-right-arrow-alt'></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="200">
                            <img src="{{ asset('frontend/assets/img/all-img/server.jpg') }}" alt="icon">
                            <h4>{{ __('home.tkj_server_admin') }}</h4>
                            <p>{{ __('home.tkj_server_admin_description') }}</p>
                            <a href="#">{{ __('home.tkj_learn_more') }} <i class='bx bx-right-arrow-alt'></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="300">
                            <img src="{{ asset('frontend/assets/img/all-img/security.jpg') }}" alt="icon">
                            <h4>{{ __('home.tkj_security') }}</h4>
                            <p>{{ __('home.tkj_security_description') }}</p>
                            <a href="#">{{ __('home.tkj_learn_more') }} <i class='bx bx-right-arrow-alt'></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="400">
                            <img src="{{ asset('frontend/assets/img/all-img/troubleshooting.jpg') }}" alt="icon">
                            <h4>{{ __('home.tkj_troubleshooting') }}</h4>
                            <p>{{ __('home.tkj_troubleshooting_description') }}</p>
                            <a href="#">{{ __('home.tkj_learn_more') }} <i class='bx bx-right-arrow-alt'></i></a>
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
                                <i class='bx bxs-graduation'></i> <p>{{ __('home.tkj_facilities_title') }}</p>
                            </div>
                            <h2>{{ __('home.tkj_facilities_subtitle') }}</h2>

                            <p>{{ __('home.tkj_facilities_description') }}</p>
                            <p>{{ __('home.tkj_facilities_experience') }}</p>
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
                                <i class='bx bxs-graduation'></i> <p>{{ __('home.tkj_career_title') }}</p>
                            </div>
                            <h2>{{ __('home.tkj_career_subtitle') }}</h2>

                            <p>{{ __('home.tkj_career_description1') }}</p>
                            <p>{{ __('home.tkj_career_description2') }}</p>
                        </div>
                    </div>
                </div>
                <div class="row g-0 align-items-center flex-column-reverse flex-lg-row">
                    <div class="col-lg-6">
                        <div class="content" data-aos="fade-up" data-aos-delay="100">
                            <div class="sub-title">
                                <i class='bx bxs-graduation'></i> <p>{{ __('home.tkj_certification_title') }}</p>
                            </div>
                            <h2>{{ __('home.tkj_certification_subtitle') }}</h2>

                            <p>{!! __('home.tkj_certification_national') !!}</p>
                            <p>{!! __('home.tkj_certification_international') !!}</p>
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
                        <i class='bx bxs-graduation'></i> <p>{{ __('home.tkj_registration_title') }}</p>
                    </div>
                    <h2>{{ __('home.tkj_registration_subtitle') }}</h2>
                </div>

                <div class="subscribe-btn text-center" data-aos="fade-up" data-aos-delay="100">
                    <a class="default-btn" href="{{ route('pendaftaran.index') }}">{{ __('home.tkj_register_now') }}</a>
                </div>
            </div>
        </div> 
        <!-- End Subscribe Area -->
@endsection