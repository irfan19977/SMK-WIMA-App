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
                                        <div class="d-flex align-items-center gap-3 mb-3">
                                            <label class="form-label mb-0">{{ __('index.permissions') }}</label>
                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary d-flex align-items-center" onclick="selectAllPermissions()">
                                                    <i class="mdi mdi-checkbox-multiple-marked me-1"></i> 
                                                    <span>{{ __('index.select_all') }}</span>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger d-flex align-items-center" onclick="clearAllPermissions()">
                                                    <i class="mdi mdi-checkbox-multiple-blank-outline me-1"></i> 
                                                    <span>{{ __('index.clear_all') }}</span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="border rounded-3 p-4" style="max-height: 500px; overflow-y: auto; background: #f8f9fa;">
                                            @if(isset($permissions) && $permissions->count() > 0)
                                                @php
                                                    // Group permissions by module
                                                    $groupedPermissions = [];
                                                    foreach($permissions as $permission) {
                                                        $parts = explode('.', $permission->name);
                                                        $module = $parts[0];
                                                        if(!isset($groupedPermissions[$module])) {
                                                            $groupedPermissions[$module] = [];
                                                        }
                                                        $groupedPermissions[$module][] = $permission;
                                                    }
                                                    ksort($groupedPermissions);
                                                    
                                                    // Module icons mapping
                                                    $moduleIcons = [
                                                        'announcements' => 'mdi-bullhorn',
                                                        'attendances' => 'mdi-clock-outline',
                                                        'classes' => 'mdi-school',
                                                        'counseling' => 'mdi-head-heart',
                                                        'ekstrakurikuler' => 'mdi-soccer',
                                                        'exams' => 'mdi-clipboard-list',
                                                        'face_recognition' => 'mdi-camera',
                                                        'lab' => 'mdi-flask',
                                                        'lesson_attendances' => 'mdi-calendar-check',
                                                        'library' => 'mdi-book-open-variant',
                                                        'news' => 'mdi-newspaper',
                                                        'parents' => 'mdi-account-group',
                                                        'pendaftaran-siswa' => 'mdi-account-plus',
                                                        'permissions' => 'mdi-key',
                                                        'questions' => 'mdi-help-circle',
                                                        'reports' => 'mdi-chart-line',
                                                        'roles' => 'mdi-shield-account',
                                                        'schedules' => 'mdi-calendar',
                                                        'security' => 'mdi-security',
                                                        'setting-schedule' => 'mdi-clock-edit',
                                                        'settings' => 'mdi-cog',
                                                        'staff' => 'mdi-account-tie',
                                                        'student-grades' => 'mdi-star',
                                                        'students' => 'mdi-account-multiple',
                                                        'subjects' => 'mdi-book',
                                                        'teachers' => 'mdi-account-tie',
                                                        'users' => 'mdi-account'
                                                    ];
                                                @endphp
                                                
                                                @foreach($groupedPermissions as $module => $modulePermissions)
                                                    <div class="mb-4">
                                                        <div class="card border-0 shadow-sm">
                                                            <div class="card-header bg-white py-3">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="form-check form-switch me-3">
                                                                        <input class="form-check-input" type="checkbox" 
                                                                               id="module-{{ $module }}" 
                                                                               onchange="toggleModulePermissions('{{ $module }}')">
                                                                    </div>
                                                                    <label class="form-check-label fw-bold text-dark mb-0 d-flex align-items-center" 
                                                                           for="module-{{ $module }}">
                                                                        <i class="mdi {{ $moduleIcons[$module] ?? 'mdi-folder' }} me-2 text-primary"></i> 
                                                                        {{ ucfirst(str_replace('-', ' ', $module)) }}
                                                                        <span class="badge bg-primary rounded-pill ms-2">{{ count($modulePermissions) }}</span>
                                                                    </label>
                                                                    <button class="btn btn-sm btn-link text-muted ms-auto p-0" 
                                                                            type="button" 
                                                                            data-bs-toggle="collapse" 
                                                                            data-bs-target="#collapse-{{ $module }}"
                                                                            aria-expanded="true">
                                                                        <i class="mdi mdi-chevron-down fs-5"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="collapse show" id="collapse-{{ $module }}">
                                                                <div class="card-body">
                                                                    <div class="row g-2">
                                                                        @foreach($modulePermissions as $permission)
                                                                            <div class="col-lg-6">
                                                                                <div class="d-flex align-items-center p-2 rounded hover-bg-light">
                                                                                    <div class="form-check me-3">
                                                                                        <input class="form-check-input permission-checkbox-{{ $module }}" 
                                                                                               type="checkbox" 
                                                                                               name="permissions[]" 
                                                                                               value="{{ $permission->name }}" 
                                                                                               id="check-{{ $permission->id }}"
                                                                                               onchange="updateModuleCheckbox('{{ $module }}')"
                                                                                               @if(isset($role) && $role->permissions->contains($permission)) checked @endif>
                                                                                    </div>
                                                                                    <label class="form-check-label small mb-0 d-flex align-items-center" for="check-{{ $permission->id }}">
                                                                                        <span class="badge bg-light text-dark me-2 font-monospace">{{ $permission->name }}</span>
                                                                                        <span class="text-muted">
                                                                                            @php
                                                                                                $action = str_replace($module . '.', '', $permission->name);
                                                                                                $actionLabels = [
                                                                                                    'create' => 'Buat',
                                                                                                    'delete' => 'Hapus', 
                                                                                                    'edit' => 'Edit',
                                                                                                    'index' => 'Daftar',
                                                                                                    'view' => 'Lihat',
                                                                                                    'show' => 'Detail'
                                                                                                ];
                                                                                            @endphp
                                                                                            {{ $actionLabels[$action] ?? ucfirst($action) }}
                                                                                        </span>
                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="text-center py-5">
                                                    <i class="mdi mdi-shield-off-outline fs-1 text-muted"></i>
                                                    <p class="text-muted mt-3">{{ __('index.no_permissions_available') }}</p>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <style>
                                            .hover-bg-light:hover {
                                                background-color: #f8f9fa !important;
                                                transition: background-color 0.2s ease;
                                            }
                                            .form-check-input:checked {
                                                background-color: #0d6efd;
                                                border-color: #0d6efd;
                                            }
                                            .card {
                                                transition: box-shadow 0.2s ease;
                                            }
                                            .card:hover {
                                                box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
                                            }
                                            .badge.bg-light {
                                                font-size: 0.7rem;
                                            }
                                        </style>
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
            // Toggle all permissions in a module
            function toggleModulePermissions(module) {
                const moduleCheckbox = document.getElementById(`module-${module}`);
                const permissionCheckboxes = document.querySelectorAll(`.permission-checkbox-${module}`);
                
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.checked = moduleCheckbox.checked;
                });
            }
            
            // Update module checkbox based on individual permissions
            function updateModuleCheckbox(module) {
                const moduleCheckbox = document.getElementById(`module-${module}`);
                const permissionCheckboxes = document.querySelectorAll(`.permission-checkbox-${module}`);
                const checkedCheckboxes = document.querySelectorAll(`.permission-checkbox-${module}:checked`);
                
                // Update module checkbox: checked if all permissions are checked, unchecked if any is unchecked
                moduleCheckbox.checked = permissionCheckboxes.length === checkedCheckboxes.length;
                moduleCheckbox.indeterminate = checkedCheckboxes.length > 0 && checkedCheckboxes.length < permissionCheckboxes.length;
            }
            
            // Select all functionality
            function selectAllPermissions() {
                console.log('Select all clicked');
                document.querySelectorAll('input[type="checkbox"][name="permissions[]"]').forEach(checkbox => {
                    checkbox.checked = true;
                });
                
                // Update all module checkboxes
                document.querySelectorAll('[id^="module-"]').forEach(moduleCheckbox => {
                    const module = moduleCheckbox.id.replace('module-', '');
                    updateModuleCheckbox(module);
                });
            }
            
            // Clear all functionality
            function clearAllPermissions() {
                console.log('Clear all clicked');
                document.querySelectorAll('input[type="checkbox"][name="permissions[]"]').forEach(checkbox => {
                    checkbox.checked = false;
                });
                
                // Update all module checkboxes
                document.querySelectorAll('[id^="module-"]').forEach(moduleCheckbox => {
                    moduleCheckbox.checked = false;
                    moduleCheckbox.indeterminate = false;
                });
            }
            
            // Initialize module checkboxes on page load
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('[id^="module-"]').forEach(moduleCheckbox => {
                    const module = moduleCheckbox.id.replace('module-', '');
                    updateModuleCheckbox(module);
                });
            });

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
