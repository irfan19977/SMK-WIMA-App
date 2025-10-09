<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}">
            <img alt="" src="{{ asset('assets/img/logo.png') }}" class="header-logo" />
            <span class="logo-name">Otika</span>
        </a>
    </div>
    <ul class="sidebar-menu">
        <li class="menu-header">Main</li>

        {{-- Dashboard --}}
        <li class="dropdown {{ Request::is('dashboard*') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="nav-link">
                <i class="fas fa-home"></i><span>Dashboard</span>
            </a>
        </li>

        {{-- Pengumuman --}}
        @can('announcements.index')
            <li class="dropdown {{ Request::is('announcements*') ? 'active' : '' }}">
                <a href="#" class="nav-link">
                    <i class="fas fa-bullhorn"></i><span>Pengumuman</span>
                </a>
            </li>
        @endcan
        
        {{-- Pengumuman --}}
        @can('announcements.index')
            <li class="dropdown {{ Request::is('announcements*') ? 'active' : '' }}">
                <a href="{{ route('pendaftaran.index') }}" class="nav-link">
                    <i class="fas fa-bullhorn"></i><span>Pendaftaran</span>
                </a>
            </li>
        @endcan

        {{-- Akademik --}}
        @canany(['classes.index', 'subjects.index', 'schedules.index', 'student-grades.index'])
            <li class="dropdown {{ Request::is('classes*') || Request::is('subjects*') || Request::is('schedules*') || Request::is('student-grades*') ? 'active' : '' }}">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fas fa-graduation-cap"></i><span>Akademik</span>
                </a>
                <ul class="dropdown-menu">
                    {{-- Menu Kelas dengan logika berbeda per role --}}
                    @can('classes.index')
                        @if(auth()->user()->hasRole('student'))
                            {{-- STUDENT: Kelas Saya --}}
                            @php
                                $student = \App\Models\Student::where('user_id', auth()->id())->first();
                                $studentClass = $student ? \App\Models\StudentClass::where('student_id', $student->id)->first() : null;
                            @endphp
                            @if($studentClass)
                                <li class="dropdown {{ Request::is('classes/' . $studentClass->class_id . '*') ? 'active' : '' }}">
                                    <a href="{{ route('classes.show', $studentClass->class_id) }}" class="nav-link">
                                        <i class="fas fa-chalkboard"></i><span>Kelas Saya</span>
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
                                <li class="dropdown {{ Request::is('classes/' . $studentClass->class_id . '*') ? 'active' : '' }}">
                                    <a href="{{ route('classes.show', $studentClass->class_id) }}" class="nav-link">
                                        <i class="fas fa-chalkboard"></i><span>Kelas Anak</span>
                                    </a>
                                </li>
                            @endif
                        @else
                            {{-- ADMIN/TEACHER: Semua Kelas --}}
                            <li class="dropdown {{ Request::is('classes*') && !Request::is('classes/*/') ? 'active' : '' }}">
                                <a href="{{ route('classes.index') }}" class="nav-link">
                                    <i class="fas fa-chalkboard"></i><span>Kelas</span>
                                </a>
                            </li>
                        @endif
                    @endcan

                    @can('subjects.index')
                        <li class="dropdown {{ Request::is('subjects*') ? 'active' : '' }}">
                            <a href="{{ route('subjects.index') }}" class="nav-link">
                                <i class="fas fa-book-open"></i><span>Mata Pelajaran</span>
                            </a>
                        </li>
                    @endcan

                    @can('schedules.index')
                        <li class="dropdown {{ Request::is('schedules*') ? 'active' : '' }}">
                            <a href="{{ route('schedules.index') }}" class="nav-link">
                                <i class="fas fa-calendar"></i><span>Jadwal Pelajaran</span>
                            </a>
                        </li>
                    @endcan

                    @can('student-grades.index')
                        <li class="dropdown {{ Request::is('student-grades*') ? 'active' : '' }}">
                            <a href="{{ route('student-grades.index') }}" class="nav-link">
                                <i class="fas fa-star"></i><span>Input Nilai</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        {{-- Absensi --}}
        @canany(['attendances.index', 'lesson_attendances.index'])
            <li class="dropdown {{ Request::is('attendance*') || Request::is('lesson*') || Request::is('face-recognition*') ? 'active' : '' }}">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fas fa-user-clock"></i><span>Absensi</span>
                </a>
                <ul class="dropdown-menu">
                    @can('lesson_attendances.index')
                        <li class="dropdown {{ Request::is('lesson*') || Request::is('face-recognition*') ? 'active' : '' }}">
                            @if(auth()->user()->hasRole('student'))
                                <a href="{{ route('face-recognition.index') }}" class="nav-link">
                                    <i class="fas fa-camera-retro"></i><span>Absensi Scan Wajah</span>
                                </a>
                            @else
                                <a href="{{ route('lesson-attendances.index') }}" class="nav-link">
                                    <i class="fas fa-clipboard-list"></i><span>Absensi Harian</span>
                                </a>
                            @endif
                        </li>
                    @endcan

                    @can('attendances.index')
                        <li class="dropdown {{ Request::is('attendances*') ? 'active' : '' }}">
                            <a href="{{ route('attendances.index') }}" class="nav-link">
                                <i class="fas fa-hourglass-half"></i>
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
        @canany(['tahfiz.index', 'asrama.index', 'ekstrakurikuler.index'])
            <li class="dropdown {{ Request::is('tahfiz*') || Request::is('asrama*') || Request::is('ekstrakurikuler*') ? 'active' : '' }}">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fas fa-book-reader"></i><span>Non Akademik</span>
                </a>
                <ul class="dropdown-menu">
                    @can('tahfiz.index')
                        <li class="dropdown {{ Request::is('tahfiz*') ? 'active' : '' }}">
                            <a href="{{ route('tahfiz.index') }}" class="nav-link">
                                <i class="fas fa-quran"></i><span>Kompetensi Tahfiz</span>
                            </a>
                        </li>
                    @endcan

                    @can('asrama.index')
                        <li class="dropdown {{ Request::is('asrama*') ? 'active' : '' }}">
                            <a href="{{ route('asrama.index') }}" class="nav-link">
                                <i class="fas fa-building"></i><span>Asrama</span>
                            </a>
                        </li>
                    @endcan

                    @can('ekstrakurikuler.index')
                        <li class="dropdown {{ Request::is('ekstrakurikuler*') ? 'active' : '' }}">
                            <a href="{{ route('ekstrakurikuler.index') }}" class="nav-link">
                                <i class="fas fa-futbol"></i><span>Ekstrakurikuler</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany

        {{-- Manajemen Biometrik --}}
        @can('face_recognition.create')
            <li class="dropdown {{ Request::is('face-recognition*') ? 'active' : '' }}">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fas fa-fingerprint"></i><span>Manajemen Biometrik</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="dropdown {{ Request::is('face-recognition/index*') ? 'active' : '' }}">
                        <a href="{{ route('face-recognition.index') }}" class="nav-link">
                            <i class="fas fa-camera"></i><span>Scan Wajah</span>
                        </a>
                    </li>
                    <li class="dropdown {{ Request::is('face-recognition/create*') ? 'active' : '' }}">
                        <a href="{{ route('face-recognition.create') }}" class="nav-link">
                            <i class="fas fa-database"></i><span>Data Biometrik</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        {{-- Ujian --}}
        @canany(['exams.index', 'questions.index'])
            <li class="dropdown {{ Request::is('exams*') || Request::is('questions*') ? 'active' : '' }}">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fas fa-tasks"></i><span>Ujian</span>
                </a>
                <ul class="dropdown-menu">
                    @can('questions.index')
                        <li class="dropdown {{ Request::is('questions*') ? 'active' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="fas fa-folder"></i><span>Bank Soal</span>
                            </a>
                        </li>
                    @endcan

                    @can('exams.index')
                        <li class="dropdown {{ Request::is('exams*') ? 'active' : '' }}">
                            <a href="#" class="nav-link">
                                <i class="fas fa-pencil-alt"></i><span>Ujian/Tes</span>
                            </a>
                        </li>
                    @endcan

                    <li class="dropdown {{ Request::is('exam-results*') ? 'active' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="fas fa-chart-bar"></i><span>Hasil Ujian</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endcanany

        {{-- Laporan --}}
        @can('reports.index')
            <li class="dropdown {{ Request::is('reports*') ? 'active' : '' }}">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fas fa-chart-line"></i><span>Laporan</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="dropdown {{ Request::is('reports/attendance*') ? 'active' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="fas fa-user-clock"></i><span>Laporan Kehadiran</span>
                        </a>
                    </li>
                    <li class="dropdown {{ Request::is('reports/academic*') ? 'active' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="fas fa-graduation-cap"></i><span>Laporan Akademik</span>
                        </a>
                    </li>
                </ul>
            </li>
        @endcan

        {{-- Pengaturan --}}
        @canany(['settings.index', 'roles.index', 'permissions.index', 'students.index', 'teachers.index', 'parents.index'])
            <li class="dropdown {{ Request::is('settings*') || Request::is('roles*') || Request::is('permissions*') || Request::is('setting-schedule*') || Request::is('profile*') || Request::is('teachers*') || Request::is('students*') || Request::is('parents*') ? 'active' : '' }}">
                <a href="#" class="menu-toggle nav-link has-dropdown">
                    <i class="fas fa-cog"></i><span>Pengaturan</span>
                </a>
                <ul class="dropdown-menu">
                    {{-- User Management --}}
                    @canany(['students.index', 'teachers.index', 'parents.index'])
                        <li class="dropdown {{ Request::is('teachers*') || Request::is('students*') || Request::is('parents*') ? 'active' : '' }}">
                            <a href="#" class="menu-toggle nav-link has-dropdown">
                                <i class="fas fa-users-cog"></i><span>User Management</span>
                            </a>
                            <ul class="dropdown-menu">
                                @can('students.index')
                                    <li class="dropdown {{ Request::is('students*') ? 'active' : '' }}">
                                        <a href="{{ route('students.index') }}" class="nav-link">
                                            <i class="fas fa-users"></i><span>Siswa</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('teachers.index')
                                    <li class="dropdown {{ Request::is('teachers*') ? 'active' : '' }}">
                                        <a href="{{ route('teachers.index') }}" class="nav-link">
                                            <i class="fas fa-chalkboard-teacher"></i><span>Guru</span>
                                        </a>
                                    </li>
                                @endcan
                                @can('parents.index')
                                    <li class="dropdown {{ Request::is('parents*') ? 'active' : '' }}">
                                        <a href="{{ route('parents.index') }}" class="nav-link">
                                            <i class="fas fa-user-friends"></i><span>Orang Tua</span>
                                        </a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                    {{-- Profil Pengguna --}}
                    <li class="dropdown {{ Request::is('profile*') ? 'active' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="fas fa-user-cog"></i><span>Profil Pengguna</span>
                        </a>
                    </li>

                    {{-- Jam Masuk/Pulang --}}
                    @can('settings.index')
                        <li class="dropdown {{ Request::is('setting-schedule*') ? 'active' : '' }}">
                            <a href="{{ route('setting-schedule.index') }}" class="nav-link">
                                <i class="fas fa-clock"></i><span>Jam Masuk/Pulang</span>
                            </a>
                        </li>
                    @endcan

                    {{-- Roles Management --}}
                    @can('roles.index')
                        <li class="dropdown {{ Request::is('roles*') ? 'active' : '' }}">
                            <a href="{{ route('roles.index') }}" class="nav-link">
                                <i class="fas fa-user-shield"></i><span>Roles</span>
                            </a>
                        </li>
                    @endcan

                    {{-- Permissions Management --}}
                    @can('permissions.index')
                        <li class="dropdown {{ Request::is('permissions*') ? 'active' : '' }}">
                            <a href="{{ route('permissions.index') }}" class="nav-link">
                                <i class="fas fa-key"></i><span>Permissions</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcanany
    </ul>
</aside>