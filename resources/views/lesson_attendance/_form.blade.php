<form id="lesson-attendance-form" action="{{ $action }}" method="POST">
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
        /* Fix for Bootstrap 5 select dropdown */
        .modal .form-select {
            position: relative !important;
            z-index: 1 !important;
        }
        .modal .form-select:focus {
            z-index: 2 !important;
        }
    </style>
    
    <!-- NISN Search Section -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="mb-3">
                <label for="nisn_search" class="form-label">{{ __('index.nisn') }}</label>
                <input type="text" class="form-control" id="nisn_search" placeholder="{{ __('index.search_by_nisn') }}">
                <small class="text-muted">{{ __('index.nisn_search_description') }}</small>
            </div>
        </div>
    </div>
    
    <!-- Name and Class Section -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="student_name" class="form-label">Nama</label>
                <input type="text" class="form-control" id="student_name" readonly>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="class_id" class="form-label">{{ __('index.class') }} <span class="text-danger">*</span></label>
                <select class="form-select form-control" id="class_id" name="class_id" required data-bs-container="body">
                    <option value="">{{ __('index.select_class') }}</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id', $preselectedClassId ?? ($lessonAttendance ? $lessonAttendance->class_id : '')) == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    {{ __('index.please_select_class') }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Subject and Date Section -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="subject_id" class="form-label">{{ __('index.subject') }} <span class="text-danger">*</span></label>
                <select class="form-select form-control" id="subject_id" name="subject_id" required data-bs-container="body">
                    <option value="">{{ __('index.select_subject') }}</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id', $preselectedSubjectId ?? ($lessonAttendance ? $lessonAttendance->subject_id : '')) == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    {{ __('index.please_select_subject') }}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="date" class="form-label">{{ __('index.date') }} <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="date" name="date" 
                    value="{{ old('date', $lessonAttendance ? $lessonAttendance->date : date('Y-m-d')) }}" required>
                <div class="invalid-feedback">
                    {{ __('index.please_select_date') }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Attendance Time and Status Section -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="check_in" class="form-label">{{ __('index.check_in_time') }}</label>
                <input type="time" class="form-control" id="check_in" name="check_in">
                <div class="form-text">
                    {{ __('index.optional_fill_check_in_time') }}
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="check_in_status" class="form-label">{{ __('index.check_in_status') }}</label>
                <select class="form-select form-control" id="check_in_status" name="check_in_status" data-bs-container="body">
                    <option value="">{{ __('index.select_status') }}</option>
                    <option value="tepat">{{ __('index.on_time') }}</option>
                    <option value="terlambat">{{ __('index.late') }}</option>
                    <option value="izin">{{ __('index.permission') }}</option>
                    <option value="sakit">{{ __('index.sick') }}</option>
                    <option value="alpha">{{ __('index.absent') }}</option>
                </select>
                <div class="form-text">
                    {{ __('index.status_check_in_will_be_filled_automatically') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="mdi mdi-close"></i> {{ __('index.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="mdi mdi-content-save"></i> {{ __('index.save_lesson_attendance') }}
                </button>
            </div>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const classSelect = document.getElementById('class_id');
    const subjectSelect = document.getElementById('subject_id');
    
    // Note: studentsTableBody no longer exists since we removed the students table
    
    // Auto-load if preselected values exist
    const preselectedClass = classSelect.value;
    const preselectedSubject = subjectSelect.value;
    
    if (preselectedClass) {
        loadSubjects();
    }
    
    function loadSubjects() {
        const classId = classSelect.value;
        
        if (!classId) {
            // Reset subject dropdown properly
            subjectSelect.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
            subjectSelect.disabled = false;
            // Reinitialize Bootstrap dropdown
            const bsSelect = new bootstrap.Select(subjectSelect);
            return;
        }
        
        // Show loading state
        subjectSelect.innerHTML = '<option value="">Loading...</option>';
        subjectSelect.disabled = true;
        
        fetch('/lesson-attendances/get-subjects-by-class', {
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
            subjectSelect.disabled = false;
            if (data.success && data.data.length > 0) {
                let html = '<option value="">Pilih Mata Pelajaran</option>';
                data.data.forEach(subject => {
                    const selected = subject.id == '{{ $preselectedSubjectId ?? "" }}' ? 'selected' : '';
                    html += `<option value="${subject.id}" ${selected}>${subject.name}</option>`;
                });
                subjectSelect.innerHTML = html;
            } else {
                subjectSelect.innerHTML = '<option value="">Tidak ada mata pelajaran untuk kelas ini</option>';
            }
            
            // Reinitialize Bootstrap dropdown after content change
            const bsSelect = new bootstrap.Select(subjectSelect);
        })
        .catch(error => {
            console.error('Error loading subjects:', error);
            subjectSelect.innerHTML = '<option value="">Gagal memuat mata pelajaran</option>';
            subjectSelect.disabled = false;
            // Reinitialize Bootstrap dropdown after error
            const bsSelect = new bootstrap.Select(subjectSelect);
        });
    }
    
    // Event listeners
    classSelect.addEventListener('change', function() {
        loadSubjects();
    });
});

// Initialize form submission handler
if (typeof initializeLessonAttendanceForm === 'function') {
    initializeLessonAttendanceForm();
}
</script>
