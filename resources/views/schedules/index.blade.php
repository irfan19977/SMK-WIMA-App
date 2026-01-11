@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Jadwal Pelajaran</h1>
        <div class="section-header-breadcrumb">
            <div class="breadcrumb-item active"><a href="{{ route('dashboard') }}">Dashboard</a></div>
            <div class="breadcrumb-item">Jadwal Pelajaran</div>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Filter Kelas</h4>
                        <div class="d-flex">
                            <div class="mr-2">
                                <label class="mb-1 small">Semester</label>
                                <select id="semesterFilter" class="form-control form-control-sm">
                                    <option value="Ganjil" {{ (isset($selectedSemester) && $selectedSemester === 'Ganjil') ? 'selected' : '' }}>Ganjil</option>
                                    <option value="Genap" {{ (isset($selectedSemester) && $selectedSemester === 'Genap') ? 'selected' : '' }}>Genap</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1 small">Tahun Akademik</label>
                                <select id="academicYearFilterSchedules" class="form-control form-control-sm">
                                    @foreach(App\Helpers\AcademicYearHelper::generateAcademicYears(2, 2) as $year)
                                        <option value="{{ $year }}" {{ (isset($selectedAcademicYear) && $selectedAcademicYear == $year) ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="btn-group" role="group">
                            @if($classes->count() > 0)
                                @foreach($classes as $index => $class)
                                    <button type="button" class="btn {{ $index === 0 || $class->id == $selectedClass?->id ? 'btn-primary' : 'btn-outline-primary' }} class-filter-btn" 
                                        data-class-id="{{ $class->id }}">
                                        <i class="fas fa-users mr-1"></i>{{ $class->name }}
                                    </button>
                                @endforeach
                            @else
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-info-circle mr-2"></i>Tidak ada kelas tersedia
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($classes->count() > 0)
            @foreach($classes as $index => $class)
            <div class="row class-schedule-section {{ $index === 0 || $class->id == $selectedClass?->id ? '' : 'd-none' }}" 
                id="schedule-section-{{ $class->id }}">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Pelajaran - {{ $class->name }}</h4>
                            <div class="card-header-action">
                                <div class="input-group">
                                    <button type="button" class="btn btn-primary btn-add-schedule" 
                                        data-class-id="{{ $class->id }}" data-toggle="tooltip"
                                        style="margin-right: 10px;" title="Tambah Jadwal">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    <input type="text" class="form-control search-schedule" 
                                        placeholder="Cari Jadwal (Mata Pelajaran, Guru)" 
                                        id="searchSchedule-{{ $class->id }}" 
                                        data-class-id="{{ $class->id }}"
                                        autocomplete="off">
                                    <div class="input-group-btn">
                                        <button type="button" class="btn btn-primary" id="search-button" style="margin-top: 1px;">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped" id="schedulesTable-{{ $class->id }}">
                                    <thead>
                                        <tr class="text-center">
                                            <th>No</th>
                                            <th>Hari</th>
                                            <th>Mata Pelajaran</th>
                                            <th>Guru</th>
                                            <th>Jam Mulai</th>
                                            <th>Jam Selesai</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="scheduleTableBody-{{ $class->id }}">
                                        @if($index === 0 && $selectedClass && $schedules->count() > 0)
                                            @foreach($schedules as $scheduleIndex => $schedule)
                                            <tr>
                                                <td class="text-center">{{ $scheduleIndex + 1 }}</td>
                                                <td><span class="badge badge-primary">{{ ucfirst($schedule->day) }}</span></td>
                                                <td><a href="#" class="text-secondery font-weight-bold">{{ $schedule->subject->name ?? '-' }}</a></td>
                                                <td>
                                                    {{ $schedule->teacher->name ?? '-' }}<br>
                                                    <small class="text-muted">{{ $schedule->teacher->nip ?? '-' }}</small>
                                                </td>
                                                <td class="text-center"><span class="badge badge-success">{{ $schedule->start_time }}</span></td>
                                                <td class="text-center"><span class="badge badge-danger">{{ $schedule->end_time }}</span></td>
                                                <td>
                                                    <button type="button" class="btn btn-primary btn-action mr-1 btn-edit-schedule" 
                                                        data-id="{{ $schedule->id }}" data-toggle="tooltip" title="Edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>
                                                    <form id="delete-form-{{ $schedule->id }}" action="{{ route('schedules.destroy', $schedule->id) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-action" data-toggle="tooltip"
                                                            title="Delete" onclick="confirmDelete('{{ $schedule->id }}')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @else
                                            <tr id="empty-row-{{ $class->id }}">
                                                <td colspan="7" class="text-center">Tidak ada data jadwal</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($index === 0 && $selectedClass && $schedules->hasPages())
                        <div class="card-footer text-right" id="pagination-{{ $class->id }}">
                            <nav class="d-inline-block">
                                <ul class="pagination mb-0">
                                    {{-- Previous Page Link --}}
                                    @if ($schedules->onFirstPage())
                                        <li class="page-item disabled" aria-disabled="true">
                                            <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $schedules->previousPageUrl() }}" rel="prev"><i class="fas fa-chevron-left"></i></a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($schedules->getUrlRange(1, $schedules->lastPage()) as $page => $url)
                                        @if ($page == $schedules->currentPage())
                                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                                        @else
                                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($schedules->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $schedules->nextPageUrl() }}" rel="next"><i class="fas fa-chevron-right"></i></a>
                                        </li>
                                    @else
                                        <li class="page-item disabled" aria-disabled="true">
                                            <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                        @else
                        <div class="card-footer text-right" id="pagination-{{ $class->id }}"></div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        @else
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-school fa-5x text-muted mb-3"></i>
                            <h5>Belum ada kelas</h5>
                            <p class="text-muted">Silahkan buat kelas terlebih dahulu sebelum membuat jadwal</p>
                            <a href="{{ route('classes.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus mr-2"></i>Buat Kelas Baru
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

    <div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleModalLabel">Tambah Jadwal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="scheduleForm">
                    @csrf
                    <input type="hidden" id="schedule_id" name="schedule_id">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="class_id">Kelas <span class="text-danger">*</span></label>
                                    <select class="form-control" id="class_id" name="class_id" required>
                                        <option value="">Pilih Kelas</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback d-none" id="class_id-error"></div>
                                    <small class="text-muted">*Kelas Terisi Otomatis</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="day">Hari <span class="text-danger">*</span></label>
                                    <select class="form-control" id="day" name="day" required>
                                        <option value="">Pilih Hari</option>
                                        <option value="senin">Senin</option>
                                        <option value="selasa">Selasa</option>
                                        <option value="rabu">Rabu</option>
                                        <option value="kamis">Kamis</option>
                                        <option value="jumat">Jumat</option>
                                        <option value="sabtu">Sabtu</option>
                                    </select>
                                    <div class="invalid-feedback d-none" id="day-error"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="subject_id">Mata Pelajaran <span class="text-danger">*</span></label>
                                    <select class="form-control" id="subject_id" name="subject_id" required>
                                        <option value="">Pilih Mata Pelajaran</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->code }} - {{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback d-none" id="subject_id-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="teacher_id">Guru <span class="text-danger">*</span></label>
                                    <select class="form-control" id="teacher_id" name="teacher_id" required>
                                        <option value="">Pilih Guru</option>
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->name }} - {{ $teacher->nip }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback d-none" id="teacher_id-error"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_time">Jam Mulai <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" id="start_time" name="start_time" required>
                                    <div class="invalid-feedback d-none" id="start_time-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_time">Jam Selesai <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control" id="end_time" name="end_time" required>
                                    <div class="invalid-feedback d-none" id="end_time-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="semester">Semester <span class="text-danger">*</span></label>
                                    <select class="form-control" id="semester" name="semester" required>
                                        <option value="">Pilih Semester</option>
                                            <option value="Ganjil">Ganjil</option>
                                            <option value="Genap">Genap</option>
                                    </select>
                                    <div class="invalid-feedback d-none" id="semester-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="academicYearFilter">Tahun Akademik <span class="text-danger">*</span></label>
                                    <select id="academicYearFilter" class="form-control">
                                        <option value="">Pilih Tahun Akademik</option>
                                        @foreach(App\Helpers\AcademicYearHelper::generateAcademicYears(2, 2) as $year)
                                            <option value="{{ $year }}" {{ App\Helpers\AcademicYearHelper::getCurrentAcademicYear() == $year ? 'selected' : '' }}>{{ $year }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback d-none" id="teacher_id-error"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="scheduleSubmitBtn">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <script>
        $(function() {
            // Open modal for create
            $(document).on('click', '.btn-add-schedule', function(e) {
                e.preventDefault();
                var classId = $(this).data('class-id');
                
                $('#scheduleModalLabel').text('Tambah Jadwal');
                $('#scheduleForm')[0].reset();
                $('#schedule_id').val('');
                
                // Set class_id jika ada - PERBAIKAN: Set setelah reset
                if (classId) {
                    $('#class_id').val(classId);
                }
                
                // Clear all error messages
                $('.invalid-feedback').addClass('d-none').text('');
                $('.form-control').removeClass('is-invalid');
                
                $('#scheduleModal').modal('show');
            });

            // Open modal for edit - GUNAKAN SELECTOR YANG LEBIH SPESIFIK
            $(document).on('click', '.btn-edit-schedule', function(e) {
                e.preventDefault();
                // console.log('Edit button clicked'); // Debug log
                
                var id = $(this).data('id');
                // console.log('Schedule ID:', id); // Debug log
                
                if (!id) {
                    alert('ID jadwal tidak ditemukan');
                    return;
                }
                
                // Show loading state
                $('#scheduleModalLabel').text('Memuat data...');
                $('#scheduleForm')[0].reset();
                $('.invalid-feedback').addClass('d-none').text('');
                $('.form-control').removeClass('is-invalid');
                $('#scheduleModal').modal('show');
                
                $.ajax({
                    url: '/schedules/' + id + '/edit',
                    method: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        // console.log('AJAX response:', res); // Debug log
                        
                        if(res.success) {
                            $('#scheduleModalLabel').text('Edit Jadwal');
                            $('#schedule_id').val(res.data.id);
                            $('#class_id').val(res.data.class_id);
                            $('#day').val(res.data.day).trigger('change'); // PERBAIKAN: Pastikan ini ter-set
                            $('#subject_id').val(res.data.subject_id);
                            $('#teacher_id').val(res.data.teacher_id);
                            $('#start_time').val(res.data.start_time);
                            $('#end_time').val(res.data.end_time);
                            
                            // TAMBAHAN: Trigger change event untuk memastikan UI terupdate
                            $('#day').trigger('change');
                        } else {
                            alert('Gagal memuat data: ' + (res.message || 'Unknown error'));
                            $('#scheduleModal').modal('hide');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', xhr.responseText); // Debug log
                        console.error('Status:', status);
                        console.error('Error:', error);
                        
                        var errorMessage = 'Terjadi kesalahan saat memuat data';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.status === 404) {
                            errorMessage = 'Data jadwal tidak ditemukan';
                        } else if (xhr.status === 500) {
                            errorMessage = 'Kesalahan server internal';
                        }
                        
                        alert(errorMessage);
                        $('#scheduleModal').modal('hide');
                    }
                });
            });

            // Submit form (create or update)
            $('#scheduleForm').on('submit', function(e) {
                e.preventDefault();
                
                var id = $('#schedule_id').val();
                var url = id ? '/schedules/' + id : '/schedules';
                var method = id ? 'PUT' : 'POST';
                var formData = $(this).serialize();
                
                // Add method override for PUT requests
                if (method === 'PUT') {
                    formData += '&_method=PUT';
                }

                $('#scheduleSubmitBtn').prop('disabled', true).text('Menyimpan...');
                
                // Clear previous errors
                $('.invalid-feedback').addClass('d-none').text('');
                $('.form-control').removeClass('is-invalid');

                $.ajax({
                    url: url,
                    method: 'POST', // Always use POST for Laravel
                    data: formData,
                    dataType: 'json',
                    success: function(res) {
                        if(res.success) {
                            $('#scheduleModal').modal('hide');
                            
                            // Show success message
                            swal({
                                title: "Berhasil!",
                                text: res.message || "Data berhasil disimpan.",
                                icon: "success",
                                timer: 3000,
                                buttons: false
                            });
                            
                            // Reload current class schedules
                            var currentClassId = $('.nav-link.active[data-class-id]').data('class-id');
                            if(currentClassId && typeof loadClassSchedules === 'function') {
                                loadClassSchedules(currentClassId);
                            } else {
                                // Fallback: reload page
                                setTimeout(function() {
                                    location.reload();
                                }, 1500);
                            }
                        } else {
                            if (res.errors) {
                                showErrors(res.errors);
                            } else {
                                alert('Gagal menyimpan data: ' + (res.message || 'Unknown error'));
                            }
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr);
                        
                        if(xhr.status === 422) {
                            // Validation errors
                            var errors = xhr.responseJSON?.errors || {};
                            showErrors(errors);
                        } else if(xhr.status === 500) {
                            alert('Terjadi kesalahan server. Silakan coba lagi.');
                        } else {
                            alert('Terjadi kesalahan: ' + (xhr.responseJSON?.message || 'Network error'));
                        }
                    },
                    complete: function() {
                        $('#scheduleSubmitBtn').prop('disabled', false).text('Simpan');
                    }
                });
            });

            function showErrors(errors) {
                $('.invalid-feedback').addClass('d-none').text('');
                $('.form-control').removeClass('is-invalid');
                
                $.each(errors, function(key, val) {
                    var errorElement = $('#' + key + '-error');
                    var inputElement = $('#' + key);
                    
                    if (errorElement.length > 0) {
                        errorElement.removeClass('d-none').text(Array.isArray(val) ? val[0] : val);
                        inputElement.addClass('is-invalid');
                    } else {
                        // Fallback: show in console if error element not found
                        console.warn('Error element not found for:', key, val);
                    }
                });
            }
            
            // Clear form when modal is closed
            $('#scheduleModal').on('hidden.bs.modal', function () {
                $('#scheduleForm')[0].reset();
                $('#schedule_id').val('');
                $('.invalid-feedback').addClass('d-none').text('');
                $('.form-control').removeClass('is-invalid');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Global variables
            let currentClassId = null;
            let searchTimeout = null;
            let currentPage = 1;

            // Initialize first active tab
            const firstActiveTab = $('.nav-link.active').first();
            if (firstActiveTab.length > 0) {
                currentClassId = firstActiveTab.data('class-id');
                if (currentClassId) {
                    loadClassSchedules(currentClassId);
                }
            }

            // Reload when semester/year filter changes
            $('#semesterFilter, #academicYearFilterSchedules').on('change', function() {
                if (currentClassId) {
                    loadClassSchedules(currentClassId);
                }
            });

            // Class filter button click handler
            $('.class-filter-btn').on('click', function(e) {
                e.preventDefault();
                
                const classId = $(this).data('class-id');
                currentClassId = classId;
                currentPage = 1;
                
                // Clear search when switching class
                const searchInput = $(`#searchSchedule-${classId}`);
                searchInput.val('');
                
                // Load schedules for the selected class
                loadClassSchedules(classId);
                
                // Update button states
                $('.class-filter-btn').removeClass('btn-primary').addClass('btn-outline-primary');
                $(this).removeClass('btn-outline-primary').addClass('btn-primary');
                
                // Show corresponding schedule section
                $('.class-schedule-section').addClass('d-none');
                $('#schedule-section-' + classId).removeClass('d-none');
            });

            // PERBAIKAN: Modifikasi tombol tambah jadwal untuk auto-fill class_id
            $(document).on('click', '.btn-add-schedule', function(e) {
                e.preventDefault();
                var classId = $(this).data('class-id');
                
                // Jika tidak ada class-id dari button, ambil dari tab yang aktif
                if (!classId) {
                    classId = $('.nav-link.active[data-class-id]').data('class-id');
                }
                
                $('#scheduleModalLabel').text('Tambah Jadwal');
                $('#scheduleForm')[0].reset();
                $('#schedule_id').val('');
                
                // Set class_id otomatis
                if (classId) {
                    $('#class_id').val(classId);
                    // PERBAIKAN: Gunakan readonly instead of disabled
                    $('#class_id').prop('readonly', true).addClass('');
                } else {
                    $('#class_id').prop('readonly', false).removeClass('');
                }
                
                // Clear all error messages
                $('.invalid-feedback').addClass('d-none').text('');
                $('.form-control').removeClass('is-invalid');
                
                $('#scheduleModal').modal('show');
            });

            // Search functionality
            $('.search-schedule').on('input', function() {
                const classId = $(this).data('class-id');
                const searchTerm = $(this).val().trim();
                const clearButton = $('#clear-search-' + classId);
                
                // Show/hide clear button
                if (searchTerm.length > 0) {
                    clearButton.show();
                } else {
                    clearButton.hide();
                }
                
                // Clear previous timeout
                if (searchTimeout) {
                    clearTimeout(searchTimeout);
                }
                
                // Set new timeout for search
                searchTimeout = setTimeout(function() {
                    currentPage = 1;
                    loadClassSchedules(classId, searchTerm);
                }, 500); // 500ms delay
            });

            // Clear search button
            $('.clear-search').on('click', function() {
                const classId = $(this).data('class-id');
                const searchInput = $('#searchSchedule-' + classId);
                
                searchInput.val('');
                $(this).hide();
                currentPage = 1;
                loadClassSchedules(classId);
            });

            // Function to load class schedules
            function loadClassSchedules(classId, searchTerm, page) {
                if (!classId) return;
                
                searchTerm = searchTerm || '';
                page = page || 1;
                
                const tableBody = $('#scheduleTableBody-' + classId);
                const paginationContainer = $('#pagination-' + classId);
                
                // Show loading state
                showLoadingState(tableBody, classId);
                
                // Prepare request parameters
                const params = {
                    class_id: classId,
                    page: page
                };
                
                if (searchTerm) {
                    params.q = searchTerm;
                }

                const semester = $('#semesterFilter').val();
                const academicYear = $('#academicYearFilterSchedules').val();
                if (semester) params.semester = semester;
                if (academicYear) params.academic_year = academicYear;
                
                // Make AJAX request
                $.ajax({
                    url: '/schedules/class/' + classId,
                    method: 'GET',
                    data: params,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            renderScheduleTable(response.schedules, tableBody, classId);
                            renderPagination(response.pagination, paginationContainer, classId, searchTerm);
                            currentPage = response.currentPage;
                        } else {
                            showErrorState(tableBody, classId, 'Gagal memuat data jadwal');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading schedules:', error);
                        showErrorState(tableBody, classId, 'Terjadi kesalahan saat memuat data');
                    }
                });
            }

            // Function to show loading state
            function showLoadingState(tableBody, classId) {
                const loadingHtml = '<tr>' +
                    '<td colspan="7" class="text-center py-4">' +
                        '<div class="spinner-border text-primary" role="status">' +
                            '<span class="sr-only">Loading...</span>' +
                        '</div>' +
                        '<p class="mt-2 text-muted">Memuat data jadwal...</p>' +
                    '</td>' +
                '</tr>';
                
                tableBody.html(loadingHtml);
            }

            // Function to show error state
            function showErrorState(tableBody, classId, message) {
                const errorHtml = '<tr>' +
                    '<td colspan="7" class="text-center py-4">' +
                        '<div class="empty-state">' +
                            '<div class="empty-state-icon mb-3">' +
                                '<i class="fas fa-exclamation-triangle text-warning"></i>' +
                            '</div>' +
                            '<h6 class="empty-state-title">' + message + '</h6>' +
                            '<button class="btn btn-primary btn-sm mt-2" onclick="refreshSchedules(\'' + classId + '\')">' +
                                '<i class="fas fa-refresh mr-1"></i>Coba Lagi' +
                            '</button>' +
                        '</div>' +
                    '</td>' +
                '</tr>';
                
                tableBody.html(errorHtml);
            }

            // Function to render schedule table
            function renderScheduleTable(schedules, tableBody, classId) {
                if (schedules.length === 0) {
                    const emptyHtml = '<tr id="empty-row-' + classId + '">' +
                        '<td colspan="7" class="text-center py-5">' +
                            '<div class="p-5">' +
                                '<i class="fas fa-calendar-times fa-5x text-muted mb-3"></i>' +
                                '<h5 class="font-weight-bold">Tidak ada jadwal ditemukan</h5>' +
                                '<p class="text-muted">Coba ubah kata kunci pencarian atau buat jadwal baru</p>' +
                                '<a href="/schedules/create?class_id=' + classId + '" class="btn btn-primary">' +
                                    '<i class="fas fa-plus mr-2"></i>Buat Jadwal Baru' +
                                '</a>' +
                            '</div>' +
                        '</td>' +
                    '</tr>';
                    
                    tableBody.html(emptyHtml);
                    return;
                }

                let html = '';
                schedules.forEach((schedule, index) => {
                    const rowNumber = ((currentPage - 1) * 10) + index + 1;
                    const teacherInitial = schedule.teacher && schedule.teacher.name 
                        ? schedule.teacher.name.charAt(0).toUpperCase() 
                        : 'T';
                    
                    const teacherName = schedule.teacher ? escapeHtml(schedule.teacher.name) : '-';
                    const teacherNip = schedule.teacher ? (schedule.teacher.nip || '-') : '-';
                    const subjectName = schedule.subject ? escapeHtml(schedule.subject.name) : '-';
                    const dayName = schedule.day || '-';
                    const startTime = schedule.start_time || '-';
                    const endTime = schedule.end_time || '-';
                    
                    html += '<tr>' +
                        '<td class="text-center">' + rowNumber + '</td>' +
                        '<td>' +
                            '<span class="badge badge-primary">' + dayName.charAt(0).toUpperCase() + dayName.slice(1) + '</span>' +
                        '</td>' +
                        '<td><a href="#" class="text-secondery font-weight-bold">' + subjectName + '</a></td>' +
                        '<td>' +
                            teacherName + '<br>' +
                            '<small class="text-muted">' + teacherNip + '</small>' +
                        '</td>' +
                        '<td class="text-center">' +
                            '<span class="badge badge-success">' + startTime + '</span>' +
                        '</td>' +
                        '<td class="text-center">' +
                            '<span class="badge badge-danger">' + endTime + '</span>' +
                        '</td>' +
                        '<td>' +
                            '<button type="button" class="btn btn-primary btn-action mr-1 btn-edit-schedule" ' +
                                'data-id="' + schedule.id + '" data-toggle="tooltip" title="Edit">' +
                                '<i class="fas fa-pencil-alt"></i>' +
                            '</button> ' +
                            '<form id="delete-form-' + schedule.id + '" action="/schedules/' + schedule.id + '" method="POST" style="display:inline;">' +
                                '<input type="hidden" name="_token" value="' + getCsrfToken() + '">' +
                                '<input type="hidden" name="_method" value="DELETE">' +
                                '<button type="button" class="btn btn-danger btn-action" data-toggle="tooltip" title="Delete" onclick="confirmDelete(\'' + schedule.id + '\')">' +
                                    '<i class="fas fa-trash"></i>' +
                                '</button>' +
                            '</form>' +
                        '</td>' +
                    '</tr>';
                });

                tableBody.html(html);
            }

            // Function to render pagination
            function renderPagination(pagination, container, classId, searchTerm) {
                searchTerm = searchTerm || '';
                
                if (pagination.last_page <= 1) {
                    container.html('');
                    return;
                }

                let html = '<nav class="d-inline-block"><ul class="pagination mb-0">';
                
                // Previous page
                if (pagination.current_page > 1) {
                    html += '<li class="page-item">' +
                        '<a class="page-link" href="#" data-page="' + (pagination.current_page - 1) + '" data-class-id="' + classId + '" data-search="' + escapeHtml(searchTerm) + '">' +
                            '<i class="fas fa-chevron-left"></i>' +
                        '</a>' +
                    '</li>';
                } else {
                    html += '<li class="page-item disabled">' +
                        '<span class="page-link"><i class="fas fa-chevron-left"></i></span>' +
                    '</li>';
                }
                
                // Page numbers
                const startPage = Math.max(1, pagination.current_page - 2);
                const endPage = Math.min(pagination.last_page, pagination.current_page + 2);
                
                for (let i = startPage; i <= endPage; i++) {
                    const activeClass = i === pagination.current_page ? 'active' : '';
                    if (i === pagination.current_page) {
                        html += '<li class="page-item active">' +
                            '<span class="page-link">' + i + '</span>' +
                        '</li>';
                    } else {
                        html += '<li class="page-item">' +
                            '<a class="page-link" href="#" data-page="' + i + '" data-class-id="' + classId + '" data-search="' + escapeHtml(searchTerm) + '">' + i + '</a>' +
                        '</li>';
                    }
                }
                
                // Next page
                if (pagination.current_page < pagination.last_page) {
                    html += '<li class="page-item">' +
                        '<a class="page-link" href="#" data-page="' + (pagination.current_page + 1) + '" data-class-id="' + classId + '" data-search="' + escapeHtml(searchTerm) + '">' +
                            '<i class="fas fa-chevron-right"></i>' +
                        '</a>' +
                    '</li>';
                } else {
                    html += '<li class="page-item disabled">' +
                        '<span class="page-link"><i class="fas fa-chevron-right"></i></span>' +
                    '</li>';
                }
                
                html += '</ul></nav>';
                
                container.html(html);
            }

            // Helper function to escape HTML
            function escapeHtml(text) {
                if (!text) return '';
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.toString().replace(/[&<>"']/g, function(m) { return map[m]; });
            }

            // Helper function to get CSRF token
            function getCsrfToken() {
                return $('meta[name="csrf-token"]').attr('content') || '';
            }

            // Pagination click handler
            $(document).on('click', '.page-link', function(e) {
                e.preventDefault();
                
                const page = $(this).data('page');
                const classId = $(this).data('class-id');
                const searchTerm = $(this).data('search') || '';
                
                if (page && classId) {
                    loadClassSchedules(classId, searchTerm, page);
                }
            });

            // Refresh button handler
            window.refreshSchedules = function(classId) {
                const searchInput = $('#searchSchedule-' + classId);
                const searchTerm = searchInput ? searchInput.val().trim() : '';
                loadClassSchedules(classId, searchTerm, 1);
            };

            // Global function to be called from onclick
            window.loadClassSchedules = loadClassSchedules;

            // PERBAIKAN: Re-enable class select saat modal ditutup
            $('#scheduleModal').on('hidden.bs.modal', function () {
                $('#class_id').prop('disabled', false);
            });
        });

        // Delete confirmation function (global scope)
        function confirmDelete(id) {
            swal({
                title: "Apakah Anda Yakin?",
                text: "Data ini akan dihapus secara permanen!",
                icon: "warning",
                buttons: [
                    'Tidak',
                    'Ya, Hapus'
                ],
                dangerMode: true,
            }).then(function(isConfirm) {
                if (isConfirm) {
                    // Langsung submit form yang sudah ada
                    const form = document.getElementById(`delete-form-${id}`);
                    
                    // Atau gunakan AJAX dengan pendekatan yang lebih sederhana
                    const url = form.action;
                    const token = form.querySelector('input[name="_token"]').value;

                    fetch(url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new URLSearchParams({
                            '_method': 'DELETE',
                            '_token': token
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            swal({
                                title: "Berhasil!",
                                text: data.message || "Data berhasil dihapus.",
                                icon: "success",
                                timer: 3000,
                                buttons: false
                            }).then(() => {
                                // Hapus baris tabel
                                const rowToRemove = form.closest('tr');
                                if (rowToRemove) {
                                    rowToRemove.remove();
                                    // Perbarui nomor urut
                                    renumberTableRows();
                                }
                            });
                        } else {
                            swal("Gagal", data.message || "Terjadi kesalahan saat menghapus data.", "error");
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        swal("Gagal", "Terjadi kesalahan saat menghapus data.", "error");
                    });
                }
            });
        }

        function renumberTableRows() {
            // Find the currently active schedule table
            const activeTable = document.querySelector('.tab-pane.show.active table tbody');
            if (!activeTable) return;
            const rows = activeTable.querySelectorAll('tr');
            
            // Use the currentPage and perPage from the schedules paginator if available, otherwise default to 1 and 10
            const currentPage = 1; // You may need to get this from a global variable
            const perPage = 10;
            
            rows.forEach((row, index) => {
                const numberCell = row.querySelector('td:first-child');
                if (numberCell) {
                    numberCell.textContent = (currentPage - 1) * perPage + index + 1;
                }
            });
        }
    </script>
@endpush
