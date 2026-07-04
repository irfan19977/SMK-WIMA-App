@extends('layouts.master')

@section('title')
    Hasil Ujian - {{ $exam->title }}
@endsection

@section('page-title')
    Hasil Ujian
@endsection

@section('body')
    <body data-sidebar="colored">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Hasil Ujian</h4>
                    <p class="text-muted mb-0">{{ $exam->title }}</p>
                </div>
                <div class="card-body">
                    <!-- Score Summary -->
                    <div class="row mb-4">
                        @if($exam->show_results)
                        <div class="col-md-4">
                            <div class="text-center p-4 bg-light rounded">
                                <h2 class="text-primary mb-1">{{ $submission->score }}%</h2>
                                <p class="text-muted mb-0">Nilai Akhir</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($exam->show_status)
                        <div class="col-md-4">
                            <div class="text-center p-4 bg-light rounded">
                                <h4 class="text-success mb-1">{{ $submission->score >= $exam->passing_score ? 'LULUS' : 'TIDAK LULUS' }}</h4>
                                <p class="text-muted mb-0">Status Kelulusan</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($exam->show_results || $exam->show_status)
                        <div class="col-md-4">
                            <div class="text-center p-4 bg-light rounded">
                                <h4 class="text-info mb-1">{{ $submission->getDuration() ?? '-' }} menit</h4>
                                <p class="text-muted mb-0">Waktu Pengerjaan</p>
                            </div>
                        </div>
                        @else
                        <div class="col-md-12">
                            <div class="text-center p-4 bg-light rounded">
                                <h4 class="text-info mb-1">{{ $submission->getDuration() ?? '-' }} menit</h4>
                                <p class="text-muted mb-0">Waktu Pengerjaan</p>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Exam Details -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6>Informasi Ujian</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td>Mata Pelajaran</td>
                                    <td>{{ $exam->subject->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td>Jumlah Soal</td>
                                    <td>{{ $exam->questions()->count() }} soal</td>
                                </tr>
                                <tr>
                                    <td>Durasi</td>
                                    <td>{{ $exam->duration_minutes }} menit</td>
                                </tr>
                                <tr>
                                    <td>Nilai Minimum</td>
                                    <td>{{ $exam->passing_score }}%</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Waktu</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td>Mulai</td>
                                    <td>{{ \Carbon\Carbon::parse($submission->started_at)->format('d M Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <td>Selesai</td>
                                    <td>{{ \Carbon\Carbon::parse($submission->finished_at)->format('d M Y H:i:s') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="text-center mb-4">
                        <a href="{{ route('student.exams.index') }}" class="btn btn-primary me-2">
                            <i class="mdi mdi-arrow-left me-1"></i> Kembali ke Daftar Ujian
                        </a>
                        @if($exam->show_review)
                        <a href="{{ route('student.exams.review', $exam->id) }}" class="btn btn-info">
                            <i class="mdi mdi-find-replace me-1"></i> Lihat Jawaban
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
