@extends('layouts.master')

@section('title')
    Manajemen Kelas
@endsection

@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('page-title')
    Manajemen Kelas
@endsection

@section('body')
    <body data-sidebar="colored">
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-3">
            <div class="card filemanager-sidebar">
                <div class="card-body">
                    <div class="d-flex flex-column h-100">
                        <div>
                            <div class="mb-3">
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle w-100" type="button"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="mdi mdi-plus me-1"></i> Create New
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="#" id="btn-create"><i class="mdi mdi-folder me-1"></i>
                                            Kelas</a>
                                        <a class="dropdown-item" href="#" id="btn-open-next-semester-bulk"><i class="mdi mdi-swap-horizontal me-1"></i>
                                            Tutup & Buka Semester</a>
                                        <a class="dropdown-item" href="#" id="btn-promote-bulk"><i class="mdi mdi-arrow-up-bold me-1"></i>
                                            Naikkan Kelas</a>
                                    </div>
                                </div>
                            </div>
                            <ul class="list-unstyled categories-list">
                                <li>
                                    <a href="javascript: void(0);" id="toggle-active" class="text-body fw-medium py-1 d-flex align-items-center active">
                                        <i class="mdi mdi-school font-size-20 text-success me-2"></i> <span
                                            class="me-auto">Active Classes</span>
                                        <span class="badge bg-success rounded-pill">{{ $classes->filter(function($class) { return !($class->is_archived ?? false); })->count() }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript: void(0);" id="toggle-archived" class="text-body d-flex align-items-center">
                                        <i class="mdi mdi-archive font-size-20 me-2 text-warning"></i> <span
                                            class="me-auto">Archived Classes</span>
                                        <span class="badge bg-warning rounded-pill">{{ $classes->filter(function($class) { return $class->is_archived ?? false; })->count() }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <h5 class="font-size-16 mb-0">Class Statistics</h5>

                        <div class="mt-2">
                            <div class="px-2 py-3 border-bottom">
                                <a href="javascript: void(0);" class="text-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm align-self-center me-3">
                                            <div class="avatar-title rounded bg-primary font-size-24">
                                                <i class="mdi mdi-school"></i>
                                            </div>
                                        </div>
                                        <div class="overflow-hidden me-auto">
                                            <h5 class="font-size-15 text-truncate mb-1">Grade 10</h5>
                                            <p class="text-muted text-truncate mb-0">{{ $classes->filter(function($class) { return ($class->grade ?? '') == '10'; })->count() }} Classes</p>
                                        </div>
                                        <div class="ms-2">
                                            <p class="text-muted font-size-14">{{ $classes->filter(function($class) { return ($class->grade ?? '') == '10'; })->sum('students_count') }} Students</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="px-2 py-3 border-bottom">
                                <a href="javascript: void(0);" class="text-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm align-self-center me-3">
                                            <div class="avatar-title rounded bg-success font-size-24">
                                                <i class="mdi mdi-school"></i>
                                            </div>
                                        </div>
                                        <div class="overflow-hidden me-auto">
                                            <h5 class="font-size-15 text-truncate mb-1">Grade 11</h5>
                                            <p class="text-muted text-truncate mb-0">{{ $classes->filter(function($class) { return ($class->grade ?? '') == '11'; })->count() }} Classes</p>
                                        </div>
                                        <div class="ms-2">
                                            <p class="text-muted font-size-14">{{ $classes->filter(function($class) { return ($class->grade ?? '') == '11'; })->sum('students_count') }} Students</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="px-2 py-3 border-bottom">
                                <a href="javascript: void(0);" class="text-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm align-self-center me-3">
                                            <div class="avatar-title rounded bg-warning font-size-24">
                                                <i class="mdi mdi-school"></i>
                                            </div>
                                        </div>
                                        <div class="overflow-hidden me-auto">
                                            <h5 class="font-size-15 text-truncate mb-1">Grade 12</h5>
                                            <p class="text-muted text-truncate mb-0">{{ $classes->filter(function($class) { return ($class->grade ?? '') == '12'; })->count() }} Classes</p>
                                        </div>
                                        <div class="ms-2">
                                            <p class="text-muted font-size-14">{{ $classes->filter(function($class) { return ($class->grade ?? '') == '12'; })->sum('students_count') }} Students</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div class="px-2 py-3 border-bottom">
                                <a href="javascript: void(0);" class="text-body">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm align-self-center me-3">
                                            <div class="avatar-title rounded bg-info font-size-24">
                                                <i class="mdi mdi-archive"></i>
                                            </div>
                                        </div>
                                        <div class="overflow-hidden me-auto">
                                            <h5 class="font-size-15 text-truncate mb-1">Archived</h5>
                                            <p class="text-muted text-truncate mb-0">{{ $classes->filter(function($class) { return $class->is_archived ?? false; })->count() }} Classes</p>
                                        </div>
                                        <div class="ms-2">
                                            <p class="text-muted font-size-14">{{ $classes->filter(function($class) { return $class->is_archived ?? false; })->sum('students_count') }} Students</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <h5 class="font-size-16 mt-4 mb-0">Quick Actions</h5>

                        <div class="border text-center rounded p-3 mt-4">
                            <div class="">
                                <i class="mdi mdi-school display-4 text-primary mb-3"></i>
                            </div>
                            <h5>Class Management</h5>
                            <p class="pt-1">Manage classes, students, and academic operations</p>
                            <div class="text-center pt-2">
                                <button type="button" class="btn btn-primary w-100" id="btn-create-quick">Create Class <i
                                        class="mdi mdi-plus ms-1"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-9">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="font-size-16 mb-0">My Classes</h5>
                        <div class="d-flex gap-2">
                            <div class="input-group" style="width: 300px;">
                                <input type="text" class="form-control" placeholder="Search classes..." id="search-input">
                                <button class="btn btn-primary" type="button" id="search-button">
                                    <i class="mdi mdi-magnify"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4" id="classes-container">
                        @forelse ($classes as $class)
                        <div class="col-xl-4 col-sm-6 class-card-item mb-4">
                            <div class="border p-3 rounded mb-3">
                                <div class="">
                                    <div class="dropdown float-end">
                                        <a class="dropdown-toggle font-size-16" href="#" role="button"
                                            data-bs-toggle="dropdown" aria-haspopup="true">
                                            <i class="mdi mdi-dots-vertical font-size-18"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item edit-class" href="#"
                                               data-id="{{ $class->id }}"
                                               data-name="{{ $class->name }}"
                                               data-code="{{ $class->code }}"
                                               data-grade="{{ $class->grade }}"
                                               data-major="{{ $class->major }}">Edit</a>
                                            <a class="dropdown-item" href="#" onclick="toggleArchive('{{ $class->id }}'); return false;">
                                                {{ $class->is_archived ? 'Unarchive' : 'Archive' }}
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item text-danger" href="#" onclick="confirmDelete('{{ $class->id }}'); return false;">
                                                Remove
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center overflow-hidden">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-sm align-self-center">
                                                <div class="avatar-title rounded bg-primary font-size-24">
                                                    <i class="mdi mdi-school"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="font-size-15 mb-1 text-truncate">{{ $class->name }}</h5>
                                            <a href="{{ route('classes.show', $class->id) }}" class="font-size-14 text-muted text-truncate"><u>View
                                                    Class</u></a>
                                        </div>
                                    </div>
                                    <div class="mt-3 pt-2">
                                        <div class="d-flex justify-content-between">
                                            <p class="text-muted font-size-13 mb-1">{{ $class->code }}</p>
                                            <p class="text-muted font-size-13 mb-1 text-truncate">Grade {{ $class->grade }}</p>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <p class="text-muted font-size-13 mb-1">{{ $class->major }}</p>
                                            <p class="text-muted font-size-13 mb-1 text-truncate">{{ $class->students_count }} Students</p>
                                        </div>
                                        <div class="mt-2">
                                            @if($class->is_archived)
                                                <span class="badge bg-warning">Archived</span>
                                            @else
                                                <span class="badge bg-success">Active</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="mdi mdi-school display-1 text-muted"></i>
                                </div>
                                <h5 class="text-muted">
                                    @if(request('show_archived'))
                                        No archived classes found
                                    @else
                                        No classes found
                                    @endif
                                </h5>
                                <p class="text-muted">
                                    @if(request('show_archived'))
                                        Archived classes will appear here.
                                    @else
                                        Click "Create New" to add your first class.
                                    @endif
                                </p>
                                @if(!request('show_archived'))
                                    <button type="button" class="btn btn-primary" id="btn-create-empty">
                                        <i class="mdi mdi-plus"></i> Create Class
                                    </button>
                                @endif
                            </div>
                        </div>
                        @endforelse
                    </div>

                    <div class="d-flex flex-wrap mt-4">
                        <h5 class="font-size-16 me-3">Recent Classes</h5>
                        <div class="ms-auto">
                            <a href="javascript: void(0);" class="fw-medium text-reset">View All</a>
                        </div>
                    </div>

                    <div class="table-responsive mt-3">
                        <table class="table align-middle table-nowrap table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Code</th>
                                    <th scope="col">Grade</th>
                                    <th scope="col">Major</th>
                                    <th scope="col">Students</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($classes->take(5) as $class)
                                <tr>
                                    <td>
                                        <a href="{{ route('classes.show', $class->id) }}" class="text-dark fw-medium">
                                            <i class="mdi mdi-school font-size-16 align-middle text-primary me-2"></i>
                                            {{ $class->name }}
                                        </a>
                                    </td>
                                    <td>{{ $class->code }}</td>
                                    <td>{{ $class->grade }}</td>
                                    <td>{{ $class->major }}</td>
                                    <td>{{ $class->students_count }}</td>
                                    <td>
                                        @if($class->is_archived)
                                            <span class="badge bg-warning">Archived</span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <a class="font-size-16 text-muted" role="button"
                                                data-bs-toggle="dropdown" aria-haspopup="true">
                                                <i class="mdi mdi-dots-horizontal"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item" href="{{ route('classes.show', $class->id) }}">View</a>
                                                <a class="dropdown-item edit-class" href="#"
                                                   data-id="{{ $class->id }}"
                                                   data-name="{{ $class->name }}"
                                                   data-code="{{ $class->code }}"
                                                   data-grade="{{ $class->grade }}"
                                                   data-major="{{ $class->major }}">Edit</a>
                                                <a class="dropdown-item" href="#" onclick="toggleArchive('{{ $class->id }}'); return false;">
                                                    {{ $class->is_archived ? 'Unarchive' : 'Archive' }}
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item text-danger" href="#" onclick="confirmDelete('{{ $class->id }}'); return false;">
                                                    Remove
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No recent classes</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create/Edit Modal -->
    <div class="modal fade" id="classModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Create Class</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="classForm" action="/classes" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Class Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="code" class="form-label">Class Code</label>
                            <input type="text" class="form-control" id="code" name="code" required>
                        </div>
                        <div class="mb-3">
                            <label for="grade" class="form-label">Grade</label>
                            <select class="form-control" id="grade" name="grade" required>
                                <option value="">Select Grade</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="major" class="form-label">Major</label>
                            <input type="text" class="form-control" id="major" name="major" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Sweet Alerts js -->
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize variables
        let currentClassId = null;
        let showArchived = {{ request('show_archived') ? 'true' : 'false' }};

        // Toggle archived classes
        const toggleArchived = document.getElementById('toggle-archived');
        if (toggleArchived) {
            toggleArchived.addEventListener('click', function() {
                showArchived = true;
                window.location.href = '?show_archived=1';
            });
        }

        // Toggle active classes
        const toggleActive = document.getElementById('toggle-active');
        if (toggleActive) {
            toggleActive.addEventListener('click', function() {
                showArchived = false;
                window.location.href = '?show_archived=0';
            });
        }

        // Create class
        const btnCreate = document.getElementById('btn-create');
        if (btnCreate) {
            btnCreate.addEventListener('click', function() {
                currentClassId = null;
                document.getElementById('modalTitle').textContent = 'Create Class';
                document.getElementById('classForm').action = '/classes';
                
                // Remove method override if exists
                let methodInput = document.querySelector('input[name="_method"]');
                if (methodInput) {
                    methodInput.remove();
                }
                
                document.getElementById('classForm').reset();
                new bootstrap.Modal(document.getElementById('classModal')).show();
            });
        }

        const btnCreateQuick = document.getElementById('btn-create-quick');
        if (btnCreateQuick) {
            btnCreateQuick.addEventListener('click', function() {
                if (btnCreate) btnCreate.click();
            });
        }

        const btnCreateEmpty = document.getElementById('btn-create-empty');
        if (btnCreateEmpty) {
            btnCreateEmpty.addEventListener('click', function() {
                if (btnCreate) btnCreate.click();
            });
        }

        // Debug: Check if edit buttons exist
        const editButtons = document.querySelectorAll('.edit-class');
        console.log('Edit buttons found:', editButtons.length); // Debug log
        
        // Edit class
        editButtons.forEach(function(btn) {
            console.log('Adding event listener to edit button:', btn.getAttribute('data-id')); // Debug log
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Edit button clicked'); // Debug log
                
                currentClassId = this.getAttribute('data-id');
                console.log('Class ID:', currentClassId); // Debug log
                
                document.getElementById('modalTitle').textContent = 'Edit Class';
                document.getElementById('classForm').action = '/classes/' + currentClassId;
                
                // Add method override for PUT
                let methodInput = document.querySelector('input[name="_method"]');
                if (!methodInput) {
                    methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'PUT';
                    document.getElementById('classForm').appendChild(methodInput);
                } else {
                    methodInput.value = 'PUT';
                }
                
                // Fill form
                document.getElementById('name').value = this.getAttribute('data-name');
                document.getElementById('code').value = this.getAttribute('data-code');
                document.getElementById('grade').value = this.getAttribute('data-grade');
                document.getElementById('major').value = this.getAttribute('data-major');
                
                console.log('Form filled, showing modal'); // Debug log
                
                // Check if bootstrap is available
                if (typeof bootstrap !== 'undefined') {
                    const modal = new bootstrap.Modal(document.getElementById('classModal'));
                    modal.show();
                    console.log('Modal shown'); // Debug log
                } else {
                    console.error('Bootstrap is not loaded'); // Debug log
                    alert('Error: Bootstrap tidak terload. Silakan refresh halaman.');
                }
            });
        });

        // Search functionality
        let searchTimeout;
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const query = this.value.toLowerCase();
                    document.querySelectorAll('.class-card-item').forEach(function(card) {
                        const text = card.textContent.toLowerCase();
                        card.style.display = text.includes(query) ? '' : 'none';
                    });
                }, 300);
            });
        }

        // Delete confirmation
        window.confirmDelete = function(classId) {
            if (confirm('Are you sure you want to delete this class?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/classes/' + classId;
                form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">';
                document.body.appendChild(form);
                form.submit();
            }
        };

        // Toggle archive
        window.toggleArchive = function(classId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/classes/' + classId + '/toggle-archive';
            form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
            document.body.appendChild(form);
            form.submit();
        };

        // Bulk operations
        const btnOpenNextSemesterBulk = document.getElementById('btn-open-next-semester-bulk');
        if (btnOpenNextSemesterBulk) {
            btnOpenNextSemesterBulk.addEventListener('click', function() {
                if (confirm('Are you sure you want to close current semester and open next semester for all classes?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/classes/open-next-semester-bulk';
                    form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        const btnPromoteBulk = document.getElementById('btn-promote-bulk');
        if (btnPromoteBulk) {
            btnPromoteBulk.addEventListener('click', function() {
                if (confirm('Are you sure you want to promote all students to next grade?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '/classes/promote-bulk';
                    form.innerHTML = '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    });
    </script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
