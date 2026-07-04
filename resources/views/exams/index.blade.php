@extends('layouts.master')
@section('title')
    Manajemen Ujian
@endsection

@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-title')
    Manajemen Ujian
@endsection

@section('body')
    <body data-sidebar="colored">
@endsection

@section('content')
    
    <div class="row">
        <div class="col-xl-3">
            <div class="card filemanager-sidebar">
                <div class="card-body">
                    <div class="d-flex flex-column h-100">
                        <div>
                            @can('exams.create')
                                <div class="mb-3">
                                    <button class="btn btn-primary w-100" type="button" id="btn-create">
                                        <i class="mdi mdi-plus me-1"></i> Buat Ujian Baru
                                    </button>
                                </div>
                            @endcan
                            <ul class="list-unstyled categories-list">
                                <li>
                                    <a href="javascript: void(0);" class="text-body fw-medium py-1 d-flex align-items-center active" id="filter-all">
                                        <i class="mdi mdi-clipboard-list font-size-20 text-primary me-2"></i> 
                                        <span class="me-auto">Semua Ujian</span>
                                        <span class="badge bg-primary rounded-pill">{{ $exams->total() }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript: void(0);" class="text-body d-flex align-items-center" id="filter-draft">
                                        <i class="mdi mdi-file-document font-size-20 text-secondary me-2"></i> 
                                        <span class="me-auto">Draft</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript: void(0);" class="text-body d-flex align-items-center" id="filter-published">
                                        <i class="mdi mdi-publish font-size-20 text-info me-2"></i> 
                                        <span class="me-auto">Published</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript: void(0);" class="text-body d-flex align-items-center" id="filter-ongoing">
                                        <i class="mdi mdi-play-circle font-size-20 text-warning me-2"></i> 
                                        <span class="me-auto">Ongoing</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript: void(0);" class="text-body d-flex align-items-center" id="filter-completed">
                                        <i class="mdi mdi-check-circle font-size-20 text-success me-2"></i> 
                                        <span class="me-auto">Completed</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <h5 class="font-size-16 mb-0 mt-4">Statistik Ujian</h5>

                        <div class="mt-2">
                            <div class="px-2 py-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm align-self-center me-3">
                                        <div class="avatar-title rounded bg-primary font-size-24">
                                            <i class="mdi mdi-clipboard-list"></i>
                                        </div>
                                    </div>
                                    <div class="overflow-hidden me-auto">
                                        <h5 class="font-size-15 text-truncate mb-1">Total Ujian</h5>
                                        <p class="text-muted text-truncate mb-0">{{ $exams->total() }} ujian</p>
                                    </div>
                                </div>
                            </div>

                            <div class="px-2 py-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm align-self-center me-3">
                                        <div class="avatar-title rounded bg-success font-size-24">
                                            <i class="mdi mdi-help-circle"></i>
                                        </div>
                                    </div>
                                    <div class="overflow-hidden me-auto">
                                        <h5 class="font-size-15 text-truncate mb-1">Total Soal</h5>
                                        <p class="text-muted text-truncate mb-0">{{ $exams->sum('total_questions') }} soal</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border text-center rounded p-3 mt-4">
                            <div class="">
                                <i class="mdi mdi-clipboard-text display-4 text-primary mb-3"></i>
                            </div>
                            <h5>Manajemen Ujian</h5>
                            <p class="pt-1">Buat dan kelola ujian online untuk siswa</p>
                            @can('exams.create')
                                <div class="text-center pt-2">
                                    <button type="button" class="btn btn-primary w-100" id="btn-create-quick">
                                        <i class="mdi mdi-plus ms-1"></i> Buat Ujian Baru
                                    </button>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9">
            <div class="card">
                <div class="card-body">
                    <h5 class="font-size-16 mb-0 mt-4">Daftar Ujian</h5>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex gap-2">
                            <div class="input-group" style="width: 300px;">
                                <input type="text" class="form-control" placeholder="Cari ujian..." id="search-input">
                                <button class="btn btn-primary" type="button" id="search-button">
                                    <i class="mdi mdi-magnify"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4" id="exams-container">
                        @forelse ($exams as $exam)
                        <div class="col-xl-4 col-sm-6 exam-card-item mb-4">
                            <div class="border p-3 rounded mb-3">
                                <div class="">
                                    @can('exams.edit')
                                        <div class="dropdown float-end">
                                            <a class="dropdown-toggle font-size-16" href="#" role="button"
                                                data-bs-toggle="dropdown" aria-haspopup="true">
                                                <i class="mdi mdi-dots-vertical font-size-18"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('exams.show', $exam->id) }}">
                                                    <i class="mdi mdi-eye me-1"></i> Lihat Detail
                                                </a>
                                                <a class="dropdown-item edit-exam" href="#"
                                                data-id="{{ $exam->id }}">
                                                    <i class="mdi mdi-pencil me-1"></i> Edit
                                                </a>
                                                @if($exam->status == 'draft' && $exam->questions()->count() > 0)
                                                    <a class="dropdown-item publish-exam" href="#"
                                                    data-id="{{ $exam->id }}">
                                                        <i class="mdi mdi-publish me-1"></i> Publish
                                                    </a>
                                                @endif
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger delete-exam" href="#"
                                                data-id="{{ $exam->id }}"
                                                data-title="{{ $exam->title }}">
                                                    <i class="mdi mdi-delete me-1"></i> Hapus
                                                </a>
                                            </div>
                                        </div>
                                    @endcan
                                    <div class="d-flex align-items-center overflow-hidden">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-sm align-self-center">
                                                <div class="avatar-title rounded bg-primary font-size-24">
                                                    <i class="mdi mdi-clipboard-list"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="font-size-15 mb-1 text-truncate">{{ $exam->title }}</h5>
                                            <a href="{{ route('exams.show', $exam->id) }}" class="font-size-14 text-muted text-truncate">
                                                <u>Lihat Detail</u>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-2">
                                        <div class="d-flex justify-content-between">
                                            <p class="text-muted font-size-13 mb-1">{{ $exam->subject->name ?? '-' }}</p>
                                            <p class="text-muted font-size-13 mb-1 text-truncate">{{ $exam->class->name ?? '-' }}</p>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-muted font-size-13 mb-1">{{ $exam->duration_minutes }} menit</p>
                                            <p class="text-muted font-size-13 mb-1 text-truncate">{{ $exam->total_questions }} soal</p>
                                        </div>
                                        <div class="mt-2">
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="mdi mdi-clipboard-list display-1 text-muted"></i>
                                </div>
                                <h5 class="text-muted">Belum ada ujian</h5>
                                <p class="text-muted">Klik tombol "Buat Ujian Baru" untuk mulai membuat ujian</p>
                                <button type="button" class="btn btn-primary" id="btn-create-empty">
                                    <i class="mdi mdi-plus"></i> Buat Ujian Baru
                                </button>
                            </div>
                        </div>
                        @endforelse
                    </div>

                    @if($exams->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $exams->links() }}
                    </div>
                    @endif
                </div>
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
@endsection

@section('scripts')
    <!-- Sweet Alerts js -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Create exam
        const btnCreate = document.getElementById('btn-create');
        if (btnCreate) {
            btnCreate.addEventListener('click', function() {
                openExamModal();
            });
        }

        const btnCreateQuick = document.getElementById('btn-create-quick');
        if (btnCreateQuick) {
            btnCreateQuick.addEventListener('click', function() {
                openExamModal();
            });
        }

        const btnCreateEmpty = document.getElementById('btn-create-empty');
        if (btnCreateEmpty) {
            btnCreateEmpty.addEventListener('click', function() {
                openExamModal();
            });
        }

        // Edit exam
        document.querySelectorAll('.edit-exam').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                editExam(id);
            });
        });

        // Delete exam
        document.querySelectorAll('.delete-exam').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                const title = this.getAttribute('data-title');
                deleteExam(id, title);
            });
        });

        // Publish exam
        document.querySelectorAll('.publish-exam').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                publishExam(id);
            });
        });

        // Filter by status
        document.getElementById('filter-all').addEventListener('click', function() {
            filterExams('');
        });

        document.getElementById('filter-draft').addEventListener('click', function() {
            filterExams('draft');
        });

        document.getElementById('filter-published').addEventListener('click', function() {
            filterExams('published');
        });

        document.getElementById('filter-ongoing').addEventListener('click', function() {
            filterExams('ongoing');
        });

        document.getElementById('filter-completed').addEventListener('click', function() {
            filterExams('completed');
        });

        // Search functionality
        let searchTimeout;
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const query = this.value;
                    const url = new URL(window.location);
                    if (query) {
                        url.searchParams.set('search', query);
                    } else {
                        url.searchParams.delete('search');
                    }
                    window.location.href = url.toString();
                }, 500);
            });
        }

        // Update active filter
        updateActiveFilter();
    });

    function updateActiveFilter() {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status') || '';
        
        document.querySelectorAll('.categories-list a').forEach(function(link) {
            link.classList.remove('active');
        });

        if (status === '') {
            document.getElementById('filter-all').classList.add('active');
        } else {
            document.getElementById('filter-' + status).classList.add('active');
        }
    }

    function filterExams(status) {
        const url = new URL(window.location);
        if (status) {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }
        window.location.href = url.toString();
    }

    function openExamModal() {
        fetch('/exams/create', {
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
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Gagal memuat form'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Gagal memuat form'
            });
        });
    }

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
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: data.message
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Gagal memuat form'
            });
        });
    }

    function deleteExam(id, title) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: `Ujian "${title}" akan dihapus permanen!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
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
    
    // Handle subject dropdown for exam form
    document.addEventListener('change', function(e) {
        if (e.target && e.target.id === 'class_id') {
            loadSubjectsForExam(e.target.value);
        }
        
        if (e.target && e.target.id === 'subject_id') {
            const classId = document.getElementById('class_id').value;
            if (classId && e.target.value) {
                loadTeacherForExam(classId, e.target.value);
            }
        }
    });
    
    function loadSubjectsForExam(classId) {
        const subjectSelect = document.getElementById('subject_id');
        if (!subjectSelect) {
            return;
        }
        
        subjectSelect.innerHTML = '<option value="">Loading...</option>';
        subjectSelect.disabled = false;
        
        const url = `/test-subjects/${classId}?t=${Date.now()}`;
        
        fetch(url, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            subjectSelect.innerHTML = '';
            subjectSelect.disabled = false;
            
            if (data.success && data.subjects && data.subjects.length > 0) {
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Pilih Mata Pelajaran';
                subjectSelect.appendChild(defaultOption);
                
                data.subjects.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject.id;
                    option.textContent = subject.name;
                    subjectSelect.appendChild(option);
                });
            } else {
                const noOption = document.createElement('option');
                noOption.value = '';
                noOption.textContent = 'Tidak ada mata pelajaran';
                subjectSelect.appendChild(noOption);
            }
        })
        .catch(error => {
            subjectSelect.innerHTML = '<option value="">Error loading subjects</option>';
        });
    }
    
    function loadTeacherForExam(classId, subjectId) {
        const teacherNameInput = document.getElementById('teacher_name');
        const teacherIdInput = document.getElementById('teacher_id');
        
        if (!teacherNameInput || !teacherIdInput) {
            return;
        }
        
        teacherNameInput.value = 'Loading...';
        
        const url = `/test-teacher/${classId}/${subjectId}?t=${Date.now()}`;
        
        fetch(url, {
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.teacher) {
                teacherNameInput.value = data.teacher.name || 'Tidak ada nama';
                teacherIdInput.value = data.teacher.id || '';
            } else {
                teacherNameInput.value = 'Tidak ada guru';
                teacherIdInput.value = '';
            }
        })
        .catch(error => {
            teacherNameInput.value = 'Error loading teacher';
            teacherIdInput.value = '';
        });
    }
    
    </script>
@endsection