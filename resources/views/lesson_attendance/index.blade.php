@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Absensi Pelajaran</h4>
        <div class="card-header-action">
            @can('lesson_attendances.create')    
                <div class="input-group">
                    <button class="btn btn-primary" id="btn-create" data-toggle="tooltip"
                        style="margin-right: 10px;" title="Tambah Data">
                        <i class="fas fa-plus"></i>
                    </button>
                    <input type="text" class="form-control" placeholder="Cari Siswa/Kelas/Pelajaran/Tanggal" 
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
        @livewire('lesson-attendance-table')
    </div>
</div>

<!-- Modal untuk Create/Edit -->
<div class="modal fade" id="lessonAttendanceModal" tabindex="-1" role="dialog" aria-labelledby="lessonAttendanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="lessonAttendanceModalLabel">Tambah Data Absensi Pelajaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="lessonAttendanceForm">
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
                            <small class="text-muted">*Masukkan NISN maka nama, kelas, dan mata pelajaran akan terisi otomatis</small>
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
                        <label for="subject_id">Mata Pelajaran <span class="text-danger">*</span></label>
                        <select class="form-control" id="subject_id" name="subject_id" required>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                        </select>
                        <div class="invalid-feedback d-none" id="subject_id-error"></div>
                    </div>
                    
                    <div class="form-group">
                        <label for="date">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="date" name="date" required>
                        <div class="invalid-feedback d-none" id="date-error"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-primary">Absensi Masuk Pelajaran</h6>
                            <div class="form-group">
                                <label for="check_in">Jam Masuk Pelajaran</label>
                                <input type="time" class="form-control" id="check_in" name="check_in">
                                <div class="invalid-feedback d-none" id="check_in-error"></div>
                                <small class="text-muted">*Kosongkan jika ingin menggunakan waktu saat ini</small>
                            </div>
                            <!-- Status Masuk - Hanya tampil saat edit -->
                            <div class="form-group">
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

    // Function to load subjects based on class
    function loadSubjectsByClass(classId) {
        if (!classId) {
            const subjectSelect = document.getElementById('subject_id');
            subjectSelect.innerHTML = '<option value="">-- Pilih Mata Pelajaran --</option>';
            return;
        }

        fetch(`/lesson-attendances/get-subjects-by-class/${classId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            const subjectSelect = document.getElementById('subject_id');
            subjectSelect.innerHTML = '<option value="">-- Pilih Mata Pelajaran --</option>';
            
            if (data.success && data.subjects) {
                data.subjects.forEach(subject => {
                    subjectSelect.innerHTML += `<option value="${subject.id}">${subject.name}</option>`;
                });
            }
        })
        .catch(error => {
            console.error('Error loading subjects:', error);
        });
    }

    // Function to find student by NISN - Fixed Version
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

    // Clear previous selections
    clearFormSelections();

    fetch(`/lesson-attendances/find-by-nisn/${nisn}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        // Check if response is ok
        if (!response.ok) {
            return response.json().then(errorData => {
                throw new Error(errorData.message || `HTTP Error: ${response.status}`);
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data); // For debugging
        
        if (data.success && data.data) {
            const studentData = data.data;
            
            // Populate student select
            const studentSelect = document.getElementById('student_id');
            studentSelect.innerHTML = `<option value="${studentData.id}" selected>${studentData.name}</option>`;
            
            // Populate class select
            const classSelect = document.getElementById('class_id');
            // First try to find existing option
            let classFound = false;
            for (let option of classSelect.options) {
                if (option.value == studentData.class_id) {
                    option.selected = true;
                    classFound = true;
                    break;
                }
            }
            
            // If class not found in existing options, add it
            if (!classFound) {
                const classOption = document.createElement('option');
                classOption.value = studentData.class_id;
                classOption.text = studentData.class_name;
                classOption.selected = true;
                classSelect.appendChild(classOption);
            }
            
            // Load subjects for the selected class and set the current subject
            loadSubjectsByClass(studentData.class_id, () => {
                // Callback to set subject after subjects are loaded
                const subjectSelect = document.getElementById('subject_id');
                let subjectFound = false;
                
                for (let option of subjectSelect.options) {
                    if (option.value == studentData.subject_id) {
                        option.selected = true;
                        subjectFound = true;
                        break;
                    }
                }
                
                // If subject not found, add it
                if (!subjectFound) {
                    const subjectOption = document.createElement('option');
                    subjectOption.value = studentData.subject_id;
                    subjectOption.text = studentData.subject_name;
                    subjectOption.selected = true;
                    subjectSelect.appendChild(subjectOption);
                }
            });
            
            // Set current date
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('date').value = today;
            
            // Show success message with schedule info
            const scheduleInfo = studentData.current_schedule;
            const message = `Siswa ditemukan: ${studentData.name}\n` +
                          `Kelas: ${studentData.class_name}\n` +
                          `Mata Pelajaran: ${studentData.subject_name}\n` +
                          `Guru: ${studentData.teacher_name}\n` +
                          `Waktu: ${scheduleInfo.start_time} - ${scheduleInfo.end_time}`;
            
            showAlert('success', 'Berhasil!', message);
            
        } else {
            throw new Error(data.message || 'Data siswa tidak lengkap');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        clearFormSelections();
        showAlert('error', 'Tidak Ditemukan', error.message);
    })
    .finally(() => {
        // Restore button state
        findBtn.innerHTML = originalText;
        findBtn.disabled = false;
    });
}

// Helper function to clear form selections
function clearFormSelections() {
    const studentSelect = document.getElementById('student_id');
    const classSelect = document.getElementById('class_id');
    const subjectSelect = document.getElementById('subject_id');
    
    // Reset to default options
    studentSelect.innerHTML = '<option value="">-- Pilih Siswa --</option>';
    classSelect.value = '';
    subjectSelect.innerHTML = '<option value="">-- Pilih Mata Pelajaran --</option>';
}

// Updated loadSubjectsByClass function with callback support
function loadSubjectsByClass(classId, callback = null) {
    if (!classId) {
        const subjectSelect = document.getElementById('subject_id');
        subjectSelect.innerHTML = '<option value="">-- Pilih Mata Pelajaran --</option>';
        return;
    }

    fetch(`/lesson-attendances/get-subjects-by-class/${classId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        const subjectSelect = document.getElementById('subject_id');
        subjectSelect.innerHTML = '<option value="">-- Pilih Mata Pelajaran --</option>';
        
        if (data.success && data.subjects) {
            data.subjects.forEach(subject => {
                const option = document.createElement('option');
                option.value = subject.id;
                option.text = subject.name;
                subjectSelect.appendChild(option);
            });
        }
        
        // Execute callback if provided
        if (callback && typeof callback === 'function') {
            callback();
        }
    })
    .catch(error => {
        console.error('Error loading subjects:', error);
        if (callback && typeof callback === 'function') {
            callback();
        }
    });
}

    // CRUD functions
    function createLessonAttendance() {
        isEditMode = false;
        editId = null;
        
        fetch('{{ route("lesson-attendances.create") }}', {
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
                document.getElementById('lessonAttendanceModalLabel').textContent = data.title;
                populateSelects(data.students, data.classes, data.subjects);
                resetForm();
                toggleStatusFields(false); // Hide status fields for create
                toggleNisnSearch(true); // Show NISN search for create
                $('#lessonAttendanceModal').modal('show');
            }
        })
        .catch(() => showAlert('error', 'Gagal', 'Terjadi kesalahan saat memuat data.'));
    }

    function editLessonAttendance(id) {
        isEditMode = true;
        editId = id;
        
        fetch(`{{ url('lesson-attendances') }}/${id}/edit`, {
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
                document.getElementById('lessonAttendanceModalLabel').textContent = data.title;
                populateSelects(data.students, data.classes, data.subjects);
                fillForm(data.lesson_attendance);
                toggleStatusFields(true); // Show status fields for edit
                toggleNisnSearch(false); // Hide NISN search for edit
                $('#lessonAttendanceModal').modal('show');
            } else {
                showAlert('error', 'Gagal', data.message || 'Terjadi kesalahan saat memuat data.');
            }
        })
        .catch(() => showAlert('error', 'Gagal', 'Terjadi kesalahan saat memuat data.'));
    }

    function showLessonAttendanceDetail(id) {
        fetch(`{{ url('lesson-attendances') }}/${id}`, {
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
                const lessonAttendance = data.lesson_attendance;
                const dateInfo = formatDate(lessonAttendance.date);
                const detailHtml = `
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Nama Siswa:</strong><br>${lessonAttendance.student_name}<br><br>
                            <strong>Kelas:</strong><br>${lessonAttendance.class_name}<br><br>
                            <strong>Mata Pelajaran:</strong><br>${lessonAttendance.subject_name}<br><br>
                            <strong>Tanggal:</strong><br>${dateInfo.day}, ${dateInfo.formatted}
                        </div>
                        <div class="col-md-6">
                            <strong>Jam Masuk:</strong><br>${formatTime(lessonAttendance.check_in) || 'Tidak ada data'}<br><br>
                            <strong>Status:</strong><br>${statusConfig[lessonAttendance.check_in_status]?.text || 'Tidak ada data'}
                        </div>
                    </div>
                `;
                
                swal({
                    title: "Detail Absensi Pelajaran",
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
    function populateSelects(students, classes, subjects) {
        const studentSelect = document.getElementById('student_id');
        const classSelect = document.getElementById('class_id');
        const subjectSelect = document.getElementById('subject_id');
        
        studentSelect.innerHTML = '<option value="">-- Pilih Siswa --</option>';
        students.forEach(student => {
            studentSelect.innerHTML += `<option value="${student.id}">${student.name}</option>`;
        });
        
        classSelect.innerHTML = '<option value="">-- Pilih Kelas --</option>';
        classes.forEach(classItem => {
            classSelect.innerHTML += `<option value="${classItem.id}">${classItem.name}</option>`;
        });

        subjectSelect.innerHTML = '<option value="">-- Pilih Mata Pelajaran --</option>';
        if (subjects) {
            subjects.forEach(subject => {
                subjectSelect.innerHTML += `<option value="${subject.id}">${subject.name}</option>`;
            });
        }
    }

    function fillForm(lessonAttendance) {
        Object.keys(lessonAttendance).forEach(key => {
            const element = document.getElementById(key);
            if (element) element.value = lessonAttendance[key] || '';
        });

        // Load subjects when editing and class is selected
        if (lessonAttendance.class_id) {
            loadSubjectsByClass(lessonAttendance.class_id);
            // Set timeout to ensure subjects are loaded before setting the value
            setTimeout(() => {
                const subjectSelect = document.getElementById('subject_id');
                if (lessonAttendance.subject_id) {
                    subjectSelect.value = lessonAttendance.subject_id;
                }
            }, 500);
        }
    }

    function resetForm() {
        document.getElementById('lessonAttendanceForm').reset();
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
        const url = isEditMode ? `{{ url('lesson-attendances') }}/${editId}` : '{{ route("lesson-attendances.store") }}';
        
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
                    $('#lessonAttendanceModal').modal('hide');
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
        document.getElementById('btn-create').addEventListener('click', createLessonAttendance);
        document.getElementById('lessonAttendanceForm').addEventListener('submit', handleFormSubmit);
        document.getElementById('findStudentBtn').addEventListener('click', findStudentByNisn);

        // Class change event to load subjects
        document.getElementById('class_id').addEventListener('change', function() {
            const classId = this.value;
            loadSubjectsByClass(classId);
        });

        // Allow Enter key to trigger NISN search
        document.getElementById('nisn').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                findStudentByNisn();
            }
        });

        // Clear validation on input change
        document.querySelectorAll('#lessonAttendanceForm input, #lessonAttendanceForm select').forEach(element => {
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
        $('#lessonAttendanceModal').on('hidden.bs.modal', function() {
            resetForm();
            isEditMode = false;
            editId = null;
            toggleStatusFields(false); // Hide status fields when modal is closed
            toggleNisnSearch(false); // Hide NISN search when modal is closed
        });
    });
</script>
@endpush