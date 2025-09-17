@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Manajemen Absensi Mata Pelajaran</h4>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="kelasFilter" class="form-label">Filter Kelas:</label>
                        <select id="kelasFilter" class="form-control">
                            <option value="">Pilih Kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" data-name="{{ $class->name }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="matpelFilter" class="form-label">Filter Mata Pelajaran:</label>
                        <select id="matpelFilter" class="form-control" disabled>
                            <option value="">Pilih Mata Pelajaran</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="tanggalFilter" class="form-label">Tanggal:</label>
                        <input type="date" id="tanggalFilter" class="form-control" disabled value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-4">
                        <label for="academicYearFilter" class="form-label">Tahun Akademik:</label>
                        <select id="academicYearFilter" class="form-control">
                            <option value="">Pilih Tahun Akademik</option>
                            @foreach(App\Helpers\AcademicYearHelper::generateAcademicYears(2, 2) as $year)
                                <option value="{{ $year }}" {{ App\Helpers\AcademicYearHelper::getCurrentAcademicYear() == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mb-3">
                    <div class="col-12">
                        <button type="button" id="resetFilters" class="btn btn-secondary">
                            <i class="fas fa-sync-alt"></i> Reset Filter
                        </button>
                        <button type="button" id="editModeBtn" class="btn btn-warning" style="display: none;">
                            <i class="fas fa-edit"></i> Mode Edit
                        </button>
                        <button type="button" id="saveAllBtn" class="btn btn-primary" style="display: none;">
                            <i class="fas fa-save"></i> Simpan Semua
                        </button>
                        <button type="button" id="hadirkanSemuaBtn" class="btn btn-success" style="display: none;">
                            <i class="fas fa-check-double"></i> Hadirkan Semua
                        </button>
                        <button type="button" id="alphakanSemuaBtn" class="btn btn-danger" style="display: none;">
                            <i class="fas fa-times"></i> Alpha Semua
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

                <div class="table-responsive">
                    <table id="mainTable" class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th width="8%">No Absen</th>
                                <th width="25%">Nama Siswa</th>
                                <th width="15%">NISN</th>
                                <th width="12%">Jam Masuk</th>
                                <th width="15%">Status Kehadiran</th>
                                <th width="15%">Keterangan</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <!-- Data akan dimuat via AJAX -->
                        </tbody>
                    </table>
                </div>

                <!-- Loading Indicator -->
                <div id="loadingIndicator" class="text-center py-4" style="display: none;">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data...</p>
                </div>

                <!-- No Data Indicator -->
                <div id="noDataIndicator" class="text-center py-4" style="display: none;">
                    <i class="fas fa-inbox fa-3x text-muted"></i>
                    <p class="mt-2 text-muted">Tidak ada data untuk ditampilkan</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Add/Edit Attendance -->
<div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="attendanceModalTitle">Tambah/Edit Absensi</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="attendanceForm">
                    @csrf
                    <input type="hidden" id="attendanceId" name="attendance_id">
                    <input type="hidden" id="studentId" name="student_id">
                    <input type="hidden" id="classId" name="class_id">
                    <input type="hidden" id="subjectId" name="subject_id">
                    <input type="hidden" id="attendanceDate" name="date">
                    <input type="hidden" id="academicYear" name="academic_year">
                    
                    <div class="form-group">
                        <label>Nama Siswa</label>
                        <input type="text" id="studentName" class="form-control" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label>NISN</label>
                        <input type="text" id="studentNisn" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="checkIn">Jam Masuk <span class="text-muted">(Opsional)</span></label>
                        <input type="time" name="check_in" id="checkIn" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="checkInStatus">Status Kehadiran</label>
                        <select name="check_in_status" id="checkInStatus" class="form-control" required>
                            <option value="">Pilih Status</option>
                            <option value="hadir">Hadir</option>
                            <option value="terlambat">Terlambat</option>
                            <option value="izin">Izin</option>
                            <option value="sakit">Sakit</option>
                            <option value="alpha">Alpha</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" id="saveAttendanceBtn" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="{{ asset('backend/assets/bundles/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/bundles/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">
    <style>
        .status-badge {
            padding: 0.375rem 0.75rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .status-hadir { background-color: #d4edda; color: #155724; }
        .status-terlambat { background-color: #fff3cd; color: #856404; }
        .status-izin { background-color: #cce5ff; color: #004085; }
        .status-sakit { background-color: #f8d7da; color: #721c24; }
        .status-alpha { background-color: #f5c6cb; color: #721c24; }
        
        .editable-cell {
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .editable-cell:hover {
            background-color: #f8f9fa;
        }
        .editing {
            background-color: #fff3cd !important;
        }
    </style>
@endpush

@push('scripts')
    <!-- DataTables JS -->
    <script src="{{ asset('backend/assets/bundles/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            let currentMode = 'view'; // view, edit
            let currentFilters = {
                class_id: '',
                subject_id: '',
                date: '{{ date("Y-m-d") }}',
                academic_year: '{{ App\Helpers\AcademicYearHelper::getCurrentAcademicYear() }}'
            };

            // Initialize DataTable
            const table = $('#mainTable').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "searching": false,
                "paging": false,
                "info": false,
                "ordering": false
            });

            // Status mapping for display
            const statusMap = {
                'hadir': { text: 'Hadir', class: 'status-hadir', icon: 'fas fa-check' },
                'terlambat': { text: 'Terlambat', class: 'status-terlambat', icon: 'fas fa-clock' },
                'izin': { text: 'Izin', class: 'status-izin', icon: 'fas fa-hand-paper' },
                'sakit': { text: 'Sakit', class: 'status-sakit', icon: 'fas fa-thermometer-half' },
                'alpha': { text: 'Alpha', class: 'status-alpha', icon: 'fas fa-times' }
            };

            // Auto input shortcut mappings
            const statusShortcuts = {
                'a': 'alpha',
                'h': 'hadir',
                'i': 'izin',
                's': 'sakit',
                't': 'terlambat'
            };

            // Filter Events
            $('#kelasFilter').change(function() {
                const classId = $(this).val();
                const className = $(this).find('option:selected').data('name');
                
                currentFilters.class_id = classId;
                
                // Reset dependent fields when class changes
                $('#matpelFilter').empty().append('<option value="">Pilih Mata Pelajaran</option>').prop('disabled', true);
                $('#tanggalFilter').prop('disabled', true).val('{{ date("Y-m-d") }}');
                currentFilters.subject_id = '';
                currentFilters.date = '{{ date("Y-m-d") }}';
                
                hideActionButtons();
                clearTable();
                $('#statusInfo').hide();
                
                if (classId) {
                    updateStatus(`Memuat mata pelajaran untuk kelas: ${className}...`);
                    loadStudents();
                    loadSubjectsByClass(classId);
                } else {
                    resetView();
                }
            });

            $('#matpelFilter').change(function() {
                const subjectId = $(this).val();
                const subjectName = $(this).find('option:selected').data('name');
                
                currentFilters.subject_id = subjectId;
                
                if (subjectId && currentFilters.class_id) {
                    updateStatus(`Mata pelajaran: ${subjectName} - Tanggal: ${formatDate(currentFilters.date)}`);
                    $('#tanggalFilter').prop('disabled', false);
                    
                    // Trigger logic untuk check apakah semua filter sudah lengkap
                    checkAndShowButtons();
                    loadStudents();
                } else {
                    $('#tanggalFilter').prop('disabled', true);
                    hideActionButtons();
                    if (currentFilters.class_id) {
                        loadStudents();
                    }
                }
            });

            // Tambahkan fungsi helper untuk mengecek kelengkapan filter
            function checkAndShowButtons() {
                if (currentFilters.class_id && currentFilters.subject_id && currentFilters.date) {
                    const className = $('#kelasFilter').find('option:selected').data('name');
                    const subjectName = $('#matpelFilter').find('option:selected').data('name');
                    const dateText = formatDate(currentFilters.date);
                    
                    updateStatus(`${className} - ${subjectName} - ${dateText} - Mode CRUD aktif`);
                    showActionButtons();
                    loadAttendance();
                }
            }

            $('#tanggalFilter').change(function() {
                const date = $(this).val();
                currentFilters.date = date;
                checkAndShowButtons();
            });

            $('#academicYearFilter').change(function() {
                currentFilters.academic_year = $(this).val();
                
                if (currentFilters.class_id) {
                    loadSubjectsByClass(currentFilters.class_id);
                    $('#matpelFilter').val('').prop('disabled', true);
                    $('#tanggalFilter').prop('disabled', true).val('{{ date("Y-m-d") }}');
                    currentFilters.subject_id = '';
                    currentFilters.date = '{{ date("Y-m-d") }}';
                    hideActionButtons();
                    loadStudents();
                }
                
                if (currentFilters.class_id && currentFilters.subject_id && currentFilters.date) {
                    loadAttendance();
                } else if (currentFilters.class_id) {
                    loadStudents();
                }
            });

            // Reset Filters
            $('#resetFilters').click(function() {
                resetView();
            });

            // Action Buttons
            $('#editModeBtn').click(function() {
                toggleEditMode();
            });

            $('#saveAllBtn').click(function() {
                saveAllAttendance();
            });

            $('#hadirkanSemuaBtn').click(function() {
                bulkSetStatus('hadir');
            });

            $('#alphakanSemuaBtn').click(function() {
                bulkSetStatus('alpha');
            });

            // Modal Events
            $('#saveAttendanceBtn').click(function() {
                saveAttendance();
            });

            // Functions
            function updateStatus(message) {
                $('#statusInfo').show();
                $('#statusText').text(message);
            }

            function resetView() {
                currentFilters = {
                    class_id: '',
                    subject_id: '',
                    date: '{{ date("Y-m-d") }}',
                    academic_year: $('#academicYearFilter').val() || '{{ App\Helpers\AcademicYearHelper::getCurrentAcademicYear() }}'
                };
                
                $('#kelasFilter').val('');
                $('#matpelFilter').empty().append('<option value="">Pilih Mata Pelajaran</option>').prop('disabled', true);
                $('#tanggalFilter').prop('disabled', true).val('{{ date("Y-m-d") }}');
                
                $('#statusInfo').hide();
                hideActionButtons();
                clearTable();
                currentMode = 'view';
            }

            function showActionButtons() {
                $('#editModeBtn').show();
                $('#hadirkanSemuaBtn').show();
                $('#alphakanSemuaBtn').show();
            }

            function hideActionButtons() {
                $('#editModeBtn').hide();
                $('#saveAllBtn').hide();
                $('#hadirkanSemuaBtn').hide();
                $('#alphakanSemuaBtn').hide();
                currentMode = 'view';
            }

            function clearTable() {
                table.clear().draw();
                $('#loadingIndicator').hide();
                $('#noDataIndicator').hide();
            }

            function showLoading() {
                $('#loadingIndicator').show();
                $('#noDataIndicator').hide();
                clearTable();
            }

            function hideLoading() {
                $('#loadingIndicator').hide();
            }

            function showNoData() {
                $('#noDataIndicator').show();
                $('#loadingIndicator').hide();
            }

            function formatDate(dateString) {
                const date = new Date(dateString);
                const options = { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                };
                return date.toLocaleDateString('id-ID', options);
            }

            function loadSubjectsByClass(classId) {
                if (!classId) return;
                
                $.ajax({
                    url: '{{ route("lesson-attendances.get-subjects-by-class") }}',
                    method: 'GET',
                    data: {
                        class_id: classId,
                        academic_year: currentFilters.academic_year
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        const $matpelFilter = $('#matpelFilter');
                        $matpelFilter.empty().append('<option value="">Pilih Mata Pelajaran</option>');
                        
                        if (response.data && response.data.length > 0) {
                            response.data.forEach(function(subject) {
                                $matpelFilter.append(`
                                    <option value="${subject.id}" data-name="${subject.name}">
                                        ${subject.name}
                                    </option>
                                `);
                            });
                            
                            $matpelFilter.prop('disabled', false);
                            
                            const className = $('#kelasFilter').find('option:selected').data('name');
                            updateStatus(`Kelas: ${className} - Pilih mata pelajaran (${response.data.length} tersedia)`);
                        } else {
                            $matpelFilter.prop('disabled', true);
                            updateStatus(`Kelas: ${$('#kelasFilter').find('option:selected').data('name')} - Tidak ada mata pelajaran yang dijadwalkan`);
                        }
                    },
                    error: function(xhr) {
                        console.log('Error loading subjects:', xhr.responseText);
                        $('#matpelFilter').prop('disabled', true);
                        updateStatus(`Error: Gagal memuat mata pelajaran untuk kelas ini`);
                        
                        Swal.fire({
                            title: 'Error',
                            text: 'Gagal memuat mata pelajaran untuk kelas ini',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }

            function loadStudents() {
                showLoading();
                
                $.ajax({
                    url: '{{ route("lesson-attendances.get-students") }}',
                    method: 'GET',
                    data: {
                        class_id: currentFilters.class_id,
                        academic_year: currentFilters.academic_year
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        hideLoading();
                        
                        if (response.data && response.data.length > 0) {
                            displayStudents(response.data);
                        } else {
                            showNoData();
                        }
                    },
                    error: function(xhr) {
                        hideLoading();
                        showNoData();
                        console.log('Error:', xhr.responseText);
                        Swal.fire('Error', 'Gagal memuat data siswa', 'error');
                    }
                });
            }

            function loadAttendance() {
                showLoading();
                
                $.ajax({
                    url: '{{ route("lesson-attendances.get-attendance") }}',
                    method: 'GET',
                    data: currentFilters,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        hideLoading();
                        
                        if (response.data && response.data.length > 0) {
                            displayAttendance(response.data);
                        } else {
                            showNoData();
                        }
                    },
                    error: function(xhr) {
                        hideLoading();
                        showNoData();
                        console.log('Error:', xhr.responseText);
                        Swal.fire('Error', 'Gagal memuat data absensi', 'error');
                    }
                });
            }

            function displayStudents(students) {
                table.clear();
                
                students.forEach(function(student, index) {
                    table.row.add([
                        student.no_absen || '-',
                        student.name,
                        student.nisn,
                        '-',
                        '<span class="text-muted">Pilih tanggal untuk input absensi</span>',
                        '-',
                        '<span class="text-muted">-</span>'
                    ]);
                });
                
                table.draw();
            }

            function displayAttendance(attendances) {
                table.clear();
                
                attendances.forEach(function(attendance, index) {
                    const row = [
                        attendance.no_absen || '-',
                        attendance.student_name,
                        attendance.student_nisn,
                        formatCheckIn(attendance.check_in),
                        formatStatus(attendance.check_in_status),
                        getStatusDescription(attendance.check_in_status),
                        generateActionButtons(attendance)
                    ];
                    
                    const addedRow = table.row.add(row);
                    const rowNode = addedRow.node();
                    $(rowNode).data('student-id', attendance.student_id);
                    $(rowNode).attr('data-student-id', attendance.student_id);
                    $(rowNode).data('attendance-id', attendance.id);
                });
                
                table.draw();
            }

            function formatCheckIn(checkIn) {
                if (!checkIn) return '-';
                return checkIn.substring(0, 5); // HH:MM format
            }

            function formatStatus(status) {
                const statusInfo = statusMap[status] || statusMap['alpha'];
                return `<span class="status-badge ${statusInfo.class}">
                            <i class="${statusInfo.icon}"></i> ${statusInfo.text}
                        </span>`;
            }

            function getStatusDescription(status) {
                const descriptions = {
                    'hadir': 'Mengikuti pembelajaran',
                    'terlambat': 'Datang terlambat',
                    'izin': 'Tidak hadir dengan izin',
                    'sakit': 'Tidak hadir karena sakit', 
                    'alpha': 'Tidak hadir tanpa keterangan'
                };
                return descriptions[status] || descriptions['alpha'];
            }

            function generateActionButtons(attendance) {
                const attendanceData = {
                    id: attendance.id || '',
                    student_id: attendance.student_id,
                    student_name: attendance.student_name,
                    student_nisn: attendance.student_nisn,
                    no_absen: attendance.no_absen,
                    check_in: attendance.check_in,
                    check_in_status: attendance.check_in_status,
                    date: attendance.date,
                    class_id: attendance.class_id,
                    subject_id: attendance.subject_id,
                    academic_year: attendance.academic_year
                };
                
                return `
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-info btn-edit" 
                                data-attendance='${JSON.stringify(attendanceData).replace(/'/g, "&#39;")}'>
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-delete" data-id="${attendance.id || ''}">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
            }

            function toggleEditMode() {
                if (currentMode === 'view') {
                    currentMode = 'edit';
                    $('#editModeBtn').text('Mode View').removeClass('btn-warning').addClass('btn-info');
                    $('#saveAllBtn').show();
                    makeTableEditable();
                } else {
                    currentMode = 'view';
                    $('#editModeBtn').text('Mode Edit').removeClass('btn-info').addClass('btn-warning');
                    $('#saveAllBtn').hide();
                    makeTableReadonly();
                }
            }

            function makeTableEditable() {
                $('#mainTable tbody tr').each(function() {
                    const row = $(this);
                    
                    // Make check_in time column editable (column 3)
                    const checkInCell = row.find('td:eq(3)');
                    const currentCheckIn = checkInCell.text().trim();
                    checkInCell.html(`<input type="time" class="form-control form-control-sm check-in-input" value="${currentCheckIn !== '-' ? currentCheckIn : ''}">`);
                    
                    // Make status column editable (column 4)
                    const statusCell = row.find('td:eq(4)');
                    const currentStatus = getCurrentStatusFromCell(statusCell);
                    statusCell.html(generateStatusSelect(currentStatus));
                });
                
                updateStatus('Mode Edit - Anda dapat mengubah jam masuk dan status kehadiran dengan mengklik pada sel');
            }

            function makeTableReadonly() {
                // Reload attendance to restore original display
                loadAttendance();
                
                const className = $('#kelasFilter').find('option:selected').data('name');
                const subjectName = $('#matpelFilter').find('option:selected').data('name'); 
                const dateText = formatDate($('#tanggalFilter').val());
                updateStatus(`${className} - ${subjectName} - ${dateText} - Mode CRUD aktif`);
            }

            function getCurrentStatusFromCell(cell) {
                const statusText = cell.text().toLowerCase();
                if (statusText.includes('hadir')) return 'hadir';
                if (statusText.includes('terlambat')) return 'terlambat';
                if (statusText.includes('izin')) return 'izin';
                if (statusText.includes('sakit')) return 'sakit';
                return 'alpha';
            }

            function generateStatusSelect(selectedStatus) {
                let options = '';
                Object.keys(statusMap).forEach(function(status) {
                    const selected = status === selectedStatus ? 'selected' : '';
                    options += `<option value="${status}" ${selected}>${statusMap[status].text}</option>`;
                });
                
                return `<select class="form-control form-control-sm status-select">${options}</select>`;
            }

            function bulkSetStatus(status) {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: `Apakah Anda yakin ingin mengatur semua siswa menjadi ${statusMap[status].text}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#mainTable tbody tr').each(function() {
                            const row = $(this);
                            if (currentMode === 'edit') {
                                row.find('.status-select').val(status);
                                if (status === 'hadir' && !row.find('.check-in-input').val()) {
                                    row.find('.check-in-input').val('07:00');
                                }
                            } else {
                                // If not in edit mode, switch to edit mode first
                                if (currentMode === 'view') {
                                    toggleEditMode();
                                    setTimeout(() => {
                                        bulkSetStatus(status);
                                    }, 100);
                                    return false;
                                }
                            }
                        });
                        
                        Swal.fire('Berhasil', `Semua siswa berhasil diatur menjadi ${statusMap[status].text}`, 'success');
                    }
                });
            }

            function openAttendanceModal(attendanceData = null) {
                if (!currentFilters.class_id || !currentFilters.subject_id || !currentFilters.date) {
                    Swal.fire('Perhatian', 'Pilih kelas, mata pelajaran, dan tanggal terlebih dahulu', 'warning');
                    return;
                }

                if (attendanceData) {
                    $('#attendanceModalTitle').text('Edit Absensi Siswa');
                    populateAttendanceForm(attendanceData);
                } else {
                    $('#attendanceModalTitle').text('Tambah Absensi Siswa');
                    clearAttendanceForm();
                }

                $('#attendanceModal').modal('show');
            }

            function populateAttendanceForm(attendanceData) {
                $('#attendanceId').val(attendanceData.id || '');
                $('#studentId').val(attendanceData.student_id);
                $('#classId').val(currentFilters.class_id);
                $('#subjectId').val(currentFilters.subject_id);
                $('#attendanceDate').val(currentFilters.date);
                $('#academicYear').val(currentFilters.academic_year);
                
                $('#studentName').val(attendanceData.student_name || '');
                $('#studentNisn').val(attendanceData.student_nisn || '');
                $('#checkIn').val(attendanceData.check_in || '');
                $('#checkInStatus').val(attendanceData.check_in_status || '');
            }

            function clearAttendanceForm() {
                $('#attendanceForm')[0].reset();
                $('#attendanceId').val('');
                $('#studentId').val('');
                $('#classId').val(currentFilters.class_id);
                $('#subjectId').val(currentFilters.subject_id);
                $('#attendanceDate').val(currentFilters.date);
                $('#academicYear').val(currentFilters.academic_year);
            }

            function saveAttendance() {
                const formData = new FormData($('#attendanceForm')[0]);
                
                const url = $('#attendanceId').val() ? 
                    `{{ url('lesson-attendances') }}/${$('#attendanceId').val()}` : 
                    '{{ route("lesson-attendances.store") }}';
                
                const method = $('#attendanceId').val() ? 'PUT' : 'POST';
                
                if (method === 'PUT') {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#attendanceModal').modal('hide');
                        Swal.fire('Berhasil', 'Absensi berhasil disimpan', 'success');
                        loadAttendance();
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors || {};
                        let errorMessage = 'Gagal menyimpan absensi';
                        
                        if (Object.keys(errors).length > 0) {
                            errorMessage = Object.values(errors).flat().join('<br>');
                        }
                        
                        Swal.fire('Error', errorMessage, 'error');
                    }
                });
            }

            function saveAllAttendance() {
                const attendancesData = [];
                let hasChanges = false;
                
                $('#mainTable tbody tr').each(function() {
                    const row = $(this);
                    const studentId = row.data('student-id');
                    
                    if (!studentId) {
                        return;
                    }
                    
                    const checkIn = row.find('.check-in-input').val();
                    const status = row.find('.status-select').val();
                    
                    hasChanges = true;
                    
                    const attendanceData = {
                        student_id: studentId,
                        class_id: currentFilters.class_id,
                        subject_id: currentFilters.subject_id,
                        date: currentFilters.date,
                        academic_year: currentFilters.academic_year,
                        check_in: checkIn || null,
                        check_in_status: status || 'alpha'
                    };
                    
                    attendancesData.push(attendanceData);
                });
                
                if (!hasChanges || attendancesData.length === 0) {
                    Swal.fire('Perhatian', 'Tidak ada perubahan data untuk disimpan', 'warning');
                    return;
                }
                
                Swal.fire({
                    title: 'Menyimpan Data...',
                    text: `Menyimpan ${attendancesData.length} data absensi`,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '{{ route("lesson-attendances.bulk-update") }}',
                    method: 'POST',
                    data: {
                        attendances: attendancesData,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    success: function(response) {
                        Swal.close();
                        
                        if (response.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK',
                                timer: 3000,
                                timerProgressBar: true
                            });
                            
                            toggleEditMode();
                            loadAttendance();
                        } else {
                            throw new Error(response.message || 'Unknown error');
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        
                        let errorMessage = 'Gagal menyimpan absensi';
                        
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            
                            if (errorResponse.message) {
                                errorMessage = errorResponse.message;
                            }
                            
                        } catch (parseError) {
                            errorMessage += '\n\nRaw error: ' + xhr.responseText;
                        }
                        
                        Swal.fire({
                            title: 'Error',
                            text: errorMessage,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }

            // Event delegation for dynamic buttons
            $(document).on('click', '.btn-edit', function() {
                try {
                    const attendanceDataStr = $(this).attr('data-attendance');
                    const attendanceData = JSON.parse(attendanceDataStr);
                    openAttendanceModal(attendanceData);
                } catch (error) {
                    console.error('Error parsing attendance data:', error);
                    Swal.fire('Error', 'Gagal memuat data absensi', 'error');
                }
            });

            $(document).on('click', '.btn-delete', function() {
                const attendanceId = $(this).data('id');
                
                if (!attendanceId) {
                    Swal.fire('Perhatian', 'Data absensi tidak ditemukan', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Hapus Absensi?',
                    text: 'Data yang dihapus tidak dapat dikembalikan',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteAttendance(attendanceId);
                    }
                });
            });

            function deleteAttendance(attendanceId) {
                $.ajax({
                    url: `{{ url('lesson-attendances') }}/${attendanceId}`,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire('Berhasil', 'Absensi berhasil dihapus', 'success');
                        loadAttendance();
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal menghapus absensi', 'error');
                    }
                });
            }

            // Keyboard shortcuts for quick status entry
            $(document).on('keydown', '.status-select', function(e) {
                const key = String.fromCharCode(e.which).toLowerCase();
                if (statusShortcuts[key]) {
                    $(this).val(statusShortcuts[key]);
                    e.preventDefault();
                    
                    // Auto set check-in time for 'hadir' status
                    if (statusShortcuts[key] === 'hadir') {
                        const checkInInput = $(this).closest('tr').find('.check-in-input');
                        if (!checkInInput.val()) {
                            checkInInput.val('07:00');
                        }
                    }
                }
            });

            // Initialize with current academic year and today's date
            $('#academicYearFilter').val('{{ App\Helpers\AcademicYearHelper::getCurrentAcademicYear() }}');
            $('#tanggalFilter').val('{{ date("Y-m-d") }}');
            currentFilters.academic_year = '{{ App\Helpers\AcademicYearHelper::getCurrentAcademicYear() }}';
            currentFilters.date = '{{ date("Y-m-d") }}';
        });
    </script>
@endpush