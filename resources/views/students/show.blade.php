@extends('layouts.master')
@section('title')
    Detail Siswa
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-title')
    Detail Siswa
@endsection
@section('body')

    <body data-sidebar="colored">
    @endsection
    @section('content')
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="card-title mb-1">Detail Siswa</h4>
                                <p class="text-muted mb-0">Informasi lengkap data siswa</p>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('students.index') }}" class="btn btn-secondary">
                                    <i class="mdi mdi-arrow-left"></i> Kembali
                                </a>
                                <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary">
                                    <i class="mdi mdi-pencil"></i> Edit
                                </a>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Profil & Kontak -->
                            <div class="col-lg-4 mb-4">
                                <div class="card shadow-sm">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            @if($student->photo)
                                                <img src="{{ asset('storage/students/' . $student->photo) }}" alt="Foto Siswa" class="rounded-circle img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" style="width: 120px; height: 120px; font-size: 36px;">
                                                    {{ strtoupper(substr($student->name ?? 'N/A', 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <h5 class="mb-1">{{ $student->name ?? '-' }}</h5>
                                        <span class="badge rounded-pill {{ $student->status == 'siswa' ? 'bg-success' : 'bg-warning' }} font-size-12">
                                            {{ $student->status == 'siswa' ? 'Siswa' : 'Calon Siswa' }}
                                        </span>
                                        <div class="mt-3 text-start">
                                            <div class="mb-2">
                                                <i class="mdi mdi-email text-muted me-2"></i>
                                                <small class="text-muted">{{ $student->user->email ?? '-' }}</small>
                                            </div>
                                            <div class="mb-2">
                                                <i class="mdi mdi-phone text-muted me-2"></i>
                                                @if($student->user->phone)
                                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->user->phone) }}" target="_blank" class="text-decoration-none" title="Chat via WhatsApp">
                                                        <small>{{ $student->user->phone }}</small>
                                                    </a>
                                                @else
                                                    <small class="text-muted">-</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informasi Orang Tua -->
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title mb-3">
                                            <i class="mdi mdi-account-group me-2"></i>Informasi Orang Tua/Wali
                                        </h6>
                                        <div class="mb-2">
                                            <small class="text-muted">Nama</small>
                                            <p class="mb-1">{{ $student->parent_name ?? '-' }}</p>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">No. HP</small>
                                            <p class="mb-1">
                                                @if($student->parent_phone)
                                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->parent_phone) }}" target="_blank" class="text-decoration-none" title="Chat via WhatsApp">
                                                        {{ $student->parent_phone }}
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </p>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Email</small>
                                            <p class="mb-1">{{ $student->parent_email ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Pribadi -->
                            <div class="col-lg-8 mb-4">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title mb-4">
                                            <i class="mdi mdi-account me-2"></i>Data Pribadi
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <small class="text-muted">NISN</small>
                                                <p class="mb-1 fw-medium">{{ $student->nisn ?? '-' }}</p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <small class="text-muted">No. Kartu</small>
                                                <p class="mb-1 fw-medium">{{ $student->no_kartu ?? '-' }}</p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <small class="text-muted">No. Absen</small>
                                                <p class="mb-1 fw-medium">{{ $student->no_absen ?? '-' }}</p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <small class="text-muted">Jenis Kelamin</small>
                                                <p class="mb-1 fw-medium">
                                                    @if($student->gender == 'L')
                                                        Laki-laki
                                                    @elseif($student->gender == 'P')
                                                        Perempuan
                                                    @else
                                                        -
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <small class="text-muted">Tempat Lahir</small>
                                                <p class="mb-1 fw-medium">{{ $student->birth_place ?? '-' }}</p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <small class="text-muted">Tanggal Lahir</small>
                                                <p class="mb-1 fw-medium">
                                                    @if($student->birth_date)
                                                        {{ $student->birth_date->format('d F Y') }}
                                                    @else
                                                        -
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <small class="text-muted">Alamat</small>
                                                <p class="mb-1 fw-medium">{{ $student->address ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Informasi Akademik -->
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title mb-4">
                                            <i class="mdi mdi-school me-2"></i>Informasi Akademik
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <small class="text-muted">Status</small>
                                                <p class="mb-1">
                                                    <span class="badge rounded-pill {{ $student->status == 'siswa' ? 'bg-success' : 'bg-warning' }} font-size-12">
                                                        {{ $student->status == 'siswa' ? 'Siswa Aktif' : 'Calon Siswa' }}
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <small class="text-muted">Kelas</small>
                                                <p class="mb-1 fw-medium">
                                                    @if($student->classes->isNotEmpty())
                                                        @foreach($student->classes as $class)
                                                            <span class="badge bg-primary">{{ $class->name }}</span>
                                                        @endforeach
                                                    @else
                                                        -
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @section('scripts')
        <!-- Sweet Alert-->
        <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
        
        <script>
            // Image preview functionality
            document.addEventListener('DOMContentLoaded', function() {
                const images = document.querySelectorAll('img[data-src]');
                images.forEach(function(img) {
                    img.addEventListener('click', function() {
                        const src = this.getAttribute('data-src');
                        const title = this.getAttribute('data-title');
                        
                        Swal.fire({
                            title: title,
                            imageUrl: src,
                            imageAlt: title,
                            imageWidth: 'auto',
                            imageHeight: 'auto',
                            showConfirmButton: true,
                            confirmButtonText: 'Tutup',
                            confirmButtonColor: '#3085d6'
                        });
                    });
                });
            });
        </script>
    @endsection
