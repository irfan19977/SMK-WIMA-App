@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Absensi Masuk In/ Out</h4>
        <div class="card-header-action">
            @can('attendances.create')    
                <div class="input-group">
                    <button class="btn btn-primary" id="btn-create" data-toggle="tooltip"
                        style="margin-right: 10px;" title="Tambah Data">
                        <i class="fas fa-plus"></i>
                    </button>
                    <input type="text" class="form-control" placeholder="Cari Siswa/Kelas/Tanggal" 
                        wire:model.debounce.500ms="search" autocomplete="off">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-primary" style="margin-top: 1px;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            @endcan
        </div>
    </div>
    <div class="card-body p-0">
        @livewire('attendance-table')
    </div>
</div>

<!-- Modal untuk Create/Edit -->
<div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="attendanceModalLabel">Tambah Data Absensi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="attendanceForm">
                @csrf
                <div class="modal-body">
                    <!-- NISN Search Section - Only show in create mode -->
                    <div id="nisn-search-section">
                        <div class="form-group">
                            <label for="nisn">NISN <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="nisn" name="nisn" 
                                       placeholder="Masukkan NISN Siswa" autocomplete="off">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary" id="findStudentBtn">
                                        <i class="fas fa-search"></i> Cari Siswa
                                    </button>
                                </div>
                            </div>
                            <div class="invalid-feedback d-none" id="nisn-error"></div>
                            <small class="text-muted">*Masukkan NISN maka nama dan kelas akan terisi otomatis</small>
                        </div>
                        <hr>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="student_id">Siswa <span class="text-danger">*</span></label>
                                <select class="form-control" id="student_id" name="student_id" required>
                                    <option value="">-- Pilih Siswa --</option>
                                </select>
                                <div class="invalid-feedback d-none" id="student_id-error"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="class_id">Kelas <span class="text-danger">*</span></label>
                                <select class="form-control" id="class_id" name="class_id" required>
                                    <option value="">-- Pilih Kelas --</option>
                                </select>
                                <div class="invalid-feedback d-none" id="class_id-error"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="date">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date" name="date" required>
                        <div class="invalid-feedback d-none" id="date-error"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary">Absensi Masuk</h6>
                            <div class="form-group">
                                <label for="check_in">Jam Masuk</label>
                                <input type="time" class="form-control" id="check_in" name="check_in">
                                <div class="invalid-feedback d-none" id="check_in-error"></div>
                            </div>
                            <!-- Status Masuk - Hanya tampil saat edit -->
                            <div class="form-group status-field" style="display: none;">
                                <label for="check_in_status">Status Masuk</label>
                                <select class="form-control" id="check_in_status" name="check_in_status">
                                    <option value="tepat">Tepat Waktu</option>
                                    <option value="terlambat">Terlambat</option>
                                    <option value="izin">Izin</option>
                                    <option value="sakit">Sakit</option>
                                    <option value="alpha">Alpha</option>
                                </select>
                                <div class="invalid-feedback d-none" id="check_in_status-error"></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h6 class="text-success">Absensi Pulang</h6>
                            <div class="form-group">
                                <label for="check_out">Jam Pulang</label>
                                <input type="time" class="form-control" id="check_out" name="check_out">
                                <div class="invalid-feedback d-none" id="check_out-error"></div>
                            </div>
                            <!-- Status Pulang - Hanya tampil saat edit -->
                            <div class="form-group status-field" style="display: none;">
                                <label for="check_out_status">Status Pulang</label>
                                <select class="form-control" id="check_out_status" name="check_out_status">
                                    <option value="tepat">Tepat Waktu</option>
                                    <option value="lebih_awal">Lebih Awal</option>
                                    <option value="tidak_absen">Tidak Absen</option>
                                    <option value="izin">Izin</option>
                                    <option value="sakit">Sakit</option>
                                    <option value="alpha">Alpha</option>
                                </select>
                                <div class="invalid-feedback d-none" id="check_out_status-error"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Global variables
    let isEditMode = false;
    let editId = null;

    // Status configuration
    const statusConfig = {
        'tepat': { class: 'success', text: 'Tepat Waktu' },
        'terlambat': { class: 'warning', text: 'Terlambat' },
        'lebih_awal': { class: 'warning', text: 'Lebih Awal' },
        'tidak_absen': { class: 'danger', text: 'Tidak Absen' },
        'izin': { class: 'info', text: 'Izin' },
        'sakit': { class: 'secondary', text: 'Sakit' },
        'alpha': { class: 'danger', text: 'Alpha' }
    };

    // Utility functions
    function formatTime(time) {
        return time ? new Date(`1970-01-01T${time}`).toLocaleTimeString('id-ID', {hour: '2-digit', minute: '2-digit'}) : null;
    }

    function formatDate(date) {
        const d = new Date(date);
        return {
            day: d.toLocaleDateString('id-ID', { weekday: 'long' }),
            formatted: d.toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' })
        };
    }

    function showAlert(type, title, message) {
        swal({
            title: title,
            text: message,
            icon: type,
            timer: type === 'success' ? 3000 : undefined,
            buttons: type === 'success' ? false : true
        });
    }

    // Function to toggle status fields visibility
    function toggleStatusFields(show) {
        const statusFields = document.querySelectorAll('.status-field');
        statusFields.forEach(field => {
            field.style.display = show ? 'block' : 'none';
        });
    }

    // Function to toggle NISN search section
    function toggleNisnSearch(show) {
        const nisnSection = document.getElementById('nisn-search-section');
        nisnSection.style.display = show ? 'block' : 'none';
    }

    // Function to find student by NISN
    function findStudentByNisn() {
        const nisn = document.getElementById('nisn').value.trim();
        
        if (!nisn) {
            showAlert('warning', 'Peringatan', 'Silakan masukkan NISN');
            return;
        }

        // Show loading state
        const findBtn = document.getElementById('findStudentBtn');
        const originalText = findBtn.innerHTML;
        findBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari...';
        findBtn.disabled = true;

        fetch(`/attendances/find-by-nisn/${nisn}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            // Cek status response
            if (!response.ok) {
                if (response.status === 404) {
                    throw new Error('NISN tidak ditemukan');
                }
                throw new Error('Terjadi kesalahan pada server');
            }
            return response.json();
        })
        .then(data => {
            if (data.success && data.data) {
                const studentData = data.data;
                
                // Populate student select with found student
                const studentSelect = document.getElementById('student_id');
                studentSelect.innerHTML = `<option value="${studentData.id}" selected>${studentData.name}</option>`;
                
                // Find and select the class if available
                if (studentData.class_id) {
                    const classSelect = document.getElementById('class_id');
                    classSelect.value = studentData.class_id;
                    
                    // If class not found in select options, add it
                    if (classSelect.value !== studentData.class_id) {
                        const option = document.createElement('option');
                        option.value = studentData.class_id;
                        option.text = studentData.class_name;
                        option.selected = true;
                        classSelect.appendChild(option);
                    }
                }

                showAlert('success', 'Berhasil', `Siswa ditemukan: ${studentData.name}\nKelas: ${studentData.class_name}`);
            } else {
                throw new Error(data.message || 'Data siswa tidak lengkap');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Clear selections on error
            const studentSelect = document.getElementById('student_id');
            const classSelect = document.getElementById('class_id');
            studentSelect.innerHTML = '<option value="">-- Pilih Siswa --</option>';
            classSelect.value = '';
            
            showAlert('error', 'Tidak Ditemukan', error.message || 'Siswa dengan NISN tersebut tidak ditemukan');
        })
        .finally(() => {
            findBtn.innerHTML = originalText;
            findBtn.disabled = false;
        });
    }

    // CRUD functions
    function createAttendance() {
        isEditMode = false;
        editId = null;
        
        fetch('{{ route("attendances.create") }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('attendanceModalLabel').textContent = data.title;
                populateSelects(data.students, data.classes);
                resetForm();
                toggleStatusFields(false); // Hide status fields for create
                toggleNisnSearch(true); // Show NISN search for create
                $('#attendanceModal').modal('show');
            }
        })
        .catch(() => showAlert('error', 'Gagal', 'Terjadi kesalahan saat memuat data.'));
    }

    function editAttendance(id) {
        isEditMode = true;
        editId = id;
        
        fetch(`{{ url('attendances') }}/${id}/edit`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('attendanceModalLabel').textContent = data.title;
                populateSelects(data.students, data.classes);
                fillForm(data.attendance);
                toggleStatusFields(true); // Show status fields for edit
                toggleNisnSearch(false); // Hide NISN search for edit
                $('#attendanceModal').modal('show');
            } else {
                showAlert('error', 'Gagal', data.message || 'Terjadi kesalahan saat memuat data.');
            }
        })
        .catch(() => showAlert('error', 'Gagal', 'Terjadi kesalahan saat memuat data.'));
    }

    function showAttendanceDetail(id) {
        fetch(`{{ url('attendances') }}/${id}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const attendance = data.attendance;
                const dateInfo = formatDate(attendance.date);
                const detailHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Nama Siswa:</strong><br>${attendance.student_name}<br><br>
                            <strong>Kelas:</strong><br>${attendance.class_name}<br><br>
                            <strong>Tanggal:</strong><br>${dateInfo.day}, ${dateInfo.formatted}
                        </div>
                        <div class="col-md-6">
                            <strong>Jam Masuk:</strong><br>${formatTime(attendance.check_in) || 'Tidak ada data'}<br><br>
                            <strong>Status Masuk:</strong><br>${statusConfig[attendance.check_in_status]?.text || 'Tidak ada data'}<br><br>
                            <strong>Jam Pulang:</strong><br>${formatTime(attendance.check_out) || 'Belum absen'}<br><br>
                            <strong>Status Pulang:</strong><br>${statusConfig[attendance.check_out_status]?.text || 'Belum absen'}
                        </div>
                    </div>
                `;
                
                swal({
                    title: "Detail Absensi",
                    content: { element: "div", attributes: { innerHTML: detailHtml } },
                    button: "Tutup"
                });
            } else {
                showAlert('error', 'Gagal', data.message || 'Terjadi kesalahan saat memuat data.');
            }
        })
        .catch(() => showAlert('error', 'Gagal', 'Terjadi kesalahan saat memuat data.'));
    }

    // Form functions
    function populateSelects(students, classes) {
        const studentSelect = document.getElementById('student_id');
        const classSelect = document.getElementById('class_id');
        
        studentSelect.innerHTML = '<option value="">-- Pilih Siswa --</option>';
        students.forEach(student => {
            studentSelect.innerHTML += `<option value="${student.id}">${student.name}</option>`;
        });
        
        classSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>';
        classes.forEach(classItem => {
            classSelect.innerHTML += `<option value="${classItem.id}">${classItem.name}</option>`;
        });
    }

    function fillForm(attendance) {
        Object.keys(attendance).forEach(key => {
            const element = document.getElementById(key);
            if (element) element.value = attendance[key] || '';
        });
    }

    function resetForm() {
        document.getElementById('attendanceForm').reset();
        document.querySelectorAll('.invalid-feedback').forEach(el => {
            el.classList.add('d-none');
            el.textContent = '';
        });
        document.querySelectorAll('.form-control').forEach(el => {
            el.classList.remove('is-invalid');
        });
    }

    function handleFormSubmit(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('submitBtn');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Menyimpan...';
        submitBtn.disabled = true;
        
        const formData = new FormData(e.target);
        const url = isEditMode ? `{{ url('attendances') }}/${editId}` : '{{ route("attendances.store") }}';
        
        if (isEditMode) formData.append('_method', 'PUT');
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('success', 'Berhasil!', data.message);
                setTimeout(() => {
                    $('#attendanceModal').modal('hide');
                    // Refresh Livewire component
                    window.livewire.emit('refreshTable');
                }, 1000);
            } else {
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const errorElement = document.getElementById(`${field}-error`);
                        const inputElement = document.getElementById(field);
                        
                        if (errorElement && inputElement) {
                            errorElement.textContent = data.errors[field][0];
                            errorElement.classList.remove('d-none');
                            inputElement.classList.add('is-invalid');
                        }
                    });
                } else {
                    showAlert('error', 'Gagal', data.message || 'Terjadi kesalahan saat menyimpan data.');
                }
            }
        })
        .catch(() => showAlert('error', 'Gagal', 'Terjadi kesalahan saat menyimpan data.'))
        .finally(() => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    }

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        // Button events
        document.getElementById('btn-create').addEventListener('click', createAttendance);
        document.getElementById('attendanceForm').addEventListener('submit', handleFormSubmit);
        document.getElementById('findStudentBtn').addEventListener('click', findStudentByNisn);

        // Allow Enter key to trigger NISN search
        document.getElementById('nisn').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                findStudentByNisn();
            }
        });

        // Clear validation on input change
        document.querySelectorAll('#attendanceForm input, #attendanceForm select').forEach(element => {
            element.addEventListener('input', function() {
                this.classList.remove('is-invalid');
                const errorElement = document.getElementById(`${this.name}-error`);
                if (errorElement) {
                    errorElement.classList.add('d-none');
                    errorElement.textContent = '';
                }
            });
        });

        // Modal events
        $('#attendanceModal').on('hidden.bs.modal', function() {
            resetForm();
            isEditMode = false;
            editId = null;
            toggleStatusFields(false); // Hide status fields when modal is closed
            toggleNisnSearch(false); // Hide NISN search when modal is closed
        });
    });
</script>
@endpush