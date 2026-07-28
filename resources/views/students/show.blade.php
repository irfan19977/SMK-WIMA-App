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
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="profile-avatar mb-3">
                            @if($student->photo)
                                <img src="{{ asset('storage/students/' . $student->photo) }}" alt="Foto Siswa" class="rounded-circle" width="120" height="120" style="object-fit: cover;">
                            @else
                                <div class="avatar-placeholder rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px; font-size: 48px;">
                                    {{ strtoupper(substr($student->name ?? 'N/A', 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <h4 class="card-title">{{ $student->name ?? '-' }}</h4>
                        <p class="text-muted">{{ $student->user->email ?? '-' }}</p>
                        <p class="badge bg-{{ $student->status == 'siswa' ? 'success' : 'warning' }}">
                            {{ $student->status == 'siswa' ? 'Siswa Aktif' : 'Calon Siswa' }}
                        </p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Informasi Akun</h5>
                        <div class="mb-3">
                            <label class="form-label text-muted">Email</label>
                            <p class="mb-0">{{ $student->user->email ?? '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Nomor HP</label>
                            <p class="mb-0">
                                @if($student->phone)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->phone) }}" target="_blank" class="text-decoration-none">
                                        {{ $student->phone }}
                                    </a>
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Bergabung Sejak</label>
                            <p class="mb-0">{{ $student->created_at ? \Carbon\Carbon::parse($student->created_at)->format('d M Y') : '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Terakhir Update</label>
                            <p class="mb-0">{{ $student->updated_at ? \Carbon\Carbon::parse($student->updated_at)->format('d M Y H:i') : '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Informasi Orang Tua/Wali</h5>
                        <div class="mb-3">
                            <label class="form-label text-muted">Nama</label>
                            <p class="mb-0">{{ $student->parent_name ?? '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Nomor HP</label>
                            <p class="mb-0">
                                @if($student->parent_phone)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $student->parent_phone) }}" target="_blank" class="text-decoration-none">
                                        {{ $student->parent_phone }}
                                    </a>
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted">Email</label>
                            <p class="mb-0">{{ $student->parent_email ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="card-title mb-0">Detail Siswa</h5>
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
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">NISN</label>
                                <p class="mb-0 fw-medium">{{ $student->nisn ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">NIK</label>
                                <p class="mb-0 fw-medium">{{ $student->nik ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">No. Kartu</label>
                                <p class="mb-0 fw-medium">{{ $student->no_kartu ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">No. Absen</label>
                                <p class="mb-0 fw-medium">{{ $student->no_absen ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Jenis Kelamin</label>
                                <p class="mb-0 fw-medium">
                                    @if($student->gender == 'L' || $student->gender == 'laki-laki')
                                        Laki-laki
                                    @elseif($student->gender == 'P' || $student->gender == 'perempuan')
                                        Perempuan
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Agama</label>
                                <p class="mb-0 fw-medium">{{ $student->religion ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Tempat Lahir</label>
                                <p class="mb-0 fw-medium">{{ $student->birth_place ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Tanggal Lahir</label>
                                <p class="mb-0 fw-medium">
                                    @if($student->birth_date)
                                        {{ \Carbon\Carbon::parse($student->birth_date)->format('d F Y') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label text-muted">Alamat</label>
                                <p class="mb-0 fw-medium">{{ $student->address ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">Informasi Akademik</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Status</label>
                                <p class="mb-0">
                                    <span class="badge bg-{{ $student->status == 'siswa' ? 'success' : 'warning' }}">
                                        {{ $student->status == 'siswa' ? 'Siswa Aktif' : 'Calon Siswa' }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Tahun Akademik</label>
                                <p class="mb-0 fw-medium">{{ $student->academic_year ?? '-' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-muted">Kelas</label>
                                <p class="mb-0 fw-medium">
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
