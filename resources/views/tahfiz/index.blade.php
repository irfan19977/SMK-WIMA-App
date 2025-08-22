@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Manajemen Data Tahfiz</h4>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="kelasFilter" class="form-label">Filter Kelas:</label>
                        <select id="kelasFilter" class="form-control">
                            <option value="">Pilih Kelas</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" data-name="{{ $class->name }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
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
                    <div class="col-md-4">
                        <label for="academicYearFilter" class="form-label">Tahun Akademik:</label>
                        <select id="academicYearFilter" class="form-control">
                            <option value="">Pilih Tahun Akademik</option>
                            <option value="2023/2024">2023/2024</option>
                            <option value="2024/2025">2024/2025</option>
                            <option value="2025/2026">2025/2026</option>
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row mb-3">
                    <div class="col-12">
                        <button type="button" id="resetFilters" class="btn btn-secondary">
                            <i class="fas fa-sync-alt"></i> Reset Filter
                        </button>
                        @can('tahfiz.create')
                            <button type="button" id="editModeBtn" class="btn btn-warning" style="display: none;">
                                <i class="fas fa-edit"></i> Mode Edit
                            </button>
                        @endcan
                        
                        <button type="button" id="saveAllBtn" class="btn btn-primary" style="display: none;">
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

                <div class="table-responsive">
                    <table id="mainTable" class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>No Absen</th>
                                <th>Nama Siswa</th>
                                <th>NISN</th>
                                <th>Progres Tahfiz</th>
                                <th>Progres Tahsin</th>
                                <th>Target Hafalan</th>
                                <th>Efektif Halaqoh</th>
                                <th>Hadir</th>
                                <th>Keaktifan</th>
                                <th>Izin</th>
                                <th>Sakit</th>
                                <th>Alpha</th>
                                @can('edit')
                                    <th>Aksi</th>  
                                @endcan
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

<!-- Modal for Add/Edit Tahfiz -->
<div class="modal fade" id="tahfizModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tahfizModalTitle">Tambah/Edit Data Tahfiz</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="tahfizForm">
                    @csrf
                    <input type="hidden" id="tahfizId" name="tahfiz_id">
                    <input type="hidden" id="studentId" name="student_id">
                    <input type="hidden" id="classId" name="class_id">
                    
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
                                <label>Progres Tahfiz</label>
                                <input type="text" name="progres_tahfiz" id="progresTahfiz" class="form-control" maxlength="255">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Progres Tahsin</label>
                                <input type="text" name="progres_tahsin" id="progresTahsin" class="form-control" maxlength="255">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Target Hafalan</label>
                                <input type="text" name="target_hafalan" id="targetHafalan" class="form-control" maxlength="255">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Efektif Halaqoh</label>
                                <input type="number" name="efektif_halaqoh" id="efektifHalaqoh" class="form-control" min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Hadir</label>
                                <input type="number" name="hadir" id="hadir" class="form-control" min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Keaktifan</label>
                                <input type="number" name="keatifan" id="keatifan" class="form-control" min="0" max="100">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Izin</label>
                                <input type="number" name="izin" id="izin" class="form-control" min="0">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Sakit</label>
                                <input type="number" name="sakit" id="sakit" class="form-control" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alpha</label>
                                <input type="number" name="alpha" id="alpha" class="form-control" min="0">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" id="saveTahfizBtn" class="btn btn-primary">Simpan</button>
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
                month: '',
                academic_year: '',
                semester: ''
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

            // Filter Events
            $('#kelasFilter').change(function() {
                const classId = $(this).val();
                const className = $(this).find('option:selected').data('name');
                
                currentFilters.class_id = classId;
                
                // Reset dependent fields when class changes
                $('#bulanFilter').val('').prop('disabled', true);
                currentFilters.month = '';
                
                // Hide action buttons
                hideActionButtons();
                
                // Clear table and status
                clearTable();
                $('#statusInfo').hide();
                
                if (classId) {
                    updateStatus(`Memuat siswa untuk kelas: ${className}...`);
                    $('#bulanFilter').prop('disabled', false);
                    loadStudents();
                } else {
                    resetView();
                }
            });

            $('#bulanFilter').change(function() {
                const month = $(this).val();
                const monthName = $(this).find('option:selected').text();
                
                currentFilters.month = month;
                
                if (month && currentFilters.class_id) {
                    updateStatus(`Bulan: ${monthName} - Mode CRUD aktif`);
                    showActionButtons();
                    loadTahfizRecords();
                } else {
                    hideActionButtons();
                    if (currentFilters.class_id) {
                        const className = $('#kelasFilter').find('option:selected').data('name');
                        updateStatus(`Menampilkan siswa di kelas: ${className}`);
                        loadStudents();
                    }
                }
            });

            $('#academicYearFilter').change(function() {
                currentFilters.academic_year = $(this).val();
                
                // Reload data if filters are set
                if (currentFilters.class_id && currentFilters.month) {
                    loadTahfizRecords();
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
                saveAllTahfiz();
            });

            // Modal Events
            $('#saveTahfizBtn').click(function() {
                saveTahfiz();
            });

            // Functions
            function updateStatus(message) {
                $('#statusInfo').show();
                $('#statusText').text(message);
            }

            function resetView() {
                currentFilters = {
                    class_id: '',
                    month: '',
                    academic_year: $('#academicYearFilter').val() || '{{ date("Y") }}/{{ date("Y") + 1 }}' || ''
                };
                
                $('#kelasFilter').val('');
                $('#bulanFilter').val('').prop('disabled', true);
                
                $('#statusInfo').hide();
                hideActionButtons();
                clearTable();
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

            function loadStudents() {
                showLoading();
                
                $.ajax({
                    url: '{{ route("tahfiz.getStudents") }}',
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

            function loadTahfizRecords() {
                showLoading();
                
                $.ajax({
                    url: '{{ route("tahfiz.getTahfizRecords") }}',
                    method: 'GET',
                    data: currentFilters,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        hideLoading();
                        
                        if (response.data && response.data.length > 0) {
                            displayTahfizRecords(response.data);
                        } else {
                            showNoData();
                        }
                    },
                    error: function(xhr) {
                        hideLoading();
                        showNoData();
                        console.log('Error:', xhr.responseText);
                        Swal.fire('Error', 'Gagal memuat data tahfiz', 'error');
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
                        '-', '-', '-', '-', '-', '-', '-', '-', '-',
                        '<span class="text-muted">Pilih bulan untuk input data tahfiz</span>'
                    ]);
                });
                
                table.draw();
            }

            function displayTahfizRecords(records) {
                table.clear();
                
                records.forEach(function(record, index) {
                    const row = [
                        record.no_absen || '-',
                        record.student_name,
                        record.student_nisn,
                        record.progres_tahfiz || '-',
                        record.progres_tahsin || '-',
                        record.target_hafalan || '-',
                        record.efektif_halaqoh || '-',
                        record.hadir || '-',
                        record.keatifan || '-',
                        record.izin || '-',
                        record.sakit || '-',
                        record.alpha || '-',
                        generateActionButtons(record)
                    ];
                    
                    const addedRow = table.row.add(row);
                    const rowNode = addedRow.node();
                    $(rowNode).data('student-id', record.student_id);
                    $(rowNode).attr('data-student-id', record.student_id);
                });
                
                table.draw();
            }

            function generateActionButtons(record) {
                const tahfizDataForEdit = {
                    id: record.id || '',
                    student_id: record.student_id,
                    student_name: record.student_name,
                    student_nisn: record.student_nisn,
                    no_absen: record.no_absen,
                    progres_tahfiz: record.progres_tahfiz,
                    progres_tahsin: record.progres_tahsin,
                    target_hafalan: record.target_hafalan,
                    efektif_halaqoh: record.efektif_halaqoh,
                    hadir: record.hadir,
                    keatifan: record.keatifan,
                    izin: record.izin,
                    sakit: record.sakit,
                    alpha: record.alpha
                };
                
                return `
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-info btn-edit" 
                                data-tahfiz='${JSON.stringify(tahfizDataForEdit).replace(/'/g, "&#39;")}'>
                            <i class="fas fa-edit"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-delete" data-id="${record.id || ''}">
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
                    
                    // Make specific columns editable (progres_tahfiz, progres_tahsin, target_hafalan, efektif_halaqoh, hadir, keatifan, izin, sakit, alpha)
                    row.find('td:eq(3), td:eq(4), td:eq(5), td:eq(6), td:eq(7), td:eq(8), td:eq(9), td:eq(10), td:eq(11)')
                    .addClass('editable-cell')
                    .attr('contenteditable', 'true')
                    .css({
                        'background-color': '#fff3cd',
                        'cursor': 'text',
                        'border': '1px dashed #ffeaa7'
                    });
                });
                
                updateStatus('Mode Edit - Anda dapat mengubah data tahfiz dengan mengklik pada sel');
            }

            function makeTableReadonly() {
                $('#mainTable tbody td')
                    .removeClass('editable-cell editing')
                    .attr('contenteditable', 'false')
                    .css({
                        'background-color': '',
                        'cursor': '',
                        'border': ''
                    });
                
                const className = $('#kelasFilter').find('option:selected').data('name');
                const monthName = $('#bulanFilter').find('option:selected').text();
                updateStatus(`${className} - ${monthName} - Mode CRUD aktif`);
            }

            function openTahfizModal(tahfizData = null) {
                if (!currentFilters.class_id || !currentFilters.month) {
                    Swal.fire('Perhatian', 'Pilih kelas dan bulan terlebih dahulu', 'warning');
                    return;
                }

                if (tahfizData) {
                    $('#tahfizModalTitle').text('Edit Data Tahfiz');
                    populateTahfizForm(tahfizData);
                } else {
                    $('#tahfizModalTitle').text('Tambah Data Tahfiz');
                    clearTahfizForm();
                }

                $('#tahfizModal').modal('show');
            }

            function populateTahfizForm(tahfizData) {
                $('#tahfizId').val(tahfizData.id || '');
                $('#studentId').val(tahfizData.student_id);
                $('#classId').val(currentFilters.class_id);
                
                $('#studentName').val(tahfizData.student_name || '');
                $('#studentNisn').val(tahfizData.student_nisn || '');
                
                $('#progresTahfiz').val(tahfizData.progres_tahfiz || '');
                $('#progresTahsin').val(tahfizData.progres_tahsin || '');
                $('#targetHafalan').val(tahfizData.target_hafalan || '');
                $('#efektifHalaqoh').val(tahfizData.efektif_halaqoh || '');
                $('#hadir').val(tahfizData.hadir || '');
                $('#keatifan').val(tahfizData.keatifan || '');
                $('#izin').val(tahfizData.izin || '');
                $('#sakit').val(tahfizData.sakit || '');
                $('#alpha').val(tahfizData.alpha || '');
            }

            function clearTahfizForm() {
                $('#tahfizForm')[0].reset();
                $('#tahfizId').val('');
                $('#studentId').val('');
                $('#classId').val(currentFilters.class_id);
            }

            function saveTahfiz() {
                const formData = new FormData($('#tahfizForm')[0]);
                formData.append('month', currentFilters.month);
                formData.append('academic_year', currentFilters.academic_year || '{{ date("Y") }}/{{ date("Y") + 1 }}');
                formData.append('semester', currentFilters.semester || 'ganjil');
                
                const url = $('#tahfizId').val() ? 
                    `{{ url('tahfiz') }}/${$('#tahfizId').val()}` : 
                    '{{ route("tahfiz.store") }}';
                
                const method = $('#tahfizId').val() ? 'PUT' : 'POST';
                
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
                        $('#tahfizModal').modal('hide');
                        Swal.fire('Berhasil', 'Data tahfiz berhasil disimpan', 'success');
                        loadTahfizRecords();
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors || {};
                        let errorMessage = 'Gagal menyimpan data tahfiz';
                        
                        if (Object.keys(errors).length > 0) {
                            errorMessage = Object.values(errors).flat().join('<br>');
                        }
                        
                        Swal.fire('Error', errorMessage, 'error');
                    }
                });
            }

            function saveAllTahfiz() {
                const recordsData = [];
                let hasChanges = false;
                
                $('#mainTable tbody tr').each(function() {
                    const row = $(this);
                    const studentId = row.data('student-id');
                    
                    if (!studentId) {
                        return;
                    }
                    
                    const progres_tahfiz = row.find('td:eq(3)').text();
                    const progres_tahsin = row.find('td:eq(4)').text();
                    const target_hafalan = row.find('td:eq(5)').text();
                    const efektif_halaqoh = getNumericValue(row.find('td:eq(6)').text());
                    const hadir = getNumericValue(row.find('td:eq(7)').text());
                    const keatifan = getNumericValue(row.find('td:eq(8)').text());
                    const izin = getNumericValue(row.find('td:eq(9)').text());
                    const sakit = getNumericValue(row.find('td:eq(10)').text());
                    const alpha = getNumericValue(row.find('td:eq(11)').text());
                    
                    hasChanges = true;
                    
                    let semester = 'ganjil';
                    const month = parseInt(currentFilters.month);
                    if (month >= 1 && month <= 6) {
                        semester = 'genap';
                    }
                    
                    const recordData = {
                        student_id: studentId,
                        class_id: currentFilters.class_id,
                        month: month,
                        academic_year: currentFilters.academic_year || new Date().getFullYear() + '/' + (new Date().getFullYear() + 1),
                        semester: semester,
                        progres_tahfiz: progres_tahfiz !== '-' ? progres_tahfiz : null,
                        progres_tahsin: progres_tahsin !== '-' ? progres_tahsin : null,
                        target_hafalan: target_hafalan !== '-' ? target_hafalan : null,
                        efektif_halaqoh: efektif_halaqoh,
                        hadir: hadir,
                        keatifan: keatifan,
                        izin: izin,
                        sakit: sakit,
                        alpha: alpha
                    };
                    
                    recordsData.push(recordData);
                });
                
                if (!hasChanges || recordsData.length === 0) {
                    Swal.fire('Perhatian', 'Tidak ada perubahan data untuk disimpan', 'warning');
                    return;
                }
                
                // Show loading
                Swal.fire({
                    title: 'Menyimpan Data...',
                    text: `Menyimpan ${recordsData.length} data tahfiz`,
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: '{{ route("tahfiz.bulkUpdate") }}',
                    method: 'POST',
                    data: {
                        records: recordsData,
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
                                text: response.message || 'Semua data tahfiz berhasil disimpan',
                                icon: 'success',
                                confirmButtonText: 'OK',
                                timer: 3000,
                                timerProgressBar: true
                            });
                            
                            // Switch back to view mode and reload data
                            toggleEditMode();
                            loadTahfizRecords();
                        } else {
                            throw new Error(response.message || 'Unknown error');
                        }
                    },
                    error: function(xhr) {
                        Swal.close();
                        
                        let errorMessage = 'Gagal menyimpan data tahfiz';
                        
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);
                            
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
                            
                        } catch (parseError) {
                            errorMessage += '\n\nRaw error: ' + xhr.responseText;
                        }
                        
                        Swal.fire('Error', errorMessage, 'error');
                    }
                });
            }

            function displayStatistics(stats) {
                const content = `
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6>Ringkasan Siswa</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Total Siswa:</strong> ${stats.total_students}</p>
                                    <p><strong>Rata-rata Kehadiran:</strong> ${parseFloat(stats.average_kehadiran || 0).toFixed(2)}</p>
                                    <p><strong>Rata-rata Keaktifan:</strong> ${parseFloat(stats.average_keaktifan || 0).toFixed(2)}</p>
                                    <p><strong>Total Efektif Halaqoh:</strong> ${stats.total_efektif_halaqoh || 0}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6>Ringkasan Kehadiran</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Total Hadir:</strong> ${stats.attendance_summary.total_hadir || 0}</p>
                                    <p><strong>Total Izin:</strong> ${stats.attendance_summary.total_izin || 0}</p>
                                    <p><strong>Total Sakit:</strong> ${stats.attendance_summary.total_sakit || 0}</p>
                                    <p><strong>Total Alpha:</strong> ${stats.attendance_summary.total_alpha || 0}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6>Progress Tahfiz</h6>
                                </div>
                                <div class="card-body">
                                    <p><strong>Dengan Progress Tahfiz:</strong> ${stats.progress_counts.with_tahfiz_progress}</p>
                                    <p><strong>Dengan Progress Tahsin:</strong> ${stats.progress_counts.with_tahsin_progress}</p>
                                    <p><strong>Dengan Target Hafalan:</strong> ${stats.progress_counts.with_target_hafalan}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                $('#statisticsContent').html(content);
            }

            function getNumericValue(text) {
                if (!text || text === '-' || text === '') {
                    return null;
                }
                
                const numValue = parseFloat(text);
                return isNaN(numValue) ? null : numValue;
            }

            // Event delegation for dynamic buttons
            $(document).on('click', '.btn-edit', function() {
                try {
                    const tahfizDataStr = $(this).attr('data-tahfiz');
                    const tahfizData = JSON.parse(tahfizDataStr);
                    openTahfizModal(tahfizData);
                } catch (error) {
                    console.error('Error parsing tahfiz data:', error);
                    Swal.fire('Error', 'Gagal memuat data tahfiz', 'error');
                }
            });

            $(document).on('click', '.btn-delete', function() {
                const tahfizId = $(this).data('id');
                
                if (!tahfizId) {
                    Swal.fire('Perhatian', 'Data tahfiz tidak ditemukan', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Hapus Data Tahfiz?',
                    text: 'Data yang dihapus tidak dapat dikembalikan',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteTahfiz(tahfizId);
                    }
                });
            });

            function deleteTahfiz(tahfizId) {
                $.ajax({
                    url: `{{ url('tahfiz') }}/${tahfizId}`,
                    method: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire('Berhasil', 'Data tahfiz berhasil dihapus', 'success');
                        loadTahfizRecords();
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal menghapus data tahfiz', 'error');
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
            });

            // Prevent invalid input in numeric cells
            $(document).on('keypress', '.editable-cell', function(e) {
                const cellIndex = $(this).index();
                
                // Check if this is a numeric cell (efektif_halaqoh, hadir, keatifan, izin, sakit, alpha)
                if (cellIndex >= 6 && cellIndex <= 11) {
                    const char = String.fromCharCode(e.which);
                    
                    // Allow: backspace, delete, tab, escape, enter
                    if ([8, 9, 27, 13].indexOf(e.keyCode) !== -1 ||
                        // Allow: Ctrl+A, Command+A
                        (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                        // Allow: home, end, left, right, down, up
                        (e.keyCode >= 35 && e.keyCode <= 40)) {
                        return;
                    }
                    
                    // Ensure that it is a number
                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && 
                        (e.keyCode < 96 || e.keyCode > 105)) {
                        e.preventDefault();
                    }
                }
            });

            // Format numeric input
            $(document).on('input', '.editable-cell', function() {
    const cellIndex = $(this).index();
    let value = $(this).text();
    const originalValue = value; // Simpan nilai asli untuk perbandingan

    // --- AWAL MODIFIKASI ---

    // Handle text cells (Progres Tahfiz, Tahsin, Target Hafalan)
    // Indeks kolom: 3, 4, 5
    if (cellIndex >= 3 && cellIndex <= 5) {
        // Cukup hapus simbol strip '-'. Ini akan menghilangkan placeholder
        // tanpa menghapus karakter lain seperti spasi atau huruf.
        value = value.replace('-', '');
    } 

    // Handle numeric cells (Efektif Halaqoh, Hadir, dll.)
    // Indeks kolom: 6, 7, 8, 9, 10, 11
    else if (cellIndex >= 6 && cellIndex <= 11) {
        // Hapus semua karakter yang bukan angka
        value = value.replace(/[^0-9]/g, '');

        // Penanganan khusus untuk Keaktifan (maksimal 100)
        if (cellIndex === 8 && parseInt(value) > 100) {
            value = '100';
        }
    }

    // --- AKHIR MODIFIKASI ---

    // Update isi sel jika nilainya benar-benar berubah
    if (originalValue !== value) {
        $(this).text(value);

        // Pindahkan kursor ke akhir teks untuk pengalaman mengetik yang lancar
        try {
            const range = document.createRange();
            const sel = window.getSelection();
            // Pastikan ada konten di dalam sel sebelum mengatur range
            if (this.childNodes.length > 0) {
                range.setStart(this.childNodes[0], value.length);
                range.collapse(true);
                sel.removeAllRanges();
                sel.addRange(range);
            }
        } catch (e) {
            console.error("Error setting cursor position:", e);
        }
    }
});

            // Initialize with current academic year
            $('#academicYearFilter').val('{{ date("Y") }}/{{ date("Y") + 1 }}');
            currentFilters.academic_year = '{{ date("Y") }}/{{ date("Y") + 1 }}';
        });
    </script>
@endpush