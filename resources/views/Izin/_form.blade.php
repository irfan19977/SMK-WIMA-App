<form id="izin-form" action="{{ $action }}" method="POST">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif
    
    <style>
        .modal .dropdown-menu {
            z-index: 9999 !important;
            position: fixed !important;
            max-height: 200px !important;
            overflow-y: auto !important;
        }
        .modal .select-dropdown {
            z-index: 9999 !important;
        }
        .modal.show .select-dropdown {
            z-index: 9999 !important;
        }
        .modal .form-select {
            position: relative !important;
            z-index: 1 !important;
        }
        .modal .form-select:focus {
            z-index: 2 !important;
        }
    </style>
    
    <!-- Class and Student Section -->
    <div class="row mb-3">
        @auth
        @if(auth()->user()->hasRole('Parent'))
            {{-- Parent: langsung tampilkan anak saja tanpa dropdown kelas --}}
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="student_id" class="form-label">Siswa <span class="text-danger">*</span></label>
                    <select class="form-select form-control" id="student_id" name="student_id" required>
                        <option value="">Pilih Siswa</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id', $permission->student_id ?? '') == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} ({{ $student->nisn }})
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">Harap pilih siswa</div>
                </div>
            </div>
        @else
        {{-- Non-Parent: tampilkan dropdown kelas dan siswa --}}
        <div class="col-md-6">
            <div class="mb-3">
                <label for="class_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                <select class="form-select form-control" id="class_id" name="class_id" required data-bs-container="body">
                    <option value="">Pilih Kelas</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id', $permission ? $permission->student->getCurrentClass()?->id ?? '' : '') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    Harap pilih kelas
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="student_id" class="form-label">Siswa <span class="text-danger">*</span></label>
                <select class="form-select form-control" id="student_id" name="student_id" required data-bs-container="body">
                    <option value="">Pilih Siswa</option>
                    @if($permission && $permission->student)
                        <option value="{{ $permission->student_id }}" selected>{{ $permission->student->name }} ({{ $permission->student->nisn }})</option>
                    @endif
                </select>
                <div class="invalid-feedback">
                    Harap pilih siswa
                </div>
            </div>
        </div>
        @endif
        @endauth
    </div>
    
    <!-- Type and Date Section -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="type" class="form-label">Tipe Izin <span class="text-danger">*</span></label>
                <select class="form-select form-control" id="type" name="type" required data-bs-container="body">
                    <option value="">Pilih Tipe Izin</option>
                    <option value="sakit" {{ old('type', $permission->type ?? '') == 'sakit' ? 'selected' : '' }}>Izin Sakit</option>
                    <option value="agenda" {{ old('type', $permission->type ?? '') == 'agenda' ? 'selected' : '' }}>Izin Ada Agenda</option>
                    <option value="pulang_awal" {{ old('type', $permission->type ?? '') == 'pulang_awal' ? 'selected' : '' }}>Izin Pulang Lebih Awal</option>
                </select>
                <div class="invalid-feedback">
                    Harap pilih tipe izin
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="start_date" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="start_date" name="start_date" 
                    value="{{ old('start_date', $permission ? $permission->start_date->format('Y-m-d') : date('Y-m-d')) }}" required>
                <div class="invalid-feedback">
                    Harap pilih tanggal mulai
                </div>
            </div>
        </div>
    </div>
    
    <!-- End Date Section -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="end_date" class="form-label">Tanggal Selesai</label>
                <input type="date" class="form-control" id="end_date" name="end_date" 
                    value="{{ old('end_date', $permission ? $permission->end_date?->format('Y-m-d') : '') }}">
                <div class="form-text">
                    Kosongkan jika izin hanya untuk satu hari
                </div>
            </div>
        </div>
    </div>
    
    <!-- Reason Section -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="mb-3">
                <label for="reason" class="form-label">Alasan <span class="text-danger">*</span></label>
                <textarea class="form-control" id="reason" name="reason" rows="4" required>{{ old('reason', $permission->reason ?? '') }}</textarea>
                <div class="invalid-feedback">
                    Harap isi alasan izin
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="mdi mdi-close"></i> Batal
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="mdi mdi-content-save"></i> Simpan Izin
                </button>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const classSelect = document.getElementById('class_id');
    const studentSelect = document.getElementById('student_id');
    const typeSelect = document.getElementById('type');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    // Auto-load students if class is preselected
    const preselectedClass = classSelect.value;
    if (preselectedClass) {
        loadStudents(preselectedClass);
    }
    
    function loadStudents(classId) {
        if (!classId) {
            studentSelect.innerHTML = '<option value="">Pilih Siswa</option>';
            return;
        }
        
        studentSelect.innerHTML = '<option value="">Loading...</option>';
        studentSelect.disabled = true;
        
        fetch('/izin/get-students-by-class', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                class_id: classId
            })
        })
        .then(response => response.json())
        .then(data => {
            studentSelect.disabled = false;
            if (data.success && data.data.length > 0) {
                let html = '<option value="">Pilih Siswa</option>';
                data.data.forEach(student => {
                    const selected = student.id == '{{ $permission->student_id ?? "" }}' ? 'selected' : '';
                    html += `<option value="${student.id}" ${selected}>${student.name} (${student.nisn})</option>`;
                });
                studentSelect.innerHTML = html;
            } else {
                studentSelect.innerHTML = '<option value="">Tidak ada siswa di kelas ini</option>';
            }
        })
        .catch(error => {
            console.error('Error loading students:', error);
            studentSelect.innerHTML = '<option value="">Gagal memuat siswa</option>';
            studentSelect.disabled = false;
        });
    }
    
    // Event listener for class change
    classSelect.addEventListener('change', function() {
        loadStudents(this.value);
    });
    
    // Set minimum end date to start date
    startDateInput.addEventListener('change', function() {
        endDateInput.min = this.value;
    });
    
    // Initialize minimum end date
    if (startDateInput.value) {
        endDateInput.min = startDateInput.value;
    }
});
</script>
