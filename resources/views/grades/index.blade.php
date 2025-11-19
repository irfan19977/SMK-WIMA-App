@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Manajemen Nilai Siswa</h4>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-3 align-items-end">
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
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" data-name="{{ $subject->name }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="assessmentTypeFilter" class="form-label">Jenis Penilaian:</label>
                        <select id="assessmentTypeFilter" class="form-control">
                            <option value="bulanan" selected>Nilai Bulanan</option>
                            <option value="uts">UTS</option>
                            <option value="uas">UAS</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="bulanFilter" class="form-label">Filter Bulan:</label>
                        <select id="bulanFilter" class="form-control" disabled>
                            <option value="">Pilih Bulan</option>
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                            <option value="7">Juli</option>
                            <option value="8">Agustus</option>
                            <option value="9">September</option>
                            <option value="10">Oktober</option>
                            <option value="11">November</option>
                            <option value="12">Desember</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="semesterFilter" class="form-label">Semester:</label>
                        <select id="semesterFilter" class="form-control" disabled>
                            <option value="">Pilih Semester</option>
                            <option value="ganjil">Ganjil</option>
                            <option value="genap">Genap</option>
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
                    <div class="col-md-auto">
                        <div class="form-group mb-0">
                            <button type="button" id="resetFilters" class="btn btn-secondary mr-2">
                                <i class="fas fa-sync-alt"></i> Reset Filter
                            </button>
                            <button type="button" id="editModeBtn" class="btn btn-warning mr-2" style="display: none;">
                                <i class="fas fa-edit"></i> Mode Edit
                            </button>
                            <button type="button" id="saveAllBtn" class="btn btn-primary" style="display: none;">
                                <i class="fas fa-save"></i> Simpan Semua
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons moved next to filters above -->

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
                                <th>No Absen</th>
                                <th>Nama Siswa</th>
                                <th>NISN</th>
                                <th id="colH1">H1</th>
                                <th id="colH2">H2</th>
                                <th id="colH3">H3</th>
                                <th id="colK1">K1</th>
                                <th id="colK2">K2</th>
                                <th id="colK3">K3</th>
                                <th id="colCK">CK</th>
                                <th id="colP">P</th>
                                <th id="colK">K</th>
                                <th id="colAktif">Aktif</th>
                                <th id="colNilai">Nilai</th>
                                <th>Aksi</th>
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

<!-- Modal for Add/Edit Grade -->
<div class="modal fade" id="gradeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gradeModalTitle">Tambah/Edit Nilai</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="gradeForm">
                    @csrf
                    <input type="hidden" id="gradeId" name="grade_id">
                    <input type="hidden" id="studentId" name="student_id">
                    <input type="hidden" id="classId" name="class_id">
                    <input type="hidden" id="subjectId" name="subject_id">
                    
                    <div class="row">
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

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>H1</label>
                                <input type="number" name="h1" id="h1" class="form-control" min="0" max="100" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>H2</label>
                                <input type="number" name="h2" id="h2" class="form-control" min="0" max="100" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>H3</label>
                                <input type="number" name="h3" id="h3" class="form-control" min="0" max="100" step="0.01">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>K1</label>
                                <input type="number" name="k1" id="k1" class="form-control" min="0" max="100" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>K2</label>
                                <input type="number" name="k2" id="k2" class="form-control" min="0" max="100" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>K3</label>
                                <input type="number" name="k3" id="k3" class="form-control" min="0" max="100" step="0.01">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>CK</label>
                                <input type="number" name="ck" id="ck" class="form-control" min="0" max="100" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>P</label>
                                <input type="number" name="p" id="p" class="form-control" min="0" max="100" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>K</label>
                                <input type="number" name="k" id="k" class="form-control" min="0" max="100" step="0.01">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Aktif</label>
                                <input type="number" name="aktif" id="aktif" class="form-control" min="0" max="100" step="0.01">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Nilai Akhir</label>
                                <input type="number" name="nilai" id="nilai" class="form-control" min="0" max="100" step="0.01" readonly>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" id="saveGradeBtn" class="btn btn-primary">Simpan</button>
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
                month: '',
                semester: '',
                academic_year: '',
                assessment_type: 'bulanan'
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

            // Set initial columns based on default assessment type
            updateColumnsForAssessmentType(currentFilters.assessment_type);

            // Assessment type change
            $('#assessmentTypeFilter').change(function() {
                const type = $(this).val();
                currentFilters.assessment_type = type;

                // Toggle month/semester controls
                if (type === 'bulanan') {
                    $('#bulanFilter').prop('disabled', false);
                    $('#semesterFilter').val('').prop('disabled', true);
                    currentFilters.semester = '';
                } else {
                    $('#bulanFilter').val('').prop('disabled', true);
                    $('#semesterFilter').prop('disabled', false);
                    currentFilters.month = '';
                }

                // Update columns to reflect type
                updateColumnsForAssessmentType(type);

                // Reset data view
                hideActionButtons();
                clearTable();
                $('#statusInfo').hide();

                // Reload data depending on state
                if (currentFilters.class_id && currentFilters.subject_id) {
                    if (type === 'bulanan') {
                        // wait for month selection
                        loadStudents();
                        updateStatus(`Pilih bulan untuk input nilai bulanan`);
                    } else if (currentFilters.semester) {
                        updateStatus(`Mode ${type.toUpperCase()} - Pilih semester untuk input nilai`);
                        loadGrades();
                    } else {
                        loadStudents();
                        updateStatus(`Pilih semester untuk input nilai ${type.toUpperCase()}`);
                    }
                }
            });

            // Filter Events
            $('#kelasFilter').change(function() {
                const classId = $(this).val();
                const className = $(this).find('option:selected').data('name');
                
                currentFilters.class_id = classId;
                
                // Reset dependent fields when class changes
                $('#matpelFilter').empty().append('<option value="">Pilih Mata Pelajaran</option>').prop('disabled', true);
                $('#bulanFilter').val('').prop('disabled', true);
                $('#semesterFilter').val('').prop('disabled', currentFilters.assessment_type === 'bulanan');
                currentFilters.subject_id = '';
                currentFilters.month = '';
                currentFilters.semester = '';
                
                // Hide action buttons
                hideActionButtons();
                
                // Clear table and status
                clearTable();
                $('#statusInfo').hide();
                
                if (classId) {
                    updateStatus(`Memuat mata pelajaran untuk kelas: ${className}...`);
                    
                    // Load students for the class
                    loadStudents();
                    
                    // Load subjects for the class
                    loadSubjectsByClass(classId);
                } else {
                    resetView();
                }
            });

            $('#matpelFilter').change(function() {
                const subjectId = $(this).val();
                const subjectName = $(this).find('option:selected').data('name');
                
                currentFilters.subject_id = subjectId;
                
                // Reset month field when subject changes
                $('#bulanFilter').val('').prop('disabled', currentFilters.assessment_type !== 'bulanan');
                $('#semesterFilter').val('').prop('disabled', currentFilters.assessment_type === 'bulanan' ? true : false);
                currentFilters.month = '';
                currentFilters.semester = '';
                
                // Hide action buttons
                hideActionButtons();
                
                // Clear grades data but keep students
                if (currentFilters.class_id) {
                    if (subjectId) {
                        if (currentFilters.assessment_type === 'bulanan') {
                            updateStatus(`Mata pelajaran: ${subjectName} - Pilih bulan untuk input/edit nilai`);
                            $('#bulanFilter').prop('disabled', false);
                        } else {
                            updateStatus(`Mata pelajaran: ${subjectName} - Pilih semester untuk input nilai ${currentFilters.assessment_type.toUpperCase()}`);
                            $('#semesterFilter').prop('disabled', false);
                        }
                        // Reload students to show clean table without grades
                        loadStudents();
                    } else {
                        updateStatus(`Menampilkan siswa di kelas: ${$('#kelasFilter').find('option:selected').data('name')}`);
                        loadStudents();
                    }
                }
            });

            $('#bulanFilter').change(function() {
                const month = $(this).val();
                const monthName = $(this).find('option:selected').text();
                
                currentFilters.month = month;
                
                if (currentFilters.assessment_type === 'bulanan' && month && currentFilters.class_id && currentFilters.subject_id) {
                    updateStatus(`Bulan: ${monthName} - Mode CRUD aktif`);
                    showActionButtons();
                    loadGrades();
                } else {
                    hideActionButtons();
                    // If month is cleared but we still have class and subject, show students
                    if (currentFilters.assessment_type === 'bulanan' && currentFilters.class_id && currentFilters.subject_id) {
                        const subjectName = $('#matpelFilter').find('option:selected').data('name');
                        updateStatus(`Mata pelajaran: ${subjectName} - Pilih bulan untuk input/edit nilai`);
                        loadStudents();
                    } else if (currentFilters.class_id) {
                        const className = $('#kelasFilter').find('option:selected').data('name');
                        updateStatus(`Menampilkan siswa di kelas: ${className}`);
                        loadStudents();
                    }
                }
            });

            $('#semesterFilter').change(function() {
                const semester = $(this).val();
                currentFilters.semester = semester;

                if (currentFilters.assessment_type !== 'bulanan' && semester && currentFilters.class_id && currentFilters.subject_id) {
                    updateStatus(`Semester: ${semester} - Mode ${currentFilters.assessment_type.toUpperCase()} aktif`);
                    showActionButtons();
                    loadGrades();
                } else {
                    hideActionButtons();
                    if (currentFilters.class_id && currentFilters.subject_id) {
                        const subjectName = $('#matpelFilter').find('option:selected').data('name');
                        updateStatus(`Mata pelajaran: ${subjectName} - Pilih semester untuk input nilai ${currentFilters.assessment_type.toUpperCase()}`);
                        loadStudents();
                    }
                }
            });

            $('#academicYearFilter').change(function() {
                currentFilters.academic_year = $(this).val();
                
                // Reload subjects if class is selected
                if (currentFilters.class_id) {
                    loadSubjectsByClass(currentFilters.class_id);
                    
                    // Reset dependent fields
                    $('#matpelFilter').val('').prop('disabled', true);
                    $('#bulanFilter').val('').prop('disabled', true);
                    currentFilters.subject_id = '';
                    currentFilters.month = '';
                    hideActionButtons();
                    
                    // Reload students
                    loadStudents();
                }
                
                // Reload appropriate data based on current filter state
                if (currentFilters.class_id && currentFilters.subject_id && currentFilters.month) {
                    loadGrades();
                } else if (currentFilters.class_id) {
                    loadStudents();
                }
            });

            // Reset Filters
            $('#resetFilters').click(function() {
                resetView();
            });

            // Action Buttons
            $('#addGradeBtn').click(function() {
                openGradeModal();
            });

            $('#editModeBtn').click(function() {
                toggleEditMode();
            });

            $('#saveAllBtn').click(function() {
                saveAllGrades();
            });

            // Modal Events
            $('#saveGradeBtn').click(function() {
                saveGrade();
            });

            // Auto calculate P, K, and Nilai when H or K inputs change
            $('#gradeForm input[name="h1"], #gradeForm input[name="h2"], #gradeForm input[name="h3"], #gradeForm input[name="k1"], #gradeForm input[name="k2"], #gradeForm input[name="k3"]').on('input', function() {
                calculateFinalGrade();
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
                    month: '',
                    semester: '',
                    academic_year: $('#academicYearFilter').val() || '{{ date("Y") }}/{{ date("Y") + 1 }}',
                    assessment_type: $('#assessmentTypeFilter').val() || 'bulanan'
                };
                
                $('#kelasFilter').val('');
                $('#matpelFilter').empty().append('<option value="">Pilih Mata Pelajaran</option>').prop('disabled', true);
                $('#bulanFilter').val('').prop('disabled', true);
                $('#semesterFilter').val('').prop('disabled', currentFilters.assessment_type === 'bulanan');
                // Don't reset academic year as it should persist
                
                $('#statusInfo').hide();
                hideActionButtons();
                clearTable();
                currentMode = 'view';
                updateColumnsForAssessmentType(currentFilters.assessment_type);
            }

            function showActionButtons() {
                $('#addGradeBtn').show();
                $('#editModeBtn').show();
            }

            function hideActionButtons() {
                $('#addGradeBtn').hide();
                $('#editModeBtn').hide();
                $('#saveAllBtn').hide();
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

            function loadSubjectsByClass(classId) {
                if (!classId) return;
                
                $.ajax({
                    url: '{{ route("student-grades.get-subjects-by-class") }}',
                    method: 'GET',
                    data: {
                        class_id: classId,
                        academic_year: currentFilters.academic_year || '{{ date("Y") }}/{{ date("Y") + 1 }}'
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
                    url: '{{ route("student-grades.get-students") }}',
                    method: 'GET',
                    data: {
                        class_id: currentFilters.class_id,
                        academic_year: currentFilters.academic_year || '{{ date("Y") }}/{{ date("Y") + 1 }}'
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

            function loadGrades() {
                showLoading();
                
                $.ajax({
                    url: '{{ route("student-grades.get-grades") }}',
                    method: 'GET',
                    data: currentFilters,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        hideLoading();
                        
                        if (response.data && response.data.length > 0) {
                            displayGrades(response.data);
                        } else {
                            showNoData();
                        }
                    },
                    error: function(xhr) {
                        hideLoading();
                        showNoData();
                        console.log('Error:', xhr.responseText);
                        Swal.fire('Error', 'Gagal memuat data nilai', 'error');
                    }
                });
            }

            function displayStudents(students) {
                table.clear();
                
                students.forEach(function(student, index) {
                    table.row.add([
                        student.no_absen || '-',  // Ubah dari: index + 1
                        student.name,
                        student.nisn,
                        '-', '-', '-', '-', '-', '-', '-', '-', '-', '-', '-',
                        '<span class="text-muted">Pilih mata pelajaran dan ' + (currentFilters.assessment_type === 'bulanan' ? 'bulan' : 'semester') + '</span>'
                    ]);
                });
                
                table.draw();
            }

            function displayGrades(grades) {
                table.clear();
                
                grades.forEach(function(grade, index) {
                    const type = currentFilters.assessment_type;

                    let row;
                    if (type === 'bulanan') {
                        row = [
                            grade.no_absen || '-',
                            grade.student_name,
                            grade.student_nisn,
                            formatGrade(grade.tugas1), // col 3 (H1 renamed later)
                            formatGrade(grade.tugas2), // col 4 (H2 renamed later)
                            '-', // H3 hidden for bulanan
                            '-', '-', '-', // K1-K3 hidden
                            '-', // CK hidden
                            '-', // P hidden
                            formatGrade(grade.sikap), // K column reused as Sikap
                            '-', // Aktif hidden for now
                            '-', // Nilai left empty for bulanan
                            generateActionButtons(grade)
                        ];
                    } else {
                        row = [
                            grade.no_absen || '-',
                            grade.student_name,
                            grade.student_nisn,
                            '-', '-', '-', '-', '-', '-', '-', '-', '-', '-',
                            formatGrade(grade.nilai, true),
                            generateActionButtons(grade)
                        ];
                    }

                    const addedRow = table.row.add(row);
                    
                    // CRITICAL: Store student_id as data attribute
                    const rowNode = addedRow.node();
                    $(rowNode).data('student-id', grade.student_id);
                    $(rowNode).attr('data-student-id', grade.student_id); // Backup attribute
                    
                    console.log('Added row for student:', grade.student_id, 'Row data:', $(rowNode).data('student-id'));
                });
                
                table.draw();
                
                // Verify student IDs are stored
                console.log('Total rows with student IDs:', $('#mainTable tbody tr[data-student-id]').length);
            }

            function updateColumnsForAssessmentType(type) {
                // Reset all headers to default first
                $('#colH1').text(type === 'bulanan' ? 'Tugas 1' : 'H1');
                $('#colH2').text(type === 'bulanan' ? 'Tugas 2' : 'H2');
                $('#colK').text(type === 'bulanan' ? 'Sikap' : 'K');

                const showForBulanan = [0,1,2,3,4,11,13,14];
                const showForUjian = [0,1,2,13,14];
                const totalCols = 15; // 0..14

                const visibleCols = type === 'bulanan' ? showForBulanan : showForUjian;

                for (let i = 0; i < totalCols; i++) {
                    const shouldShow = visibleCols.indexOf(i) !== -1;
                    table.column(i).visible(shouldShow);
                }
            }

            // New function to calculate P, K, and Nilai based on the correct logic
            function calculateGradeValues(grade) {
                // Calculate P (average of H1, H2, H3)
                const h1 = parseFloat(grade.h1) || 0;
                const h2 = parseFloat(grade.h2) || 0;
                const h3 = parseFloat(grade.h3) || 0;
                
                let pValue = 0;
                let hCount = 0;
                
                if (h1 > 0) { pValue += h1; hCount++; }
                if (h2 > 0) { pValue += h2; hCount++; }
                if (h3 > 0) { pValue += h3; hCount++; }
                
                pValue = hCount > 0 ? (pValue / hCount) : 0;
                
                // Calculate K (average of K1, K2, K3)
                const k1 = parseFloat(grade.k1) || 0;
                const k2 = parseFloat(grade.k2) || 0;
                const k3 = parseFloat(grade.k3) || 0;
                
                let kValue = 0;
                let kCount = 0;
                
                if (k1 > 0) { kValue += k1; kCount++; }
                if (k2 > 0) { kValue += k2; kCount++; }
                if (k3 > 0) { kValue += k3; kCount++; }
                
                kValue = kCount > 0 ? (kValue / kCount) : 0;
                
                // Calculate final grade (average of P and K)
                let finalGrade = 0;
                let finalCount = 0;
                
                if (pValue > 0) { finalGrade += pValue; finalCount++; }
                if (kValue > 0) { finalGrade += kValue; finalCount++; }
                
                finalGrade = finalCount > 0 ? (finalGrade / finalCount) : 0;
                
                return {
                    p: pValue,
                    k: kValue,
                    nilai: finalGrade
                };
            }

            // Updated calculateFinalGrade function for modal form
            function calculateFinalGrade() {
                // Calculate P (average of H1, H2, H3)
                const h1 = parseFloat($('#h1').val()) || 0;
                const h2 = parseFloat($('#h2').val()) || 0;
                const h3 = parseFloat($('#h3').val()) || 0;
                
                let pValue = 0;
                let hCount = 0;
                
                if (h1 > 0) { pValue += h1; hCount++; }
                if (h2 > 0) { pValue += h2; hCount++; }
                if (h3 > 0) { pValue += h3; hCount++; }
                
                pValue = hCount > 0 ? (pValue / hCount) : 0;
                $('#p').val(pValue.toFixed(2));
                
                // Calculate K (average of K1, K2, K3)
                const k1 = parseFloat($('#k1').val()) || 0;
                const k2 = parseFloat($('#k2').val()) || 0;
                const k3 = parseFloat($('#k3').val()) || 0;
                
                let kValue = 0;
                let kCount = 0;
                
                if (k1 > 0) { kValue += k1; kCount++; }
                if (k2 > 0) { kValue += k2; kCount++; }
                if (k3 > 0) { kValue += k3; kCount++; }
                
                kValue = kCount > 0 ? (kValue / kCount) : 0;
                $('#k').val(kValue.toFixed(2));
                
                // Calculate final grade (average of P and K)
                let finalGrade = 0;
                let finalCount = 0;
                
                if (pValue > 0) { finalGrade += pValue; finalCount++; }
                if (kValue > 0) { finalGrade += kValue; finalCount++; }
                
                finalGrade = finalCount > 0 ? (finalGrade / finalCount) : 0;
                $('#nilai').val(finalGrade.toFixed(2));
            }

            // Function to calculate inline grades for bulk editing
            function calculateInlineGrades(row) {
                // Get H values from the row (columns 3, 4, 5)
                const h1 = getNumericValue(row.find('td:eq(3)').text()) || 0;
                const h2 = getNumericValue(row.find('td:eq(4)').text()) || 0;
                const h3 = getNumericValue(row.find('td:eq(5)').text()) || 0;
                
                // Calculate P (average of H values that are > 0)
                let pValue = 0;
                let hCount = 0;
                if (h1 > 0) { pValue += h1; hCount++; }
                if (h2 > 0) { pValue += h2; hCount++; }
                if (h3 > 0) { pValue += h3; hCount++; }
                pValue = hCount > 0 ? (pValue / hCount) : 0;
                
                // Get K values from the row (columns 6, 7, 8)
                const k1 = getNumericValue(row.find('td:eq(6)').text()) || 0;
                const k2 = getNumericValue(row.find('td:eq(7)').text()) || 0;
                const k3 = getNumericValue(row.find('td:eq(8)').text()) || 0;
                
                // Calculate K (average of K values that are > 0)
                let kValue = 0;
                let kCount = 0;
                if (k1 > 0) { kValue += k1; kCount++; }
                if (k2 > 0) { kValue += k2; kCount++; }
                if (k3 > 0) { kValue += k3; kCount++; }
                kValue = kCount > 0 ? (kValue / kCount) : 0;
                
                // Calculate final grade (average of P and K if both > 0)
                let finalGrade = 0;
                let finalCount = 0;
                if (pValue > 0) { finalGrade += pValue; finalCount++; }
                if (kValue > 0) { finalGrade += kValue; finalCount++; }
                finalGrade = finalCount > 0 ? (finalGrade / finalCount) : 0;
                
                // Update P column (10)
                row.find('td:eq(10)').text(pValue > 0 ? pValue.toFixed(2) : '-');
                
                // Update K column (11) 
                row.find('td:eq(11)').text(kValue > 0 ? kValue.toFixed(2) : '-');
                
                // Update Nilai column (13) with badge
                const nilaiCell = row.find('td:eq(13)');
                if (finalGrade > 0) {
                    nilaiCell.html(formatGrade(finalGrade, true));
                } else {
                    nilaiCell.html('<span class="badge badge-secondary">-</span>');
                }
            }

            function formatGrade(value, isFinal = false) {
                if (value === null || value === undefined || value === '' || value === 0) {
                    return isFinal ? '<span class="badge badge-secondary">-</span>' : '-';
                }
                
                const numValue = parseFloat(value);
                let badgeClass = 'badge-secondary';
                
                if (isFinal) {
                    if (numValue >= 80) badgeClass = 'badge-success';
                    else if (numValue >= 70) badgeClass = 'badge-warning';
                    else if (numValue >= 60) badgeClass = 'badge-info';
                    else badgeClass = 'badge-danger';
                    
                    return `<span class="badge ${badgeClass}">${numValue.toFixed(2)}</span>`;
                }
                
                return numValue.toFixed(2);
            }

            function generateActionButtons(grade) {
                // Buat objek data yang lengkap untuk tombol edit
                const gradeDataForEdit = {
                    id: grade.id || '',
                    student_id: grade.student_id,
                    student_name: grade.student_name,
                    student_nisn: grade.student_nisn,
                    no_absen: grade.no_absen,
                    h1: grade.h1,
                    h2: grade.h2,
                    h3: grade.h3,
                    k1: grade.k1,
                    k2: grade.k2,
                    k3: grade.k3,
                    ck: grade.ck,
                    p: grade.p,
                    k: grade.k,
                    aktif: grade.aktif,
                    nilai: grade.nilai
                };
                
                return `
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-info btn-edit" 
                                data-grade='${JSON.stringify(gradeDataForEdit).replace(/'/g, "&#39;")}'>
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-delete" data-id="${grade.id || ''}">
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
                    
                    // Store original values as data attributes for comparison
                    row.find('td:eq(3)').attr('data-original', row.find('td:eq(3)').text()); // H1
                    row.find('td:eq(4)').attr('data-original', row.find('td:eq(4)').text()); // H2
                    row.find('td:eq(5)').attr('data-original', row.find('td:eq(5)').text()); // H3
                    row.find('td:eq(6)').attr('data-original', row.find('td:eq(6)').text()); // K1
                    row.find('td:eq(7)').attr('data-original', row.find('td:eq(7)').text()); // K2
                    row.find('td:eq(8)').attr('data-original', row.find('td:eq(8)').text()); // K3
                    row.find('td:eq(9)').attr('data-original', row.find('td:eq(9)').text()); // CK
                    row.find('td:eq(12)').attr('data-original', row.find('td:eq(12)').text()); // Aktif
                    
                    // Make specific columns editable (H1-H3, K1-K3, CK, Aktif)
                    row.find('td:eq(3), td:eq(4), td:eq(5), td:eq(6), td:eq(7), td:eq(8), td:eq(9), td:eq(12)')
                    .addClass('editable-cell')
                    .attr('contenteditable', 'true')
                    .css({
                        'background-color': '#fff3cd',
                        'cursor': 'text',
                        'border': '1px dashed #ffeaa7'
                    });
                    
                    // Make P, K, Nilai non-editable but visually distinct
                    row.find('td:eq(10), td:eq(11), td:eq(13)')
                    .addClass('auto-calculated')
                    .css({
                        'background-color': '#f8f9fa',
                        'color': '#6c757d',
                        'font-style': 'italic'
                    });
                });
                
                updateStatus('Mode Edit - Anda dapat mengubah nilai dengan mengklik pada sel');
            }

            function makeTableReadonly() {
                $('#mainTable tbody td')
                    .removeClass('editable-cell editing auto-calculated')
                    .attr('contenteditable', 'false')
                    .css({
                        'background-color': '',
                        'cursor': '',
                        'border': '',
                        'color': '',
                        'font-style': ''
                    });
                
                const className = $('#kelasFilter').find('option:selected').data('name');
                const subjectName = $('#matpelFilter').find('option:selected').data('name'); 
                const monthName = $('#bulanFilter').find('option:selected').text();
                updateStatus(`${className} - ${subjectName} - ${monthName} - Mode CRUD aktif`);
            }

            function openGradeModal(gradeData = null) {
                if (!currentFilters.class_id || !currentFilters.subject_id || !currentFilters.month) {
                    Swal.fire('Perhatian', 'Pilih kelas, mata pelajaran, dan bulan terlebih dahulu', 'warning');
                    return;
                }

                if (gradeData) {
                    // Edit mode
                    $('#gradeModalTitle').text('Edit Nilai Siswa');
                    populateGradeForm(gradeData);
                } else {
                    // Add mode - show student selection
                    $('#gradeModalTitle').text('Tambah Nilai Siswa');
                    clearGradeForm();
                }

                $('#gradeModal').modal('show');
            }

            function populateGradeForm(gradeData) {
                console.log('Grade data:', gradeData); // Debug log
                
                $('#gradeId').val(gradeData.id || '');
                $('#studentId').val(gradeData.student_id);
                $('#classId').val(currentFilters.class_id);
                $('#subjectId').val(currentFilters.subject_id);
                
                // Perbaikan: pastikan data nama siswa tersedia
                $('#studentName').val(gradeData.student_name || gradeData.name || '');
                $('#studentNisn').val(gradeData.student_nisn || gradeData.nisn || '');
                
                // Populate grade values
                ['h1', 'h2', 'h3', 'k1', 'k2', 'k3', 'ck', 'aktif'].forEach(function(field) {
                    $(`#${field}`).val(gradeData[field] || '');
                });
                
                // Calculate and populate P, K, and Nilai
                calculateFinalGrade();
            }

            function clearGradeForm() {
                $('#gradeForm')[0].reset();
                $('#gradeId').val('');
                $('#studentId').val('');
                $('#classId').val(currentFilters.class_id);
                $('#subjectId').val(currentFilters.subject_id);
                
                // Clear calculated values
                $('#p').val('');
                $('#k').val('');
                $('#nilai').val('');
            }

            function saveGrade() {
                const formData = new FormData($('#gradeForm')[0]);
                formData.append('month', currentFilters.month);
                formData.append('academic_year', currentFilters.academic_year || '{{ date("Y") }}/{{ date("Y") + 1 }}');
                formData.append('semester', 'ganjil'); // You can make this dynamic
                
                const url = $('#gradeId').val() ? 
                    `{{ url('student-grades') }}/${$('#gradeId').val()}` : 
                    '{{ route("student-grades.store") }}';
                
                const method = $('#gradeId').val() ? 'PUT' : 'POST';
                
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
                        $('#gradeModal').modal('hide');
                        Swal.fire('Berhasil', 'Nilai berhasil disimpan', 'success');
                        loadGrades();
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors || {};
                        let errorMessage = 'Gagal menyimpan nilai';
                        
                        if (Object.keys(errors).length > 0) {
                            errorMessage = Object.values(errors).flat().join('<br>');
                        }
                        
                        Swal.fire('Error', errorMessage, 'error');
                    }
                });
            }

            function saveAllGrades() {
                const gradesData = [];
                let hasChanges = false;
                
                $('#mainTable tbody tr').each(function() {
                    const row = $(this);
                    const studentId = row.data('student-id');
                    
                    if (!studentId) {
                        console.log('No student ID found for row');
                        return; // Skip if no student ID
                    }
                    
                    // Get values from table cells (using correct column indices)
                    const h1 = getNumericValue(row.find('td:eq(3)').text()); // H1 column
                    const h2 = getNumericValue(row.find('td:eq(4)').text()); // H2 column  
                    const h3 = getNumericValue(row.find('td:eq(5)').text()); // H3 column
                    const k1 = getNumericValue(row.find('td:eq(6)').text()); // K1 column
                    const k2 = getNumericValue(row.find('td:eq(7)').text()); // K2 column
                    const k3 = getNumericValue(row.find('td:eq(8)').text()); // K3 column
                    const ck = getNumericValue(row.find('td:eq(9)').text()); // CK column
                    const aktif = getNumericValue(row.find('td:eq(12)').text()); // Aktif column
                    
                    // Check if there are any actual grade values (not just null/empty)
                    const hasGradeValues = [h1, h2, h3, k1, k2, k3, ck, aktif].some(val => val !== null && val !== undefined && val > 0);
                    
                    if (!hasGradeValues) {
                        console.log('No grade values for student:', studentId);
                        return; // Skip if no grades entered
                    }
                    
                    hasChanges = true;
                    
                    // Determine semester based on month
                    let semester = 'ganjil';
                    const month = parseInt(currentFilters.month);
                    if (month >= 1 && month <= 6) {
                        semester = 'genap';
                    } else if (month >= 7 && month <= 12) {
                        semester = 'ganjil';
                    }
                    
                    const gradeData = {
                        student_id: studentId,
                        class_id: currentFilters.class_id,
                        subject_id: currentFilters.subject_id,
                        month: month,
                        academic_year: currentFilters.academic_year || new Date().getFullYear() + '/' + (new Date().getFullYear() + 1),
                        semester: semester,
                        h1: h1,
                        h2: h2, 
                        h3: h3,
                        k1: k1,
                        k2: k2,
                        k3: k3,
                        ck: ck,
                        aktif: aktif
                        // DO NOT send p, k, nilai - they will be calculated in backend
                    };
                    
                    console.log('Adding grade data:', gradeData);
                    gradesData.push(gradeData);
                });
                
                if (!hasChanges || gradesData.length === 0) {
                    Swal.fire('Perhatian', 'Tidak ada perubahan data untuk disimpan', 'warning');
                    return;
                }
                
                console.log('Final grades data to send:', gradesData);
                
                // Show loading
                Swal.fire({
                    title: 'Menyimpan Data...',
                    text: `Menyimpan ${gradesData.length} data nilai`,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: window.location.origin + '/student-grades/bulk-update',
                    method: 'POST',
                    data: {
                        grades: gradesData,
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
                                text: response.message || 'Semua nilai berhasil disimpan',
                                icon: 'success',
                                confirmButtonText: 'OK',
                                timer: 3000,
                                timerProgressBar: true
                            });
                            
                            // Switch back to view mode and reload grades
                            toggleEditMode();
                            loadGrades();
                        } else {
                            throw new Error(response.message || 'Unknown error');
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        
                        console.error('Bulk update error:', xhr);
                        console.error('Response text:', xhr.responseText);
                        
                        let errorMessage = 'Gagal menyimpan nilai';
                        
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            console.error('Parsed error response:', errorResponse);
                            
                            if (errorResponse.message) {
                                errorMessage = errorResponse.message;
                            }
                            
                            if (errorResponse.errors) {
                                errorMessage += '\n\nDetail error:';
                                Object.keys(errorResponse.errors).forEach(key => {
                                    const errors = Array.isArray(errorResponse.errors[key]) 
                                        ? errorResponse.errors[key] 
                                        : [errorResponse.errors[key]];
                                    errorMessage += `\n- ${key}: ${errors.join(', ')}`;
                                });
                            }
                            
                            if (errorResponse.error) {
                                errorMessage += '\n\nTechnical error: ' + errorResponse.error;
                            }
                            
                        } catch (parseError) {
                            console.error('Error parsing response:', parseError);
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

            function getNumericValue(text) {
                if (!text) return null;
                
                // Convert to string and clean
                const cleaned = text.toString().trim();
                
                // Return null for empty, dash, or zero values
                if (cleaned === '-' || cleaned === '' || cleaned === '0' || cleaned === '0.00') {
                    return null;
                }
                
                // Parse as float
                const numValue = parseFloat(cleaned);
                
                // Return null if not a valid number or if it's 0
                if (isNaN(numValue) || numValue <= 0) {
                    return null;
                }
                
                // Return the numeric value
                return numValue;
            }

            // Event delegation for dynamic buttons
            $(document).on('click', '.btn-edit', function() {
                try {
                    const gradeDataStr = $(this).attr('data-grade');
                    console.log('Raw grade data string:', gradeDataStr); // Debug log
                    
                    const gradeData = JSON.parse(gradeDataStr);
                    console.log('Parsed grade data:', gradeData); // Debug log
                    
                    openGradeModal(gradeData);
                } catch (error) {
                    console.error('Error parsing grade data:', error);
                    Swal.fire('Error', 'Gagal memuat data nilai', 'error');
                }
            });

            $(document).on('click', '.btn-delete', function() {
                const gradeId = $(this).data('id');
                
                if (!gradeId) {
                    Swal.fire('Perhatian', 'Data nilai tidak ditemukan', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Hapus Nilai?',
                    text: 'Data yang dihapus tidak dapat dikembalikan',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteGrade(gradeId);
                    }
                });
            });

            function deleteGrade(gradeId) {
                $.ajax({
                    url: `{{ url('student-grades') }}/${gradeId}`,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire('Berhasil', 'Nilai berhasil dihapus', 'success');
                        loadGrades();
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal menghapus nilai', 'error');
                    }
                });
            }

            // Make cells editable on click
            $(document).on('click', '.editable-cell', function() {
                if (currentMode === 'edit') {
                    $(this).addClass('editing').focus();
                }
            });

            $(document).on('blur', '.editable-cell', function() {
                $(this).removeClass('editing');
                // Auto-save could be implemented here
            });

            // Prevent non-numeric input in grade cells
            $(document).on('keypress', '.editable-cell', function(e) {
                const char = String.fromCharCode(e.which);
                const value = $(this).text();
                
                // Allow: backspace, delete, tab, escape, enter and .
                if ([8, 9, 27, 13, 46].indexOf(e.keyCode) !== -1 ||
                    // Allow: Ctrl+A, Command+A
                    (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                    // Allow: home, end, left, right, down, up
                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                    return;
                }
                
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && 
                    (e.keyCode < 96 || e.keyCode > 105) && 
                    e.keyCode !== 190) {
                    e.preventDefault();
                }
                
                // Only allow one decimal point
                if (char === '.' && value.indexOf('.') !== -1) {
                    e.preventDefault();
                }
                
                // Limit to 2 decimal places
                if (value.indexOf('.') !== -1) {
                    const decimalPart = value.substring(value.indexOf('.') + 1);
                    if (decimalPart.length >= 2 && e.keyCode !== 8 && e.keyCode !== 46) {
                        e.preventDefault();
                    }
                }
                
                // Don't allow values greater than 100
                const newValue = value + char;
                if (parseFloat(newValue) > 100) {
                    e.preventDefault();
                }
            });

            // Format number on input
            $(document).on('input', '.editable-cell', function() {
                let value = $(this).text();
                
                // Remove any non-numeric characters except decimal point
                value = value.replace(/[^0-9.]/g, '');
                
                // Ensure only one decimal point
                const parts = value.split('.');
                if (parts.length > 2) {
                    value = parts[0] + '.' + parts.slice(1).join('');
                }
                
                // Limit to 100
                if (parseFloat(value) > 100) {
                    value = '100';
                }
                
                // Limit decimal places to 2
                if (value.includes('.')) {
                    const [integer, decimal] = value.split('.');
                    if (decimal.length > 2) {
                        value = integer + '.' + decimal.substring(0, 2);
                    }
                }
                
                // Update the cell if value changed
                if ($(this).text() !== value) {
                    $(this).text(value);
                    
                    // Move cursor to end
                    const range = document.createRange();
                    const sel = window.getSelection();
                    range.selectNodeContents(this);
                    range.collapse(false);
                    sel.removeAllRanges();
                    sel.addRange(range);
                }
                
                // Auto-calculate P, K, and Nilai for the current row
                if (currentMode === 'edit') {
                    calculateInlineGrades($(this).closest('tr'));
                }
            });

            // Initialize with current academic year from helper
            $('#academicYearFilter').val('{{ App\Helpers\AcademicYearHelper::getCurrentAcademicYear() }}');
            currentFilters.academic_year = '{{ App\Helpers\AcademicYearHelper::getCurrentAcademicYear() }}';
        });
    </script>
@endpush