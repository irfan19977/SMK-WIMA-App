<!-- Schedules Form for Modal -->
<form class="was-validated" action="{{ $action }}" method="POST" id="schedule-form">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif
    
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="class_id" class="form-label">{{ __('index.class') }} <span class="text-danger">*</span></label>
                <select class="form-select" id="class_id" name="class_id" required>
                    <option value="">{{ __('index.select_class') }}</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id', $schedule ? $schedule->class_id : '') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    {{ __('index.please_select_class') }}
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="subject_id" class="form-label">{{ __('index.subject') }} <span class="text-danger">*</span></label>
                <select class="form-select" id="subject_id" name="subject_id" required>
                    <option value="">{{ __('index.select_subject') }}</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id', $schedule ? $schedule->subject_id : '') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    {{ __('index.please_select_subject') }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="teacher_id" class="form-label">{{ __('index.teacher') }} <span class="text-danger">*</span></label>
                <select class="form-select" id="teacher_id" name="teacher_id" required>
                    <option value="">{{ __('index.select_teacher') }}</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ old('teacher_id', $schedule ? $schedule->teacher_id : '') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    {{ __('index.please_select_teacher') }}
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="day" class="form-label">{{ __('index.day') }} <span class="text-danger">*</span></label>
                <select class="form-select" id="day" name="day" required>
                    <option value="">{{ __('index.select_day') }}</option>
                    <option value="Senin" {{ strtolower(old('day', $schedule ? $schedule->day : '')) == 'senin' ? 'selected' : '' }}>{{ __('index.monday') }}</option>
                    <option value="Selasa" {{ strtolower(old('day', $schedule ? $schedule->day : '')) == 'selasa' ? 'selected' : '' }}>{{ __('index.tuesday') }}</option>
                    <option value="Rabu" {{ strtolower(old('day', $schedule ? $schedule->day : '')) == 'rabu' ? 'selected' : '' }}>{{ __('index.wednesday') }}</option>
                    <option value="Kamis" {{ strtolower(old('day', $schedule ? $schedule->day : '')) == 'kamis' ? 'selected' : '' }}>{{ __('index.thursday') }}</option>
                    <option value="Jumat" {{ strtolower(old('day', $schedule ? $schedule->day : '')) == 'jumat' ? 'selected' : '' }}>{{ __('index.friday') }}</option>
                    <option value="Sabtu" {{ strtolower(old('day', $schedule ? $schedule->day : '')) == 'sabtu' ? 'selected' : '' }}>{{ __('index.saturday') }}</option>
                </select>
                <div class="invalid-feedback">
                    {{ __('index.please_select_day') }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="start_time" class="form-label">{{ __('index.start_time') }} <span class="text-danger">*</span></label>
                <input type="time" class="form-control" id="start_time" name="start_time"
                    value="{{ old('start_time', $schedule ? substr($schedule->start_time, 0, 5) : '') }}" required>
                <div class="invalid-feedback">
                    {{ __('index.please_enter_start_time') }}
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="end_time" class="form-label">{{ __('index.end_time') }} <span class="text-danger">*</span></label>
                <input type="time" class="form-control" id="end_time" name="end_time"
                    value="{{ old('end_time', $schedule ? substr($schedule->end_time, 0, 5) : '') }}" required>
                <div class="invalid-feedback">
                    {{ __('index.please_enter_end_time') }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="academic_year" class="form-label">{{ __('index.academic_year') }} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="academic_year" name="academic_year" 
                       value="{{ old('academic_year', $schedule ? $schedule->academic_year : ($activeSemester ? $activeSemester->academic_year : \App\Helpers\AcademicYearHelper::getCurrentAcademicYear())) }}" 
                       readonly>
                <div class="form-text text-muted">
                    <i class="mdi mdi-information"></i> {{ __('index.auto_from_active_semester') }}
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="semester" class="form-label">{{ __('index.semester') }} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="semester" name="semester" 
                       value="{{ old('semester', $schedule ? $schedule->semester : ($activeSemester ? ucfirst($activeSemester->semester_type) : 'Ganjil')) }}" 
                       readonly>
                <div class="form-text text-muted">
                    <i class="mdi mdi-information"></i> {{ __('index.auto_from_active_semester') }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> {{ $method === 'PUT' ? __('index.update') : __('index.save') }} {{ __('index.schedule') }}
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close"></i> {{ __('index.cancel') }}
                    </button>
                </div>
                @if($method === 'PUT' && $schedule)
                    <div>
                        <button type="button" class="btn btn-danger" onclick="deleteScheduleFromModal('{{ $schedule->id }}', '{{ $schedule->subject->name ?? '' }}')">
                            <i class="mdi mdi-delete"></i> {{ __('index.delete') }}
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</form>

<script>
    // Form submission via AJAX
    document.getElementById('schedule-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        
        // Show loading
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Menyimpan...';
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw data;
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('schedule-modal'));
                modal.hide();
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message || 'Data berhasil disimpan.',
                    timer: 1500,
                    showConfirmButton: false
                });
                
                // Reload page
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                // Show error message
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: data.message || 'Terjadi kesalahan.'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Handle validation errors
            if (error.errors) {
                let errorMessage = '';
                for (let field in error.errors) {
                    errorMessage += error.errors[field].join('\n') + '\n';
                }
                
                Swal.fire({
                    icon: 'warning',
                    title: 'Validasi Gagal!',
                    text: errorMessage.trim(),
                    confirmButtonColor: '#3085d6'
                });
                
                // Highlight error fields
                for (let field in error.errors) {
                    const input = document.getElementById(field);
                    if (input) {
                        input.classList.add('is-invalid');
                        input.addEventListener('input', function() {
                            this.classList.remove('is-invalid');
                        }, { once: true });
                    }
                }
            } else {
                // Handle other errors
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message || 'Terjadi kesalahan saat menyimpan data.'
                });
            }
        })
        .finally(() => {
            // Reset button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

    // Delete from modal
    function deleteScheduleFromModal(id, name) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Jadwal \"" + name + "\" akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Close modal first
                const modal = bootstrap.Modal.getInstance(document.getElementById('schedule-modal'));
                modal.hide();
                
                // Create form for delete
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/schedules/${id}`;
                form.style.display = 'none';
                
                // Add CSRF token
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (csrfToken) {
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = csrfToken.getAttribute('content');
                    form.appendChild(csrfInput);
                }
                
                // Add method override for DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    // Time validation
    document.addEventListener('DOMContentLoaded', function() {
        const startTimeInput = document.getElementById('start_time');
        const endTimeInput = document.getElementById('end_time');
        
        function validateTimeRange() {
            if (startTimeInput.value && endTimeInput.value) {
                const startTime = startTimeInput.value;
                const endTime = endTimeInput.value;
                
                if (startTime >= endTime) {
                    endTimeInput.setCustomValidity('Waktu selesai harus lebih besar dari waktu mulai');
                } else {
                    endTimeInput.setCustomValidity('');
                }
            }
        }
        
        if (startTimeInput && endTimeInput) {
            startTimeInput.addEventListener('change', validateTimeRange);
            endTimeInput.addEventListener('change', validateTimeRange);
        }
    });
</script>
