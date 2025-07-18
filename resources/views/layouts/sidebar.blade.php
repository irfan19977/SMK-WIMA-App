<aside id="sidebar-wrapper">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}">
            <img alt="" src="{{ asset('assets/img/logo.png') }}" class="header-logo" />
            <span class="logo-name">Otika</span>
        </a>
    </div>
    <ul class="sidebar-menu">
        <li class="menu-header">Main</li>

        <li class="dropdown {{ Request::is('dashboard*') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}" class="nav-link">
                <i class="fas fa-home"></i><span>Dashboard</span>
            </a>
        </li>

        @can('announcements.index')
        <li class="dropdown {{ Request::is('announcements*') ? 'active' : '' }}">
            <a href="#" class="nav-link">
                <i class="fas fa-bullhorn"></i><span>Pengumuman</span>
            </a>
        </li>
        @endcan

        @canany(['students.index', 'teachers.index', 'parents.index'])
        <li
            class="dropdown {{ Request::is('teachers*') || Request::is('students*') || Request::is('parents*') ? 'active' : '' }}">
            <a href="#" class="menu-toggle nav-link has-dropdown">
                <i class="fas fa-user"></i><span>Pengguna</span>
            </a>
            <ul class="dropdown-menu">
                @can('students.index')
                <li class="dropdown {{ Request::is('students*') ? 'active' : ''}}">
                    <a href="{{ route('students.index') }}" class="nav-link">
                        <i class="fas fa-users"></i><span>Siswa</span>
                    </a>
                </li>
                @endcan
                @can('teachers.index')
                <li class="dropdown {{ Request::is('teachers*') ? 'active' : ''}}">
                    <a href="{{ route('teachers.index') }}" class="nav-link">
                        <i class="fas fa-chalkboard-teacher"></i><span>Guru</span>
                    </a>
                </li>
                @endcan
                @can('parents.index')
                <li class="dropdown {{ Request::is('parents*') ? 'active' : ''}}">
                    <a href="{{ route('parents.index') }}" class="nav-link">
                        <i class="fas fa-user-friends"></i><span>Orang Tua</span>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endcanany

        @canany(['classes.index', 'subjects.index', 'schedules.index'])
        <li
            class="dropdown {{ Request::is('classes*') || Request::is('subjects*') || Request::is('schedules*') ? 'active' : '' }}">
            <a href="#" class="menu-toggle nav-link has-dropdown">
                <i class="fas fa-school"></i><span>Akademik</span>
            </a>
            <ul class="dropdown-menu">
                @can('classes.index')
                    @if(auth()->user()->hasRole('student'))
                        {{-- Untuk role students - tampilkan kelas yang diikuti siswa --}}
                        @php
                            $student = \App\Models\Student::where('user_id', auth()->id())->first();
                            $studentClass = $student ? \App\Models\StudentClass::where('student_id', $student->id)->first() : null;
                        @endphp

                        @if($studentClass)
                            <li class="dropdown {{ Request::is('classes*') ? 'active' : ''}}">
                                <a href="{{ route('classes.show', $studentClass->class_id) }}" class="nav-link">
                                    <i class="fas fa-door-open"></i><span>Kelas Saya</span>
                                </a>
                            </li>
                        @endif
                    @elseif(auth()->user()->hasRole('parent'))
                        {{-- Untuk role parent - tampilkan kelas dari anak --}}
                        @php
                            $parent = \App\Models\ParentModel::where('user_id', auth()->id())->first();
                            $student = $parent && $parent->student_id ? \App\Models\Student::find($parent->student_id) : null;
                            $studentClass = $student ? \App\Models\StudentClass::where('student_id', $student->id)->first() : null;
                        @endphp

                        @if($studentClass)
                            <li class="dropdown {{ Request::is('classes*') ? 'active' : ''}}">
                                <a href="{{ route('classes.show', $studentClass->class_id) }}" class="nav-link">
                                    <i class="fas fa-door-open"></i><span>Kelas Anak</span>
                                </a>
                            </li>
                        @endif
                    @else
                        {{-- Untuk role selain students/parent (admin, teacher, dll) - tampilkan semua kelas --}}
                        @can('classes.index')
                            <li class="dropdown {{ Request::is('classes*') && !Request::is('classes/my-class*') ? 'active' : ''}}">
                                <a href="{{ route('classes.index') }}" class="nav-link">
                                    <i class="fas fa-door-open"></i><span>Kelas</span>
                                </a>
                            </li>
                        @endcan
                    @endif
                @endcan
                @can('subjects.index')
                <li class="dropdown {{ Request::is('subjects*') ? 'active' : ''}}">
                    <a href="{{ route('subjects.index') }}" class="nav-link">
                        <i class="fas fa-book"></i><span>Mata Pelajaran</span>
                    </a>
                </li>
                @endcan
                @can('schedules.index')
                <li class="dropdown {{ Request::is('schedules*') ? 'active' : ''}}">
                    <a href="{{ route('schedules.index') }}" class="nav-link">
                        <i class="fas fa-calendar-alt"></i><span>Jadwal Pelajaran</span>
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        @endcanany

        @canany(['attendances.index', 'leasson.index'])
        <li class="dropdown {{ Request::is('attendance*') ? 'active' : '' }}">
            <a href="#" class="menu-toggle nav-link has-dropdown">
                <i class="fas fa-clipboard-check"></i><span>Absensi</span>
            </a>
            <ul class="dropdown-menu">
                @can('leasson.index')
                <li class="dropdown {{ Request::is('leasson*') ? 'active' : ''}}">
                    <a href="#" class="nav-link">
                        <i class="fas fa-calendar-check"></i><span>Absensi Harian</span>
                    </a>
                </li>
                @endcan
                @can('attendances.index')
                <li class="dropdown {{ Request::is('attendances*') ? 'active' : ''}}">
                    @if(auth()->user()->hasRole('parent'))
                        <a href="{{ route('attendances.index') }}" class="nav-link">
                            <i class="fas fa-clock"></i><span>Kehadiran In/Out Anak</span>
                        </a>
                    @else
                        <a href="{{ route('attendances.index') }}" class="nav-link">
                            <i class="fas fa-clock"></i><span>Kehadiran In/Out</span>
                        </a>
                    @endif
                </li>
                @endcan
            </ul>
        </li>
        @endcanany

        @canany(['exams.index', 'questions.index'])
        <li class="dropdown {{ Request::is('exams*') ? 'active' : '' }}">
            <a href="#" class="menu-toggle nav-link has-dropdown">
                <i class="fas fa-tasks"></i><span>Ujian</span>
            </a>
            <ul class="dropdown-menu">
                @can('questions.index')
                <li class="dropdown {{ Request::is('questions*') ? 'active' : ''}}">
                    <a href="#" class="nav-link">
                        <i class="fas fa-folder"></i><span>Bank Soal</span>
                    </a>
                </li>
                @endcan

                @can('exams.index')
                <li class="dropdown {{ Request::is('exams*') ? 'active' : ''}}">
                    <a href="#" class="nav-link">
                        <i class="fas fa-pencil-alt"></i><span>Ujian/Tes</span>
                    </a>
                </li>
                @endcan
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-chart-bar"></i><span>Hasil Ujian</span>
                    </a>
                </li>
            </ul>
        </li>
        @endcanany

        @canany(['reports.index'])
        <li class="dropdown {{ Request::is('reports*') ? 'active' : '' }}">
            <a href="#" class="menu-toggle nav-link has-dropdown">
                <i class="fas fa-chart-line"></i><span>Laporan</span>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-user-clock"></i><span>Laporan Kehadiran</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-graduation-cap"></i><span>Laporan Akademik</span>
                    </a>
                </li>
            </ul>
        </li>
        @endcanany

        @canany(['settings.index', 'roles.index', 'permissions.index'])
        <li
            class="dropdown {{ Request::is('settings*') || Request::is('roles*') || Request::is('permissions*') ? 'active' : '' }}">
            <a href="#" class="menu-toggle nav-link has-dropdown">
                <i class="fas fa-cog"></i><span>Pengaturan</span>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-users-cog"></i><span>User Management</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-user-cog"></i><span>Profil Pengguna</span>
                    </a>
                </li>
                @can('settings.index')
                <li class="dropdown {{ Request::is('settings*') ? 'active' : '' }}">
                    <a href="{{ route('setting-schedule.index') }}" class="nav-link">
                        <i class="fas fa-clock"></i><span>Jam Masuk/Pulang</span>
                    </a>
                </li>
                @endcan
                @can('roles.index')
                <li class="dropdown {{ Request::is('roles*') ? 'active' : '' }}">
                    <a href="{{ route('roles.index') }}" class="nav-link">
                        <i class="fas fa-user-shield"></i><span>Roles</span>
                    </a>
                </li>
                @endcan
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
