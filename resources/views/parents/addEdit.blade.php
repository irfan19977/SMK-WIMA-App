@extends('layouts.master')
@section('title')
    {{ isset($parent) ? __('index.edit_parent') : __('index.add_parent') }}
@endsection
@section('page-title')
    {{ isset($parent) ? __('index.edit_parent') : __('index.add_parent') }}
@endsection
@section('body')
    <body data-sidebar="colored">
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ isset($parent) ? __('index.edit_parent') : __('index.add_parent') }}</h4>
                    <p class="card-title-desc">{{ isset($parent) ? __('index.edit_parent_description') : __('index.add_parent_description') }} {{ __('index.form_validation_description') }}.</p>
                    
                    <form class="was-validated" action="{{ isset($parent) ? route('parents.update', $parent->id) : route('parents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @isset($parent)
                            @method('PUT')
                        @endisset
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('index.parent_full_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', isset($parent) ? $parent->name : '') }}" 
                                        placeholder="{{ __('index.enter_parent_full_name') }}" required>
                                    <div class="invalid-feedback">
                                        {{ __('index.please_enter_parent_full_name') }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">{{ __('index.relationship_status') }} <span class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="">{{ __('index.select_relationship_status') }}</option>
                                        <option value="ayah" {{ old('status', isset($parent) ? $parent->status : '') == 'ayah' ? 'selected' : '' }}>{{ __('index.father') }}</option>
                                        <option value="ibu" {{ old('status', isset($parent) ? $parent->status : '') == 'ibu' ? 'selected' : '' }}>{{ __('index.mother') }}</option>
                                        <option value="wali" {{ old('status', isset($parent) ? $parent->status : '') == 'wali' ? 'selected' : '' }}>{{ __('index.guardian') }}</option>
                                    </select>
                                    <div class="invalid-feedback">
                                        {{ __('index.please_select_relationship_status') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('index.email') }} <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', isset($parent) ? $parent->user->email : '') }}" 
                                        placeholder="{{ __('index.enter_parent_email') }}" required>
                                    <div class="invalid-feedback">
                                        {{ __('index.please_enter_valid_email') }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">{{ __('index.phone_number') }}</label>
                                    <input type="tel" class="form-control" id="phone" name="phone"
                                        value="{{ old('phone', isset($parent) ? $parent->user->phone : '') }}" 
                                        placeholder="{{ __('index.optional_parent_phone') }}">
                                    <div class="form-text">{{ __('index.leave_empty_if_no_parent_phone') }}</div>
                                    <div class="invalid-feedback">
                                        {{ __('index.invalid_phone_number') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(!isset($parent))
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">{{ __('index.parent_password') }} <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                    <div class="invalid-feedback">
                                        {{ __('index.please_enter_parent_password') }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">{{ __('index.parent_password_confirmation') }} <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                    <div class="invalid-feedback">
                                        {{ __('index.parent_password_confirmation_not_match') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jenis_kelamin" class="form-label">{{ __('index.parent_gender') }}</label>
                                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin">
                                        <option value="">{{ __('index.parent_select_gender') }}</option>
                                        <option value="laki-laki" {{ old('jenis_kelamin', isset($parent) ? $parent->jenis_kelamin : '') == 'laki-laki' ? 'selected' : '' }}>{{ __('index.male') }}</option>
                                        <option value="perempuan" {{ old('jenis_kelamin', isset($parent) ? $parent->jenis_kelamin : '') == 'perempuan' ? 'selected' : '' }}>{{ __('index.female') }}</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="student_id" class="form-label">{{ __('index.parent_student_child') }}</label>
                                    <select class="form-select" id="student_id" name="student_id">
                                        <option value="">{{ __('index.select_student_optional') }}</option>
                                        @if(isset($students))
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}" {{ old('student_id', isset($parent) ? $parent->student_id : '') == $student->id ? 'selected' : '' }}>
                                                    {{ $student->name }} - {{ $student->nisn ?? 'No NISN' }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="form-text">{{ __('index.leave_empty_if_no_student_related') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="address" class="form-label">{{ __('index.parent_address') }} <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="address" name="address" rows="3"
                                        placeholder="{{ __('index.enter_parent_address') }}" required>{{ old('address', isset($parent) ? $parent->address : '') }}</textarea>
                                    <div class="invalid-feedback">
                                        {{ __('index.please_enter_parent_address') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="province" class="form-label">{{ __('index.parent_province') }}</label>
                                    <input type="text" class="form-control" id="province" name="province"
                                        value="{{ old('province', isset($parent) ? $parent->province : '') }}" 
                                        placeholder="{{ __('index.optional_parent_province') }}">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="regency" class="form-label">{{ __('index.parent_regency') }}</label>
                                    <input type="text" class="form-control" id="regency" name="regency"
                                        value="{{ old('regency', isset($parent) ? $parent->regency : '') }}" 
                                        placeholder="{{ __('index.optional_parent_regency') }}">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="district" class="form-label">{{ __('index.parent_district') }}</label>
                                    <input type="text" class="form-control" id="district" name="district"
                                        value="{{ old('district', isset($parent) ? $parent->district : '') }}" 
                                        placeholder="{{ __('index.optional_parent_district') }}">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="village" class="form-label">{{ __('index.parent_village') }}</label>
                                    <input type="text" class="form-control" id="village" name="village"
                                        value="{{ old('village', isset($parent) ? $parent->village : '') }}" 
                                        placeholder="{{ __('index.optional_parent_village') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="photo" class="form-label">{{ __('index.parent_photo') }}</label>
                                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                                    <div class="form-text">{{ __('index.parent_max_500kb_jpg_png_gif') }}</div>
                                    @if(isset($parent) && $parent->user->photo)
                                        <div class="mt-2">
                                            <small>{{ __('index.parent_current_photo') }} 
                                                <img src="{{ asset('storage/' . $parent->user->photo) }}" alt="Foto" style="max-height: 50px; max-width: 50px;" class="rounded">
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if(isset($parent))
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="user_status" name="user_status" {{ $parent->user->status ? 'checked' : '' }}>
                                        <label class="form-check-label" for="user_status">
                                            {{ __('index.parent_active_status') }}
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
                                            <i class="mdi mdi-content-save"></i> {{ isset($parent) ? __('index.update_parent') : __('index.save_parent') }}
                                        </button>
                                        <a href="{{ route('parents.index') }}" class="btn btn-secondary">
                                            <i class="mdi mdi-arrow-left"></i> {{ __('index.back') }}
                                        </a>
                                    </div>
                                    <div>
                                        @isset($parent)
                                            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                                <i class="mdi mdi-delete"></i> {{ __('index.delete_parent') }}
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
                                text: '{{ __('index.parent_password_confirmation_mismatch') }}'
                            });
                            return false;
                        }
                    }
                });
            }
        });

        // Delete confirmation (only for edit)
        @isset($parent)
        function confirmDelete() {
            Swal.fire({
                title: '{{ __('index.parent_are_you_sure') }}',
                text: "{{ __('index.parent_will_be_deleted') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ __('index.parent_yes_delete') }}',
                cancelButtonText: '{{ __('index.cancel') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create form for delete
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("parents.destroy", $parent->id) }}';
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
