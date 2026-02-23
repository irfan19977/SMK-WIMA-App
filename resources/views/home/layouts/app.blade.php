<!doctype html>
<html lang="zxx">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Links of CSS files -->
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/aos.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/boxicons.min.css') }}">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/flaticon.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/magnific-popup.min.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/header.css') }}">
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/responsive.css') }}">

        <title>{{ __('index.smk_pgri_lawang') }} - {{ __('index.school_tagline') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('frontend/assets/img/logo/logo 1.png') }}">

        @stack('style')
    </head>
    <body>

        <!-- preloader -->
        <div class="preloader-container" id="preloader">
            <div class="preloader-dot"></div>
            <div class="preloader-dot"></div>
            <div class="preloader-dot"></div>
            <div class="preloader-dot"></div>
            <div class="preloader-dot"></div>
        </div>
        <!-- preloader -->

       <!-- Start Navbar Area Start -->
       <div class="navbar-area style-2" id="navbar">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand" href="{{ route('/') }}">
                    <img class="logo-light" src="{{ asset('frontend/assets/img/logo/logo 1.png') }}" alt="logo" style="max-height: 64px; width: auto;">
                    <img class="logo-dark" src="{{ asset('frontend/assets/img/logo/logo 1.png') }}" alt="logo" style="max-height: 64px; width: auto;">
                </a>
                <div class="other-option d-lg-none">
                    <div class="option-item">
                        <button type="button" class="search-btn" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop">
                            <i class='bx bx-search'></i>
                        </button>
                    </div>
                </div>
                <a class="navbar-toggler" data-bs-toggle="offcanvas" href="#navbarOffcanvas" role="button" aria-controls="navbarOffcanvas">
                    <i class='bx bx-menu'></i>
                </a>
                <div class="collapse navbar-collapse justify-content-between">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a href="{{ route('/') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">{{ __('index.home') }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('about') }}" class="nav-link {{ request()->is('about') ? 'active' : '' }}">{{ __('index.about') }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('pendaftaran.index') }}" class="nav-link {{ request()->is('pendaftaran') ? 'active' : '' }}">{{ __('index.registration') }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="javascript:void(0)" class="dropdown-toggle nav-link">
                                {{ __('index.majors') }}
                            </a>
                            <ul class="dropdown-menu">
                                <li class="nav-item"><a href="{{ route('kimia.index') }}" class="nav-link">{{ __('index.chemical_engineering') }}</a></li>
                                <li class="nav-item"><a href="{{ route('tkj.index') }}" class="nav-link">{{ __('index.computer_engineering') }}</a></li>
                                <li class="nav-item"><a href="{{ route('tbsm.index') }}" class="nav-link">{{ __('index.motorcycle_engineering') }}</a></li>
                                <li class="nav-item"><a href="{{ route('tkr.index') }}" class="nav-link">{{ __('index.light_vehicle_engineering') }}</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('berita.index') }}" class="nav-link">{{ __('index.news') }}</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link">
                                {{ __('index.login') }}
                            </a>
                        </li>
                    </ul>
                    <div class="others-option d-flex align-items-center">
                        <!-- Language Switcher -->
                        <div class="option-item">
                            <div class="nav-btn">
                                <div class="dropdown">
                                    <button class="nav-link dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bx bx-globe"></i>
                                        {{ strtoupper(app()->getLocale()) }}
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                                        <li>
                                            <a class="dropdown-item {{ app()->getLocale() == 'id' ? 'active' : '' }}" href="{{ route('language.switch', 'id') }}">
                                                🇮🇩 Indonesia
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route('language.switch', 'en') }}">
                                                🇬🇧 English
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="option-item">
                            <div class="nav-btn">
                                <a href="{{ route('contact.index') }}" class="default-btn">{{ __('index.contact_us') }}</a>
                            </div>
                        </div>
                        <div class="option-item">
                            <div class="nav-search">
                                <a href="#" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop" class="search-button"><i class='bx bx-search'></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
       </div>
       <!-- End Navbar Area Start -->

        <!-- Start Responsive Navbar Area -->
        <div class="responsive-navbar offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-1" id="navbarOffcanvas">
            <div class="offcanvas-header">
                <a href="{{ route('/') }}" class="logo d-inline-block">
                    <img class="logo-light" src="{{ asset('frontend/assets/img/logo/logo 1.png') }}" alt="logo" style="max-height: 48px; width: auto;">
                </a>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="accordion" id="navbarAccordion">
                    <div class="accordion-item">
                        <a href="{{ route('/') }}" class="accordion-link without-icon {{ Request::is('/') ? 'active' : '' }}">{{ __('index.home') }}</a>
                    </div>
                    <div class="accordion-item">
                        <a href="{{ route('about') }}" class="accordion-link without-icon {{ Request::is('about') ? 'active' : '' }}">{{ __('index.about') }}</a>
                    </div>
                    <div class="accordion-item">
                        <a href="{{ route('pendaftaran.index') }}" class="accordion-link without-icon {{ Request::is('pendaftaran*') ? 'active' : '' }}">{{ __('index.registration') }}</a>
                    </div>
                    <div class="accordion-item">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseJurusan" aria-expanded="false" aria-controls="collapseJurusan">
                            {{ __('index.majors') }}
                        </button>
                        <div id="collapseJurusan" class="accordion-collapse collapse" data-bs-parent="#navbarAccordion">
                            <div class="accordion-body">
                                <div class="accordion" id="navbarAccordionJurusan">
                                    <div class="accordion-item">
                                        <a href="javascript:void(0)" class="accordion-link {{ Request::is('jurusan/teknik-kimia-industri') ? 'active' : '' }}">{{ __('index.chemical_engineering') }}</a>
                                    </div>
                                    <div class="accordion-item">
                                        <a href="javascript:void(0)" class="accordion-link {{ Request::is('jurusan/teknik-komputer-dan-jaringan') ? 'active' : '' }}">{{ __('index.computer_engineering') }}</a>
                                    </div>
                                    <div class="accordion-item">
                                        <a href="javascript:void(0)" class="accordion-link {{ Request::is('jurusan/teknik-sepeda-motor') ? 'active' : '' }}">{{ __('index.motorcycle_engineering') }}</a>
                                    </div>
                                    <div class="accordion-item">
                                        <a href="javascript:void(0)" class="accordion-link {{ Request::is('jurusan/teknik-kendaraan-ringan') ? 'active' : '' }}">{{ __('index.light_vehicle_engineering') }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <a href="{{ route('berita.index') }}" class="accordion-link without-icon">{{ __('index.news') }}</a>
                    </div>
                    <div class="accordion-item">
                        <a href="{{ route('contact.index') }}" class="accordion-link without-icon">{{ __('index.contact') }}</a>
                    </div>
                    <div class="accordion-item">
                        <a href="{{ route('login') }}" class="accordion-link without-icon">{{ __('index.login') }}</a>
                    </div>
                </div>
                <div class="offcanvas-contact-info">
                    <h4>{{ __('index.contact_info') }}</h4>
                    <ul class="contact-info list-style">
                        <li>
                            <i class="bx bxs-envelope"></i>
                            <a href="mailto:info@smkpgrilawang.sch.id">info@smkpgrilawang.sch.id</a>
                        </li>
                        <li>
                            <i class="bx bxs-time"></i>
                            <p>{{ __('index.monday_friday_hours') }}</p>
                        </li>
                    </ul>
                    <ul class="social-profile list-style">
                        <li><a href="https://www.fb.com" target="_blank"><i class='bx bxl-facebook'></i></a></li>
                        <li><a href="https://www.instagram.com" target="_blank"><i class='bx bxl-instagram'></i></a></li>
                        <li><a href="https://www.linkedin.com" target="_blank"><i class='bx bxl-linkedin' ></i></a></li>
                    </ul>
                </div>
                <div class="offcanvas-other-options">
                    <div class="option-item">
                        <a href="{{ route('contact.index') }}" class="default-btn">{{ __('index.contact') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Responsive Navbar Area -->

        <!-- Start Clgun Searchbar Area -->
        <div class="clgun offcanvas offcanvas-start" data-bs-backdrop="static" tabindex="-1" id="staticBackdrop">
            <div class="offcanvas-header">
                <a href="index.html" class="logo">
                    <img src="{{ asset('frontend/assets/img/logo/logo 1.png') }}" alt="image" style="max-height: 48px; width: auto;">
                </a>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="search-box">
                    <div class="searchwrapper"> 
                        <div class="searchbox"> 
                            <div class="row align-items-center"> 
                                <div class="col-md-9"><input type="text" class="form-control" placeholder="{{ __('index.find_your_course_here') }}"></div> 
                                <div class="col-lg-3"> 
                                    <a class="btn" href="#">{{ __('index.search') }}</a> 
                                </div> 
                            </div> 
                        </div>
                    </div>
                </div>

                <div class="offcanvas-contact-info">
                    <h4>{{ __('index.contact_info') }}</h4>
                    <ul class="contact-info list-style">
                        <li>
                            <i class="bx bxs-time"></i>
                            <p>Senin - Jumat: 07:00 - 16:00</p>
                        </li>
                        <li><i class="bx bxs-phone-call"></i> {{ __('index.general_information') }} - <a href="tel:+62341891234">(0341) 891-234</a></li>
                        <li>
                            <i class="bx bxs-envelope"></i>
                            <a href="mailto:info@smkpgrilawang.sch.id">info@smkpgrilawang.sch.id</a>
                        </li>
                        <li>
                            <i class="bx bxs-map"></i>
                            <p>{{ __('index.address') }}: Jl. Soekarno Hatta No. 123, Lawang, Malang, Jawa Timur 65215</p>
                        </li>
                    </ul>
                    <ul class="social-profile list-style">
                        <li><a href="https://www.fb.com" target="_blank"><i class='bx bxl-facebook'></i></a></li>
                        <li><a href="https://www.instagram.com" target="_blank"><i class='bx bxl-instagram'></i></a></li>
                        <li><a href="https://www.twitter.com" target="_blank"><i class='bx bxl-twitter'></i></a></li>
                        <li><a href="https://www.dribbble.com" target="_blank"><i class='bx bxl-dribbble'></i></a></li>
                        <li><a href="https://www.linkedin.com" target="_blank"><i class='bx bxl-linkedin' ></i></a></li>
                    </ul>
                </div>

            </div>
        </div>
        <!-- End Clgun Searchbar Area -->

        @yield('content')

        <!-- Start Footer Area -->
        <div class="footer-area">
            <div class="footer-widget-info ptb-100">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3 col-sm-6 col-md-6">
                            <div class="footer-widget">
                                <h4>{{ __('index.information') }}</h4>
                                <ul>
                                    <li><a href="{{ route('pendaftaran.index') }}"><i class='bx bx-chevron-right'></i> {{ __('index.registration') }}</a></li>
                                    <li><a href="{{ route('pendaftaran.index') }}"><i class='bx bx-chevron-right'></i> {{ __('index.education_fee') }}</a></li>
                                    <li><a href="{{ route('pendaftaran.index') }}"><i class='bx bx-chevron-right'></i> {{ __('index.scholarship') }}</a></li>
                                    <li><a href="{{ route('contact.index') }}"><i class='bx bx-chevron-right'></i> {{ __('index.contact') }}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-md-6">
                            <div class="footer-widget">
                                <h4>{{ __('index.majors') }}</h4>
                                <ul>
                                    <li><a href="{{ route('kimia.index') }}"><i class='bx bx-chevron-right'></i> {{ __('index.chemical_engineering') }}</a></li>
                                    <li><a href="{{ route('tkj.index') }}"><i class='bx bx-chevron-right'></i> {{ __('index.computer_engineering') }}</a></li>
                                    <li><a href="{{ route('tbsm.index') }}"><i class='bx bx-chevron-right'></i> {{ __('index.motorcycle_engineering') }}</a></li>
                                    <li><a href="{{ route('tkr.index') }}"><i class='bx bx-chevron-right'></i> {{ __('index.light_vehicle_engineering') }}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-md-6">
                            <div class="footer-widget">
                                <h4>{{ __('index.academic') }}</h4>
                                <ul>
                                    <li><a href="#"><i class='bx bx-chevron-right'></i> {{ __('index.e_learning') }}</a></li>
                                    <li><a href="#"><i class='bx bx-chevron-right'></i> {{ __('index.curriculum') }}</a></li>
                                    <li><a href="#"><i class='bx bx-chevron-right'></i> {{ __('index.academic_calendar') }}</a></li>
                                    <li><a href="#"><i class='bx bx-chevron-right'></i> {{ __('index.student_activities') }}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-md-6">
                            <div class="footer-widget">
                                <h4>{{ __('index.services') }}</h4>
                                <ul>
                                    <li><a href="#"><i class='bx bx-chevron-right'></i> {{ __('index.counseling') }}</a></li>
                                    <li><a href="#"><i class='bx bx-chevron-right'></i> {{ __('index.library') }}</a></li>
                                    <li><a href="#"><i class='bx bx-chevron-right'></i> {{ __('index.facilities') }}</a></li>
                                    <li><a href="#"><i class='bx bx-chevron-right'></i> {{ __('index.sports') }}</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="copy-right-area style-2">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-4">
                            <div class="cpr-left">
                                <p>{{ __('index.copyright') }}© <a href="#">{{ __('index.smk_pgri_lawang') }}</a>, {{ __('index.all_rights_reserved') }}.</p>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="cpr-right">
                                <ul>
                                    <li><a href="#">{{ __('index.privacy_policy') }}</a></li>
                                    <li><a href="#">{{ __('index.cookie_policy') }}</a></li>
                                </ul>
                                <ul class="social-list">
                                    <li><a href="#"><i class='bx bxl-facebook'></i></a></li>
                                    <li><a href="#"><i class='bx bxl-instagram-alt'></i></a></li>
                                    <li><a href="#"><i class='bx bxl-twitter'></i></a></li>
                                    <li><a href="#"><i class='bx bxl-linkedin-square'></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="copy-logo">
                        <img src="assets/img/logo/footer-Logo.png" alt="image">
                    </div>
                </div>
            </div>
        </div>
        <!-- End Footer Area -->

        <div class="go-top active">
            <i class="bx bx-up-arrow-alt"></i>
        </div>

        <!-- Links of JS files -->
        <script src="{{ asset('frontend/assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/aos.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/magnific-popup.min.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('frontend/assets/js/main.js') }}"></script>

        @stack('scripts')
    </body>
</html>