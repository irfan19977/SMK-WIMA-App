@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Header dengan gradient -->
            <div class="header-section mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="text-dark mb-1">{{ $classes->name }}</h2>
                        <p class="mb-0">
                            <i class="fas fa-graduation-cap mr-2"></i>{{ $classes->major }} -
                            <span class="badge badge-light">{{ $classes->code }}</span>
                        </p>
                    </div>
                    <div>
                        <a href="{{ route('classes.edit', $classes->id) }}" class="btn btn-primary btn-shadow">
                            <i class="fas fa-edit"></i> Edit Kelas
                        </a>
                        <a href="{{ route('classes.index') }}" class="btn btn-light btn-shadow">
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
                                    <div class="icon-big text-center" style="color: #667eea; sixe: 50rem;">
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
                            <h4>Hadir Hari Ini</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-check-circle text-success"></i>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <div class="numbers">
                                        <h4 class="card-title">{{ rand(20, $students->count()) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h4>Tidak Hadir</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-exclamation-triangle text-warning"></i>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <div class="numbers">
                                        <h4 class="card-title">{{ rand(0, 5) }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="card card-info">
                        <div class="card-header">
                            <h4>Kehadiran</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <div class="icon-big text-center">
                                        <i class="fas fa-percentage text-info"></i>
                                    </div>
                                </div>
                                <div class="col-7">
                                    <div class="numbers">
                                        <h4 class="card-title">{{ rand(85, 98) }}%</h4>
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
                                <i class="fas fa-info-circle mr-2"></i>Informasi Kelas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="students-tab" data-toggle="pill" href="#students" role="tab">
                                <i class="fas fa-users mr-2"></i>Daftar Siswa
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="attendance-tab" data-toggle="pill" href="#attendance" role="tab">
                                <i class="fas fa-calendar-check mr-2"></i>Absensi
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
                                                Detail Kelas
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-6 mb-3">
                                                    <label class="text-muted small">Nama Kelas</label>
                                                    <div class="font-weight-bold">{{ $classes->name }}</div>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <label class="text-muted small">Jurusan</label>
                                                    <div class="font-weight-bold">{{ $classes->major }}</div>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <label class="text-muted small">Kode Kelas</label>
                                                    <div><span
                                                            class="badge badge-light badge-lg">{{ $classes->code }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <label class="text-muted small">Tingkat</label>
                                                    <div class="font-weight-bold">{{ $classes->grade ?? '-' }}</div>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <label class="text-muted small">Tahun Akademik</label>
                                                    <div class="font-weight-bold">
                                                        {{ $classes->academic_year ?? '2024/2025' }}</div>
                                                </div>
                                                <div class="col-sm-6 mb-3">
                                                    <label class="text-muted small">Total Siswa</label>
                                                    <div><span
                                                            class="badge badge-light badge-lg">{{ $students->count() }}
                                                            siswa</span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Actions -->
                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-light text-dark">
                                            <h5 class="card-title mb-0" style="text-bold">
                                                <i class="fas fa-bolt mr-2"></i>Aksi Cepat
                                            </h5>
                                        </div>
                                        <div class="card-body">
                                            @if($availableStudents->count() > 0)
                                            <button type="button" class="btn btn-primary btn-block btn-modern mb-3"
                                                data-toggle="modal" data-target="#bulkAssignModal">
                                                <i class="fas fa-users mr-2"></i>Tambah Siswa
                                            </button>
                                            <button type="button" class="btn btn-info btn-block btn-modern mb-3"
                                                onclick="window.print()">
                                                <i class="fas fa-print mr-2"></i>Cetak Daftar Siswa
                                            </button>
                                            <button type="button" class="btn btn-success btn-block btn-modern mb-3"
                                                onclick="window.print()">
                                                <i class="fas fa-print mr-2"></i>Cetak Daftar Absen
                                            </button>
                                            @else
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle mr-2"></i>
                                                Tidak ada siswa yang tersedia untuk ditambahkan.
                                            </div>
                                            @endif
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
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-white border-right-0">
                                                <i class="fas fa-search text-muted"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control border-left-0" id="searchStudent"
                                            placeholder="Cari nama, email, atau NISN...">
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
                                            <th width="100">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($students as $index => $student)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($student->photo && file_exists(public_path('foto/student/' . $student->photo)))
                                                        <img src="{{ asset('foto/student/' . $student->photo) }}" alt="{{ $student->name }}" class="avatar-modern mr-3" style="object-fit:cover;">
                                                    @else
                                                        <div class="avatar-modern mr-3">
                                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="font-weight-bold">{{ $student->name }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $student->user->email ?? '-' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge badge-outline-info">{{ $student->nisn }}</span>
                                            </td>
                                            <td>
                                                @if($student->gender)
                                                <span
                                                    class="badge {{ $student->gender == 'laki-laki' ? 'badge-outline-primary' : 'badge-outline-pink' }}">
                                                    <i
                                                        class="fas {{ $student->gender == 'laki-laki' ? 'fa-mars' : 'fa-venus' }} mr-1"></i>
                                                    {{ ucfirst($student->gender) }}
                                                </span>
                                                @else
                                                <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger"
                                                    onclick="removeStudent('{{ $student->id }}', '{{ $student->name }}')"
                                                    title="Hapus dari kelas">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
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
                                <h5 class="empty-state-title">Belum ada siswa di kelas ini</h5>
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

                        <!-- Attendance Tab -->
                        <div class="tab-pane fade" id="attendance" role="tabpanel">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="attendanceMonth">Pilih Bulan:</label>
                                        <select class="form-control" id="attendanceMonth" onchange="filterAttendance()">
                                            <option value="2024-01">Januari 2024</option>
                                            <option value="2024-02">Februari 2024</option>
                                            <option value="2024-03">Maret 2024</option>
                                            <option value="2024-04">April 2024</option>
                                            <option value="2024-05">Mei 2024</option>
                                            <option value="2024-06" selected>Juni 2024</option>
                                            <option value="2024-07">Juli 2024</option>
                                            <option value="2024-08">Agustus 2024</option>
                                            <option value="2024-09">September 2024</option>
                                            <option value="2024-10">Oktober 2024</option>
                                            <option value="2024-11">November 2024</option>
                                            <option value="2024-12">Desember 2024</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex justify-content-end align-items-end h-100">
                                        <button class="btn btn-success btn-modern mr-2" onclick="exportAttendance()">
                                            <i class="fas fa-file-excel mr-2"></i>Export Absensi
                                        </button>
                                        <button class="btn btn-primary btn-modern" onclick="window.print()">
                                            <i class="fas fa-print mr-2"></i>Cetak Absensi
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-gradient-success text-white">
                                    <h6 class="card-title mb-0">
                                        <i class="fas fa-calendar-check mr-2"></i>
                                        Absensi Bulan <span id="selectedMonth">Juni 2024</span>
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-modern mb-0" id="attendanceTable">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th width="50">No</th>
                                                    <th width="200">Nama Siswa</th>
                                                    <th width="80">1</th>
                                                    <th width="80">2</th>
                                                    <th width="80">3</th>
                                                    <th width="80">4</th>
                                                    <th width="80">5</th>
                                                    <th width="80">6</th>
                                                    <th width="80">7</th>
                                                    <th width="80">8</th>
                                                    <th width="80">9</th>
                                                    <th width="80">10</th>
                                                    <th width="80">11</th>
                                                    <th width="80">12</th>
                                                    <th width="80">13</th>
                                                    <th width="80">14</th>
                                                    <th width="80">15</th>
                                                    <th width="80">16</th>
                                                    <th width="80">17</th>
                                                    <th width="80">18</th>
                                                    <th width="80">19</th>
                                                    <th width="80">20</th>
                                                    <th width="80">21</th>
                                                    <th width="80">22</th>
                                                    <th width="80">23</th>
                                                    <th width="80">24</th>
                                                    <th width="80">25</th>
                                                    <th width="80">26</th>
                                                    <th width="80">27</th>
                                                    <th width="80">28</th>
                                                    <th width="80">29</th>
                                                    <th width="80">30</th>
                                                    <th width="100">Hadir</th>
                                                    <th width="100">Tidak Hadir</th>
                                                    <th width="100">Persentase</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($students->count() > 0)
                                                @foreach($students as $index => $student)
                                                @php
                                                $attendanceData = [];
                                                $presentCount = 0;
                                                $absentCount = 0;

                                                // Generate random attendance data for demo
                                                for($day = 1; $day <= 30; $day++) { $status=rand(1, 10) <=8 ? 'H' :
                                                    (rand(1, 2)==1 ? 'S' : 'I' ); // 80% hadir, 20% sakit/izin
                                                    $attendanceData[$day]=$status; if($status=='H' ) $presentCount++;
                                                    else $absentCount++; } $percentage=$presentCount> 0 ?
                                                    round(($presentCount / 30) * 100, 1) : 0;
                                                    @endphp
                                                    <tr>
                                                        <td class="text-center">{{ $index + 1 }}</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-small mr-2">
                                                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                                                </div>
                                                                <span
                                                                    class="font-weight-medium">{{ $student->name }}</span>
                                                            </div>
                                                        </td>
                                                        @for($day = 1; $day <= 30; $day++) <td class="text-center">
                                                            @php $status = $attendanceData[$day]; @endphp
                                                            <span
                                                                class="attendance-status attendance-{{ strtolower($status) }}">
                                                                {{ $status }}
                                                            </span>
                                                            </td>
                                                            @endfor
                                                            <td class="text-center">
                                                                <span
                                                                    class="badge badge-success">{{ $presentCount }}</span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span
                                                                    class="badge badge-danger">{{ $absentCount }}</span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span
                                                                    class="badge {{ $percentage >= 80 ? 'badge-success' : ($percentage >= 70 ? 'badge-warning' : 'badge-danger') }}">
                                                                    {{ $percentage }}%
                                                                </span>
                                                            </td>
                                                    </tr>
                                                    @endforeach
                                                    @else
                                                    <tr>
                                                        <td colspan="35" class="text-center py-4">
                                                            <div class="text-muted">Tidak ada data siswa untuk
                                                                ditampilkan</div>
                                                        </td>
                                                    </tr>
                                                    @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Attendance Legend -->
                            <div class="mt-3">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-body py-2">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <span class="mr-4">
                                                        <span class="attendance-status attendance-h mr-1">H</span>
                                                        <small>Hadir</small>
                                                    </span>
                                                    <span class="mr-4">
                                                        <span class="attendance-status attendance-s mr-1">S</span>
                                                        <small>Sakit</small>
                                                    </span>
                                                    <span class="mr-4">
                                                        <span class="attendance-status attendance-i mr-1">I</span>
                                                        <small>Izin</small>
                                                    </span>
                                                    <span>
                                                        <span class="attendance-status attendance-a mr-1">A</span>
                                                        <small>Alpha</small>
                                                    </span>
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
        </div>
    </div>
</div>

@if($availableStudents->count() > 0)
<!-- Bulk Assign Modal -->
<div class="modal fade" id="bulkAssignModal" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 900px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModal">Tambah Siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('classes.bulk-assign', $classes->id) }}" method="POST">
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
                        <div class="students-list" id="availableStudentsList">
                            @foreach($availableStudents as $student)
                            <div class="custom-control custom-checkbox mb-2 student-item">
                                <input type="checkbox" class="custom-control-input student-checkbox"
                                    id="student_{{ $student->id }}" name="student_ids[]" value="{{ $student->id }}">
                                <label class="custom-control-label d-flex align-items-center"
                                    for="student_{{ $student->id }}">
                                    <div class="avatar-small mr-2">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-weight-medium">{{ $student->name }}</div>
                                        <small class="text-muted">{{ $student->nisn }}</small>
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
        });
    </script>
    <script>
        function removeStudent(studentId, studentName) {
            swal({
                title: "Apakah Anda Yakin?",
                text: "Siswa " + studentName + " akan dihapus dari kelas ini!",
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

                    fetch("{{ route('classes.remove-student', $classes->id) }}", {
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

        // Rest of your existing scripts remain the same
        $(document).ready(function () {
            // Search functionality
            $('#searchStudent').on('keyup', function () {
                var value = $(this).val().toLowerCase();
                $('#studentsTable tbody tr').filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });

            // Initialize tooltips
            $('[data-toggle="tooltip"]').tooltip();
        });

        function selectAll() {
            $('.student-checkbox').prop('checked', true);
        }

        function deselectAll() {
            $('.student-checkbox').prop('checked', false);
        }

        function filterAttendance() {
            const selectedValue = $('#attendanceMonth').val();
            const monthNames = {
                '2024-01': 'Januari 2024',
                '2024-02': 'Februari 2024',
                '2024-03': 'Maret 2024',
                '2024-04': 'April 2024',
                '2024-05': 'Mei 2024',
                '2024-06': 'Juni 2024',
                '2024-07': 'Juli 2024',
                '2024-08': 'Agustus 2024',
                '2024-09': 'September 2024',
                '2024-10': 'Oktober 2024',
                '2024-11': 'November 2024',
                '2024-12': 'Desember 2024'
            };

            $('#selectedMonth').text(monthNames[selectedValue]);

            swal({
                title: 'Memuat Data...',
                text: 'Sedang memuat data absensi untuk ' + monthNames[selectedValue],
                icon: 'info',
                timer: 1500,
                buttons: false
            });
        }

        function exportAttendance() {
            swal({
                title: 'Export Berhasil',
                text: 'Data absensi berhasil diexport ke Excel',
                icon: 'success',
                timer: 1500,
                buttons: false
            });
        }

        function exportToExcel() {
            swal({
                title: 'Export Berhasil',
                text: 'Data siswa berhasil diexport ke Excel',
                icon: 'success',
                timer: 1500,
                buttons: false
            });
        }
    </script>
@endpush

@push('styles')
    <style>
        /* Modern Design Styles */
        .header-section {
            background-color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .btn-shadow {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-shadow:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* .card-stats {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card-stats:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .card-stats .icon-big {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .card-stats .numbers {
            text-align: right;
        }

        .card-stats .card-category {
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .card-stats .card-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }

        .card-modern {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        } */

        .nav-pills .nav-link {
            border-radius: 25px;
            font-weight: 500;
            /* padding: 0.75rem 1.5rem; */
            margin-right: 0.5rem;
            transition: all 0.3s ease;
        }

        .nav-pills .nav-link.active {
            background-color: #007bff;
            border: none;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }

        .btn-modern {
            border-radius: 25px;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .avatar-modern {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .avatar-small {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .table-modern {
            border: none;
        }

        .table-modern thead th {
            border: none;
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
            padding: 1rem;
        }

        .table-modern tbody tr {
            transition: all 0.3s ease;
        }

        .table-modern tbody tr:hover {
            background-color: #f8f9fa;
            transform: translateY(-1px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .table-modern td {
            padding: 1rem;
            border: none;
            border-bottom: 1px solid #e9ecef;
        }

        .badge-outline-info {
            color: #17a2b8;
            border: 1px solid #17a2b8;
            background: transparent;
        }

        .badge-outline-primary {
            color: #007bff;
            border: 1px solid #007bff;
            background: transparent;
        }

        .badge-outline-pink {
            color: #e83e8c;
            border: 1px solid #e83e8c;
            background: transparent;
        }

        .badge-outline-danger {
            color: #dc3545;
            border: 1px solid #dc3545;
            background: transparent;
        }

        .badge-lg {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }

        .search-box {
            width: 300px;
        }

        .search-box .form-control {
            border-radius: 25px;
            padding-left: 0;
        }

        .search-box .input-group-text {
            border-radius: 25px 0 0 25px;
        }

        .empty-state {
            padding: 3rem 2rem;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: #dee2e6;
        }

        .empty-state-title {
            color: #6c757d;
            font-weight: 600;
        }

        .empty-state-text {
            margin-bottom: 2rem;
        }

        .students-list {
            max-height: 400px;
            overflow-y: auto;
            padding: 0.5rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            background-color: #f8f9fa;
        }

        /* Attendance Styles */
        .attendance-status {
            display: inline-block;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            text-align: center;
            line-height: 24px;
            font-weight: 600;
            font-size: 0.8rem;
            color: white;
        }

        .attendance-h {
            background-color: #28a745;
        }

        .attendance-s {
            background-color: #ffc107;
            color: #212529;
        }

        .attendance-i {
            background-color: #17a2b8;
        }

        .attendance-a {
            background-color: #dc3545;
        }

        #attendanceTable {
            font-size: 0.9rem;
        }

        #attendanceTable th {
            text-align: center;
            padding: 0.5rem;
            white-space: nowrap;
        }

        #attendanceTable td {
            padding: 0.5rem;
            text-align: center;
        }

        .font-weight-medium {
            font-weight: 500;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-section {
                padding: 1.5rem;
            }

            .search-box {
                width: 100%;
                margin-top: 1rem;
            }

            .card-stats .numbers {
                text-align: left;
                margin-top: 1rem;
            }

            .btn-modern {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }

            #attendanceTable {
                font-size: 0.8rem;
            }

            .attendance-status {
                width: 20px;
                height: 20px;
                line-height: 20px;
                font-size: 0.7rem;
            }
        }

    </style>
@endpush
