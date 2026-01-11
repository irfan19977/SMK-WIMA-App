@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-graduation-cap mr-2"></i>Input Nilai Per Mata Pelajaran</h4>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <label for="kelasFilter" class="form-label font-weight-bold">Kelas:</label>
                        <select id="kelasFilter" class="form-control select2">
                            <option value="">Pilih Kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" data-name="{{ $class->name }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="matpelFilter" class="form-label font-weight-bold">Mata Pelajaran:</label>
                        <select id="matpelFilter" class="form-control select2" disabled>
                            <option value="">Pilih Mata Pelajaran</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="academicYearFilter" class="form-label font-weight-bold">Tahun Akademik:</label>
                        <select id="academicYearFilter" class="form-control">
                            @foreach(App\Helpers\AcademicYearHelper::generateAcademicYears(2, 2) as $year)
                                <option value="{{ $year }}" {{ App\Helpers\AcademicYearHelper::getCurrentAcademicYear() == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="semesterFilter" class="form-label font-weight-bold">Semester:</label>
                        <select id="semesterFilter" class="form-control">
                            @php
                                $semesterDefault = isset($defaultSemester) ? $defaultSemester : App\Helpers\AcademicYearHelper::getCurrentSemester();
                            @endphp
                            <option value="1" {{ $semesterDefault == 1 ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                            <option value="2" {{ $semesterDefault == 2 ? 'selected' : '' }}>Semester 2 (Genap)</option>
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
                            <i class="fas fa-save"></i> Simpan Semua Nilai
                        </button>
                        <button type="button" id="showStatisticsBtn" class="btn btn-info" style="display: none;">
                            <i class="fas fa-chart-bar"></i> Lihat Statistik
                        </button>
                        <button type="button" id="printPdfBtn" class="btn btn-danger" style="display: none;">
                            <i class="fas fa-file-pdf"></i> Cetak PDF
                        </button>
                    </div>
                </div>

                <!-- Status Info -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div id="statusInfo" class="alert alert-info" style="display: none;">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Status:</strong> <span id="statusText"></span>
                        </div>
                    </div>
                </div>

                <!-- Statistics Card (inline) -->
                <div id="statisticsCard" class="row mb-3" style="display: none;">
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-chart-pie mr-2"></i>Statistik Nilai</h5>
                                <div class="row">
                                    <div class="col-md-2 col-6 mb-2">
                                        <div class="text-center">
                                            <h4 class="text-dark font-weight-bold" id="statTotalSiswa">0</h4>
                                            <small class="text-muted">Total Siswa</small>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-6 mb-2">
                                        <div class="text-center">
                                            <h4 class="text-info" id="statRataRata">0</h4>
                                            <small>Rata-rata</small>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-6 mb-2">
                                        <div class="text-center">
                                            <h4 class="text-success" id="statTertinggi">0</h4>
                                            <small>Tertinggi</small>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-6 mb-2">
                                        <div class="text-center">
                                            <h4 class="text-danger" id="statTerendah">0</h4>
                                            <small>Terendah</small>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-6 mb-2">
                                        <div class="text-center">
                                            <h4 class="text-success" id="statTuntas">0</h4>
                                            <small>Tuntas (≥70)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-6 mb-2">
                                        <div class="text-center">
                                            <h4 class="text-danger" id="statTidakTuntas">0</h4>
                                            <small>Tidak Tuntas</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grade Table -->
                <div class="table-responsive">
                    <table id="gradeTable" class="table table-striped table-hover table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center" style="min-width: 40px;">No</th>
                                <th style="min-width: 120px;">Nama Siswa</th>
                                <th style="min-width: 80px;">NISN</th>
                                <th class="text-center" style="min-width: 60px;">T1</th>
                                <th class="text-center" style="min-width: 60px;">T2</th>
                                <th class="text-center" style="min-width: 60px;">T3</th>
                                <th class="text-center" style="min-width: 60px;">T4</th>
                                <th class="text-center" style="min-width: 60px;">T5</th>
                                <th class="text-center" style="min-width: 60px;">T6</th>
                                <th class="text-center" style="min-width: 60px;">Sikap<br><small>(10%)</small></th>
                                <th class="text-center" style="min-width: 60px;">UTS<br><small>(30%)</small></th>
                                <th class="text-center" style="min-width: 60px;">UAS<br><small>(30%)</small></th>
                                <th class="text-center bg-info text-white" style="min-width: 70px;">Rata2<br>Tugas<br><small>(30%)</small></th>
                                <th class="text-center bg-info text-white" style="min-width: 70px;">Nilai<br>Akhir</th>
                                <th class="text-center" style="min-width: 70px;">Status</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <!-- Data akan dimuat via AJAX -->
                        </tbody>
                    </table>
                </div>

                <!-- Loading Indicator -->
                <div id="loadingIndicator" class="text-center py-5" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat data nilai...</p>
                </div>

                <!-- No Data Indicator -->
                <div id="noDataIndicator" class="text-center py-5" style="display: none;">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <p class="text-muted">Pilih kelas dan mata pelajaran untuk menampilkan data nilai</p>
                </div>

                <!-- Bobot Info -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="alert alert-secondary text-dark">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Keterangan Bobot Nilai:</strong>
                            <ul class="mb-0 mt-2">
                                <li><strong>Tugas (T1-T6):</strong> 30% dari nilai akhir (rata-rata Tugas 1 s/d 6, kosong = 0)</li>
                                <li><strong>Sikap:</strong> 10% dari nilai akhir</li>
                                <li><strong>UTS:</strong> 30% dari nilai akhir</li>
                                <li><strong>UAS:</strong> 30% dari nilai akhir</li>
                                <li><strong>KKM (Kriteria Ketuntasan Minimal):</strong> 70</li>
                            </ul>
                            <small>* Jika tugas tidak dikerjakan (kosong), dihitung sebagai 0 sehingga rata-rata tugas akan berkurang</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Single Grade Edit -->
<div class="modal fade" id="gradeModal" tabindex="-1" role="dialog" aria-labelledby="gradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="gradeModalLabel">Edit Nilai Siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="gradeForm">
                @csrf
                <input type="hidden" id="gradeId" name="grade_id">
                <input type="hidden" id="studentId" name="student_id">
                
                <div class="modal-body">
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

                    <hr>
                    <h6 class="text-muted mb-3"><i class="fas fa-tasks mr-2"></i>Nilai Tugas (Rata-rata = 30%)</h6>
                    <small class="text-muted d-block mb-2">* Tugas yang kosong dihitung 0</small>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="modalTugas1">Tugas 1</label>
                                <input type="number" name="tugas1" id="modalTugas1" class="form-control grade-input" min="0" max="100" step="0.01" placeholder="0-100">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="modalTugas2">Tugas 2</label>
                                <input type="number" name="tugas2" id="modalTugas2" class="form-control grade-input" min="0" max="100" step="0.01" placeholder="0-100">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="modalTugas3">Tugas 3</label>
                                <input type="number" name="tugas3" id="modalTugas3" class="form-control grade-input" min="0" max="100" step="0.01" placeholder="0-100">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="modalTugas4">Tugas 4</label>
                                <input type="number" name="tugas4" id="modalTugas4" class="form-control grade-input" min="0" max="100" step="0.01" placeholder="0-100">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="modalTugas5">Tugas 5</label>
                                <input type="number" name="tugas5" id="modalTugas5" class="form-control grade-input" min="0" max="100" step="0.01" placeholder="0-100">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="modalTugas6">Tugas 6</label>
                                <input type="number" name="tugas6" id="modalTugas6" class="form-control grade-input" min="0" max="100" step="0.01" placeholder="0-100">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h6 class="text-muted mb-3"><i class="fas fa-clipboard-check mr-2"></i>Nilai Lainnya</h6>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="modalSikap">Sikap <span class="badge badge-info">10%</span></label>
                                <input type="number" name="sikap" id="modalSikap" class="form-control grade-input" min="0" max="100" step="0.01" placeholder="0-100">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="modalUts">UTS <span class="badge badge-info">30%</span></label>
                                <input type="number" name="uts" id="modalUts" class="form-control grade-input" min="0" max="100" step="0.01" placeholder="0-100">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="modalUas">UAS <span class="badge badge-info">30%</span></label>
                                <input type="number" name="uas" id="modalUas" class="form-control grade-input" min="0" max="100" step="0.01" placeholder="0-100">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="form-group">
                        <label><i class="fas fa-calculator mr-2"></i>Nilai Akhir (Otomatis)</label>
                        <input type="text" id="modalNilaiAkhir" class="form-control form-control-lg font-weight-bold text-center" readonly style="background-color: #e9ecef;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" id="saveGradeBtn" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('backend/assets/bundles/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
<style>
    .grade-input-cell {
        width: 70px;
        text-align: center;
    }
    .grade-input-cell:focus {
        background-color: #fff3cd;
        border-color: #ffc107;
    }
    .status-belum,
    .status-sedang,
    .status-baik,
    .status-sangat-baik,
    .status-buruk,
    .status-sangat-buruk {
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.85em;
        display: inline-block;
    }
    .status-sangat-baik {
        background-color: #d4edda;
        color: #155724;
        font-weight: 600;
    }
    .status-baik {
        background-color: #cce5ff;
        color: #004085;
        font-weight: 500;
    }
    .status-sedang {
        background-color: #fff3cd;
        color: #856404;
    }
    .status-buruk {
        background-color: #f8d7da;
        color: #721c24;
    }
    .status-sangat-buruk {
        background-color: #f5c6cb;
        color: #721c24;
        font-weight: 600;
    }
    .status-belum {
        background-color: #e2e3e5;
        color: #383d41;
    }
    .table th {
        vertical-align: middle !important;
    }
    .editing-mode .grade-input-cell {
        background-color: #fffde7;
    }
    .btn-edit-row {
        padding: 2px 8px;
        font-size: 0.8em;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('backend/assets/bundles/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('backend/assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
$(document).ready(function() {
    let currentMode = 'view';
    let currentFilters = {
        class_id: '',
        subject_id: '',
        academic_year: $('#academicYearFilter').val(),
        semester: $('#semesterFilter').val()
    };
    let gradesData = [];

    // Initialize Select2
    $('.select2').select2();

    // Filter Events
    $('#kelasFilter').change(function() {
        const classId = $(this).val();
        currentFilters.class_id = classId;
        
        // Reset mata pelajaran
        $('#matpelFilter').empty().append('<option value="">Pilih Mata Pelajaran</option>').prop('disabled', true);
        currentFilters.subject_id = '';
        
        hideActionButtons();
        clearTable();
        $('#statusInfo').hide();
        $('#statisticsCard').hide();
        
        if (classId) {
            loadSubjectsByClass(classId);
            updateStatus('Memuat mata pelajaran untuk kelas yang dipilih...');
        }
    });

    $('#matpelFilter').change(function() {
        const subjectId = $(this).val();
        currentFilters.subject_id = subjectId;
        
        if (subjectId && currentFilters.class_id) {
            showActionButtons();
            loadGrades();
        } else {
            hideActionButtons();
            clearTable();
            $('#statisticsCard').hide();
        }
    });

    $('#academicYearFilter, #semesterFilter').change(function() {
        currentFilters.academic_year = $('#academicYearFilter').val();
        currentFilters.semester = $('#semesterFilter').val();
        
        if (currentFilters.class_id) {
            loadSubjectsByClass(currentFilters.class_id);
        }
        
        if (currentFilters.class_id && currentFilters.subject_id) {
            loadGrades();
        }
    });

    // Reset Filters
    $('#resetFilters').click(function() {
        $('#kelasFilter').val('').trigger('change');
        $('#matpelFilter').empty().append('<option value="">Pilih Mata Pelajaran</option>').prop('disabled', true);
        $('#academicYearFilter').val('{{ App\Helpers\AcademicYearHelper::getCurrentAcademicYear() }}');
        $('#semesterFilter').val('{{ isset($defaultSemester) ? $defaultSemester : App\Helpers\AcademicYearHelper::getCurrentSemester() }}');
        
        currentFilters = {
            class_id: '',
            subject_id: '',
            academic_year: $('#academicYearFilter').val(),
            semester: $('#semesterFilter').val()
        };
        
        hideActionButtons();
        clearTable();
        $('#statusInfo').hide();
        $('#statisticsCard').hide();
        currentMode = 'view';
    });

    // Edit Mode Toggle
    $('#editModeBtn').click(function() {
        toggleEditMode();
    });

    // Save All Grades
    $('#saveAllBtn').click(function() {
        saveAllGrades();
    });

    // Show/Hide Statistics (toggle)
    $('#showStatisticsBtn').click(function() {
        if ($('#statisticsCard').is(':visible')) {
            $('#statisticsCard').hide();
        } else {
            loadStatistics();
        }
    });

    // Print PDF
    $('#printPdfBtn').click(function() {
        const classId = currentFilters.class_id;
        const subjectId = currentFilters.subject_id;
        const academicYear = currentFilters.academic_year;
        const semesterValue = currentFilters.semester;

        if (!classId || !subjectId) {
            Swal.fire('Peringatan', 'Silakan pilih kelas dan mata pelajaran terlebih dahulu.', 'warning');
            return;
        }

        // Konversi format semester 1/2 ke ganjil/genap untuk AcademicReportController
        let semesterParam = 'ganjil';
        if (semesterValue == '2') {
            semesterParam = 'genap';
        }

        const baseUrl = '{{ route("reports.academic.export-pdf") }}';
        const query = `class_id=${encodeURIComponent(classId)}&subject_id=${encodeURIComponent(subjectId)}&academic_year=${encodeURIComponent(academicYear)}&semester=${encodeURIComponent(semesterParam)}`;
        const url = `${baseUrl}?${query}`;

        window.open(url, '_blank');
    });

    // Modal Grade Calculation with validation
    $('.grade-input').on('input', function() {
        let value = parseFloat($(this).val());
        
        // Validasi min 0 dan max 100
        if (value < 0) {
            $(this).val(0);
        } else if (value > 100) {
            $(this).val(100);
        }
        
        calculateModalGrade();
    });

    // Save Single Grade from Modal
    $('#saveGradeBtn').click(function() {
        saveSingleGrade();
    });

    // Functions
    function updateStatus(message) {
        $('#statusInfo').show();
        $('#statusText').text(message);
    }

    function showActionButtons() {
        $('#editModeBtn').show();
        $('#showStatisticsBtn').show();
        $('#printPdfBtn').show();
    }

    function hideActionButtons() {
        $('#editModeBtn').hide();
        $('#saveAllBtn').hide();
        $('#showStatisticsBtn').hide();
        $('#printPdfBtn').hide();
        currentMode = 'view';
    }

    function clearTable() {
        $('#tableBody').empty();
        $('#loadingIndicator').hide();
        $('#noDataIndicator').show();
    }

    function showLoading() {
        $('#loadingIndicator').show();
        $('#noDataIndicator').hide();
        $('#tableBody').empty();
    }

    function hideLoading() {
        $('#loadingIndicator').hide();
    }

    function loadSubjectsByClass(classId) {
        $.ajax({
            url: '{{ route("student-grades.get-subjects-by-class") }}',
            method: 'GET',
            data: {
                class_id: classId,
                academic_year: currentFilters.academic_year
            },
            success: function(response) {
                const $matpelFilter = $('#matpelFilter');
                $matpelFilter.empty().append('<option value="">Pilih Mata Pelajaran</option>');
                
                if (response.data && response.data.length > 0) {
                    response.data.forEach(function(subject) {
                        $matpelFilter.append(`<option value="${subject.id}" data-name="${subject.name}">${subject.name}</option>`);
                    });
                    $matpelFilter.prop('disabled', false);
                    updateStatus(`${response.data.length} mata pelajaran tersedia. Pilih mata pelajaran untuk input nilai.`);
                } else {
                    $matpelFilter.prop('disabled', true);
                    updateStatus('Tidak ada mata pelajaran yang dijadwalkan untuk kelas ini.');
                }
            },
            error: function(xhr) {
                console.error('Error loading subjects:', xhr);
                Swal.fire('Error', 'Gagal memuat mata pelajaran', 'error');
            }
        });
    }

    function loadGrades() {
        showLoading();
        
        $.ajax({
            url: '{{ route("student-grades.get-grades") }}',
            method: 'GET',
            data: currentFilters,
            success: function(response) {
                hideLoading();
                
                if (response.data && response.data.length > 0) {
                    gradesData = response.data;
                    displayGrades(response.data);
                    
                    const className = $('#kelasFilter option:selected').text();
                    const subjectName = $('#matpelFilter option:selected').text();
                    updateStatus(`Menampilkan nilai ${subjectName} untuk kelas ${className} - ${response.data.length} siswa`);
                } else {
                    $('#noDataIndicator').show();
                    updateStatus('Tidak ada data siswa untuk kelas ini.');
                }
            },
            error: function(xhr) {
                hideLoading();
                $('#noDataIndicator').show();
                console.error('Error loading grades:', xhr);
                Swal.fire('Error', 'Gagal memuat data nilai', 'error');
            }
        });
    }

    function displayGrades(grades) {
        const $tbody = $('#tableBody');
        $tbody.empty();
        $('#noDataIndicator').hide();
        
        grades.forEach(function(grade, index) {
            const nilaiAkhir = calculateNilaiAkhir(grade);
            const rataRataTugas = calculateRataRataTugas(grade);
            const status = getStatus(nilaiAkhir);
            
            const row = `
                <tr data-student-id="${grade.student_id}" data-grade-id="${grade.id || ''}">
                    <td class="text-center">${grade.no_absen}</td>
                    <td>${grade.student_name}</td>
                    <td>${grade.student_nisn}</td>
                    <td class="text-center">
                        <input type="number" class="form-control form-control-sm grade-input-cell" 
                               data-field="tugas1" value="${grade.tugas1 ?? ''}" 
                               min="0" max="100" step="0.01" ${currentMode === 'view' ? 'readonly' : ''}>
                    </td>
                    <td class="text-center">
                        <input type="number" class="form-control form-control-sm grade-input-cell" 
                               data-field="tugas2" value="${grade.tugas2 ?? ''}" 
                               min="0" max="100" step="0.01" ${currentMode === 'view' ? 'readonly' : ''}>
                    </td>
                    <td class="text-center">
                        <input type="number" class="form-control form-control-sm grade-input-cell" 
                               data-field="tugas3" value="${grade.tugas3 ?? ''}" 
                               min="0" max="100" step="0.01" ${currentMode === 'view' ? 'readonly' : ''}>
                    </td>
                    <td class="text-center">
                        <input type="number" class="form-control form-control-sm grade-input-cell" 
                               data-field="tugas4" value="${grade.tugas4 ?? ''}" 
                               min="0" max="100" step="0.01" ${currentMode === 'view' ? 'readonly' : ''}>
                    </td>
                    <td class="text-center">
                        <input type="number" class="form-control form-control-sm grade-input-cell" 
                               data-field="tugas5" value="${grade.tugas5 ?? ''}" 
                               min="0" max="100" step="0.01" ${currentMode === 'view' ? 'readonly' : ''}>
                    </td>
                    <td class="text-center">
                        <input type="number" class="form-control form-control-sm grade-input-cell" 
                               data-field="tugas6" value="${grade.tugas6 ?? ''}" 
                               min="0" max="100" step="0.01" ${currentMode === 'view' ? 'readonly' : ''}>
                    </td>
                    <td class="text-center">
                        <input type="number" class="form-control form-control-sm grade-input-cell" 
                               data-field="sikap" value="${grade.sikap || ''}" 
                               min="0" max="100" step="0.01" ${currentMode === 'view' ? 'readonly' : ''}>
                    </td>
                    <td class="text-center">
                        <input type="number" class="form-control form-control-sm grade-input-cell" 
                               data-field="uts" value="${grade.uts || ''}" 
                               min="0" max="100" step="0.01" ${currentMode === 'view' ? 'readonly' : ''}>
                    </td>
                    <td class="text-center">
                        <input type="number" class="form-control form-control-sm grade-input-cell" 
                               data-field="uas" value="${grade.uas || ''}" 
                               min="0" max="100" step="0.01" ${currentMode === 'view' ? 'readonly' : ''}>
                    </td>
                    <td class="text-center rata-rata-tugas" style="background-color: #e3f2fd; font-weight: bold;">${rataRataTugas !== null ? rataRataTugas.toFixed(2) : '-'}</td>
                    <td class="text-center nilai-akhir" style="background-color: #e3f2fd; font-weight: bold;">${nilaiAkhir !== null ? nilaiAkhir.toFixed(2) : '-'}</td>
                    <td class="text-center"><span class="${status.class}">${status.text}</span></td>
                </tr>
            `;
            
            $tbody.append(row);
        });

        // Bind input events for real-time calculation and validation
        $('.grade-input-cell').on('input', function() {
            let value = parseFloat($(this).val());
            
            // Validasi min 0 dan max 100
            if (value < 0) {
                $(this).val(0);
            } else if (value > 100) {
                $(this).val(100);
            }
            
            const $row = $(this).closest('tr');
            updateRowNilaiAkhir($row);
        });
    }

    function calculateRataRataTugas(grade) {
        const tugas1 = parseFloat(grade.tugas1) || 0;
        const tugas2 = parseFloat(grade.tugas2) || 0;
        const tugas3 = parseFloat(grade.tugas3) || 0;
        const tugas4 = parseFloat(grade.tugas4) || 0;
        const tugas5 = parseFloat(grade.tugas5) || 0;
        const tugas6 = parseFloat(grade.tugas6) || 0;

        // Jika semua tugas kosong, rata-rata = 0
        const allEmpty = !grade.tugas1 && !grade.tugas2 && !grade.tugas3 && !grade.tugas4 && !grade.tugas5 && !grade.tugas6;
        if (allEmpty) {
            return 0;
        }

        return (tugas1 + tugas2 + tugas3 + tugas4 + tugas5 + tugas6) / 6;
    }

    function calculateNilaiAkhir(grade) {
        const tugas1 = parseFloat(grade.tugas1) || 0;
        const tugas2 = parseFloat(grade.tugas2) || 0;
        const tugas3 = parseFloat(grade.tugas3) || 0;
        const tugas4 = parseFloat(grade.tugas4) || 0;
        const tugas5 = parseFloat(grade.tugas5) || 0;
        const tugas6 = parseFloat(grade.tugas6) || 0;
        const sikap = parseFloat(grade.sikap) || 0;
        const uts = parseFloat(grade.uts) || 0;
        const uas = parseFloat(grade.uas) || 0;

        // Jika semua komponen kosong, nilai akhir = 0
        const allEmpty = !grade.tugas1 && !grade.tugas2 && !grade.tugas3 && !grade.tugas4 && !grade.tugas5 && !grade.tugas6 && !grade.sikap && !grade.uts && !grade.uas;

        // Rata-rata tugas 1-6 (kosong = 0)
        const rataRataTugas = (tugas1 + tugas2 + tugas3 + tugas4 + tugas5 + tugas6) / 6;

        // Bobot: Tugas (30%) + Sikap (10%) + UTS (30%) + UAS (30%)
        const nilai = (rataRataTugas * 0.30) + (sikap * 0.10) + (uts * 0.30) + (uas * 0.30);

        return allEmpty ? 0 : nilai;
    }

    function getStatus(nilaiAkhir) {
        if (nilaiAkhir === null) {
            return { text: 'Belum', class: 'status-belum' };
        }

        if (nilaiAkhir >= 81) {
            return { text: 'Sangat Baik', class: 'status-sangat-baik' };
        }
        if (nilaiAkhir >= 61) {
            return { text: 'Baik', class: 'status-baik' };
        }
        if (nilaiAkhir >= 41) {
            return { text: 'Sedang', class: 'status-sedang' };
        }
        if (nilaiAkhir >= 21) {
            return { text: 'Buruk', class: 'status-buruk' };
        }
        return { text: 'Sangat Buruk', class: 'status-sangat-buruk' };
    }

    function updateRowNilaiAkhir($row) {
        const tugas1 = parseFloat($row.find('[data-field="tugas1"]').val()) || 0;
        const tugas2 = parseFloat($row.find('[data-field="tugas2"]').val()) || 0;
        const tugas3 = parseFloat($row.find('[data-field="tugas3"]').val()) || 0;
        const tugas4 = parseFloat($row.find('[data-field="tugas4"]').val()) || 0;
        const tugas5 = parseFloat($row.find('[data-field="tugas5"]').val()) || 0;
        const tugas6 = parseFloat($row.find('[data-field="tugas6"]').val()) || 0;
        const sikap = parseFloat($row.find('[data-field="sikap"]').val()) || 0;
        const uts = parseFloat($row.find('[data-field="uts"]').val()) || 0;
        const uas = parseFloat($row.find('[data-field="uas"]').val()) || 0;

        const hasTugas = $row.find('[data-field="tugas1"]').val() || $row.find('[data-field="tugas2"]').val() ||
                         $row.find('[data-field="tugas3"]').val() || $row.find('[data-field="tugas4"]').val() ||
                         $row.find('[data-field="tugas5"]').val() || $row.find('[data-field="tugas6"]').val();
        const hasValue = hasTugas || 
                        $row.find('[data-field="sikap"]').val() || 
                        $row.find('[data-field="uts"]').val() || 
                        $row.find('[data-field="uas"]').val();

        // Update rata-rata tugas 1-6 (kalau semua kosong, 0)
        const rataRataTugas = (tugas1 + tugas2 + tugas3 + tugas4 + tugas5 + tugas6) / 6;
        $row.find('.rata-rata-tugas').text(rataRataTugas.toFixed(2));

        // Jika semua komponen kosong, nilai akhir = 0
        let nilaiAkhir = 0;
        if (hasValue) {
            // Bobot: Tugas (30%) + Sikap (10%) + UTS (30%) + UAS (30%)
            nilaiAkhir = (rataRataTugas * 0.30) + (sikap * 0.10) + (uts * 0.30) + (uas * 0.30);
        }

        const status = getStatus(nilaiAkhir);
        
        $row.find('.nilai-akhir').text(nilaiAkhir.toFixed(2));
        $row.find('td:last span').attr('class', status.class).text(status.text);
    }

    function toggleEditMode() {
        if (currentMode === 'view') {
            currentMode = 'edit';
            $('#editModeBtn').html('<i class="fas fa-eye"></i> Mode Lihat').removeClass('btn-warning').addClass('btn-info');
            $('#saveAllBtn').show();
            $('#gradeTable').addClass('editing-mode');
            $('.grade-input-cell').prop('readonly', false);
            updateStatus('Mode Edit aktif - Anda dapat mengubah nilai langsung di tabel');
        } else {
            currentMode = 'view';
            $('#editModeBtn').html('<i class="fas fa-edit"></i> Mode Edit').removeClass('btn-info').addClass('btn-warning');
            $('#saveAllBtn').hide();
            $('#gradeTable').removeClass('editing-mode');
            $('.grade-input-cell').prop('readonly', true);
            
            const className = $('#kelasFilter option:selected').text();
            const subjectName = $('#matpelFilter option:selected').text();
            updateStatus(`Menampilkan nilai ${subjectName} untuk kelas ${className}`);
        }
    }

    function saveAllGrades() {
        const grades = [];
        let hasT1 = false, hasT2 = false, hasT3 = false, hasT4 = false, hasT5 = false, hasT6 = false;
        let hasSikap = false, hasUts = false, hasUas = false;
        
        $('#tableBody tr').each(function() {
            const $row = $(this);
            const studentId = $row.attr('data-student-id');
            
            // Skip jika tidak ada student_id
            if (!studentId) return;

            const v1 = $row.find('[data-field="tugas1"]').val();
            const v2 = $row.find('[data-field="tugas2"]').val();
            const v3 = $row.find('[data-field="tugas3"]').val();
            const v4 = $row.find('[data-field="tugas4"]').val();
            const v5 = $row.find('[data-field="tugas5"]').val();
            const v6 = $row.find('[data-field="tugas6"]').val();
            const vSikap = $row.find('[data-field="sikap"]').val();
            const vUts = $row.find('[data-field="uts"]').val();
            const vUas = $row.find('[data-field="uas"]').val();

            if (v1 !== '') hasT1 = true;
            if (v2 !== '') hasT2 = true;
            if (v3 !== '') hasT3 = true;
            if (v4 !== '') hasT4 = true;
            if (v5 !== '') hasT5 = true;
            if (v6 !== '') hasT6 = true;
            if (vSikap !== '') hasSikap = true;
            if (vUts !== '') hasUts = true;
            if (vUas !== '') hasUas = true;
            
            grades.push({
                student_id: studentId,
                tugas1: v1 === '' ? null : v1,
                tugas2: v2 === '' ? null : v2,
                tugas3: v3 === '' ? null : v3,
                tugas4: v4 === '' ? null : v4,
                tugas5: v5 === '' ? null : v5,
                tugas6: v6 === '' ? null : v6,
                sikap: vSikap === '' ? null : vSikap,
                uts: vUts === '' ? null : vUts,
                uas: vUas === '' ? null : vUas,
            });
        });

        // Jika ada minimal satu nilai di setiap kolom, siswa lain yang kosong dianggap 0
        grades.forEach(g => {
            if (hasT1 && (g.tugas1 === null || g.tugas1 === undefined)) g.tugas1 = 0;
            if (hasT2 && (g.tugas2 === null || g.tugas2 === undefined)) g.tugas2 = 0;
            if (hasT3 && (g.tugas3 === null || g.tugas3 === undefined)) g.tugas3 = 0;
            if (hasT4 && (g.tugas4 === null || g.tugas4 === undefined)) g.tugas4 = 0;
            if (hasT5 && (g.tugas5 === null || g.tugas5 === undefined)) g.tugas5 = 0;
            if (hasT6 && (g.tugas6 === null || g.tugas6 === undefined)) g.tugas6 = 0;
            if (hasSikap && (g.sikap === null || g.sikap === undefined)) g.sikap = 0;
            if (hasUts && (g.uts === null || g.uts === undefined)) g.uts = 0;
            if (hasUas && (g.uas === null || g.uas === undefined)) g.uas = 0;
        });
        
        console.log('Grades to save:', grades); // Debug log

        // Konfirmasi dengan style yang sama seperti delete berita (sweetalert v1)
        swal({
            title: "Simpan Semua Nilai?",
            text: `Apakah Anda yakin ingin menyimpan nilai untuk ${grades.length} siswa?`,
            icon: "warning",
            buttons: {
                cancel: {
                    text: "Batal",
                    value: null,
                    visible: true
                },
                confirm: {
                    text: "Ya, Simpan!",
                    value: true,
                    className: "btn-primary"
                }
            },
            dangerMode: true,
        }).then((willSave) => {
            if (willSave) {
                $.ajax({
                    url: '{{ route("student-grades.bulk-update") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        grades: grades,
                        class_id: currentFilters.class_id,
                        subject_id: currentFilters.subject_id,
                        academic_year: currentFilters.academic_year,
                        semester: currentFilters.semester
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK',
                                timer: 3000,
                                timerProgressBar: true
                            });

                            // Kembali ke mode view setelah simpan
                            if (currentMode === 'edit') {
                                toggleEditMode();
                            }

                            loadGrades();
                            // Hanya load statistik jika ada nilai yang disimpan
                            setTimeout(function() {
                                loadStatistics(true); // true = silent mode
                            }, 500);
                        } else {
                            Swal.fire('Gagal!', response.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error saving grades:', xhr);
                        Swal.fire('Error', 'Gagal menyimpan nilai', 'error');
                    }
                });
            }
        });
    }

    function loadStatistics(silentMode = false) {
        $.ajax({
            url: '{{ route("student-grades.statistics") }}',
            method: 'GET',
            data: currentFilters,
            success: function(response) {
                if (response.data) {
                    $('#statTotalSiswa').text(response.data.total_siswa);
                    $('#statRataRata').text(response.data.rata_rata);
                    $('#statTertinggi').text(response.data.nilai_tertinggi);
                    $('#statTerendah').text(response.data.nilai_terendah);
                    $('#statTuntas').text(response.data.tuntas);
                    $('#statTidakTuntas').text(response.data.tidak_tuntas);
                    $('#statisticsCard').show();
                } else {
                    $('#statisticsCard').hide();
                    if (!silentMode) {
                        Swal.fire('Info', 'Belum ada data nilai untuk ditampilkan statistiknya', 'info');
                    }
                }
            },
            error: function(xhr) {
                console.error('Error loading statistics:', xhr);
            }
        });
    }

    function calculateModalGrade() {
        const tugas1 = parseFloat($('#modalTugas1').val()) || 0;
        const tugas2 = parseFloat($('#modalTugas2').val()) || 0;
        const tugas3 = parseFloat($('#modalTugas3').val()) || 0;
        const tugas4 = parseFloat($('#modalTugas4').val()) || 0;
        const tugas5 = parseFloat($('#modalTugas5').val()) || 0;
        const tugas6 = parseFloat($('#modalTugas6').val()) || 0;
        const sikap = parseFloat($('#modalSikap').val()) || 0;
        const uts = parseFloat($('#modalUts').val()) || 0;
        const uas = parseFloat($('#modalUas').val()) || 0;

        // Rata-rata tugas 1-6 (kosong = 0)
        const rataRataTugas = (tugas1 + tugas2 + tugas3 + tugas4 + tugas5 + tugas6) / 6;
        // Bobot: Tugas (30%) + Sikap (10%) + UTS (30%) + UAS (30%)
        const nilaiAkhir = (rataRataTugas * 0.30) + (sikap * 0.10) + (uts * 0.30) + (uas * 0.30);
        
        $('#modalNilaiAkhir').val(nilaiAkhir.toFixed(2));
    }

    function saveSingleGrade() {
        const data = {
            _token: '{{ csrf_token() }}',
            student_id: $('#studentId').val(),
            class_id: currentFilters.class_id,
            subject_id: currentFilters.subject_id,
            academic_year: currentFilters.academic_year,
            semester: currentFilters.semester,
            tugas1: $('#modalTugas1').val() || null,
            tugas2: $('#modalTugas2').val() || null,
            tugas3: $('#modalTugas3').val() || null,
            tugas4: $('#modalTugas4').val() || null,
            tugas5: $('#modalTugas5').val() || null,
            tugas6: $('#modalTugas6').val() || null,
            sikap: $('#modalSikap').val() || null,
            uts: $('#modalUts').val() || null,
            uas: $('#modalUas').val() || null,
        };

        $.ajax({
            url: '/student-grades',
            method: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    $('#gradeModal').modal('hide');
                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        timer: 3000,
                        timerProgressBar: true
                    });
                    loadGrades();
                } else {
                    Swal.fire('Gagal!', response.message, 'error');
                }
            },
            error: function(xhr) {
                console.error('Error saving grade:', xhr);
                Swal.fire('Error', 'Gagal menyimpan nilai', 'error');
            }
        });
    }

    // Initialize
    $('#noDataIndicator').show();
});
</script>
@endpush
