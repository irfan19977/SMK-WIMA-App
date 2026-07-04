@extends('layouts.master')

@section('title')
    Ujian Siswa
@endsection

@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-title')
    Ujian Online
@endsection

@section('body')
    <body data-sidebar="colored">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Daftar Ujian</h4>
                    <p class="text-muted mb-0">Semua ujian yang dipublish untuk kelas Anda</p>
                </div>
                <div class="card-body">
                    @forelse($exams as $exam)
                        <div class="border rounded p-4 mb-3">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-grow-1">
                                            <h5 class="mb-2">{{ $exam->title }}</h5>
                                            <p class="text-muted mb-2">{{ $exam->description ?? 'Tidak ada deskripsi' }}</p>
                                            
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        <i class="mdi mdi-book me-1"></i> {{ $exam->subject->name ?? '-' }}
                                                    </small>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        <i class="mdi mdi-clock me-1"></i> {{ $exam->duration_minutes }} menit
                                                    </small>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        <i class="mdi mdi-calendar me-1"></i> {{ \Carbon\Carbon::parse($exam->start_time)->format('d M Y H:i') }}
                                                    </small>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted">
                                                        <i class="mdi mdi-calendar-check me-1"></i> {{ \Carbon\Carbon::parse($exam->end_time)->format('d M Y H:i') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 text-end">
                                    @switch($exam->status_label)
                                        @case('available')
                                            <span class="badge bg-success mb-2">Tersedia</span>
                                            <br>
                                            <button type="button" class="btn btn-primary" onclick="confirmStartExam('{{ $exam->id }}', '{{ $exam->title }}')">
                                                <i class="mdi mdi-play me-1"></i> Kerjakan
                                            </button>
                                            @break
                                        @case('upcoming')
                                            <span class="badge bg-warning mb-2">Akan Dimulai</span>
                                            <br>
                                            <button class="btn btn-secondary" disabled>
                                                <i class="mdi mdi-clock me-1"></i> Belum Dimulai
                                            </button>
                                            @break
                                        @case('expired')
                                            <span class="badge bg-danger mb-2">Kadaluarsa</span>
                                            <br>
                                            <button class="btn btn-secondary" disabled>
                                                <i class="mdi mdi-clock me-1"></i> Kadaluarsa
                                            </button>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-info mb-2">Selesai</span>
                                            <br>
                                            <a href="{{ route('student.exams.result', $exam->id) }}" 
                                               class="btn btn-info">
                                                <i class="mdi mdi-eye me-1"></i> Lihat Hasil
                                            </a>
                                            @break
                                    @endswitch
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="mdi mdi-clipboard-text display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada ujian</h5>
                            <p class="text-muted">Belum ada ujian yang dipublish untuk kelas Anda</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Sweet Alerts js -->
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
        <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
    function confirmStartExam(examId, examTitle) {
        Swal.fire({
            title: 'Mulai Ujian?',
            html: `
                <p>Apakah Anda siap untuk memulai ujian:</p>
                <h5>${examTitle}</h5>
                <p class="text-muted small">Pastikan Anda sudah siap dan koneksi internet stabil.</p>
            `,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#7066e0',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Mulai!',
            cancelButtonText: 'Batal',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                // Redirect to exam page
                window.location.href = `/student/exams/${examId}/take`;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Loading is handled by preConfirm
                console.log('Starting exam...');
            }
        });
    }
    </script>
@endsection
