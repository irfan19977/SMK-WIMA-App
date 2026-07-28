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
