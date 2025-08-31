@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header dengan gradient -->
            <div class="header-section mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="text-dark mb-1">{{ $asrama->name }}</h2>
                        <p class="mb-0">
                            <i class="fas fa-building mr-2"></i>Asrama
                            <span class="badge badge-light">{{ $asrama->created_at->format('d M Y') }}</span>
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
                                <i class="fas fa-info-circle mr-2"></i>Informasi Asrama
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="students-tab" data-toggle="pill" href="#students" role="tab">
                                <i class="fas fa-users mr-2"></i>Daftar Siswa
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
                                                Detail Asrama
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-6 mb-3">
                                                    <label class="text-muted small">Nama Asrama</label>
                                                    <div class="font-weight-bold">{{ $asrama->name }}</div>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <label class="text-muted small">Dibuat Tanggal</label>
                                                    <div class="font-weight-bold">{{ $asrama->created_at->format('d F Y') }}</div>
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
                <form action="{{ route('asrama.bulk-assign', $asrama->id) }}" method="POST">
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

<form id="removeStudentForm" style="display: none;">
    @csrf
    <input type="hidden" name="student_id" id="removeStudentId">
</form>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
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
        });

        function removeStudent(studentId, studentName) {
            swal({
                title: "Apakah Anda Yakin?",
                text: "Siswa " + studentName + " akan dihapus dari asrama ini!",
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

                    fetch("{{ route('asrama.remove-student', $asrama->id) }}", {
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
    </script>
@endpush