@extends('layouts.master')
@section('title')
    {{ isset($teacher) ? __('index.edit_teacher') : __('index.add_teacher') }}
@endsection
@section('page-title')
    {{ isset($teacher) ? __('index.edit_teacher') : __('index.add_teacher') }}
@endsection
@section('body')
    <body data-sidebar="colored">
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ isset($teacher) ? __('index.edit_teacher') : __('index.add_teacher') }}</h4>
                    <p class="card-title-desc">{{ isset($teacher) ? __('index.edit_teacher_description') : __('index.add_teacher_description') }} {{ __('index.form_validation_description') }}.</p>
                    
                    <form class="was-validated" action="{{ isset($teacher) ? route('teachers.update', $teacher->id) : route('teachers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($teacher)
                            @method('PUT')
                        @endisset
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('index.full_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', isset($teacher) ? $teacher->name : '') }}" 
                                        placeholder="{{ __('index.enter_teacher_full_name') }}" required>
                                    <div class="invalid-feedback">
                                        {{ __('index.please_enter_teacher_full_name') }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nip" class="form-label">{{ __('index.nip') }}</label>
                                    <input type="text" class="form-control" id="nip" name="nip"
                                        value="{{ old('nip', isset($teacher) ? $teacher->nip : '') }}" 
                                        placeholder="{{ __('index.optional_nip') }}">
                                    <div class="form-text">{{ __('index.leave_empty_if_no_nip') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('index.email') }} <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', isset($teacher) ? $teacher->user->email : '') }}" 
                                        placeholder="{{ __('index.enter_teacher_email') }}" required>
                                    <div class="invalid-feedback">
                                        {{ __('index.please_enter_valid_email') }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">{{ __('index.phone_number') }}</label>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        value="{{ old('phone', isset($teacher) ? $teacher->user->phone : '') }}" 
                                        placeholder="{{ __('index.optional_teacher_phone') }}">
                                    <div class="form-text">{{ __('index.leave_empty_if_no_phone') }}</div>
                                    <div class="invalid-feedback">
                                        {{ __('index.invalid_phone_number') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(!isset($teacher))
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">{{ __('index.teacher_password') }} <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <div class="invalid-feedback">
                                        {{ __('index.please_enter_teacher_password') }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">{{ __('index.teacher_password_confirmation') }} <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    <div class="invalid-feedback">
                                        {{ __('index.teacher_password_confirmation_not_match') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="gender" class="form-label">{{ __('index.teacher_gender') }}</label>
                                    <select class="form-select" id="gender" name="gender">
                                        <option value="">{{ __('index.teacher_select_gender') }}</option>
                                        <option value="L" {{ old('gender', isset($teacher) ? $teacher->gender : '') == 'L' ? 'selected' : '' }}>{{ __('index.male') }}</option>
                                        <option value="P" {{ old('gender', isset($teacher) ? $teacher->gender : '') == 'P' ? 'selected' : '' }}>{{ __('index.female') }}</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="no_card" class="form-label">{{ __('index.teacher_no_card') }}</label>
                                    <input type="text" class="form-control" id="no_card" name="no_card"
                                        value="{{ old('no_card', isset($teacher) ? $teacher->no_card : '') }}" 
                                        placeholder="{{ __('index.teacher_optional_no_card') }}">
                                    <div class="form-text">{{ __('index.teacher_leave_empty_if_no_card') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="education_level" class="form-label">{{ __('index.teacher_last_education') }}</label>
                                    <select class="form-select" id="education_level" name="education_level">
                                        <option value="">{{ __('index.teacher_select_education') }}</option>
                                        <option value="SMA" {{ old('education_level', isset($teacher) ? $teacher->education_level : '') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                        <option value="D3" {{ old('education_level', isset($teacher) ? $teacher->education_level : '') == 'D3' ? 'selected' : '' }}>D3</option>
                                        <option value="S1" {{ old('education_level', isset($teacher) ? $teacher->education_level : '') == 'S1' ? 'selected' : '' }}>S1</option>
                                        <option value="S2" {{ old('education_level', isset($teacher) ? $teacher->education_level : '') == 'S2' ? 'selected' : '' }}>S2</option>
                                        <option value="S3" {{ old('education_level', isset($teacher) ? $teacher->education_level : '') == 'S3' ? 'selected' : '' }}>S3</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="education_major" class="form-label">{{ __('index.education_major') }}</label>
                                    <input type="text" class="form-control" id="education_major" name="education_major"
                                        value="{{ old('education_major', isset($teacher) ? $teacher->education_major : '') }}" 
                                        placeholder="{{ __('index.optional_education_major') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="education_institution" class="form-label">{{ __('index.education_institution') }}</label>
                                    <input type="text" class="form-control" id="education_institution" name="education_institution"
                                        value="{{ old('education_institution', isset($teacher) ? $teacher->education_institution : '') }}" 
                                        placeholder="{{ __('index.optional_education_institution') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="address" class="form-label">{{ __('index.teacher_address') }} <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="address" name="address" rows="3"
                                        placeholder="{{ __('index.teacher_enter_address') }}" required>{{ old('address', isset($teacher) ? $teacher->address : '') }}</textarea>
                                    <div class="invalid-feedback">
                                        {{ __('index.teacher_please_enter_address') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="province" class="form-label">{{ __('index.province') }}</label>
                                    <input type="text" class="form-control" id="province" name="province"
                                        value="{{ old('province', isset($teacher) ? $teacher->province : '') }}" 
                                        placeholder="{{ __('index.optional_province') }}">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="regency" class="form-label">{{ __('index.regency') }}</label>
                                    <input type="text" class="form-control" id="regency" name="regency"
                                        value="{{ old('regency', isset($teacher) ? $teacher->regency : '') }}" 
                                        placeholder="{{ __('index.optional_regency') }}">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="district" class="form-label">{{ __('index.district') }}</label>
                                    <input type="text" class="form-control" id="district" name="district"
                                        value="{{ old('district', isset($teacher) ? $teacher->district : '') }}" 
                                        placeholder="{{ __('index.optional_district') }}">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="village" class="form-label">{{ __('index.village') }}</label>
                                    <input type="text" class="form-control" id="village" name="village"
                                        value="{{ old('village', isset($teacher) ? $teacher->village : '') }}" 
                                        placeholder="{{ __('index.optional_village') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="photo" class="form-label">{{ __('index.profile_photo') }}</label>
                                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                    <div class="form-text">{{ __('index.max_500kb_jpg_png_gif') }}</div>
                                    @if(isset($teacher) && $teacher->user->photo)
                                        <div class="mt-2">
                                            <small>{{ __('index.current_photo') }} 
                                                <img src="{{ asset('storage/' . $teacher->user->photo) }}" alt="Foto" style="max-height: 50px; max-width: 50px;" class="rounded">
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if(isset($teacher))
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="status" name="status" {{ $teacher->user->status ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status">
                                            {{ __('index.active_status') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <button class="btn btn-primary" type="submit">
                                            <i class="mdi mdi-content-save"></i> {{ isset($teacher) ? __('index.update_teacher') : __('index.save_teacher') }}
                                        </button>
                                        <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                                            <i class="mdi mdi-arrow-left"></i> {{ __('index.back') }}
                                        </a>
                                    </div>
                                    <div>
                                        @isset($teacher)
                                            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                                <i class="mdi mdi-delete"></i> {{ __('index.delete_teacher') }}
                                            </button>
                                        @endisset
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Sweet Alerts js -->
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-format phone number
            const phoneInput = document.getElementById('phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    // Remove all non-numeric characters
                    let value = e.target.value.replace(/\D/g, '');
                    
                    // Format as Indonesian phone number
                    if (value.length > 0) {
                        if (value.startsWith('62')) {
                            value = '+' + value;
                        } else if (value.startsWith('0')) {
                            value = '+62' + value.substring(1);
                        } else {
                            value = '+62' + value;
                        }
                    }
                    
                    e.target.value = value;
                });
            }

            // Form validation
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const password = document.getElementById('password');
                    const passwordConfirmation = document.getElementById('password_confirmation');
                    
                    // Check password confirmation if password field exists
                    if (password && passwordConfirmation) {
                        if (password.value !== passwordConfirmation.value) {
                            e.preventDefault();
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: '{{ __('index.password_confirmation_mismatch') }}'
                            });
                            return false;
                        }
                    }
                });
            }
        });

        // Delete confirmation (only for edit)
        @isset($teacher)
        function confirmDelete() {
            Swal.fire({
                title: '{{ __('index.are_you_sure') }}',
                text: "{{ __('index.teacher_will_be_deleted') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ __('index.yes_delete') }}',
                cancelButtonText: '{{ __('index.cancel') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form for delete
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("teachers.destroy", $teacher->id) }}';
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
        @endisset
    </script>
@endsection
