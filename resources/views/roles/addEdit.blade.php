@extends('layouts.master')
@section('title')
    {{ isset($role) ? __('index.edit_role') : __('index.add_role') }}
@endsection
@section('page-title')
    {{ isset($role) ? __('index.edit_role') : __('index.add_role') }}
@endsection
@section('body')

    <body data-sidebar="colored">
@endsection
@section('content')
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ isset($role) ? __('index.edit_role') : __('index.add_role') }}</h4>
                        <p class="card-title-desc">{{ isset($role) ? __('index.edit_role_description') : __('index.add_role_description') }}</p>
                        
                        <form class="was-validated" action="{{ isset($role) ? route('roles.update', $role->id) : route('roles.store') }}" method="POST">
                            @csrf
                            @isset($role)
                                @method('PUT')
                            @endisset
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">{{ __('index.role_name') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                            value="{{ old('name', isset($role) ? $role->name : '') }}" 
                                            placeholder="{{ __('index.enter_role_name') }}" required autofocus>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">{{ __('index.role_name_example') }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center gap-3 mb-2">
                                            <label class="form-label mb-0">{{ __('index.permissions') }}</label>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllPermissions()">
                                                    <i class="mdi mdi-checkbox-multiple-marked"></i> {{ __('index.select_all') }}
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearAllPermissions()">
                                                    <i class="mdi mdi-checkbox-multiple-blank-outline"></i> {{ __('index.clear_all') }}
                                                </button>
                                            </div>
                                        </div>
                                        <div class="border rounded p-3" style="max-height: 400px; overflow-y: auto;">
                                            @if(isset($permissions) && $permissions->count() > 0)
                                                <div class="row">
                                                    @foreach($permissions as $permission)
                                                        <div class="col-md-6 col-lg-4 mb-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="permissions[]" 
                                                                    value="{{ $permission->name }}" 
                                                                    id="check-{{ $permission->id }}"
                                                                    @if(isset($role) && $role->permissions->contains($permission)) checked @endif>
                                                                <label class="form-check-label" for="check-{{ $permission->id }}">
                                                                    {{ $permission->name }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-muted">{{ __('index.no_permissions_available') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <button class="btn btn-primary" type="submit">
                                                <i class="mdi mdi-content-save"></i> {{ isset($role) ? __('index.update_role') : __('index.save_role') }}
                                            </button>
                                            <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                                                <i class="mdi mdi-arrow-left"></i> {{ __('index.back') }}
                                            </a>
                                        </div>
                                        <div>
                                            @isset($role)
                                                <button type="button" class="btn btn-danger" onclick="confirmDelete('{{ $role->id }}', '{{ $role->name }}')">
                                                    <i class="mdi mdi-delete"></i> {{ __('index.delete_role') }}
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
        <script src="{{ URL::asset('build/js/app.js') }}"></script>
        <script>
            // Select all functionality
            function selectAllPermissions() {
                console.log('Select all clicked');
                document.querySelectorAll('input[type="checkbox"][name="permissions[]"]').forEach(checkbox => {
                    checkbox.checked = true;
                });
            }
            
            // Clear all functionality
            function clearAllPermissions() {
                console.log('Clear all clicked');
                document.querySelectorAll('input[type="checkbox"][name="permissions[]"]').forEach(checkbox => {
                    checkbox.checked = false;
                });
            }

            // Delete function
            function confirmDelete(id, name) {
                Swal.fire({
                    title: '{{ __("index.are_you_sure") }}',
                    text: `{{ __("index.role_will_be_deleted") }} "${name}"!`,
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
                        form.action = `{{ route('roles.destroy', ':id') }}`.replace(':id', id);
                        
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
        </script>
@endsection
