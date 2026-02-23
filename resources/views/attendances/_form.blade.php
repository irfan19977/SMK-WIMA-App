<!-- Attendance Form for Modal -->
<form class="was-validated" action="{{ $action }}" method="POST" id="attendance-form">
    @csrf
    @if($method === 'PUT')
        @method('PUT')
    @endif
    
    <!-- NISN Section -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="mb-3">
                <label for="nisn_search" class="form-label">{{ __('index.nisn') }} {{ __('index.student') }} <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nisn_search" placeholder="{{ __('index.enter_nisn_auto_search') }}" 
                           value="{{ old('nisn_search', $attendance && isset($attendance->student) ? $attendance->student->nisn : '') }}"
                           @if($method === 'PUT') disabled @endif>
                <div class="form-text">
                    @if($method === 'PUT') {{ __('index.nisn_cannot_be_changed') }} @else {{ __('index.enter_nisn_to_search_student') }} @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Student Info Section (Auto-filled) -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="student_id" class="form-label">{{ __('index.student_name') }} <span class="text-danger">*</span></label>
                <select class="form-select" id="student_id" name="student_id" required>
                    <option value="">{{ __('index.select_student') }}</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" 
                                data-nisn="{{ $student->nisn ?? '' }}"
                                data-class-id="{{ $student->classes && $student->classes->first() ? $student->classes->first()->id : '' }}"
                                {{ old('student_id', $attendance ? $attendance->student_id : '') == $student->id ? 'selected' : '' }}>
                            {{ $student->name }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    {{ __('index.please_select_student') }}
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="class_id" class="form-label">{{ __('index.class') }} <span class="text-danger">*</span></label>
                <select class="form-select" id="class_id" name="class_id" required>
                    <option value="">{{ __('index.select_class') }}</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ old('class_id', $attendance ? $attendance->class_id : '') == $class->id ? 'selected' : '' }}>
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

    <!-- Date Section -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="date" class="form-label">{{ __('index.date') }} <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="date" name="date"
                    value="{{ old('date', $attendance ? $attendance->date : date('Y-m-d')) }}" required>
                <div class="invalid-feedback">
                    {{ __('index.please_select_date') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Check In Section -->
    <div class="row mb-3" id="check-in-section">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="check_in" class="form-label">{{ __('index.check_in_time') }}</label>
                <input type="time" class="form-control" id="check_in" name="check_in"
                    value="{{ old('check_in', $attendance ? substr($attendance->check_in, 0, 5) : '') }}">
                <div class="form-text">
                    {{ __('index.optional_fill_check_in_time') }}
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="check_in_status" class="form-label">{{ __('index.check_in_status') }}</label>
                <select class="form-select" id="check_in_status" name="check_in_status">
                    <option value="">{{ __('index.select_status') }}</option>
                    <option value="tepat" {{ old('check_in_status', $attendance ? $attendance->check_in_status : '') == 'tepat' ? 'selected' : '' }}>{{ __('index.on_time') }}</option>
                    <option value="terlambat" {{ old('check_in_status', $attendance ? $attendance->check_in_status : '') == 'terlambat' ? 'selected' : '' }}>{{ __('index.late') }}</option>
                    <option value="izin" {{ old('check_in_status', $attendance ? $attendance->check_in_status : '') == 'izin' ? 'selected' : '' }}>{{ __('index.permission') }}</option>
                    <option value="sakit" {{ old('check_in_status', $attendance ? $attendance->check_in_status : '') == 'sakit' ? 'selected' : '' }}>{{ __('index.sick') }}</option>
                    <option value="alpha" {{ old('check_in_status', $attendance ? $attendance->check_in_status : '') == 'alpha' ? 'selected' : '' }}>{{ __('index.absent') }}</option>
                </select>
                <div class="form-text">
                    {{ __('index.status_check_in_will_be_filled_automatically') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Check Out Section -->
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="check_out" class="form-label">{{ __('index.check_out_time') }}</label>
                <input type="time" class="form-control" id="check_out" name="check_out"
                    value="{{ old('check_out', $attendance ? substr($attendance->check_out, 0, 5) : '') }}">
                <div class="form-text">
                    {{ __('index.optional_fill_check_out_time') }}
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="check_out_status" class="form-label">{{ __('index.check_out_status') }}</label>
                <select class="form-select" id="check_out_status" name="check_out_status">
                    <option value="">{{ __('index.select_status') }}</option>
                    <option value="tepat" {{ old('check_out_status', $attendance ? $attendance->check_out_status : '') == 'tepat' ? 'selected' : '' }}>{{ __('index.on_time') }}</option>
                    <option value="lebih_awal" {{ old('check_out_status', $attendance ? $attendance->check_out_status : '') == 'lebih_awal' ? 'selected' : '' }}>{{ __('index.early') }}</option>
                    <option value="tidak_absen" {{ old('check_out_status', $attendance ? $attendance->check_out_status : '') == 'tidak_absen' ? 'selected' : '' }}>{{ __('index.not_absent') }}</option>
                    <option value="izin" {{ old('check_out_status', $attendance ? $attendance->check_out_status : '') == 'izin' ? 'selected' : '' }}>{{ __('index.permission') }}</option>
                    <option value="sakit" {{ old('check_out_status', $attendance ? $attendance->check_out_status : '') == 'sakit' ? 'selected' : '' }}>{{ __('index.sick') }}</option>
                    <option value="alpha" {{ old('check_out_status', $attendance ? $attendance->check_out_status : '') == 'alpha' ? 'selected' : '' }}>{{ __('index.absent') }}</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> {{ $method === 'PUT' ? __('index.update') : __('index.save') }} {{ __('index.attendance') }}
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close"></i> {{ __('index.cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
