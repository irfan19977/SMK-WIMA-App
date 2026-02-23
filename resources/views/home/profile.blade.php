@extends('home.layouts.app')

@section('content')
      <!-- Start Section Banner Area -->
        <div class="section-banner bg-1">
            <div class="container">
                <div class="banner-spacing">
                    <div class="section-info">
                        <h2 data-aos="fade-up" data-aos-delay="100">{{ __('home.about_school_title') }}</h2>
                        <p data-aos="fade-up" data-aos-delay="200">{{ __('home.about_school_description') }}</p>
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
                                <i class='bx bxs-graduation'></i> <p>{{ __('home.about_school_title') }}</p>
                            </div>
                             <h2 class="title-anim">{{ __('home.creating_quality_graduates') }}</h2>
                            <p class="title-anim">{{ __('home.quality_commitment') }}</p>
                            <a class="default-btn" href="#">{{ __('home.visit_school') }}</a>
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
                                    <h4 class="title-anim">{{ __('home.quality_education') }}</h4>
                                    <p class="title-anim">{{ __('home.conducive_learning') }}</p>
                                    <div class="author-info">
                                        <span>{{ __('home.principal_title') }}</span>
                                        <h5>{{ __('home.principal_name_full') }}</h5>
                                        <p>{{ __('home.principal_title_full') }}</p>
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
                        <i class='bx bxs-graduation'></i> <p>{{ __('home.expertise_programs') }}</p>
                    </div>
                    <h2>{{ __('home.featured_expertise_programs') }}</h2>
                </div>
                <div class="row justify-content-center">
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="100">
                            <img src="{{ asset('frontend/assets/img/all-img/kimia.jpeg') }}" alt="icon">
                            <h4>{{ __('home.industrial_chemistry_engineering') }}</h4>
                            <p>{{ __('home.industrial_chemistry_description') }}</p>
                            <a href="#">{{ __('home.learn_more') }} <i class='bx bx-right-arrow-alt'></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="200">
                            <img src="{{ asset('frontend/assets/img/all-img/tkj.jpeg') }}" alt="icon">
                            <h4>{{ __('home.computer_network_engineering') }}</h4>
                            <p>{{ __('home.computer_network_description') }}</p>
                            <a href="#">{{ __('home.learn_more') }} <i class='bx bx-right-arrow-alt'></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="300">
                            <img src="{{ asset('frontend/assets/img/all-img/tsm.jpg') }}" alt="icon">
                            <h4>{{ __('home.motorcycle_engineering') }}</h4>
                            <p>{{ __('home.motorcycle_technic_description') }}</p>
                            <a href="#">{{ __('home.learn_more') }} <i class='bx bx-right-arrow-alt'></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-sm-6 col-md-6">
                        <div class="academics-item" data-aos="fade-up" data-aos-delay="400">
                            <img src="{{ asset('frontend/assets/img/all-img/tkr.jpg') }}" alt="icon">
                            <h4>{{ __('home.light_vehicle_engineering') }}</h4>
                            <p>{{ __('home.light_vehicle_description') }}</p>
                            <a href="#">{{ __('home.learn_more') }} <i class='bx bx-right-arrow-alt'></i></a>
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
                                <i class='bx bxs-graduation'></i> <p>{{ __('home.school_facilities') }}</p>
                            </div>
                            <h2>{{ __('home.facilities_infrastructure') }}</h2>

                            <p>{{ __('home.modern_facilities_description') }}</p>
                            <p>{{ __('home.facilities_list') }}</p>
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
                                <i class='bx bxs-graduation'></i> <p>{{ __('home.achievements') }}</p>
                            </div>
                            <h2>{{ __('home.excellence_graduation') }}</h2>

                            <p>{{ __('home.excellence_graduation_description') }}</p>
                            <p>{{ __('home.graduation_success') }}</p>
                        </div>
                    </div>
                </div>
                <div class="row g-0 align-items-center flex-column-reverse flex-lg-row">
                    <div class="col-lg-6">
                        <div class="content" data-aos="fade-up" data-aos-delay="100">
                            <div class="sub-title">
                                <i class='bx bxs-graduation'></i> <p>{{ __('home.values') }}</p>
                            </div>
                            <h2>{{ __('home.vision_mission_title') }}</h2>

                            <p><strong>Visi:</strong> {{ __('home.vision_statement') }}</p>
                            <p><strong>Misi:</strong> {{ __('home.mission_statement') }}</p>
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
                        <i class='bx bxs-graduation'></i> <p>{{ __('home.registration_information') }}</p>
                    </div>
                    <h2>{{ __('home.register_now_at_school') }}</h2>
                </div>

                <div class="subscribe-btn text-center" data-aos="fade-up" data-aos-delay="100">
                    <a class="default-btn" href="application-form.html">{{ __('home.register_now_button') }}</a>
                </div>
            </div>
        </div> 
        <!-- End Subscribe Area -->
@endsection