<!DOCTYPE html>
<html lang="en">


<!-- index.html  21 Nov 2019 03:44:50 GMT -->
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>SMK - Dashboard</title>
  <!-- General CSS Files -->
  <link rel="stylesheet" href="{{ asset('backend/assets/css/app.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/bundles/bootstrap-daterangepicker/daterangepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/bundles/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/bundles/select2/dist/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/bundles/jquery-selectric/selectric.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/bundles/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('backend/assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('backend/assets/css/components.css') }}">
  <!-- Custom style CSS -->
  <link rel="stylesheet" href="{{ asset('backend/assets/css/custom.css') }}">
  <link rel='shortcut icon' type='image/x-icon' href='{{ asset('logo.png') }}' />

  <!-- Template CSS -->
  <link rel="stylesheet" href="{{ asset('backend/assets/bundles/pretty-checkbox/pretty-checkbox.min.css') }}">
  <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('backend/assets/bundles/summernote/summernote-bs4.css') }}">

  @stack('styles')
</head>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>

      @include('layouts.navbar')
      
      <div class="main-sidebar sidebar-style-2">
        @include('layouts.sidebar')
      </div>
      <!-- Main Content -->
      <div class="main-content">
        
        @yield('content')
        <section class="section">
        </section>
        
        <div class="settingSidebar">
          <a href="javascript:void(0)" class="settingPanelToggle"> <i class="fa fa-spin fa-cog"></i>
          </a>
          <div class="settingSidebar-body ps-container ps-theme-default">
            <div class=" fade show active">
              <div class="setting-panel-header">Setting Panel
              </div>
              <div class="p-15 border-bottom">
                <h6 class="font-medium m-b-10">Select Layout</h6>
                <div class="selectgroup layout-color w-50">
                  <label class="selectgroup-item">
                    <input type="radio" name="value" value="1" class="selectgroup-input-radio select-layout" checked>
                    <span class="selectgroup-button">Light</span>
                  </label>
                  <label class="selectgroup-item">
                    <input type="radio" name="value" value="2" class="selectgroup-input-radio select-layout">
                    <span class="selectgroup-button">Dark</span>
                  </label>
                </div>
              </div>
              <div class="p-15 border-bottom">
                <h6 class="font-medium m-b-10">Sidebar Color</h6>
                <div class="selectgroup selectgroup-pills sidebar-color">
                  <label class="selectgroup-item">
                    <input type="radio" name="icon-input" value="1" class="selectgroup-input select-sidebar">
                    <span class="selectgroup-button selectgroup-button-icon" data-toggle="tooltip"
                      data-original-title="Light Sidebar"><i class="fas fa-sun"></i></span>
                  </label>
                  <label class="selectgroup-item">
                    <input type="radio" name="icon-input" value="2" class="selectgroup-input select-sidebar" checked>
                    <span class="selectgroup-button selectgroup-button-icon" data-toggle="tooltip"
                      data-original-title="Dark Sidebar"><i class="fas fa-moon"></i></span>
                  </label>
                </div>
              </div>
              <div class="p-15 border-bottom">
                <h6 class="font-medium m-b-10">Color Theme</h6>
                <div class="theme-setting-options">
                  <ul class="choose-theme list-unstyled mb-0">
                    <li title="white" class="active">
                      <div class="white"></div>
                    </li>
                    <li title="cyan">
                      <div class="cyan"></div>
                    </li>
                    <li title="black">
                      <div class="black"></div>
                    </li>
                    <li title="purple">
                      <div class="purple"></div>
                    </li>
                    <li title="orange">
                      <div class="orange"></div>
                    </li>
                    <li title="green">
                      <div class="green"></div>
                    </li>
                    <li title="red">
                      <div class="red"></div>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="p-15 border-bottom">
                <div class="theme-setting-options">
                  <label class="m-b-0">
                    <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input"
                      id="mini_sidebar_setting">
                    <span class="custom-switch-indicator"></span>
                    <span class="control-label p-l-10">Mini Sidebar</span>
                  </label>
                </div>
              </div>
              <div class="p-15 border-bottom">
                <div class="theme-setting-options">
                  <label class="m-b-0">
                    <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input"
                      id="sticky_header_setting">
                    <span class="custom-switch-indicator"></span>
                    <span class="control-label p-l-10">Sticky Header</span>
                  </label>
                </div>
              </div>
              <div class="mt-4 mb-4 p-3 align-center rt-sidebar-last-ele">
                <a href="#" class="btn btn-icon icon-left btn-primary btn-restore-theme">
                  <i class="fas fa-undo"></i> Restore Default
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <footer class="main-footer">
        <div class="footer-left">
          <a href="templateshub.net">Templateshub</a></a>
        </div>
        <div class="footer-right">
        </div>
      </footer>
    </div>
  </div>
  <script src="{{ asset('backend/assets/js/app.min.js') }}"></script>
  <!-- JS Libraies -->
  <script src="{{ asset('backend/assets/bundles/cleave-js/dist/cleave.min.js') }}"></script>
  <script src="{{ asset('backend/assets/bundles/cleave-js/dist/addons/cleave-phone.us.js') }}"></script>
  <script src="{{ asset('backend/assets/bundles/jquery-pwstrength/jquery.pwstrength.min.js') }}"></script>
  <script src="{{ asset('backend/assets/bundles/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
  <script src="{{ asset('backend/assets/bundles/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
  <script src="{{ asset('backend/assets/bundles/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
  <script src="{{ asset('backend/assets/bundles/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}"></script>
  <script src="{{ asset('backend/assets/bundles/select2/dist/js/select2.full.min.js') }}"></script>
  <script src="{{ asset('backend/assets/bundles/jquery-selectric/jquery.selectric.min.js') }}"></script>
  <!-- Page Specific JS File -->
  <script src="{{ asset('backend/assets/js/page/forms-advanced-forms.js') }}"></script>
  <!-- Template JS File -->
  <script src="{{ asset('backend/assets/js/scripts.js') }}"></script>
  <!-- Custom JS File -->
  <script src="{{ asset('backend/assets/js/custom.js') }}"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  <!-- Load face-api library -->
  <script src="{{ asset('js/face-api.min.js') }}"></script>
  <!-- JS Libraies -->
  <script src="{{ asset('backend/assets/bundles/summernote/summernote-bs4.js') }}"></script>
  <script src="{{ asset('backend/assets/bundles/upload-preview/assets/js/jquery.uploadPreview.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/page/create-post.js') }}"></script>
  <script>
    @if (session('success'))
        swal({
            title: "Berhasil!",
            text: "{{ session('success') }}",
            icon: "success",
            timer: 3000,
            button: false,
        });
    @endif

    @if (session('error'))
        swal({
            title: "Gagal!",
            text: "{{ session('error') }}",
            icon: "error",
            timer: 3000,
            button: false,
        });
    @endif
  </script>

  @stack('scripts')
</body>


<!-- index.html  21 Nov 2019 03:47:04 GMT -->
</html>