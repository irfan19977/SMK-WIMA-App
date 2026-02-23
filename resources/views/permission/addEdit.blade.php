@extends('layouts.master')
@section('title')
    {{ isset($permission) ? __('index.edit_permission') : __('index.add_permission') }}
@endsection
@section('page-title')
    {{ isset($permission) ? __('index.edit_permission') : __('index.add_permission') }}
@endsection
@section('body')

    <body data-sidebar="colored">
@endsection
@section('content')
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ isset($permission) ? __('index.edit_permission') : __('index.add_permission') }}</h4>
                        <p class="card-title-desc">{{ isset($permission) ? __('index.edit_existing_permission') : __('index.add_new_permission_to_system') }}</p>
                        
                        <form class="was-validated" action="{{ isset($permission) ? route('permissions.update', $permission->id) : route('permissions.store') }}" method="POST">
                            @csrf
                            @isset($permission)
                                @method('PUT')
                            @endisset
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">{{ __('index.permission_name') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                            value="{{ old('name', isset($permission) ? $permission->name : '') }}" 
                                            placeholder="{{ __('index.enter_permission_name') }}" required autofocus>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">{{ __('index.permission_name_example') }}</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="guard_name" class="form-label">{{ __('index.guard_name') }}</label>
                                        <select class="form-select" id="guard_name" name="guard_name">
                                            <option value="web" {{ old('guard_name', isset($permission) ? $permission->guard_name : 'web') == 'web' ? 'selected' : '' }}>Web</option>
                                            <option value="api" {{ old('guard_name', isset($permission) ? $permission->guard_name : 'web') == 'api' ? 'selected' : '' }}>API</option>
                                        </select>
                                        <div class="form-text">{{ __('index.select_guard_for_permission') }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">{{ __('index.description') }}</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                            rows="3" placeholder="{{ __('index.enter_permission_description') }}">{{ old('description', isset($permission) ? $permission->description : '') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">{{ __('index.explain_permission_function') }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <button class="btn btn-primary" type="submit">
                                                <i class="mdi mdi-content-save"></i> {{ isset($permission) ? __('index.update_permission') : __('index.save_permission') }}
                                            </button>
                                            <a href="{{ route('permissions.index') }}" class="btn btn-secondary">
                                                <i class="mdi mdi-arrow-left"></i> {{ __('index.back') }}
                                            </a>
                                        </div>
                                        <div>
                                            @isset($permission)
                                                <button type="button" class="btn btn-danger" onclick="confirmDelete('{{ $permission->id }}', '{{ $permission->name }}')">
                                                    <i class="mdi mdi-delete"></i> {{ __('index.delete_permission') }}
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
        <!-- end row -->
@endsection
@section('scripts')
        <!-- Sweet Alerts js -->
        <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
        
        <script>
            // Auto-format permission name
            document.addEventListener('DOMContentLoaded', function() {
                const nameInput = document.getElementById('name');
                
                nameInput.addEventListener('input', function() {
                    // Auto-format to lowercase with dots instead of spaces
                    let value = this.value.toLowerCase()
                        .replace(/[^a-z0-9\s]/g, '')
                        .replace(/\s+/g, '.');
                    
                    // Only update if user is typing (not pasting)
                    if (this.value.toLowerCase().replace(/[^a-z0-9\s]/g, '').replace(/\s+/g, '.') === value) {
                        return;
                    }
                    
                    // Add timeout to avoid interfering with user typing
                    clearTimeout(this.formatTimeout);
                    this.formatTimeout = setTimeout(() => {
                        this.value = value;
                    }, 1000);
                });

                // Auto-format on blur
                nameInput.addEventListener('blur', function() {
                    clearTimeout(this.formatTimeout);
                    this.value = this.value.toLowerCase()
                        .replace(/[^a-z0-9\s]/g, '')
                        .replace(/\s+/g, '.');
                });
            });

            // Delete function
            function confirmDelete(id, name) {
                Swal.fire({
                    title: '{{ __('index.are_you_sure') }}',
                    text: "{{ __('index.permission_will_be_deleted_permanently') }} \"" + name + "\"!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '{{ __('index.yes_delete') }}',
                    cancelButtonText: '{{ __('index.cancel') }}'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/permissions/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '{{ __('index.success') }}!',
                                    text: '{{ __('index.permission_deleted_successfully') }}',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                window.location.href = '{{ route('permissions.index') }}';
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: '{{ __('index.error') }}!',
                                    text: data.message || '{{ __('index.error_deleting_permission') }}'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: '{{ __('index.error') }}!',
                                text: '{{ __('index.error_deleting_permission') }}'
                            });
                        });
                    }
                });
            }
        </script>
@endsection
