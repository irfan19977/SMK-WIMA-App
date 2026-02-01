<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <!-- LOGO -->
    <div class="navbar-brand-box">
        <a href="{{ route('/') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ asset('backend/assets/img/logo 1.png') }}" alt="logo-sm" height="24">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('backend/assets/img/logo 1.png') }}" alt="logo" height="22">
                <span class="ms-2 logo-name">SMK PGRI</span>
            </span>
        </a>

        <a href="{{ route('/') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ asset('backend/assets/img/logo 1.png') }}" alt="logo-sm-light" height="24">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('backend/assets/img/logo 1.png') }}" alt="logo-light" height="22">
                <span class="ms-2 logo-name">SMK PGRI</span>
            </span>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect vertical-menu-btn"
        id="vertical-menu-btn">
        <i class="ri-menu-2-line align-middle"></i>
    </button>

    <div data-simplebar class="vertical-scroll">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <div class="dropdown mx-3 sidebar-user user-dropdown select-dropdown">
                <button type="button" class="btn btn-light w-100 waves-effect waves-light border-0"
                    id="page-header-user-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="avatar-xs rounded-circle flex-shrink-0">
                                <div
                                    class="avatar-title border bg-light text-primary rounded-circle text-uppercase user-sort-name">
                                    R</div>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-2 text-start">
                            <h6 class="mb-1 fw-medium user-name-text"> Reporting </h6>
                            <p class="font-size-13 text-muted user-name-sub-text mb-0"> Team Reporting </p>
                        </div>
                        <div class="flex-shrink-0 text-end">
                            <i class="mdi mdi-chevron-down font-size-16"></i>
                        </div>
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu-end w-100">
                    <!-- item-->
                    <a class="dropdown-item d-flex align-items-center px-3" href="#">
                        <div class="flex-shrink-0 me-2">
                            <div class="avatar-xs rounded-circle flex-shrink-0">
                                <div class="avatar-title border rounded-circle text-uppercase dropdown-sort-name">C
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 dropdown-name">CRM</h6>
                            <p class="text-muted font-size-13 mb-0 dropdown-sub-desc">Designer Team</p>
                        </div>
                    </a>
                    <a class="dropdown-item d-flex align-items-center px-3" href="#">
                        <div class="flex-shrink-0 me-2">
                            <div class="avatar-xs rounded-circle flex-shrink-0">
                                <div class="avatar-title border rounded-circle text-uppercase dropdown-sort-name">A
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 dropdown-name">Application Design</h6>
                            <p class="text-muted font-size-13 mb-0 dropdown-sub-desc">Flutter Devs</p>
                        </div>
                    </a>

                    <a class="dropdown-item d-flex align-items-center px-3" href="#">
                        <div class="flex-shrink-0 me-2">
                            <div class="avatar-xs rounded-circle flex-shrink-0">
                                <div class="avatar-title border rounded-circle text-uppercase dropdown-sort-name">E
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 dropdown-name">Ecommerce</h6>
                            <p class="text-muted font-size-13 mb-0 dropdown-sub-desc">Developer Team</p>
                        </div>
                    </a>

                    <a class="dropdown-item d-flex align-items-center px-3" href="#">
                        <div class="flex-shrink-0 me-2">
                            <div class="avatar-xs rounded-circle flex-shrink-0">
                                <div class="avatar-title border rounded-circle text-uppercase dropdown-sort-name">R
                                </div>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 dropdown-name">Reporting</h6>
                            <p class="text-muted font-size-13 mb-0 dropdown-sub-desc">Team Reporting</p>
                        </div>
                    </a>

                    <a class="btn btn-sm btn-link font-size-14 text-center w-100" href="javascript:void(0)">
                        View More..
                    </a>
                </div>
            </div>
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Main</li>

                {{-- Dashboard --}}
                <li>
                    <a href="{{ route('dashboard') }}" class="waves-effect {{ Request::is('dashboard*') ? 'active' : '' }}">
                        <i class="mdi mdi-view-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- Pengumuman --}}
                @can('announcements.index')
                    <li>
                        <a href="#" class="waves-effect {{ Request::is('announcements*') ? 'active' : '' }}">
                            <i class="mdi mdi-bullhorn"></i>
                            <span>Pengumuman</span>
                        </a>
                    </li>
                @endcan
                
                {{-- Berita --}}
                @can('news.index')
                    <li>
                        <a href="{{ route('news.index') }}" class="waves-effect {{ Request::is('news*') ? 'active' : '' }}">
                            <i class="mdi mdi-newspaper"></i>
                            <span>Berita</span>
                        </a>
                    </li>
                @endcan
                
                {{-- Pendaftaran --}}
                @can('pendaftaran-siswa.index')
                    <li>
                        <a href="{{ route('pendaftaran-siswa.index') }}" class="waves-effect {{ Request::is('pendaftaran*') ? 'active' : '' }}">
                            <i class="mdi mdi-account-plus"></i>
                            <span>Pendaftaran</span>
                        </a>
                    </li>
                @endcan

                {{-- Akademik --}}
                @canany(['classes.index', 'subjects.index', 'schedules.index', 'student-grades.index'])
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect {{ Request::is('classes*') || Request::is('subjects*') || Request::is('schedules*') || Request::is('student-grades*') ? 'active' : '' }}">
                            <i class="mdi mdi-school"></i>
                            <span>Akademik</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            {{-- Menu Kelas dengan logika berbeda per role --}}
                            @can('classes.index')
                                @if(auth()->user()->hasRole('student'))
                                    {{-- STUDENT: Kelas Saya --}}
                                    @php
                                        $student = \App\Models\Student::where('user_id', auth()->id())->first();
                                        $studentClass = $student ? \App\Models\StudentClass::where('student_id', $student->id)->first() : null;
                                    @endphp
                                    @if($studentClass)
                                        <li>
                                            <a href="{{ route('classes.show', $studentClass->class_id) }}" class="waves-effect {{ Request::is('classes/' . $studentClass->class_id . '*') ? 'active' : '' }}">
                                                <i class="mdi mdi-book-open-variant"></i><span>Kelas Saya</span>
                                            </a>
                                        </li>
                                    @endif
                                @elseif(auth()->user()->hasRole('parent'))
                                    {{-- PARENT: Kelas Anak --}}
                                    @php
                                        $parent = \App\Models\ParentModel::where('user_id', auth()->id())->first();
                                        $student = $parent && $parent->student_id ? \App\Models\Student::find($parent->student_id) : null;
                                        $studentClass = $student ? \App\Models\StudentClass::where('student_id', $student->id)->first() : null;
                                    @endphp
                                    @if($studentClass)
                                        <li>
                                            <a href="{{ route('classes.show', $studentClass->class_id) }}" class="waves-effect {{ Request::is('classes/' . $studentClass->class_id . '*') ? 'active' : '' }}">
                                                <i class="mdi mdi-book-open-variant"></i><span>Kelas Anak</span>
                                            </a>
                                        </li>
                                    @endif
                                @else
                                    {{-- ADMIN/TEACHER: Semua Kelas --}}
                                    <li>
                                        <a href="{{ route('classes.index') }}" class="waves-effect {{ Request::is('classes*') && !Request::is('classes/*/') ? 'active' : '' }}">
                                            <i class="mdi mdi-book-open-variant"></i><span>Kelas</span>
                                        </a>
                                    </li>
                                @endif
                            @endcan

                            @can('subjects.index')
                                <li>
                                    <a href="{{ route('subjects.index') }}" class="waves-effect {{ Request::is('subjects*') ? 'active' : '' }}">
                                        <i class="mdi mdi-book"></i><span>Mata Pelajaran</span>
                                    </a>
                                </li>
                            @endcan

                            @can('schedules.index')
                                <li>
                                    <a href="{{ route('schedules.index') }}" class="waves-effect {{ Request::is('schedules*') ? 'active' : '' }}">
                                        <i class="mdi mdi-calendar"></i><span>Jadwal Pelajaran</span>
                                    </a>
                                </li>
                            @endcan

                            @can('student-grades.index')
                                <li>
                                    <a href="{{ route('student-grades.index') }}" class="waves-effect {{ Request::is('student-grades*') ? 'active' : '' }}">
                                        <i class="mdi mdi-star"></i><span>Input Nilai</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- Absensi --}}
                @canany(['attendances.index', 'lesson_attendances.index'])
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect {{ Request::is('attendance*') || Request::is('lesson*') || Request::is('face-recognition*') ? 'active' : '' }}">
                            <i class="mdi mdi-clock-outline"></i>
                            <span>Absensi</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            @can('lesson_attendances.index')
                                <li>
                                    @if(auth()->user()->hasRole('student'))
                                        <a href="{{ route('face-recognition.index') }}" class="waves-effect {{ Request::is('lesson*') || Request::is('face-recognition*') ? 'active' : '' }}">
                                            <i class="mdi mdi-camera"></i><span>Absensi Scan Wajah</span>
                                        </a>
                                    @else
                                        <a href="{{ route('lesson-attendances.index') }}" class="waves-effect {{ Request::is('lesson*') || Request::is('face-recognition*') ? 'active' : '' }}">
                                            <i class="mdi mdi-clipboard-list"></i><span>Absensi Harian</span>
                                        </a>
                                    @endif
                                </li>
                            @endcan

                            @can('attendances.index')
                                <li>
                                    <a href="{{ route('attendances.index') }}" class="waves-effect {{ Request::is('attendances*') ? 'active' : '' }}">
                                        <i class="uim uim-clock"></i>
                                        <span>
                                            @if(auth()->user()->hasRole('parent'))
                                                Kehadiran In/Out Anak
                                            @else
                                                Kehadiran In/Out
                                            @endif
                                        </span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- Non Akademik --}}
                @can('ekstrakurikuler.index')
                    <li>
                        <a href="{{ route('ekstrakurikuler.index') }}" class="waves-effect {{ Request::is('ekstrakurikuler*') ? 'active' : '' }}">
                            <i class="mdi mdi-soccer"></i>
                            <span>Ekstrakurikuler</span>
                        </a>
                    </li>
                @endcan

                {{-- Manajemen Biometrik --}}
                @can('face_recognition.create')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect {{ Request::is('face-recognition*') ? 'active' : '' }}">
                            <i class="mdi mdi-fingerprint"></i>
                            <span>Manajemen Biometrik</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li>
                                <a href="{{ route('face-recognition.index') }}" class="waves-effect {{ Request::is('face-recognition/index*') ? 'active' : '' }}">
                                    <i class="mdi mdi-camera"></i><span>Scan Wajah</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('face-recognition.create') }}" class="waves-effect {{ Request::is('face-recognition/create*') ? 'active' : '' }}">
                                    <i class="mdi mdi-database"></i><span>Data Biometrik</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                {{-- Ujian --}}
                @canany(['exams.index', 'questions.index'])
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect {{ Request::is('exams*') || Request::is('questions*') ? 'active' : '' }}">
                            <i class="mdi mdi-format-list-checks"></i>
                            <span>Ujian</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            @can('questions.index')
                                <li>
                                    <a href="#" class="waves-effect {{ Request::is('questions*') ? 'active' : '' }}">
                                        <i class="mdi mdi-folder"></i><span>Bank Soal</span>
                                    </a>
                                </li>
                            @endcan

                            @can('exams.index')
                                <li>
                                    <a href="#" class="waves-effect {{ Request::is('exams*') ? 'active' : '' }}">
                                        <i class="mdi mdi-pencil"></i><span>Ujian/Tes</span>
                                    </a>
                                </li>
                            @endcan

                            <li>
                                <a href="#" class="waves-effect {{ Request::is('exam-results*') ? 'active' : '' }}">
                                    <i class="mdi mdi-chart-bar"></i><span>Hasil Ujian</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcanany

                {{-- Laporan --}}
                @can('reports.index')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect {{ Request::is('reports*') ? 'active' : '' }}">
                            <i class="mdi mdi-chart-line"></i>
                            <span>Laporan</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            <li>
                                <a href="#" class="waves-effect {{ Request::is('reports/attendance*') ? 'active' : '' }}">
                                    <i class="mdi mdi-clock-outline"></i><span>Laporan Kehadiran</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('reports.academic') }}" class="waves-effect {{ Request::is('reports/academic*') ? 'active' : '' }}">
                                    <i class="mdi mdi-school"></i><span>Laporan Akademik</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan

                {{-- Pengaturan --}}
                @canany(['settings.index', 'roles.index', 'permissions.index', 'students.index', 'teachers.index', 'parents.index'])
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect {{ Request::is('settings*') || Request::is('roles*') || Request::is('permissions*') || Request::is('setting-schedule*') || Request::is('profile*') || Request::is('teachers*') || Request::is('students*') || Request::is('parents*') ? 'active' : '' }}">
                            <i class="mdi mdi-cog"></i>
                            <span>Pengaturan</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="true">
                            {{-- User Management --}}
                            @canany(['students.index', 'teachers.index', 'parents.index'])
                                <li>
                                    <a href="javascript: void(0);" class="has-arrow waves-effect {{ Request::is('teachers*') || Request::is('students*') || Request::is('parents*') ? 'active' : '' }}">
                                        <i class="mdi mdi-account-group"></i><span>User Management</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="true">
                                        @can('students.index')
                                            <li>
                                                <a href="{{ route('students.index') }}" class="waves-effect {{ Request::is('students*') ? 'active' : '' }}">
                                                    <i class="mdi mdi-account-group"></i><span>Siswa</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('teachers.index')
                                            <li>
                                                <a href="{{ route('teachers.index') }}" class="waves-effect {{ Request::is('teachers*') ? 'active' : '' }}">
                                                    <i class="mdi mdi-account"></i><span>Guru</span>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('parents.index')
                                            <li>
                                                <a href="{{ route('parents.index') }}" class="waves-effect {{ Request::is('parents*') ? 'active' : '' }}">
                                                    <i class="mdi mdi-account"></i><span>Orang Tua</span>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany

                            {{-- Profil Pengguna --}}
                            <li>
                                <a href="#" class="waves-effect {{ Request::is('profile*') ? 'active' : '' }}">
                                    <i class="mdi mdi-account-cog"></i><span>Profil Pengguna</span>
                                </a>
                            </li>

                            {{-- Jam Masuk/Pulang --}}
                            @can('settings.index')
                                <li>
                                    <a href="{{ route('setting-schedule.index') }}" class="waves-effect {{ Request::is('setting-schedule*') ? 'active' : '' }}">
                                        <i class="mdi mdi-clock-outline"></i><span>Jam Masuk/Pulang</span>
                                    </a>
                                </li>
                            @endcan

                            {{-- Roles Management --}}
                            @can('roles.index')
                                <li>
                                    <a href="{{ route('roles.index') }}" class="waves-effect {{ Request::is('roles*') ? 'active' : '' }}">
                                        <i class="mdi mdi-shield-account"></i><span>Roles</span>
                                    </a>
                                </li>
                            @endcan

                            {{-- Permissions Management --}}
                            @can('permissions.index')
                                <li>
                                    <a href="{{ route('permissions.index') }}" class="waves-effect {{ Request::is('permissions*') ? 'active' : '' }}">
                                        <i class="mdi mdi-key"></i><span>Permissions</span>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="dropdown px-3 sidebar-user sidebar-user-info">
        <button type="button" class="btn w-100 px-0 border-0" id="page-header-user-dropdown"
            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    @if(Auth::user()->photo_path)
                        <img src="{{ asset('storage/' . Auth::user()->photo_path) }}"
                            class="header-profile-user rounded-circle" alt="" width="32" height="32" style="object-fit: cover;">
                    @else
                        <img src="{{ URL::asset('build/images/users/avatar-2.jpg') }}"
                            class="header-profile-user rounded-circle" alt="" width="32" height="32" style="object-fit: cover;">
                    @endif
                </div>

                <div class="flex-grow-1 ms-2 text-start">
                    <span class="ms-1 fw-medium user-name-text">{{ Auth::user()->name }}</span>
                </div>

                <div class="flex-shrink-0 text-end">
                    <i class="mdi mdi-dots-vertical font-size-16"></i>
                </div>
            </span>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
            <!-- item-->
            <a class="dropdown-item" href="{{ route('profile.show') }}">
                <i class="mdi mdi-account-circle text-muted font-size-16 align-middle me-1"></i> 
                <span class="align-middle">Profile</span>
            </a>
            <a class="dropdown-item" href="{{ route('profile.show') }}">
                <i class="mdi mdi-lock-reset text-muted font-size-16 align-middle me-1"></i> 
                <span class="align-middle">Ubah Password</span>
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="{{ url('/settings') }}">
                <i class="mdi mdi-cog-outline text-muted font-size-16 align-middle me-1"></i> 
                <span class="align-middle">Pengaturan</span>
            </a>
            <a class="dropdown-item" href="javascript:void();"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="mdi mdi-logout text-muted font-size-16 align-middle me-1"></i> 
                <span class="align-middle">Keluar</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</div>
<!-- Left Sidebar End -->
