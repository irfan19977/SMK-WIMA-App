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

        <li class="dropdown {{ Request::is('announcements*') ? 'active' : '' }}">
            <a href="#" class="nav-link">
                <i class="fas fa-bullhorn"></i><span>Pengumuman</span>
            </a>
        </li>

        <li class="dropdown {{ Request::is('teachers*') || Request::is('students*') || Request::is('parents*') ? 'active' : '' }}">
            <a href="#" class="menu-toggle nav-link has-dropdown">
                <i class="fas fa-user"></i><span>Pengguna</span>
            </a>
            <ul class="dropdown-menu">
                <li class="dropdown {{ Request::is('students*') ? 'active' : ''}}">
                    <a href="{{ route('students.index') }}" class="nav-link">
                        <i class="fas fa-users"></i><span>Siswa</span>
                    </a>
                </li>
                <li class="dropdown {{ Request::is('teachers*') ? 'active' : ''}}">
                    <a href="{{ route('teachers.index') }}" class="nav-link">
                        <i class="fas fa-chalkboard-teacher"></i><span>Guru</span>
                    </a>
                </li>
                <li class="dropdown {{ Request::is('parents*') ? 'active' : ''}}">
                    <a href="{{ route('parents.index') }}" class="nav-link">
                        <i class="fas fa-user-friends"></i><span>Orang Tua</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="dropdown {{ Request::is('classes*') || Request::is('subjects*') || Request::is('schedules*') ? 'active' : '' }}">
            <a href="#" class="menu-toggle nav-link has-dropdown">
                <i class="fas fa-school"></i><span>Akademik</span>
            </a>
            <ul class="dropdown-menu">
                <li class="dropdown {{ Request::is('classes*') ? 'active' : ''}}">
                    <a href="{{ route('classes.index') }}" class="nav-link">
                        <i class="fas fa-door-open"></i><span>Kelas</span>
                    </a>
                </li>
                <li class="dropdown {{ Request::is('subjects*') ? 'active' : ''}}">
                    <a href="{{ route('subjects.index') }}" class="nav-link">
                        <i class="fas fa-book"></i><span>Mata Pelajaran</span>
                    </a>
                </li>
                <li class="dropdown {{ Request::is('schedules*') ? 'active' : ''}}">
                    <a href="{{ route('schedules.index') }}" class="nav-link">
                        <i class="fas fa-calendar-alt"></i><span>Jadwal Pelajaran</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="dropdown {{ Request::is('attendance*') ? 'active' : '' }}">
            <a href="#" class="menu-toggle nav-link has-dropdown">
                <i class="fas fa-clipboard-check"></i><span>Absensi</span>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-calendar-check"></i><span>Absensi Harian</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-clock"></i><span>Kehadiran IN/Out</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-file-alt"></i><span>Laporan Absensi</span>
                    </a>
                </li>
            </ul>
        </li>

        <li class="dropdown {{ Request::is('exams*') ? 'active' : '' }}">
            <a href="#" class="menu-toggle nav-link has-dropdown">
                <i class="fas fa-tasks"></i><span>Ujian</span>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-folder"></i><span>Bank Soal</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-pencil-alt"></i><span>Ujian/Tes</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-chart-bar"></i><span>Hasil Ujian</span>
                    </a>
                </li>
            </ul>
        </li>

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

        <li class="dropdown {{ Request::is('settings*') ? 'active' : '' }}">
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
            </ul>
        </li>
    </ul>
</aside>