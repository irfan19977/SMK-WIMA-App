<!DOCTYPE html>
<html lang="en">

<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Otika - Admin Dashboard Template</title>
    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/bundles/bootstrap-social/bootstrap-social.css') }}">
    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/components.css') }}">
    <!-- Custom style CSS -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/custom.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css">
    <link rel='shortcut icon' type='image/x-icon' href='{{ asset('logo.png') }}' />
    <style>
        .custom-whatsapp:hover {
            background-color: #28a745 !important;
            filter: brightness(95%) !important;
            border-color: #28a745 !important;
        }
        
        .custom-telegram:hover {
            background-color: #17a2b8 !important;
            filter: brightness(95%) !important;
            border-color: #17a2b8 !important;
        }
    </style>
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row">
                    <div
                        class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4>Login</h4>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('login') }}" class="needs-validation"
                                    novalidate="">
                                    @csrf

                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror " name="email"
                                            tabindex="1" placeholder="Masukkan Email" required autofocus>
                                        @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror

                                    </div>
                                    <div class="form-group">
                                        <div class="d-block">
                                            <label for="password" class="control-label">Password</label>
                                            <div class="float-right">
                                                <a href="{{ route('password.request') }}" class="text-small">
                                                    Forgot Password?
                                                </a>
                                            </div>
                                        </div>
                                        <div class="position-relative">
                                            <input id="password-input" type="password" 
                                                class="form-control @error('password') is-invalid @enderror" 
                                                name="password"
                                                tabindex="2" 
                                                placeholder="Masukkan Password" 
                                                required
                                                style="padding-right: 40px;">
                                            <span class="position-absolute" 
                                                id="password-addon" 
                                                style="right: 10px; top: 55%; transform: translateY(-50%); cursor: pointer;">
                                                <i class="ri-eye-fill" style="color: #666;"></i>
                                            </span>
                                            @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="remember" class="custom-control-input"
                                                tabindex="3" id="remember-me">
                                            <label class="custom-control-label" for="remember-me">Remember Me</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-lg btn-block" tabindex="4">
                                            Login
                                        </button>
                                    </div>
                                </form>
                                <div class="text-center mt-4 mb-3">
                                    <div class="text-job text-muted">Hubungi Administrator</div>
                                </div>
                                <div class="row sm-gutters">
                                    <!-- WhatsApp Button -->
                                    <div class="col-6">
                                        <a href="https://wa.me/6282233088346" class="btn btn-block btn-social btn-success custom-whatsapp" tabindex="4">
                                            <span class="fab fa-whatsapp"></span> Whatsapp
                                        </a>
                                    </div>
                                    
                                    <!-- Telegram Button -->
                                    <div class="col-6">
                                        <a href="https://t.me/Hekel256" class="btn btn-block btn-social btn-info custom-telegram" tabindex="5">
                                            <span class="fab fa-telegram"></span> Telegram
                                        </a>
                                    </div>
                                    <small class="text-muted" style="margin-top: 10px">*Hubungi hanya jika ada permasalahan saja</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- General JS Scripts -->
    <script src="{{ asset('backend/assets/js/app.min.js') }}"></script>
    <!-- JS Libraies -->
    <!-- Page Specific JS File -->
    <!-- Template JS File -->
    <script src="{{ asset('backend/assets/js/scripts.js') }}"></script>
    <!-- Custom JS File -->
    <script src="{{ asset('backend/assets/js/custom.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordToggle = document.getElementById('password-addon');
            const passwordField = document.getElementById('password-input');
            
            passwordToggle.addEventListener('click', function(e) {
                console.log("Toggle password button clicked!");
                e.preventDefault();
                e.stopPropagation();
                
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                
                const iconElement = this.querySelector('i');
                if (type === 'text') {
                    iconElement.classList.remove('ri-eye-fill');
                    iconElement.classList.add('ri-eye-off-fill');
                } else {
                    iconElement.classList.remove('ri-eye-off-fill');
                    iconElement.classList.add('ri-eye-fill');
                }
                
                // Hilangkan fokus/kursor dari field password
                passwordField.blur();
            });
            
            passwordToggle.addEventListener('mousedown', function(e) {
                e.preventDefault();
            });
        });
    </script>

</body>


<!-- auth-login.html  21 Nov 2019 03:49:32 GMT -->

</html>
