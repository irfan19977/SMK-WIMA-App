@extends('layouts.master')

@section('title')
    Detail Ujian - {{ $exam->title }}
@endsection

@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-title')
    Detail Ujian - {{ $exam->title }}
@endsection

@section('body')
    <body data-sidebar="colored">
@endsection

@section('content')
    <div class="row">
        <!-- Sidebar -->
        <div class="col-xl-3">
            <!-- Exam Header Card -->
            <div class="card mb-3">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="avatar-xxl mx-auto bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="mdi mdi-file-document text-white" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                    <h4 class="mb-1">{{ $exam->title }}</h4>
                    <p class="text-muted mb-2">{{ $exam->subject->name ?? '-' }}</p>
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        @switch($exam->status)
                            @case('draft')
                                <span class="badge bg-secondary">Draft</span>
                                @break
                            @case('published')
                                <span class="badge bg-info">Published</span>
                                @break
                            @case('ongoing')
                                <span class="badge bg-warning">Ongoing</span>
                                @break
                            @case('completed')
                                <span class="badge bg-success">Completed</span>
                                @break
                            @default
                                <span class="badge bg-secondary">{{ $exam->status }}</span>
                        @endswitch
                    </div>
                    <p class="text-muted small mb-0">
                        <i class="mdi mdi-clock me-1"></i>
                        {{ $exam->duration_minutes }} menit
                    </p>
                    <p class="text-muted small mb-0">
                        <i class="mdi mdi-trophy me-1"></i>
                        Min. {{ $exam->passing_score }}%
                    </p>
                </div>
            </div>

            <!-- Quick Actions Card -->
            @can('exams.edit')
                <div class="card mb-3">
                    <div class="card-header bg-primary">
                        <h5 class="card-title mb-0 text-white">
                            <i class="mdi mdi-flash me-2"></i>Aksi Cepat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('exams.index') }}" class="btn btn-outline-primary">
                                <i class="mdi mdi-arrow-left me-1"></i> Kembali
                            </a>
                            @if(in_array($exam->status, ['draft', 'published']))
                                <button type="button" class="btn btn-outline-warning" onclick="editExam('{{ $exam->id }}')">
                                    <i class="mdi mdi-pencil me-1"></i> Edit Ujian
                                </button>
                            @endif
                            @if($exam->status == 'draft' && $exam->questions()->count() > 0)
                                <button type="button" class="btn btn-outline-success" onclick="publishExam('{{ $exam->id }}')">
                                    <i class="mdi mdi-publish me-1"></i> Publish
                                </button>
                            @endif
                            @if($exam->status == 'completed')
                                <button type="button" class="btn btn-outline-info" onclick="viewResults('{{ $exam->id }}')">
                                    <i class="mdi mdi-chart-bar me-1"></i> Lihat Hasil
                                </button>
                            @endif
                            @if(in_array($exam->status, ['draft', 'published']))
                                <button type="button" class="btn btn-outline-danger" onclick="deleteExam('{{ $exam->id }}', '{{ $exam->title }}')">
                                    <i class="mdi mdi-delete me-1"></i> Hapus
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endcan

            <!-- Statistics Card -->
            @if($exam->status == 'completed')
            <div class="card mb-3">
                <div class="card-header bg-success">
                    <h5 class="card-title mb-0 text-white">
                        <i class="mdi mdi-chart-pie me-2"></i>Statistik
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <h4 class="text-primary mb-1">{{ $exam->submissions()->count() }}</h4>
                        <p class="text-muted mb-0">Total Peserta</p>
                    </div>
                    <div class="row text-center">
                        <div class="col-6">
                            <h5 class="text-success mb-1">{{ $exam->submissions()->where('score', '>=', $exam->passing_score)->count() }}</h5>
                            <small class="text-muted">Lulus</small>
                        </div>
                        <div class="col-6">
                            <h5 class="text-danger mb-1">{{ $exam->submissions()->where('score', '<', $exam->passing_score)->count() }}</h5>
                            <small class="text-muted">Tidak Lulus</small>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <h5 class="text-info mb-1">{{ round($exam->submissions()->avg('score'), 1) }}%</h5>
                        <small class="text-muted">Rata-rata</small>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Main Content -->
        <div class="col-xl-9">
            <!-- Tabs Navigation -->
            <div class="card mb-3">
                <div class="card-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-pills nav-justified" role="tablist">
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link active" data-bs-toggle="tab" href="#info-tab" role="tab">
                                <span class="d-block d-sm-none"><i class="mdi mdi-information"></i></span>
                                <span class="d-none d-sm-block"><i class="mdi mdi-information me-2"></i>Informasi Ujian</span>
                            </a>
                        </li>
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-bs-toggle="tab" href="#questions-tab" role="tab">
                                <span class="d-block d-sm-none"><i class="mdi mdi-help-circle"></i></span>
                                <span class="d-none d-sm-block"><i class="mdi mdi-help-circle me-2"></i>Soal ({{ $exam->questions()->count() }})</span>
                            </a>
                        </li>
                        @if($exam->submissions()->count() > 0)
                        <li class="nav-item waves-effect waves-light">
                            <a class="nav-link" data-bs-toggle="tab" href="#results-tab" role="tab">
                                <span class="d-block d-sm-none"><i class="mdi mdi-chart-bar"></i></span>
                                <span class="d-none d-sm-block"><i class="mdi mdi-chart-bar me-2"></i>Hasil</span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Info Tab -->
                <div class="tab-pane active" id="info-tab" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="border rounded p-3 mb-3">
                                        <h6 class="text-muted mb-3">Informasi Umum</h6>
                                        <div class="mb-2">
                                            <strong>Mata Pelajaran:</strong> {{ $exam->subject->name ?? '-' }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>Kelas:</strong> {{ $exam->class->name ?? '-' }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>Pengajar:</strong> {{ $exam->teacher->name ?? '-' }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>Status:</strong> 
                                            @switch($exam->status)
                                                @case('draft')
                                                    <span class="badge bg-secondary">Draft</span>
                                                    @break
                                                @case('published')
                                                    <span class="badge bg-info">Published</span>
                                                    @break
                                                @case('ongoing')
                                                    <span class="badge bg-warning">Ongoing</span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge bg-success">Completed</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $exam->status }}</span>
                                            @endswitch
                                        </div>
                                        <div class="mb-2">
                                            <strong>Total Soal:</strong> {{ $exam->questions()->count() }} soal
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="border rounded p-3 mb-3">
                                        <h6 class="text-muted mb-3">Waktu & Durasi</h6>
                                        <div class="mb-2">
                                            <strong>Waktu Mulai:</strong> {{ $exam->start_time->format('d M Y H:i') }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>Waktu Selesai:</strong> {{ $exam->end_time->format('d M Y H:i') }}
                                        </div>
                                        <div class="mb-2">
                                            <strong>Durasi:</strong> {{ $exam->duration_minutes }} menit
                                        </div>
                                        <div class="mb-2">
                                            <strong>Nilai Kelulusan:</strong> {{ $exam->passing_score }}%
                                        </div>
                                        <div class="mb-2">
                                            <strong>Dibuat:</strong> {{ $exam->created_at->format('d M Y H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($exam->description)
                            <div class="mb-4">
                                <h6 class="text-muted mb-2">Deskripsi</h6>
                                <div class="border rounded p-3">
                                    <p class="mb-0">{{ $exam->description }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Questions Tab -->
                <div class="tab-pane" id="questions-tab" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <!-- Header -->
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h4 class="mb-1 text-dark fw-bold">Soal Ujian</h4>
                                    <p class="text-dark mb-0 fw-semibold">Total: {{ $exam->questions()->count() }} soal</p>
                                </div>
                                @can('exams.create1')
                                    @if(in_array($exam->status, ['draft', 'published']))
                                        <button type="button" class="btn btn-primary" id="btn-add-question">
                                            <i class="mdi mdi-plus me-1"></i> Tambah Soal
                                        </button>
                                    @endif
                                @endcan
                            </div>

                            <!-- Questions Container -->
                            <div id="questions-container">
                                @forelse($exam->questions as $question)
                                    <div class="border rounded p-3 mb-3 question-item" data-id="{{ $question->id }}">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge bg-primary me-2">{{ $loop->iteration }}</span>
                                                    <span class="badge bg-secondary me-2">
                                                        @switch($question->question_type)
                                                            @case('multiple_choice')
                                                                Pilihan Ganda
                                                                @break
                                                            @case('multiple_choice_complex')
                                                                Pilihan Ganda Kompleks
                                                                @break
                                                            @case('true_false')
                                                                Benar/Salah
                                                                @break
                                                            @case('essay')
                                                                Essay
                                                                @break
                                                            @default
                                                                {{ $question->question_type }}
                                                        @endswitch
                                                    </span>
                                                    <span class="text-dark fw-semibold">({{ $question->points }} poin)</span>
                                                </div>
                                                <p class="mb-2 text-dark fw-medium">{{ $question->question_text }}</p>
                                                
                                                <!-- Display Media -->
                                                @if($question->media_path && $question->media_type)
                                                    <div class="mb-3">
                                                        @switch($question->media_type)
                                                            @case('image')
                                                                <img src="{{ asset('storage/' . $question->media_path) }}" 
                                                                     class="img-fluid rounded" 
                                                                     style="max-height: 300px;"
                                                                     alt="Media">
                                                                @break
                                                            @case('video')
                                                                <video controls class="img-fluid rounded" style="max-height: 300px;">
                                                                    <source src="{{ asset('storage/' . $question->media_path) }}" type="video/mp4">
                                                                    Browser Anda tidak mendukung video tag.
                                                                </video>
                                                                @break
                                                            @case('audio')
                                                                <audio controls class="w-100">
                                                                    <source src="{{ asset('storage/' . $question->media_path) }}" type="audio/mpeg">
                                                                    Browser Anda tidak mendukung audio tag.
                                                                </audio>
                                                                @break
                                                        @endswitch
                                                        
                                                        @if($question->media_caption)
                                                            <p class="text-muted small mt-2">
                                                                <i class="mdi mdi-image"></i> {{ $question->media_caption }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                @endif
                                                
                                                @if($question->question_type == 'multiple_choice')
                                                    <div class="ms-3">
                                                        @foreach($question->options->sortBy('option_label') as $option)
                                                            <div class="form-check mb-1">
                                                                <input class="form-check-input" type="radio" 
                                                                    name="question_{{ $question->id }}" 
                                                                    value="{{ $option->id }}" 
                                                                    {{ $option->is_correct ? 'checked' : '' }}
                                                                    onclick="return false;">
                                                                <label class="form-check-label text-dark fw-medium">
                                                                    <span class="badge bg-secondary me-2">{{ $option->option_label }}</span>
                                                                    {{ $option->option_text }}
                                                                    @if($option->is_correct)
                                                                        <span class="badge bg-success ms-2">Benar</span>
                                                                    @endif
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @elseif($question->question_type == 'multiple_choice_complex')
                                                    <div class="ms-3">
                                                        <div class="alert alert-info mb-2">
                                                            <small class="mb-0">
                                                                <i class="mdi mdi-information me-1"></i>
                                                                <strong>Pilihan Ganda Kompleks:</strong> 
                                                                {{ $question->options->where('is_correct', true)->count() }} jawaban benar
                                                            </small>
                                                        </div>
                                                        @foreach($question->options->sortBy('option_label') as $option)
                                                            <div class="form-check mb-1">
                                                                <input class="form-check-input" type="checkbox" 
                                                                    name="question_{{ $question->id }}[]" 
                                                                    value="{{ $option->id }}" 
                                                                    {{ $option->is_correct ? 'checked' : '' }}
                                                                    onclick="return false;">
                                                                <label class="form-check-label text-dark fw-medium">
                                                                    <span class="badge bg-secondary me-2">{{ $option->option_label }}</span>
                                                                    {{ $option->option_text }}
                                                                    @if($option->is_correct)
                                                                        <span class="badge bg-success ms-2">Benar</span>
                                                                    @endif
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @elseif($question->question_type == 'true_false')
                                                    <div class="ms-3">
                                                        <div class="alert alert-info mb-3">
                                                            <small class="mb-0">
                                                                <i class="mdi mdi-information me-1"></i>
                                                                <strong>Benar/Salah:</strong> 
                                                                Tentukan apakah setiap pernyataan Benar atau Salah
                                                            </small>
                                                        </div>
                                                        
                                                        @foreach($question->options->sortBy('option_label') as $index => $option)
                                                            <div class="card mb-3 border-light">
                                                                <div class="card-body p-3">
                                                                    <div class="d-flex align-items-start">
                                                                        <div class="me-3">
                                                                            <span class="badge bg-primary rounded-pill">{{ $index + 1 }}</span>
                                                                        </div>
                                                                        <div class="flex-grow-1">
                                                                            <h6 class="mb-2 fw-medium">{{ $option->option_text }}</h6>
                                                                            <div class="d-flex gap-3">
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input" type="radio" 
                                                                                        name="question_{{ $question->id }}_{{ $option->option_label }}" 
                                                                                        value="true"
                                                                                        {{ $option->is_correct ? 'checked' : '' }}
                                                                                        onclick="return false;">
                                                                                    <label class="form-check-label">
                                                                                        <span class="badge {{ $option->is_correct ? 'bg-success' : 'bg-light text-dark' }} me-1">Benar</span>
                                                                                        @if($option->is_correct)
                                                                                            <i class="mdi mdi-check-circle text-success"></i>
                                                                                        @endif
                                                                                    </label>
                                                                                </input>
                                                                                <div class="form-check">
                                                                                    <input class="form-check-input" type="radio" 
                                                                                        name="question_{{ $question->id }}_{{ $option->option_label }}" 
                                                                                        value="false"
                                                                                        {{ !$option->is_correct ? 'checked' : '' }}
                                                                                        onclick="return false;">
                                                                                    <label class="form-check-label">
                                                                                        <span class="badge {{ !$option->is_correct ? 'bg-success' : 'bg-light text-dark' }} me-1">Salah</span>
                                                                                        @if(!$option->is_correct)
                                                                                            <i class="mdi mdi-check-circle text-success"></i>
                                                                                        @endif
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                            <div class="mt-2">
                                                                                <small class="text-muted">
                                                    Jawaban yang benar: 
                                                    <span class="badge {{ $option->is_correct ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $option->is_correct ? 'Benar' : 'Salah' }}
                                                    </span>
                                                </small>
                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @elseif($question->question_type == 'essay')
                                                    <div class="ms-3">
                                                        <textarea class="form-control" rows="3" disabled placeholder="Jawaban essay akan ditampilkan di sini"></textarea>
                                                    </div>
                                                @endif

                                                @if($question->explanation)
                                                    <div class="mt-2 p-2 bg-light rounded">
                                                        <small class="text-dark fw-medium">
                                                            <strong>Penjelasan:</strong> {{ $question->explanation }}
                                                        </small>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            @can('exams.edit')
                                                @if(in_array($exam->status, ['draft', 'published']))
                                                    <div class="dropdown ms-3">
                                                        <a class="dropdown-toggle font-size-16" href="#" role="button"
                                                            data-bs-toggle="dropdown" aria-haspopup="true">
                                                            <i class="mdi mdi-dots-vertical font-size-18"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item edit-question" href="#"
                                                            data-id="{{ $question->id }}">
                                                                <i class="mdi mdi-pencil me-1"></i> Edit
                                                            </a>
                                                            <a class="dropdown-item text-danger delete-question" href="#"
                                                            data-id="{{ $question->id }}">
                                                                <i class="mdi mdi-delete me-1"></i> Hapus
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endcan
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-5 border rounded">
                                        <i class="mdi mdi-help-circle display-4 text-muted mb-3"></i>
                                        <h5 class="text-muted">Belum ada soal</h5>
                                        @can('exams.create')
                                            <p class="text-muted">Tambahkan soal untuk memulai ujian</p>
                                            @if(in_array($exam->status, ['draft', 'published']))
                                                <button type="button" class="btn btn-primary" id="btn-add-question-empty">
                                                    <i class="mdi mdi-plus me-1"></i> Tambah Soal Pertama
                                                </button>
                                            @endif
                                        @endcan
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Results Tab -->
                @if($exam->submissions()->count() > 0)
                <div class="tab-pane" id="results-tab" role="tabpanel">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div>
                                    <h4 class="mb-1">Hasil Ujian</h4>
                                    <p class="text-muted mb-0">Statistik dan detail hasil siswa</p>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-success" onclick="exportResults()">
                                        <i class="mdi mdi-file-excel me-1"></i> Export
                                    </button>
                                </div>
                            </div>

                            <!-- Statistics Summary -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h4 class="text-primary mb-1">{{ $exam->submissions()->count() }}</h4>
                                        <p class="text-muted mb-0">Total Peserta</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h4 class="text-success mb-1">{{ $exam->submissions()->where('score', '>=', $exam->passing_score)->count() }}</h4>
                                        <p class="text-muted mb-0">Lulus</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h4 class="text-danger mb-1">{{ $exam->submissions()->where('score', '<', $exam->passing_score)->count() }}</h4>
                                        <p class="text-muted mb-0">Tidak Lulus</p>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="text-center p-3 bg-light rounded">
                                        <h4 class="text-info mb-1">{{ round($exam->submissions()->avg('score'), 1) }}%</h4>
                                        <p class="text-muted mb-0">Rata-rata</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Results Table -->
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Siswa</th>
                                            <th>Nilai</th>
                                            <th>Status</th>
                                            <th>Waktu</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($exam->submissions as $index => $submission)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $submission->student->name ?? '-' }}</td>
                                                <td>{{ $submission->score }}%</td>
                                                <td>
                                                    @if($submission->score >= $exam->passing_score)
                                                        <span class="badge bg-success">Lulus</span>
                                                    @else
                                                        <span class="badge bg-danger">Tidak Lulus</span>
                                                    @endif
                                                </td>
                                                <td>{{ $submission->getDuration() ?? '-' }} menit</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="viewSubmissionDetail('{{ $submission->id }}')">
                                                        <i class="mdi mdi-eye"></i> Detail
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-5">
                                                    <i class="mdi mdi-clipboard-text display-4 text-muted mb-3"></i>
                                                    <h5 class="text-muted">Belum ada hasil</h5>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Exam Modal -->
    <div class="modal fade" id="examModal" tabindex="-1" aria-labelledby="examModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="examModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Question Modal -->
    <div class="modal fade" id="questionModal" tabindex="-1" aria-labelledby="questionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="questionModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Form will be loaded here -->
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
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize variables
        let currentExamId = '{{ $exam->id }}';

        // Edit exam functionality
        function openEditModal() {
            document.getElementById('examModalLabel').textContent = 'Edit Ujian';
            document.getElementById('examForm').action = '/exams/' + currentExamId + '?redirect_to=show';
            
            // Add method override for PUT
            let methodInput = document.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'PUT';
                document.getElementById('examForm').appendChild(methodInput);
            } else {
                methodInput.value = 'PUT';
            }
            
            // Add redirect parameter
            let redirectInput = document.querySelector('input[name="redirect_to"]');
            if (!redirectInput) {
                redirectInput = document.createElement('input');
                redirectInput.type = 'hidden';
                redirectInput.name = 'redirect_to';
                redirectInput.value = 'show';
                document.getElementById('examForm').appendChild(redirectInput);
            }
            
            // Fill form with current exam data
            document.getElementById('title').value = '{{ $exam->title }}';
            document.getElementById('description').value = '{{ $exam->description }}';
            document.getElementById('duration_minutes').value = '{{ $exam->duration_minutes }}';
            document.getElementById('passing_score').value = '{{ $exam->passing_score }}';
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('examModal'));
            modal.show();
        }

        // Add click event to edit button
        const editExamBtn = document.querySelector('a[href="{{ route('exams.edit', $exam->id) }}"]');
        if (editExamBtn) {
            editExamBtn.addEventListener('click', function(e) {
                e.preventDefault();
                openEditModal();
            });
        }
    });

    // Action functions
    function exportResults() {
        Swal.fire({
            icon: 'info',
            title: 'Export Hasil Ujian',
            text: 'Fitur export hasil ujian akan segera tersedia',
            confirmButtonColor: '#3085d6'
        });
    }

    function viewSubmissionDetail(submissionId) {
        // TODO: Implement view submission detail
        Swal.fire({
            icon: 'info',
            title: 'Fitur dalam Pengembangan',
            text: 'Detail submission akan segera tersedia'
        });
    }

    // Question Management Functions
    function openQuestionModal(questionId = null) {
        const url = questionId ? `/exams/questions/${questionId}/edit` : `/exams/questions/create?exam_id={{ $exam->id }}`;
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('questionModalLabel').textContent = data.title;
                document.querySelector('#questionModal .modal-body').innerHTML = data.html;
                
                const modal = new bootstrap.Modal(document.getElementById('questionModal'));
                modal.show();
                
                // Add event listener to clean up backdrop when modal is hidden
                const modalElement = document.getElementById('questionModal');
                modalElement.addEventListener('hidden.bs.modal', function () {
                    // Remove any remaining backdrop
                    const backdrops = document.querySelectorAll('.modal-backdrop');
                    backdrops.forEach(backdrop => backdrop.remove());
                    
                    // Reset body styles
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                    document.body.classList.remove('modal-open');
                }, { once: true }); // Use once: true to avoid multiple listeners
                
                // Initialize form handlers
                initializeQuestionForm();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message || 'Gagal memuat form'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Gagal memuat form'
            });
        });
    }

    function initializeQuestionForm() {
        // Add event listeners for question form
        const questionType = document.getElementById('question_type');
        if (questionType) {
            questionType.addEventListener('change', function() {
                updateQuestionForm(this.value);
            });
            updateQuestionForm(questionType.value);
        }

        // Add event listener for add option button
        const addOptionBtn = document.getElementById('add-option');
        if (addOptionBtn) {
            addOptionBtn.addEventListener('click', addOption);
        }

        // Add event listener for add complex option button
        const addComplexOptionBtn = document.getElementById('add-complex-option');
        if (addComplexOptionBtn) {
            addComplexOptionBtn.addEventListener('click', addComplexOption);
        }

        // Add event listener for add statement button
        const addStatementBtn = document.getElementById('add-statement');
        if (addStatementBtn) {
            addStatementBtn.addEventListener('click', addStatement);
        }

        // Add event listeners for edit/delete buttons
        document.querySelectorAll('.edit-question').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                openQuestionModal(this.dataset.id);
            });
        });

        document.querySelectorAll('.delete-question').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                deleteQuestion(this.dataset.id);
            });
        });
    }

    function updateQuestionForm(type) {
        const optionsContainer = document.getElementById('options-container');
        const complexOptionsContainer = document.getElementById('complex-options-container');
        const trueFalseContainer = document.getElementById('true-false-container');
        
        // Hide all containers first
        optionsContainer.style.display = 'none';
        complexOptionsContainer.style.display = 'none';
        trueFalseContainer.style.display = 'none';
        
        // Show relevant container based on type
        if (type === 'multiple_choice') {
            optionsContainer.style.display = 'block';
        } else if (type === 'multiple_choice_complex') {
            complexOptionsContainer.style.display = 'block';
        } else if (type === 'true_false') {
            trueFalseContainer.style.display = 'block';
        }
    }

    function addOption() {
        const container = document.getElementById('options-container');
        const optionsList = document.getElementById('options-list');
        const optionCount = optionsList.children.length;
        
        const optionDiv = document.createElement('div');
        optionDiv.className = 'row mb-2 option-item';
        optionDiv.innerHTML = `
            <div class="col-md-1">
                <label class="form-label">Label</label>
                <input type="text" class="form-control" name="option_labels[]" value="${String.fromCharCode(65 + optionCount)}" readonly>
            </div>
            <div class="col-md-8">
                <label class="form-label">Teks Opsi</label>
                <input type="text" class="form-control" name="option_texts[]" placeholder="Masukkan teks opsi">
            </div>
            <div class="col-md-2">
                <label class="form-label">Benar?</label>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="radio" name="is_correct" value="${optionCount}">
                    <label class="form-check-label">Ya</label>
                </div>
            </div>
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label><br>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeOption(this)">
                    <i class="mdi mdi-delete"></i>
                </button>
            </div>
        `;
        
        optionsList.appendChild(optionDiv);
    }

    function removeOption(button) {
        button.closest('.option-item').remove();
    }

    function addComplexOption() {
        const container = document.getElementById('complex-options-container');
        const optionsList = document.getElementById('complex-options-list');
        const optionCount = optionsList.children.length;
        
        const optionDiv = document.createElement('div');
        optionDiv.className = 'row mb-2 complex-option-item';
        optionDiv.innerHTML = `
            <div class="col-md-1">
                <label class="form-label">Label</label>
                <input type="text" class="form-control" name="option_labels[]" value="${String.fromCharCode(65 + optionCount)}" readonly>
            </div>
            <div class="col-md-8">
                <label class="form-label">Teks Opsi</label>
                <input type="text" class="form-control" name="option_texts[]" placeholder="Masukkan teks opsi">
            </div>
            <div class="col-md-2">
                <label class="form-label">Benar?</label>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox" name="is_correct_multiple[]" value="${optionCount}">
                    <label class="form-check-label">Ya</label>
                </div>
            </div>
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label><br>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeComplexOption(this)">
                    <i class="mdi mdi-delete"></i>
                </button>
            </div>
        `;
        
        optionsList.appendChild(optionDiv);
    }

    function removeComplexOption(button) {
        button.closest('.complex-option-item').remove();
    }

    function addStatement() {
        const container = document.getElementById('true-false-container');
        const statementsList = document.getElementById('statements-list');
        
        if (!container || !statementsList) {
            console.error('Container or statementsList not found');
            return;
        }
        
        const statementCount = statementsList.children.length;
        
        const statementDiv = document.createElement('div');
        statementDiv.className = 'row mb-2 statement-item';
        statementDiv.innerHTML = `
            <div class="col-md-1">
                <label class="form-label">No.</label>
                <input type="text" class="form-control" name="statement_numbers[]" value="${statementCount + 1}" readonly>
            </div>
            <div class="col-md-8">
                <label class="form-label">Pernyataan</label>
                <input type="text" class="form-control" name="statement_texts[]" placeholder="Masukkan pernyataan">
            </div>
            <div class="col-md-2">
                <label class="form-label">Jawaban</label>
                <div class="form-check mt-2">
                    <input class="form-check-input" type="radio" name="statement_${statementCount}_answer" value="true">
                    <label class="form-check-label">Benar</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="statement_${statementCount}_answer" value="false">
                    <label class="form-check-label">Salah</label>
                </div>
            </div>
            <div class="col-md-1">
                <label class="form-label">&nbsp;</label><br>
                <button type="button" class="btn btn-danger btn-sm" onclick="removeStatement(this)">
                    <i class="mdi mdi-delete"></i>
                </button>
            </div>
        `;
        
        statementsList.appendChild(statementDiv);
    }

    function removeStatement(button) {
        button.closest('.statement-item').remove();
        renumberStatements();
    }

    function renumberStatements() {
        const statementsList = document.getElementById('statements-list');
        const statements = statementsList.querySelectorAll('.statement-item');
        
        statements.forEach((statement, index) => {
            // Update number
            const numberInput = statement.querySelector('input[name="statement_numbers[]"]');
            if (numberInput) {
                numberInput.value = index + 1;
            }
            
            // Update radio button names
            const trueRadio = statement.querySelector('input[value="true"]');
            const falseRadio = statement.querySelector('input[value="false"]');
            if (trueRadio && falseRadio) {
                const newName = `statement_${index}_answer`;
                trueRadio.name = newName;
                falseRadio.name = newName;
            }
        });
    }

    function deleteQuestion(questionId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Soal ini akan dihapus secara permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/exams/questions/${questionId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Gagal menghapus soal'
                    });
                });
            }
        });
    }

    // Exam Management Functions
    function editExam(id) {
        fetch(`/exams/${id}/edit`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('examModalLabel').textContent = data.title;
                document.querySelector('#examModal .modal-body').innerHTML = data.html;
                
                const modal = new bootstrap.Modal(document.getElementById('examModal'));
                modal.show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Gagal memuat form'
            });
        });
    }

    function deleteExam(id, title) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: `Ujian "${title}" akan dihapus secara permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/exams/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        setTimeout(() => {
                            window.location.href = '/exams';
                        }, 1500);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Gagal menghapus ujian'
                    });
                });
            }
        });
    }

    function publishExam(id) {
        Swal.fire({
            title: 'Publish Ujian?',
            text: 'Ujian akan tersedia untuk siswa setelah dipublish',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, publish!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/exams/${id}/publish`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Gagal mempublish ujian'
                    });
                });
            }
        });
    }

    function viewResults(id) {
        // Switch to results tab
        const resultsTab = document.querySelector('a[href="#results-tab"]');
        if (resultsTab) {
            const tab = new bootstrap.Tab(resultsTab);
            tab.show();
        }
    }

    function viewSubmissionDetail(submissionId) {
        const examId = '{{ $exam->id }}';
        window.location.href = `/exams/${examId}/submissions/${submissionId}`;
    }

    // Event Listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Check for tab parameter in URL and switch to questions tab
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        if (tab === 'questions') {
            const questionsTab = document.querySelector('a[href="#questions-tab"]');
            if (questionsTab) {
                const tabInstance = new bootstrap.Tab(questionsTab);
                tabInstance.show();
            }
        }
        
        // Check for hash in URL and switch to corresponding tab
        const hash = window.location.hash;
        if (hash === '#results-tab') {
            const resultsTab = document.querySelector('a[href="#results-tab"]');
            if (resultsTab) {
                const tabInstance = new bootstrap.Tab(resultsTab);
                tabInstance.show();
            }
        }
        
        // Add question button listeners
        const addQuestionBtn = document.getElementById('btn-add-question');
        if (addQuestionBtn) {
            addQuestionBtn.addEventListener('click', function() {
                openQuestionModal();
            });
        }

        const addQuestionEmptyBtn = document.getElementById('btn-add-question-empty');
        if (addQuestionEmptyBtn) {
            addQuestionEmptyBtn.addEventListener('click', function() {
                openQuestionModal();
            });
        }

        // Initialize question form listeners
        initializeQuestionForm();
    });
</script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
