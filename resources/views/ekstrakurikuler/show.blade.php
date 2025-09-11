@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header dengan gradient -->
            <div class="header-section mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="text-dark mb-1">{{ $ekstrakurikulers->name }}</h2>
                        <p class="mb-0">
                            <i class="fas fa-building mr-2"></i>Ekstrakurikuler
                            <span class="badge badge-light">{{ $ekstrakurikulers->created_at->format('d M Y') }}</span>
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('asrama.index') }}" class="btn btn-light btn-shadow">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Total Siswa</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center" style="color: #667eea; size: 50rem;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <div class="numbers">
                                        <h4 class="card-title">{{ $students->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card card-success">
                        <div class="card-header">
                            <h4>Laki-laki</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-mars text-primary"></i>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <div class="numbers">
                                        <h4 class="card-title">{{ $students->where('gender', 'laki-laki')->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h4>Perempuan</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-venus text-pink"></i>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <div class="numbers">
                                        <h4 class="card-title">{{ $students->where('gender', 'perempuan')->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card card-info">
                        <div class="card-header">
                            <h4>Pembina</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-user-tie text-info"></i>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <div class="numbers">
                                        <h4 class="card-title">{{ $assignedStudents->unique('teacher_id')->count() }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Tabs -->
            <div class="card card-modern">
                <div class="card-header bg-white">
                    <ul class="nav nav-pills card-header-pills" id="mainTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="info-tab" data-toggle="pill" href="#info" role="tab">
                                <i class="fas fa-info-circle mr-2"></i>Informasi Ekstrakurikuler
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="students-tab" data-toggle="pill" href="#students" role="tab">
                                <i class="fas fa-users mr-2"></i>Daftar Siswa
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="nilai-tab" data-toggle="pill" href="#nilai" role="tab">
                                <i class="fas fa-star mr-2"></i>Nilai Asrama
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="mainTabsContent">
                        <!-- Info Tab -->
                        <div class="tab-pane fade show active" id="info" role="tabpanel">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-light text-dark">
                                            <h5 class="card-title w-100 mb-0">
                                                Detail Ekstrakurikuler
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-6 mb-3">
                                                    <label class="text-muted small">Nama Ekstrakurikuler</label>
                                                    <div class="font-weight-bold">{{ $ekstrakurikulers->name }}</div>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <label class="text-muted small">Dibuat Tanggal</label>
                                                    <div class="font-weight-bold">{{ $ekstrakurikulers->created_at->format('d F Y') }}</div>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <label class="text-muted small">Total Siswa</label>
                                                    <div><span class="badge badge-light badge-lg">{{ $students->count() }} siswa</span></div>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <label class="text-muted small">Total Pembina</label>
                                                    <div><span class="badge badge-light badge-lg">{{ $assignedStudents->unique('teacher_id')->count() }} pembina</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Actions -->
                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-light text-dark">
                                            <h5 class="card-title mb-0" style="font-weight: bold">
                                                <i class="fas fa-bolt mr-2"></i>Aksi Cepat
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            @can('asrama.edit')
                                                @if($availableStudents->count() > 0)
                                                <button type="button" class="btn btn-primary btn-block btn-modern mb-3"
                                                    data-toggle="modal" data-target="#bulkAssignModal">
                                                    <i class="fas fa-users mr-2"></i>Tambah Siswa
                                                </button>
                                                <button type="button" class="btn btn-info btn-block btn-modern mb-3"
                                                    onclick="window.print()">
                                                    <i class="fas fa-print mr-2"></i>Cetak Daftar Siswa
                                                </button>
                                                @else
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle mr-2"></i>
                                                    Tidak ada siswa yang tersedia untuk ditambahkan.
                                                </div>
                                                @endif
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Students Tab -->
                        <div class="tab-pane fade" id="students" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Daftar Siswa ({{ $students->count() }})</h5>
                                <div class="search-box">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="searchStudent"
                                        placeholder="Cari nama, email, atau NISN...">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white">
                                                <i class="fas fa-search text-muted"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($students->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-modern" id="studentsTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="50">No</th>
                                            <th>Nama Siswa</th>
                                            <th>Email</th>
                                            <th>NISN</th>
                                            <th>Gender</th>
                                            @can('asrama.edit')
                                                <th width="100">Aksi</th>
                                            @endcan
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($assignedStudents as $index => $assignment)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($assignment->student->face_photo && Storage::disk('public')->exists($assignment->student->face_photo))
                                                        <img src="{{ asset('storage/' . $assignment->student->face_photo) }}" alt="{{ $assignment->student->name }}" class="avatar-modern mr-3" style="object-fit:cover;">
                                                    @else
                                                        <div class="avatar-modern mr-3">
                                                            {{ strtoupper(substr($assignment->student->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="font-weight-bold">{{ $assignment->student->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $assignment->student->user->email ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-outline-info">{{ $assignment->student->nisn }}</span>
                                            </td>
                                            <td>
                                                @if($assignment->student->gender)
                                                <span
                                                    class="badge {{ $assignment->student->gender == 'laki-laki' ? 'badge-outline-primary' : 'badge-outline-pink' }}">
                                                    <i
                                                        class="fas {{ $assignment->student->gender == 'laki-laki' ? 'fa-mars' : 'fa-venus' }} mr-1"></i>
                                                    {{ ucfirst($assignment->student->gender) }}
                                                </span>
                                                @else
                                                <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            @can('asrama.edit')
                                                <td>
                                                    <button type="button" class="btn btn-danger"
                                                        onclick="removeStudent('{{ $assignment->student->id }}', '{{ $assignment->student->name }}')"
                                                        title="Hapus dari asrama">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </td>
                                            @endcan
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @else
                            <div class="empty-state text-center py-5">
                                <div class="empty-state-icon mb-4">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h5 class="empty-state-title">Belum ada siswa di asrama ini</h5>
                                <p class="empty-state-text text-muted">Mulai tambahkan siswa dengan menekan tombol
                                    "Tambah Siswa"</p>
                                @if($availableStudents->count() > 0)
                                <button type="button" class="btn btn-primary btn-modern" data-toggle="modal"
                                    data-target="#bulkAssignModal">
                                    <i class="fas fa-user-plus mr-2"></i>Tambah Siswa Pertama
                                </button>
                                @endif
                            </div>
                            @endif
                        </div>
                        
                        <!-- Nilai Asrama Tab -->
                        <div class="tab-pane fade" id="nilai" role="tabpanel">
                            <!-- Filter Section -->
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label for="bulanFilter" class="form-label">Filter Bulan:</label>
                                    <select id="bulanFilter" class="form-control">
                                        <option value="">Pilih Bulan</option>
                                        @php $currentMonth = date('n'); @endphp
                                        <option value="1" {{ $currentMonth == 1 ? 'selected' : '' }}>Januari</option>
                                        <option value="2" {{ $currentMonth == 2 ? 'selected' : '' }}>Februari</option>
                                        <option value="3" {{ $currentMonth == 3 ? 'selected' : '' }}>Maret</option>
                                        <option value="4" {{ $currentMonth == 4 ? 'selected' : '' }}>April</option>
                                        <option value="5" {{ $currentMonth == 5 ? 'selected' : '' }}>Mei</option>
                                        <option value="6" {{ $currentMonth == 6 ? 'selected' : '' }}>Juni</option>
                                        <option value="7" {{ $currentMonth == 7 ? 'selected' : '' }}>Juli</option>
                                        <option value="8" {{ $currentMonth == 8 ? 'selected' : '' }}>Agustus</option>
                                        <option value="9" {{ $currentMonth == 9 ? 'selected' : '' }}>September</option>
                                        <option value="10" {{ $currentMonth == 10 ? 'selected' : '' }}>Oktober</option>
                                        <option value="11" {{ $currentMonth == 11 ? 'selected' : '' }}>November</option>
                                        <option value="12" {{ $currentMonth == 12 ? 'selected' : '' }}>Desember</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="academicYearFilter" class="form-label">Tahun Akademik:</label>
                                    <select id="academicYearFilter" class="form-control">
                                        <option value="">Pilih Tahun Akademik</option>
                                        @foreach(App\Helpers\AcademicYearHelper::generateAcademicYears(2, 2) as $year)
                                            <option value="{{ $year }}" {{ App\Helpers\AcademicYearHelper::getCurrentAcademicYear() == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <button type="button" id="editModeBtn" class="btn btn-warning mr-2" style="display: none;">
                                        <i class="fas fa-edit"></i> Mode Edit
                                    </button>
                                    <button type="button" id="saveAllBtn" class="btn btn-success" style="display: none;">
                                        <i class="fas fa-save"></i> Simpan Semua
                                    </button>
                                </div>
                            </div>

                            <!-- Status Info -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div id="statusInfo" class="alert alert-info" style="display: none;">
                                        <strong>Status:</strong> <span id="statusText"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Table Container -->
                            <div class="table-responsive">
                                <table id="asramaGradesTable" class="table table-striped table-hover">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th style="width: 40px;">No</th>
                                            <th style="min-width: 100px;">Nama Siswa</th>
                                            <th style="width: 100px;">NISN</th>
                                            <!-- Aspek Ibadah -->
                                            <th style="width: 60px;" title="keaktifan">Keaktifan</th>
                                            <th style="width: 60px;" title="Keterampilan">Keterampilan</th>
                                            <th style="width: 60px;" title="Nilai rapor">Nilai Rapor</th>
                                            <th style="width: 70px;" title="Capaian Kompetensi">Capaian Kompetensi</th>
                                            <th style="width: 100px;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="gradesTableBody">
                                        <!-- Data akan dimuat via AJAX -->
                                    </tbody>
                                </table>
                            </div>

                            <!-- Loading & Empty States -->
                            <div id="loadingIndicator" class="text-center py-4" style="display: none;">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <p class="mt-2">Memuat data nilai asrama...</p>
                            </div>

                            <div id="noDataIndicator" class="text-center py-4" style="display: none;">
                                <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                                <h5>Belum ada data nilai</h5>
                                <p class="text-muted">Pilih bulan dan tahun akademik untuk melihat atau menginput nilai</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($availableStudents->count() > 0)
<!-- Bulk Assign Modal -->
<div class="modal fade" id="bulkAssignModal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 900px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModal">Tambah Siswa ke Asrama</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('ekstrakurikuler.bulk-assign', $ekstrakurikulers->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label>Pilih Siswa:</label>
                            <div>
                                <button type="button" class="btn btn-primary" onclick="selectAll()">Pilih Semua</button>
                                <button type="button" class="btn btn-secondary" onclick="deselectAll()">Batal Semua</button>
                            </div>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" id="searchAvailableStudent" placeholder="Cari nama atau NISN...">
                        </div>
                        <div class="students-list" id="availableStudentsList" style="max-height: 400px; overflow-y: auto;">
                            @foreach($availableStudents as $student)
                            <div class="custom-control custom-checkbox mb-2 student-item">
                                <input type="checkbox" class="custom-control-input student-checkbox"
                                    id="student_{{ $student->id }}" name="student_ids[]" value="{{ $student->id }}">
                                <label class="custom-control-label d-flex align-items-center"
                                    for="student_{{ $student->id }}">
                                    @if($student->face_photo && Storage::disk('public')->exists($student->face_photo))
                                        <img src="{{ asset('storage/' . $student->face_photo) }}" alt="{{ $student->name }}" class="avatar-small mr-2" style="object-fit:cover;">
                                    @else
                                        <div class="avatar-small mr-2">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-weight-medium">{{ $student->name }}</div>
                                        <small class="text-muted">{{ $student->nisn }} - {{ $student->user->email ?? 'No Email' }}</small>
                                    </div>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-users mr-2"></i>Tambah Siswa Terpilih
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Asrama Grade Modal -->
<div class="modal fade" id="asramaGradeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="asramaGradeModalTitle">Input Nilai Ekstrakurikuler</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="asramaGradeForm">
                    @csrf
                    <input type="hidden" id="gradeId" name="grade_id">
                    <input type="hidden" id="studentId" name="student_id">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Siswa</label>
                                <input type="text" id="studentName" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>NISN</label>
                                <input type="text" id="studentNisn" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nilai Keaktifan</label>
                                <input type="text" id="studentKeaktifan" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nilai Keterampilan</label>
                                <input type="text" id="studentKeterampilan" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nilai Rapor</label>
                                <input type="text" id="studentNilai" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Capaian Kompetesi</label>
                                <textarea id="studentCapaian" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" id="saveAsramaGradeBtn" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>Simpan Nilai
                </button>
            </div>
        </div>
    </div>
</div>

<form id="removeStudentForm" style="display: none;">
    @csrf
    <input type="hidden" name="student_id" id="removeStudentId">
</form>

@endsection

@push('scripts')
<script>
    // Deklarasi global agar bisa diakses semua fungsi
    let currentMode = 'view';
    // Set default bulan dan tahun akademik sesuai waktu sekarang
    let currentFilters = {
        month: '{{ date("n") }}',
        academic_year: '{{ App\Helpers\AcademicYearHelper::getCurrentAcademicYear() }}'
    };

    $(document).ready(function () {
        // Set default bulan pada filter bulan
        $('#bulanFilter').val('{{ date("n") }}');
        // Set default tahun akademik pada filter tahun
        $('#academicYearFilter').val('{{ App\Helpers\AcademicYearHelper::getCurrentAcademicYear() }}');
        
        // Search functionality for available students
        $('#searchAvailableStudent').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            var found = false;
            $('#availableStudentsList .student-item').each(function () {
                var match = $(this).text().toLowerCase().indexOf(value) > -1;
                $(this).toggle(match);
                if (match) found = true;
            });

            // Remove previous message
            $('#availableStudentsList .no-result-message').remove();

            if (!found) {
                $('#availableStudentsList').append(
                    '<div class="no-result-message text-center text-muted py-3">Tidak ada siswa ditemukan.</div>'
                );
            }
        });

        // Search functionality for assigned students
        $('#searchStudent').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            $('#studentsTable tbody tr').each(function () {
                var match = $(this).text().toLowerCase().indexOf(value) > -1;
                $(this).toggle(match);
            });
        });

        // Tambahkan ini agar tabel otomatis muncul sesuai default bulan/tahun
        const monthName = $('#bulanFilter').find('option:selected').text();
        updateStatus(`Bulan: ${monthName} - Tahun: ${currentFilters.academic_year}`);
        showActionButtons();
        loadAsramaGrades();

        // Initialize grades functionality
        initializeGradesTab();
    });

    function removeStudent(studentId, studentName) {
        swal({
            title: "Apakah Anda Yakin?",
            text: "Siswa " + studentName + " akan dihapus dari ekstrakurikuler ini!",
            icon: "warning",
            buttons: ['Tidak', 'Ya, Hapus'],
            dangerMode: true,
        }).then(function(isConfirm) {
            if (isConfirm) {
                swal({
                    title: "Menghapus...",
                    text: "Mohon tunggu sebentar",
                    icon: "info",
                    buttons: false,
                    closeOnClickOutside: false,
                    closeOnEsc: false
                });

                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';
                
                // Use FormData instead of JSON
                const formData = new FormData();
                formData.append('_method', 'DELETE');
                formData.append('student_id', studentId);
                formData.append('_token', csrfToken);

                fetch("{{ route('ekstrakurikuler.remove-student', $ekstrakurikulers->id) }}", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => {
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        swal({
                            title: "Berhasil!",
                            text: data.message,
                            icon: "success",
                            timer: 2000,
                            buttons: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        swal("Gagal", data.message || "Terjadi kesalahan", "error");
                    }
                })
                .catch(error => {
                    swal("Gagal", "Terjadi kesalahan: " + error.message, "error");
                });
            }
        });
    }

    function selectAll() {
        $('.student-checkbox').prop('checked', true);
    }

    function deselectAll() {
        $('.student-checkbox').prop('checked', false);
    }

    // Grades Tab Functions
    function initializeGradesTab() {
        // Filter Events
        $('#bulanFilter').change(function() {
            const month = $(this).val();
            const monthName = $(this).find('option:selected').text();
            
            currentFilters.month = month;
            
            if (month) {
                updateStatus(`Bulan: ${monthName} - Tahun: ${currentFilters.academic_year}`);
                showActionButtons();
                loadAsramaGrades();
            } else {
                hideActionButtons();
                clearGradesTable();
                $('#statusInfo').hide();
            }
        });

        $('#academicYearFilter').change(function() {
            currentFilters.academic_year = $(this).val();
            
            if (currentFilters.month) {
                const monthName = $('#bulanFilter').find('option:selected').text();
                updateStatus(`Bulan: ${monthName} - Tahun: ${currentFilters.academic_year}`);
                loadAsramaGrades();
            }
        });

        // Action Buttons
        $('#editModeBtn').click(function() {
            toggleEditMode();
        });

        $('#saveAllBtn').click(function() {
            saveAllAsramaGrades();
        });

        // Modal Events
        $('#saveAsramaGradeBtn').click(function() {
            saveAsramaGrade();
        });

        // Event delegation for dynamic buttons
        $(document).on('click', '.btn-edit-grade', function() {
            const gradeData = $(this).data('grade');
            openAsramaGradeModal(gradeData);
        });

        $(document).on('click', '.btn-delete-grade', function() {
            const gradeId = $(this).data('id');
            const studentName = $(this).data('student-name');
            deleteAsramaGrade(gradeId, studentName);
        });

        // Make cells editable functionality
        $(document).on('click', '.editable-cell', function() {
            if (currentMode === 'edit' && $(this).data('field') !== 'nilai_rapor') {
                $(this).addClass('editing').focus();
            }
        });

        // Clear '-' when cell is focused in edit mode
        $(document).on('focus', '.editable-cell', function() {
            if (currentMode === 'edit' && $(this).text().trim() === '-' && $(this).data('field') !== 'nilai_rapor') {
                $(this).text('');
            }
        });

        $(document).on('blur', '.editable-cell', function() {
            $(this).removeClass('editing');
        });

        // Restrict input to numbers only for grade fields (not catatan)
        $(document).on('keypress', '.editable-cell', function(e) {
            if ($(this).data('field') !== 'capaian_kompetensi' && $(this).data('field') !== 'nilai_rapor') {
                const char = String.fromCharCode(e.which);
                if (!/[0-9]/.test(char) && e.keyCode !== 8 && e.keyCode !== 9 && e.keyCode !== 13 && e.keyCode !== 27) {
                    e.preventDefault();
                }
                // Limit to 100
                const value = $(this).text();
                if (value.length >= 2 && parseInt(value + char) > 100) {
                    e.preventDefault();
                }
            }
            // else: allow any input for catatan
        });

        $(document).on('input', '.editable-cell', function() {
            if ($(this).data('field') !== 'capaian_kompetensi' && $(this).data('field') !== 'nilai_rapor') {
                let value = parseInt($(this).text()) || 0;
                if (value > 100) {
                    $(this).text('100');
                }
            }
            // else: allow any input for catatan
        });

        // Auto-calculation event listeners
        $(document).on('input blur', '.editable-cell[data-field="keaktifan"], .editable-cell[data-field="keterampilan"]', function() {
            if (currentMode === 'edit') {
                const row = $(this).closest('tr');
                calculateNilaiRapor(row);
            }
        });
    }

    // Function to calculate and update Nilai Rapor
    function calculateNilaiRapor(row) {
        const keaktifanCell = row.find('td[data-field="keaktifan"]');
        const keterampilanCell = row.find('td[data-field="keterampilan"]');
        const nilaiRaporCell = row.find('td[data-field="nilai_rapor"]');
        
        // Get numeric values
        const keaktifanValue = getNumericValueFromCell(keaktifanCell);
        const keterampilanValue = getNumericValueFromCell(keterampilanCell);
        
        // Calculate average if both values exist
        if (keaktifanValue !== null && keterampilanValue !== null) {
            const average = Math.round((keaktifanValue + keterampilanValue) / 2);
            
            // Update the cell with formatted value
            if (currentMode === 'edit') {
                nilaiRaporCell.text(average);
            } else {
                nilaiRaporCell.html(formatGradeValue(average));
            }
            
            // Add visual indicator that this is auto-calculated
            nilaiRaporCell.addClass('auto-calculated');
        } else {
            // If either value is missing, show dash
            if (currentMode === 'edit') {
                nilaiRaporCell.text('-');
            } else {
                nilaiRaporCell.html('-');
            }
            nilaiRaporCell.removeClass('auto-calculated');
        }
    }

    // Helper function to get numeric value from cell
    function getNumericValueFromCell(cell) {
        const text = cell.text().trim();
        if (!text || text === '-') return null;
        
        // Extract number from badge or plain text
        const match = text.match(/\d+/);
        if (match) {
            const value = parseInt(match[0]);
            return (value >= 0 && value <= 100) ? value : null;
        }
        
        return null;
    }

    function updateStatus(message) {
        $('#statusInfo').show();
        $('#statusText').text(message);
    }

    function resetGradesView() {
        currentFilters = {
            month: '{{ date("n") }}',
            academic_year: '{{ App\Helpers\AcademicYearHelper::getCurrentAcademicYear() }}'
        };
        $('#bulanFilter').val('{{ date("n") }}');
        $('#academicYearFilter').val('{{ App\Helpers\AcademicYearHelper::getCurrentAcademicYear() }}');
        
        $('#statusInfo').hide();
        hideActionButtons();
        clearGradesTable();
        currentMode = 'view';
    }

    function showActionButtons() {
        $('#editModeBtn').show();
    }

    function hideActionButtons() {
        $('#editModeBtn').hide();
        $('#saveAllBtn').hide();
        currentMode = 'view';
    }

    function clearGradesTable() {
        $('#gradesTableBody').empty();
        $('#loadingIndicator').hide();
        $('#noDataIndicator').show();
    }

    function showLoading() {
        $('#loadingIndicator').show();
        $('#noDataIndicator').hide();
        $('#gradesTableBody').empty();
    }

    function hideLoading() {
        $('#loadingIndicator').hide();
    }

    function loadAsramaGrades() {
        showLoading();
        
        $.ajax({
            url: '{{ route("ekstrakurikuler.get-grades", $ekstrakurikulers->id) }}',
            method: 'GET',
            data: currentFilters,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                hideLoading();
                
                if (response.success && response.data && response.data.length > 0) {
                    displayAsramaGrades(response.data);
                    $('#noDataIndicator').hide();
                } else {
                    $('#noDataIndicator').show();
                    $('#gradesTableBody').empty();
                }
            },
            error: function(xhr) {
                hideLoading();
                $('#noDataIndicator').show();
                console.log('Error loading ekstrakurikuler grades:', xhr.responseText);
                
                swal({
                    title: 'Error',
                    text: 'Gagal memuat data nilai ekstrakurikuler',
                    icon: 'error',
                    button: 'OK'
                });
            }
        });
    }

    function displayAsramaGrades(grades) {
        let tableHtml = '';
        
        grades.forEach(function(grade, index) {
            const gradeData = encodeHtmlAttribute(JSON.stringify(grade));
            
            // Calculate nilai_rapor from keaktifan and keterampilan
            let calculatedNilaiRapor = '-';
            if (grade.keaktifan !== null && grade.keterampilan !== null && 
                grade.keaktifan !== undefined && grade.keterampilan !== undefined) {
                calculatedNilaiRapor = Math.round((parseInt(grade.keaktifan) + parseInt(grade.keterampilan)) / 2);
            }
            
            tableHtml += `
                <tr data-student-id="${grade.student_id}">
                    <td>${index + 1}</td>
                    <td class="nama-siswa">${grade.student_name}</td>
                    <td>${grade.student_nisn}</td>
                    <td class="editable-cell" data-field="keaktifan">${formatGradeValue(grade.keaktifan)}</td>
                    <td class="editable-cell" data-field="keterampilan">${formatGradeValue(grade.keterampilan)}</td>
                    <td class="editable-cell auto-calculated" data-field="nilai_rapor" contenteditable="false" title="Otomatis dihitung dari rata-rata Keaktifan dan Keterampilan">${formatGradeValue(calculatedNilaiRapor)}</td>
                    <td class="editable-cell" data-field="capaian_kompetensi">${grade.capaian_kompetensi || '-'}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-info btn-edit-grade" 
                                    data-grade="${gradeData}" title="Edit Nilai">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-delete-grade" 
                                    data-id="${grade.id}" data-student-name="${grade.student_name}" title="Hapus Nilai">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });
        
        $('#gradesTableBody').html(tableHtml);
    }

    function formatGradeValue(value) {
        if (value === null || value === undefined || value === '' || value === '-') {
            return '-';
        }
        
        const numValue = parseInt(value);
        if (isNaN(numValue)) {
            return '-';
        }
        
        return `${numValue}`;
    }

    function encodeHtmlAttribute(str) {
        return str.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    function toggleEditMode() {
        if (currentMode === 'view') {
            currentMode = 'edit';
            $('#editModeBtn').html('<i class="fas fa-eye"></i> Mode View').removeClass('btn-warning').addClass('btn-info');
            $('#saveAllBtn').show();
            makeGradesTableEditable();
            updateStatus('Mode Edit - Klik pada sel untuk mengubah nilai (0-100). Nilai Rapor otomatis dihitung.');
        } else {
            currentMode = 'view';
            $('#editModeBtn').html('<i class="fas fa-edit"></i> Mode Edit').removeClass('btn-info').addClass('btn-warning');
            $('#saveAllBtn').hide();
            makeGradesTableReadonly();
            const monthName = $('#bulanFilter').find('option:selected').text();
            updateStatus(`Bulan: ${monthName} - Tahun: ${currentFilters.academic_year}`);
        }
    }

    function makeGradesTableEditable() {
        $('.editable-cell').each(function() {
            // Don't make nilai_rapor editable as it's auto-calculated
            if ($(this).data('field') === 'nilai_rapor') {
                $(this).attr('contenteditable', 'false')
                       .css({
                           'background-color': '#f8f9fa',
                           'cursor': 'not-allowed',
                           'border': '1px solid #dee2e6',
                           'font-style': 'italic'
                       })
                       .attr('title', 'Otomatis dihitung dari rata-rata Keaktifan dan Keterampilan');
                return;
            }
            
            $(this).attr('contenteditable', 'true')
                   .css({
                       'background-color': '#fff3cd',
                       'cursor': 'text',
                       'border': '1px dashed #ffeaa7'
                   });
            
            // For catatan, allow multiline and normal text
            if ($(this).data('field') === 'capaian_kompetensi') {
                $(this).css({
                    'background-color': '#e3e3e3',
                    'cursor': 'text',
                    'border': '1px dashed #bdbdbd'
                });
            }
        });
    }

    function makeGradesTableReadonly() {
        $('.editable-cell').attr('contenteditable', 'false')
                          .css({
                              'background-color': '',
                              'cursor': '',
                              'border': '',
                              'font-style': ''
                          });
    }

    function openAsramaGradeModal(gradeData = null) {
        if (!currentFilters.month) {
            swal('Perhatian', 'Pilih bulan terlebih dahulu', 'warning');
            return;
        }

        if (gradeData) {
            $('#asramaGradeModalTitle').text('Edit Nilai Asrama');
            populateAsramaGradeForm(gradeData);
        } else {
            $('#asramaGradeModalTitle').text('Input Nilai Asrama');
            clearAsramaGradeForm();
        }

        $('#asramaGradeModal').modal('show');
    }

function populateAsramaGradeForm(gradeData) {
    $('#gradeId').val(gradeData.id || '');
    $('#studentId').val(gradeData.student_id);
    $('#studentName').val(gradeData.student_name);
    $('#studentNisn').val(gradeData.student_nisn);
    
    // Pastikan menggunakan nilai yang benar
    const keaktifanValue = gradeData.keaktifan || '';
    const keterampilanValue = gradeData.keterampilan || '';
    
    $('#studentKeaktifan').val(keaktifanValue);
    $('#studentKeterampilan').val(keterampilanValue);
    
    // Auto-calculate nilai_rapor dengan validasi yang lebih baik
    const keaktifanNum = parseInt(keaktifanValue) || 0;
    const keterampilanNum = parseInt(keterampilanValue) || 0;
    
    let nilaiRapor = '';
    if (keaktifanNum > 0 || keterampilanNum > 0) {
        // Hitung rata-rata hanya jika minimal salah satu ada nilainya
        const total = (keaktifanNum > 0 ? keaktifanNum : 0) + (keterampilanNum > 0 ? keterampilanNum : 0);
        const count = (keaktifanNum > 0 ? 1 : 0) + (keterampilanNum > 0 ? 1 : 0);
        if (count > 0) {
            nilaiRapor = Math.round(total / count);
        }
    }
    
    $('#studentNilai').val(nilaiRapor);
    $('#studentCapaian').val(gradeData.capaian_kompetensi || '');
}

function clearAsramaGradeForm() {
    $('#asramaGradeForm')[0].reset();
    $('#gradeId').val('');
    $('#studentId').val('');
    $('#studentNilai').prop('readonly', true);
}

// Auto-calculate in modal when keaktifan or keterampilan changes
$(document).on('input', '#studentKeaktifan, #studentKeterampilan', function() {
    const keaktifanVal = $('#studentKeaktifan').val().trim();
    const keterampilanVal = $('#studentKeterampilan').val().trim();
    
    const keaktifan = keaktifanVal ? parseInt(keaktifanVal) : 0;
    const keterampilan = keterampilanVal ? parseInt(keterampilanVal) : 0;
    
    let nilaiRapor = '';
    
    if (keaktifan > 0 || keterampilan > 0) {
        // Hitung rata-rata dari nilai yang tersedia
        const total = keaktifan + keterampilan;
        const count = (keaktifan > 0 ? 1 : 0) + (keterampilan > 0 ? 1 : 0);
        
        if (count > 0) {
            nilaiRapor = Math.round(total / count);
        }
    }
    
    $('#studentNilai').val(nilaiRapor);
});

function saveAsramaGrade() {
    // Validasi client-side sebelum mengirim
    const keaktifan = $('#studentKeaktifan').val().trim();
    const keterampilan = $('#studentKeterampilan').val().trim();
    const capaianKompetensi = $('#studentCapaian').val().trim();
    
    // Validasi capaian_kompetensi tidak boleh kosong
    if (!capaianKompetensi) {
        swal('Perhatian', 'Capaian Kompetensi harus diisi', 'warning');
        $('#studentCapaian').focus();
        return;
    }
    
    // Validasi range nilai (0-100)
    if (keaktifan && (parseInt(keaktifan) < 0 || parseInt(keaktifan) > 100)) {
        swal('Perhatian', 'Nilai Keaktifan harus antara 0-100', 'warning');
        $('#studentKeaktifan').focus();
        return;
    }
    
    if (keterampilan && (parseInt(keterampilan) < 0 || parseInt(keterampilan) > 100)) {
        swal('Perhatian', 'Nilai Keterampilan harus antara 0-100', 'warning');
        $('#studentKeterampilan').focus();
        return;
    }
    
    const formData = new FormData();
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    
    // Data utama
    const gradeId = $('#gradeId').val();
    const studentId = $('#studentId').val();
    
    if (gradeId) {
        formData.append('_method', 'PUT');
        formData.append('grade_id', gradeId);
    }
    
    formData.append('student_id', studentId);
    formData.append('ekstrakurikuler_id', '{{ $ekstrakurikulers->id }}');
    formData.append('month', currentFilters.month);
    formData.append('academic_year', currentFilters.academic_year);
    
    // Data nilai - kirim sebagai empty string jika kosong, biarkan server handle
    formData.append('keaktifan', keaktifan || '');
    formData.append('keterampilan', keterampilan || '');
    formData.append('nilai_rapor', $('#studentNilai').val() || '');
    formData.append('capaian_kompetensi', capaianKompetensi);
    
    const url = gradeId ? 
        `{{ url('ekstrakurikuler/grades') }}/${gradeId}` : 
        '{{ route("ekstrakurikuler.store-grade") }}';
    
    // Show loading
    swal({
        title: 'Menyimpan...',
        text: 'Mohon tunggu sebentar',
        buttons: false,
        closeOnClickOutside: false,
        closeOnEsc: false,
        icon: 'info'
    });

    $.ajax({
        url: url,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            swal.close();
            $('#asramaGradeModal').modal('hide');
            swal('Berhasil', response.message || 'Nilai berhasil disimpan', 'success');
            loadAsramaGrades();
        },
        error: function(xhr) {
            swal.close();
            
            let errorMessage = 'Gagal menyimpan nilai';
            
            if (xhr.status === 422) {
                // Validation error
                const response = xhr.responseJSON;
                if (response && response.errors) {
                    const errors = Object.values(response.errors).flat();
                    errorMessage = errors.join('\n');
                } else if (response && response.message) {
                    errorMessage = response.message;
                }
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            swal('Error', errorMessage, 'error');
            console.error('Save grade error:', xhr);
        }
    });
}

    function saveAllAsramaGrades() {
        const gradesData = [];
        let hasChanges = false;
        
        $('#asramaGradesTable tbody tr').each(function() {
            const row = $(this);
            const studentId = row.data('student-id');
            
            if (!studentId) return;
            
            const gradeData = {
                student_id: studentId,
                month: parseInt(currentFilters.month),
                academic_year: currentFilters.academic_year,
                ekstrakurikuler_id: '{{ $ekstrakurikulers->id }}'
            };
            
            // Get keaktifan and keterampilan values
            const keaktifanCell = row.find('td[data-field="keaktifan"]');
            const keterampilanCell = row.find('td[data-field="keterampilan"]');
            
            const keaktifanValue = getNumericValueFromCell(keaktifanCell);
            const keterampilanValue = getNumericValueFromCell(keterampilanCell);
            
            // Auto-calculate nilai_rapor
            let nilaiRaporValue = null;
            if (keaktifanValue !== null && keterampilanValue !== null) {
                nilaiRaporValue = Math.round((keaktifanValue + keterampilanValue) / 2);
            }
            
            // Set the calculated values
            gradeData.keaktifan = keaktifanValue;
            gradeData.keterampilan = keterampilanValue;
            gradeData.nilai_rapor = nilaiRaporValue;
            
            // Get capaian_kompetensi
            const capaianCell = row.find('td[data-field="capaian_kompetensi"]');
            const capaianValue = capaianCell.text().trim();
            gradeData.capaian_kompetensi = capaianValue === '-' ? '' : capaianValue;
            
            // Check if there are any meaningful values
            let hasValues = false;
            if (keaktifanValue !== null || keterampilanValue !== null || 
                (capaianValue && capaianValue !== '-')) {
                hasValues = true;
            }
            
            if (hasValues) {
                gradesData.push(gradeData);
                hasChanges = true;
            }
        });
        
        if (!hasChanges || gradesData.length === 0) {
            swal('Perhatian', 'Tidak ada perubahan data untuk disimpan', 'warning');
            return;
        }
        
        // Show loading
        swal({
            title: 'Menyimpan Data...',
            text: `Menyimpan ${gradesData.length} data nilai ekstrakurikuler`,
            buttons: false,
            closeOnClickOutside: false,
            closeOnEsc: false,
            icon: 'info'
        });

        $.ajax({
            url: '{{ route("ekstrakurikuler.bulk-update-grades") }}',
            method: 'POST',
            data: {
                grades: gradesData,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Accept': 'application/json'
            },
            success: function(response) {
                swal.close();
                
                if (response.success) {
                    swal({
                        title: 'Berhasil!',
                        text: response.message || 'Semua nilai ekstrakurikuler berhasil disimpan',
                        icon: 'success',
                        timer: 3000,
                        buttons: false
                    });
                    
                    toggleEditMode();
                    loadAsramaGrades();
                } else {
                    throw new Error(response.message || 'Unknown error');
                }
            },
            error: function(xhr) {
                swal.close();
                console.error('Bulk update error:', xhr);
                
                let errorMessage = 'Gagal menyimpan nilai ekstrakurikuler';
                
                try {
                    const errorResponse = JSON.parse(xhr.responseText);
                    if (errorResponse.message) {
                        errorMessage = errorResponse.message;
                    }
                    if (errorResponse.errors) {
                        console.log('Validation errors:', errorResponse.errors);
                    }
                } catch (parseError) {
                    console.error('Error parsing response:', parseError);
                }
                
                swal('Error', errorMessage, 'error');
            }
        });
    }

    function deleteAsramaGrade(gradeId, studentName) {
        if (!gradeId) {
            swal('Perhatian', 'Data nilai tidak ditemukan', 'warning');
            return;
        }

        swal({
            title: 'Hapus Nilai?',
            text: `Hapus nilai asrama untuk ${studentName}?`,
            icon: 'warning',
            buttons: ['Batal', 'Ya, Hapus'],
            dangerMode: true
        }).then((isConfirm) => {
            if (isConfirm) {
                $.ajax({
                    url: `{{ url('ekstrakurikuler/grades') }}/${gradeId}`,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        swal('Berhasil', response.message || 'Nilai berhasil dihapus', 'success');
                        loadAsramaGrades();
                    },
                    error: function(xhr) {
                        swal('Error', 'Gagal menghapus nilai', 'error');
                    }
                });
            }
        });
    }

    // Function to get numeric value from editable cell
    function getGradeNumericValue(text) {
        if (!text || text.trim() === '-') return null;
        
        const cleaned = text.replace(/[^0-9]/g, '');
        const numValue = parseInt(cleaned);
        
        if (isNaN(numValue) || numValue <= 0) return null;
        if (numValue > 100) return 100;
        
        return numValue;
    }

    // Tab switching handler
    $('#nilai-tab').on('shown.bs.tab', function () {
        if (!currentFilters.academic_year) {
            currentFilters.academic_year = '{{ App\Helpers\AcademicYearHelper::getCurrentAcademicYear() }}';
            $('#academicYearFilter').val(currentFilters.academic_year);
        }
        // Set default bulan saat tab dibuka
        if (!currentFilters.month) {
            currentFilters.month = '{{ date("n") }}';
            $('#bulanFilter').val(currentFilters.month);
        }
    });
</script>
@endpush