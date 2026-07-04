@extends('layouts.master-without-nav')
@section('title')
    Lupa Password - SMK PGRI LAWANG
@endsection
@section('content')
    <!-- Language Switcher Top Right -->
    <div style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
        <div class="dropdown">
            <button class="btn btn-sm btn-success dropdown-toggle" type="button" id="forgotLanguageDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 8px 15px; border-radius: 6px; font-weight: 500; box-shadow: 0 2px 8px rgba(0,0,0,0.2); font-size: 15px;">
                <i class="mdi mdi-earth me-1"></i>
                {{ strtoupper(app()->getLocale()) }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="forgotLanguageDropdown" style="background: rgba(255,255,255,0.98); border: 1px solid #dee2e6; min-width: 160px; box-shadow: 0 6px 20px rgba(0,0,0,0.2); border-radius: 8px; margin-top: 8px;">
                <li>
                    <a class="dropdown-item d-flex align-items-center {{ app()->getLocale() == 'id' ? 'active' : '' }}" href="{{ route('language.switch', 'id') }}" style="color: #333; padding: 10px 15px; transition: all 0.2s;">
                        <span style="font-size: 18px; margin-right: 10px;">🇮🇩</span>
                        <span style="flex: 1;">Indonesia</span>
                        @if(app()->getLocale() == 'id')
                            <i class="mdi mdi-check-circle" style="color: #28a745; font-size: 16px;"></i>
                        @endif
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex align-items-center {{ app()->getLocale() == 'en' ? 'active' : '' }}" href="{{ route('language.switch', 'en') }}" style="color: #333; padding: 10px 15px; transition: all 0.2s;">
                        <span style="font-size: 18px; margin-right: 10px;">🇬🇧</span>
                        <span style="flex: 1;">English</span>
                        @if(app()->getLocale() == 'en')
                            <i class="mdi mdi-check-circle" style="color: #28a745; font-size: 16px;"></i>
                        @endif
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="auth-maintenance d-flex align-items-center min-vh-100">
        <div class="bg-overlay bg-light"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="auth-full-page-content d-flex min-vh-100 py-sm-5 py-4">
                        <div class="w-100">
                            <div class="d-flex flex-column h-100 py-0 py-xl-3">
                                <div class="text-center mb-4">
                                    <a href="{{ route('/') }}" class="">
                                        <img src="{{ asset('backend/assets/img/logo 1.png') }}" alt=""
                                            height="40" class="auth-logo mx-auto">
                                    </a>
                                    <p class="text-muted mt-2">{{ __('index.school_management_system') }}</p>
                                </div>

                                <div class="card my-auto overflow-hidden">
                                    <div class="row g-0">
                                        <div class="col-lg-6">
                                            <div class="bg-overlay bg-success"></div>
                                            <div class="h-100 bg-auth align-items-end">
                                                <div class="p-4 text-white text-center">
                                                    <h4 class="mb-3">{{ __('index.forgot_password_title') }}</h4>
                                                    <p class="mb-4">{{ __('index.forgot_password_description') }}</p>
                                                    <div class="mt-4">
                                                        <i class="mdi mdi-lock-reset-outline display-4"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="p-lg-5 p-4">
                                                <div>
                                                    <div class="text-center mt-1">
                                                        <h4 class="font-size-18">{{ __('index.forgot_password') }}</h4>
                                                        <p class="text-muted">{{ __('index.forgot_password_instruction') }}</p>
                                                    </div>

                                                    <!-- Session Status -->
                                                    @if (session('status'))
                                                        <div class="alert alert-success" role="alert">
                                                            {{ session('status') }}
                                                        </div>
                                                    @endif

                                                    <form method="POST" action="{{ route('password.email') }}" class="auth-input">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="email" class="form-label">{{ __('index.email') }}</label>
                                                            <input id="email" type="email"
                                                                class="form-control @error('email') is-invalid @enderror"
                                                                placeholder="{{ __('index.enter_email') }}" value="{{ old('email') }}" required
                                                                autocomplete="email" autofocus>
                                                            @error('email')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>

                                                        <div class="mt-4">
                                                            <button class="btn btn-success w-100" type="submit">
                                                                <i class="mdi mdi-email-send me-2"></i> {{ __('index.send_reset_link') }}
                                                            </button>
                                                        </div>

                                                        <div class="mt-4 pt-2 text-center">
                                                            <div class="signin-other-title">
                                                                <h5 class="font-size-14 mb-3 title">{{ __('index.contact_administrator') }}</h5>
                                                            </div>
                                                            <div class="pt-2 hstack gap-2 justify-content-center">
                                                                <a href="https://wa.me/6282233088346" class="btn btn-success btn-sm">
                                                                    <i class="mdi mdi-whatsapp font-size-16"></i>
                                                                </a>
                                                                <a href="https://t.me/Hekel256" class="btn btn-info btn-sm">
                                                                    <i class="mdi mdi-telegram font-size-16"></i>
                                                                </a>
                                                            </div>
                                                            <small class="text-muted d-block mt-2">{{ __('index.contact_note') }}</small>
                                                        </div>
                                                    </form>
                                                </div>

                                                <div class="mt-4 text-center">
                                                    <p class="mb-0">{{ __('index.remember_password') }} <a href="{{ route('login') }}" class="fw-medium text-success">{{ __('index.login') }}</a></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end card -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
    </div>
@endsection
@section('scripts')
    <!-- App js -->
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
