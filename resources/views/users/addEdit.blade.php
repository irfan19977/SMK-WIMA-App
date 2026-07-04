@extends('layouts.master')
@section('title')
    {{ isset($user) ? __('index.edit_user') : __('index.add_user') }}
@endsection
@section('page-title')
    {{ isset($user) ? __('index.edit_user') : __('index.add_user') }}
@endsection
@section('body')
    <body data-sidebar="colored">
@endsection
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">{{ isset($user) ? __('index.edit_user') : __('index.add_user') }}</h4>
                    <p class="card-title-desc">{{ isset($user) ? __('index.edit_user_description') : __('index.add_user_description') }}</p>
                    
                    <form class="was-validated" action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}" method="POST">
                        @csrf
                        @isset($user)
                            @method('PUT')
                        @endisset
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('index.name') }} <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                        value="{{ old('name', isset($user) ? $user->name : '') }}" 
                                        placeholder="{{ __('index.enter_name') }}" required autofocus>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('index.email') }} <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                        value="{{ old('email', isset($user) ? $user->email : '') }}" 
                                        placeholder="{{ __('index.enter_email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">{{ __('index.phone') }}</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                                        value="{{ old('phone', isset($user) ? $user->phone : '') }}" 
                                        placeholder="{{ __('index.enter_phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="role" class="form-label">{{ __('index.role') }} <span class="text-danger">*</span></label>
                                    <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                        <option value="">{{ __('index.select_role') }}</option>
                                        @foreach($roles as $roleId => $roleName)
                                            <option value="{{ $roleName }}" 
                                                @if(isset($user) && $user->role_name == $roleName) selected @endif>
                                                {{ $roleName }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        {{ isset($user) ? __('index.new_password') : __('index.password') }} 
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" 
                                           placeholder="{{ __('index.enter_password') }}" 
                                           @if(!isset($user)) required @endif>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if(isset($user))
                                        <div class="form-text">{{ __('index.leave_empty_if_no_change') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">
                                        {{ __('index.confirm_password') }}
                                        @if(!isset($user)) <span class="text-danger">*</span> @endif
                                    </label>
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                           id="password_confirmation" name="password_confirmation" 
                                           placeholder="{{ __('index.confirm_password') }}" 
                                           @if(!isset($user)) required @endif>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <button class="btn btn-primary" type="submit">
                                            <i class="mdi mdi-content-save"></i> {{ isset($user) ? __('index.update') : __('index.save') }}
                                        </button>
                                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                            <i class="mdi mdi-arrow-left"></i> {{ __('index.back') }}
                                        </a>
                                    </div>
                                    <div>
                                        @isset($user)
                                            @if(!$user->role_name || $user->role_name != 'Super Admin')
                                                <button type="button" class="btn btn-danger" onclick="confirmDelete('{{ $user->id }}', '{{ $user->name }}')">
                                                    <i class="mdi mdi-delete"></i> {{ __('index.delete_user') }}
                                                </button>
                                            @endif
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
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
        // Delete function
        function confirmDelete(id, name) {
            Swal.fire({
                title: '{{ __("index.are_you_sure") }}',
                text: `{{ __("index.user_will_be_deleted") }} "${name}"!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{ __("index.yes_delete") }}',
                cancelButtonText: '{{ __("index.cancel") }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit delete form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ route('users.destroy', ':id') }}`.replace(':id', id);
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (csrfToken) {
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken;
                        form.appendChild(csrfInput);
                    }
                    
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

        // Password validation
        document.getElementById('password')?.addEventListener('input', function() {
            const password = this.value;
            const confirm = document.getElementById('password_confirmation').value;
            
            if (password && confirm && password !== confirm) {
                document.getElementById('password_confirmation').setCustomValidity('{{ __("index.passwords_do_not_match") }}');
            } else {
                document.getElementById('password_confirmation').setCustomValidity('');
            }
        });

        document.getElementById('password_confirmation')?.addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirm = this.value;
            
            if (password && confirm && password !== confirm) {
                this.setCustomValidity('{{ __("index.passwords_do_not_match") }}');
            } else {
                this.setCustomValidity('');
            }
        });
    </script>
@endsection
