<!-- Classes Form for Modal -->
<form class="was-validated" action="{{ $action }}?redirect_to={{ request()->redirect_to ?? '' }}" method="POST" id="class-form">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif
    
    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="grade" class="form-label">{{ __('index.grade') }} <span class="text-danger">*</span></label>
                <select class="form-select" id="grade" name="grade" required>
                    <option value="">{{ __('index.select_grade') }}</option>
                    <option value="10" {{ old('grade', $class ? $class->grade : '') == '10' ? 'selected' : '' }}>X (10)</option>
                    <option value="11" {{ old('grade', $class ? $class->grade : '') == '11' ? 'selected' : '' }}>XI (11)</option>
                    <option value="12" {{ old('grade', $class ? $class->grade : '') == '12' ? 'selected' : '' }}>XII (12)</option>
                </select>
                <div class="invalid-feedback">
                    {{ __('index.please_select_grade') }}.
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="major" class="form-label">{{ __('index.major') }} <span class="text-danger">*</span></label>
                <select class="form-select" id="major" name="major" required>
                    <option value="">{{ __('index.select_major') }}</option>
                    <option value="Teknik Komputer & Jaringan" {{ old('major', $class ? $class->major : '') == 'Teknik Komputer & Jaringan' ? 'selected' : '' }}>{{ __('index.major_tkj') }}</option>
                    <option value="Teknik Bisnis Sepeda Motor" {{ old('major', $class ? $class->major : '') == 'Teknik Bisnis Sepeda Motor' ? 'selected' : '' }}>{{ __('index.major_tsm') }}</option>
                    <option value="Teknik Kendaraan Ringan Otomotif" {{ old('major', $class ? $class->major : '') == 'Teknik Kendaraan Ringan Otomotif' ? 'selected' : '' }}>{{ __('index.major_tkr') }}</option>
                    <option value="Teknik Kimia Industri" {{ old('major', $class ? $class->major : '') == 'Teknik Kimia Industri' ? 'selected' : '' }}>{{ __('index.major_ki') }}</option>
                </select>
                <div class="invalid-feedback">
                    {{ __('index.please_select_major') }}.
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="academic_year" class="form-label">{{ __('index.academic_year') }} <span class="text-danger">*</span></label>
                <select class="form-select" id="academic_year" name="academic_year" required>
                    <option value="">{{ __('index.select_academic_year') }}</option>
                    @php
                        $currentYear = \App\Helpers\AcademicYearHelper::getCurrentAcademicYear();
                        $academicYears = \App\Helpers\AcademicYearHelper::generateAcademicYears(1, 3);
                        $selectedYear = old('academic_year', $class ? $class->academic_year : $currentYear);
                    @endphp
                    @foreach($academicYears as $year)
                        <option value="{{ $year }}" {{ $year === $selectedYear ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    {{ __('index.please_select_academic_year') }}.
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('index.class_name') }} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name', $class ? $class->name : '') }}" 
                    placeholder="{{ __('index.class_name_example') }}" required>
                <div class="form-text">{{ __('index.auto_generate_class_name') }}</div>
                <div class="invalid-feedback">
                    {{ __('index.please_enter_class_name') }}.
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> {{ $method === 'PUT' ? __('index.update') : __('index.save') }} {{ __('index.class') }}
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close"></i> {{ __('index.cancel') }}
                    </button>
                </div>
                @if($method === 'PUT' && $class)
                    <div>
                        <button type="button" class="btn btn-danger" onclick="deleteFromClassModal('{{ $class->id }}', '{{ $class->name }}')">
                            <i class="mdi mdi-delete"></i> {{ __('index.delete') }}
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</form>

<script>
    // Auto-generate class name when grade, major, or academic year changes
    document.addEventListener('DOMContentLoaded', function() {
        const gradeSelect = document.getElementById('grade');
        const majorSelect = document.getElementById('major');
        const academicYearSelect = document.getElementById('academic_year');
        const nameInput = document.getElementById('name');
        
        function generateClassName() {
            const grade = gradeSelect.value;
            const major = majorSelect.value;
            const academicYear = academicYearSelect.value;
            
            if (grade && major && academicYear) {
                // Generate class name using same logic as controller
                const majorShortMap = {
                    'Teknik Komputer & Jaringan': '{{ __("index.tkj") }}',
                    'Teknik Bisnis Sepeda Motor': '{{ __("index.tsm") }}',
                    'Teknik Kendaraan Ringan Otomotif': '{{ __("index.tkr") }}',
                    'Teknik Kimia Industri': '{{ __("index.ki") }}',
                };

                const romanGradeMap = {
                    '10': '{{ __("index.grade_10_short") }}',
                    '11': '{{ __("index.grade_11_short") }}',
                    '12': '{{ __("index.grade_12_short") }}',
                };

                const gradeLabel = romanGradeMap[grade] || grade;
                const majorLabel = majorShortMap[major] || major;
                
                const className = gradeLabel + ' ' + majorLabel + ' ' + academicYear;
                nameInput.value = className;
            }
        }
        
        // Only auto-generate if name is empty (for create mode)
        @if(!$class)
        gradeSelect.addEventListener('change', function() {
            if (!nameInput.value.trim()) {
                generateClassName();
            }
        });
        
        majorSelect.addEventListener('change', function() {
            if (!nameInput.value.trim()) {
                generateClassName();
            }
        });
        
        academicYearSelect.addEventListener('change', function() {
            if (!nameInput.value.trim()) {
                generateClassName();
            }
        });
        @endif
    });

    // Form submission via AJAX
    document.getElementById('class-form').addEventListener('submit', function(e) {
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
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('class-modal'));
                modal.hide();
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message || 'Data berhasil disimpan.',
                    timer: 1500,
                    showConfirmButton: false
                });
                
                // Check if should redirect to show page
                const urlParams = new URLSearchParams(this.action);
                const redirectTo = urlParams.get('redirect_to');
                
                if (redirectTo === 'show') {
                    // Redirect to show page
                    setTimeout(() => {
                        window.location.href = `/classes/${data.data?.id || ''}`;
                    }, 1500);
                } else {
                    // Reload page (index)
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                }
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
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Terjadi kesalahan saat menyimpan data.'
            });
        })
        .finally(() => {
            // Reset button
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    });

    // Delete from modal
    function deleteFromClassModal(id, name) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Kelas \"" + name + "\" akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Close modal first
                const modal = bootstrap.Modal.getInstance(document.getElementById('class-modal'));
                modal.hide();
                
                // Create form for delete
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/classes/${id}`;
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
</script>
