@extends('layouts.app')

@section('content')

<section class="section">
    <div class="section-header">
        <h1>Daftar Kelas</h1>
        <div class="section-header-button">
            <button class="btn btn-primary" id="btn-create" data-toggle="modal" data-target="#classModal">
                <i class="fas fa-plus"></i> Tambah Kelas
            </button>
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Cari Kelas</h4>
                        <div class="card-header-action">
                            <button type="button" class="btn btn-outline-secondary" id="toggle-archived" title="Tampilkan/Sembunyikan Kelas Arsip">
                                <i class="fas fa-archive"></i> <span id="toggle-text">Tampilkan Arsip</span>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari Kelas (Nama, Code, Grade, Jurusan)" 
                                name="q" id="search-input" autocomplete="off">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-primary" id="search-button">
                                    <i class="fas fa-search"></i>
                                </button>
                                <button type="button" class="btn btn-primary" id="clear-search" title="Clear Search" style="display: none;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" id="classes-container">
            @forelse ($classes as $class)
            <div class="col-12 col-md-6 col-lg-4 class-item mb-4">
                <div class="pricing pricing-highlight class-card" style="position: relative; cursor: pointer;" data-class-id="{{ $class->id }}">
                    <!-- Dropdown Menu -->
                    <div class="dropdown" style="position: absolute; top: 15px; right: 15px; z-index: 10;">
                        <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                style="padding: 5px 10px;">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="{{ route('classes.show', $class->id) }}">
                                <i class="fas fa-eye mr-2"></i> Lihat Detail
                            </a>
                            <a class="dropdown-item edit-class" href="#"
                               data-id="{{ $class->id }}"
                               data-name="{{ $class->name }}"
                               data-code="{{ $class->code }}"
                               data-grade="{{ $class->grade }}"
                               data-major="{{ $class->major }}">
                                <i class="fas fa-edit mr-2"></i> Edit
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-warning" href="#" onclick="toggleArchive('{{ $class->id }}'); return false;">
                                <i class="fas fa-archive mr-2"></i> {{ $class->is_archived ? 'Batalkan Arsip' : 'Arsipkan' }}
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="#" onclick="confirmDelete('{{ $class->id }}'); return false;">
                                <i class="fas fa-trash-alt mr-2"></i> Hapus
                            </a>
                        </div>
                    </div>

                    <div class="pricing-title">
                        {{ $class->major }}
                    </div>
                    <div class="pricing-padding">
                        <div class="pricing-price">
                            <div>{{ $class->grade }}</div>
                        </div>
                        <div class="pricing-details">
                            <div class="pricing-item">
                                <div class="pricing-item-icon"><i class="fas fa-code"></i></div>
                                <div class="pricing-item-label">Code: {{ $class->code }}</div>
                            </div>
                            <div class="pricing-item">
                                <div class="pricing-item-icon"><i class="fas fa-graduation-cap"></i></div>
                                <div class="pricing-item-label">Nama: {{ $class->name }}</div>
                            </div>
                            <div class="pricing-item">
                                <div class="pricing-item-icon"><i class="fas fa-book"></i></div>
                                <div class="pricing-item-label">Jurusan: {{ $class->major }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="pricing-cta">
                        <a href="{{ route('classes.show', $class->id) }}" class="btn btn-primary btn-block">
                            <i class="fas fa-eye"></i> Lihat Detail
                        </a>
                    </div>

                    <form id="delete-form-{{ $class->id }}" action="{{ route('classes.destroy', $class->id) }}"
                        method="POST" style="display:none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i>
                    @if(request('show_archived'))
                        Belum ada kelas yang diarsipkan. Kelas yang diarsipkan akan muncul di sini.
                    @else
                        Tidak ada data kelas. Klik tombol "Tambah Kelas" untuk menambahkan kelas baru.
                    @endif
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Modal for Create/Edit Class -->
<div class="modal fade" id="classModal" tabindex="-1" role="dialog" aria-labelledby="classModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="classModalLabel">Tambah Kelas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="classForm" method="POST" action="{{ route('classes.store') }}">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">
                <input type="hidden" name="id" id="class_id">

                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <small class="text-muted">Contoh: X TKJ</small>
                        <div class="invalid-feedback" id="name-error"></div>
                    </div>

                    <div class="form-group">
                        <label>Grade <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="grade" name="grade" required min="1" max="12">
                        <div class="invalid-feedback" id="grade-error"></div>
                    </div>

                    <div class="form-group">
                        <label>Jurusan <span class="text-danger">*</span></label>
                        <select class="form-control" id="major" name="major" required>
                            <option value="">Pilih Jurusan</option>
                            <option value="Teknik Kendaraan Ringan Otomotif">Teknik Kendaraan Ringan Otomotif</option>
                            <option value="Teknik Bisnis dan Sepeda Motor">Teknik Bisnis dan Sepeda Motor</option>
                            <option value="Teknik Kimian Industri">Teknik Kimian Industri</option>
                            <option value="Teknik Komputer & Jaringan">Teknik Komputer & Jaringan</option>
                        </select>
                        <div class="invalid-feedback" id="major-error"></div>
                    </div>

                    <div class="form-group">
                        <label>Tahun Akademik</label>
                        <input type="text" class="form-control" id="academic_year" name="academic_year"
                               value="{{ \App\Helpers\AcademicYearHelper::getCurrentAcademicYear() }}" readonly>
                        <small class="text-muted">Otomatis diisi dengan tahun akademik saat ini</small>
                        <div class="invalid-feedback" id="academic_year-error"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
    <style>
        .pricing {
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }
        .pricing:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .pricing-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .pricing-price {
            font-size: 2rem;
            font-weight: 700;
            margin: 20px 0;
        }
        .pricing-details {
            margin: 20px 0;
        }
        .pricing-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .pricing-item-icon {
            width: 30px;
            margin-right: 10px;
            color: #6777ef;
        }
        .pricing-cta {
            margin-top: 20px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        function confirmDelete(id) {
            swal({
                title: "Apakah Anda Yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                buttons: {
                    cancel: "Batal",
                    confirm: "Ya, Hapus!"
                },
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
        function toggleArchive(classId) {
            // Multiple ways to find the class item
            let classItem;

            // Method 1: Find by data-class-id
            classItem = $(`[data-class-id="${classId}"]`).closest('.class-item');
            if (classItem.length > 0) {
            } else {
                // Method 2: Find by edit button data-id
                classItem = $(`.edit-class[data-id="${classId}"]`).closest('.class-item');
                if (classItem.length > 0) {
                } else {
                    // Method 3: Find by onclick attribute
                    classItem = $(`a[onclick*="toggleArchive('${classId}')"]`).closest('.class-item');
                    if (classItem.length > 0) {
                    } else {
                        // Method 4: Search through all class items
                        $('.class-item').each(function() {
                            if ($(this).find(`[onclick*="toggleArchive('${classId}')"]`).length > 0 ||
                                $(this).find(`[data-id="${classId}"]`).length > 0 ||
                                $(this).find(`[onclick*="confirmDelete('${classId}')"]`).length > 0) {
                                classItem = $(this);
                                return false; // break the loop
                            }
                        });
                    }
                }
            }

            if (classItem.length === 0) {
                // If not found, try to find the parent container and reload
                swal({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: 'Halaman akan dimuat ulang untuk memperbarui tampilan.',
                    timer: 1500,
                    buttons: false
                }).then(() => {
                    window.location.reload();
                });
                return;
            }

            // Check if currently archived by looking at the dropdown text
            const archiveButton = classItem.find('.dropdown-item.text-warning');
            const isCurrentlyArchived = archiveButton.text().includes('Batalkan');
            const urlParams = new URLSearchParams(window.location.search);
            const isInArchiveView = urlParams.has('show_archived');

            swal({
                title: "Apakah Anda Yakin?",
                text: isCurrentlyArchived ?
                    "Kelas akan dibatalkan dari arsip dan akan muncul kembali dalam daftar kelas utama." :
                    "Kelas akan diarsipkan dan tidak akan muncul dalam daftar kelas utama. Kelas yang diarsipkan dapat dibatalkan kapan saja melalui tombol 'Tampilkan Arsip'.",
                icon: "warning",
                buttons: {
                    cancel: "Batal",
                    confirm: isCurrentlyArchived ? "Ya, Batalkan!" : "Ya, Arsipkan!"
                },
                dangerMode: true,
            })
            .then((willArchive) => {
                if (willArchive) {
                    $.ajax({
                        url: '{{ url("classes") }}/' + classId + '/toggle-archive',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                swal({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.is_archived ?
                                        'Kelas telah diarsipkan dan tidak akan muncul dalam daftar kelas utama. Gunakan tombol "Tampilkan Arsip" untuk melihat kelas yang diarsipkan.' :
                                        'Kelas telah dibatalkan dari arsip dan akan muncul kembali dalam daftar kelas utama.',
                                    timer: 3000,
                                    buttons: false
                                }).then(() => {
                                    if (response.is_archived) {
                                        // Class was archived - remove from current view if not in archive view
                                        if (!isInArchiveView) {
                                            classItem.fadeOut(300, function() {
                                                $(this).remove();
                                            });
                                        }
                                    } else {
                                        // Class was unarchived - hide from archive view if in archive view
                                        if (isInArchiveView) {
                                            classItem.fadeOut(300, function() {
                                                $(this).remove();
                                            });
                                        } else {
                                            // If in normal view and unarchived, just update visual state
                                            classItem.removeClass('archived');
                                            // Update dropdown text
                                            const archiveButton = classItem.find('.dropdown-item.text-warning');
                                            archiveButton.text(archiveButton.text().replace('Batalkan Arsip', 'Arsipkan'));
                                        }
                                    }
                                });
                            }
                        },
                        error: function(xhr) {
                            swal({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Terjadi kesalahan saat mengarsipkan kelas.'
                            });
                        }
                    });
                }
            });
        }

        // Handle toggle archived functionality
        function initToggleArchived() {
            const urlParams = new URLSearchParams(window.location.search);
            const showArchived = urlParams.has('show_archived');

            $('#toggle-archived').data('show-archived', showArchived);

            if (showArchived) {
                $('#toggle-text').text('Sembunyikan Arsip');
                $('#toggle-archived').removeClass('btn-outline-secondary').addClass('btn-secondary');
            } else {
                $('#toggle-text').text('Tampilkan Arsip');
                $('#toggle-archived').removeClass('btn-secondary').addClass('btn-outline-secondary');
            }
        }

        $('#toggle-archived').on('click', function() {
            const showArchived = $(this).data('show-archived') || false;
            const newShowArchived = !showArchived;

            $(this).data('show-archived', newShowArchived);

            if (newShowArchived) {
                $('#toggle-text').text('Sembunyikan Arsip');
                $(this).removeClass('btn-outline-secondary').addClass('btn-secondary');
            } else {
                $('#toggle-text').text('Tampilkan Arsip');
                $(this).removeClass('btn-secondary').addClass('btn-outline-secondary');
            }

            // Reload the page with the new parameter
            const currentUrl = new URL(window.location);
            if (newShowArchived) {
                currentUrl.searchParams.set('show_archived', '1');
            } else {
                currentUrl.searchParams.delete('show_archived');
            }

            // Preserve search query if exists
            const searchQuery = $('#search-input').val().trim();
            if (searchQuery) {
                currentUrl.searchParams.set('q', searchQuery);
            }

            window.location.href = currentUrl.toString();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="q"]');
            const classesContainer = document.getElementById('classes-container');
            let searchTimeout;

            function performSearch(query) {
                if (query.length < 1) {
                    // If search is empty, reload page to show proper filtered results
                    window.location.reload();
                    return;
                }

                // Get current show_archived parameter
                const urlParams = new URLSearchParams(window.location.search);
                const showArchived = urlParams.has('show_archived');
                const searchUrl = showArchived ?
                    `{{ route('classes.search') }}?q=${encodeURIComponent(query)}&show_archived=1` :
                    `{{ route('classes.search') }}?q=${encodeURIComponent(query)}`;

                fetch(searchUrl)
                    .then(response => response.json())
                    .then(data => {
                        updateClasses(data);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            function updateClasses(classes) {
                // Get current show_archived parameter
                const urlParams = new URLSearchParams(window.location.search);
                const showArchived = urlParams.has('show_archived');

                if (classes.length === 0) {
                    const emptyMessage = showArchived ?
                        '<div class="col-12"><div class="alert alert-info text-center"><i class="fas fa-info-circle"></i> Tidak ada kelas yang diarsipkan sesuai dengan pencarian Anda.</div></div>' :
                        '<div class="col-12"><div class="alert alert-info text-center"><i class="fas fa-info-circle"></i> Tidak ada kelas sesuai dengan pencarian Anda.</div></div>';
                    classesContainer.innerHTML = emptyMessage;
                    return;
                }

                let html = '';
                classes.forEach((classItem) => {
                    html += `
                        <div class="col-12 col-md-6 col-lg-4 class-item">
                            <div class="pricing pricing-highlight class-card" style="position: relative; cursor: pointer;" data-class-id="${classItem.id}">
                                <div class="dropdown" style="position: absolute; top: 15px; right: 15px; z-index: 10;">
                                    <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                            style="padding: 5px 10px;">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="{{ url('classes') }}/${classItem.id}">
                                            <i class="fas fa-eye"></i> Lihat Detail
                                        </a>
                                        <a class="dropdown-item edit-class" href="#" data-id="${classItem.id}" data-name="${classItem.name}" data-grade="${classItem.grade}" data-major="${classItem.major}" data-code="${classItem.code}">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-warning" href="#" onclick="toggleArchive('${classItem.id}'); return false;">
                                            <i class="fas fa-archive"></i> ${classItem.is_archived ? 'Batalkan Arsip' : 'Arsipkan'}
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger" href="#" onclick="confirmDelete('${classItem.id}'); return false;">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </div>
                                </div>

                                <div class="pricing-title">
                                    ${classItem.major}
                                </div>
                                <div class="pricing-padding">
                                    <div class="pricing-price">
                                        <div>${classItem.grade}</div>
                                    </div>
                                    <div class="pricing-details">
                                        <div class="pricing-item">
                                            <div class="pricing-item-icon"><i class="fas fa-code"></i></div>
                                            <div class="pricing-item-label">Code: ${classItem.code}</div>
                                        </div>
                                        <div class="pricing-item">
                                            <div class="pricing-item-icon"><i class="fas fa-graduation-cap"></i></div>
                                            <div class="pricing-item-label">Grade: ${classItem.grade}</div>
                                        </div>
                                        <div class="pricing-item">
                                            <div class="pricing-item-icon"><i class="fas fa-book"></i></div>
                                            <div class="pricing-item-label">Jurusan: ${classItem.major}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="pricing-cta">
                                    <a href="{{ url('classes') }}/${classItem.id}" class="btn btn-primary btn-block">
                                        <i class="fas fa-eye"></i> Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                });

                classesContainer.innerHTML = html;

                // Re-initialize dropdowns and event handlers after AJAX update
                initDropdowns();
            }

            searchInput.addEventListener('input', function() {
                const query = this.value.trim();

                clearTimeout(searchTimeout);

                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            });

            // Handle clear search button
            const clearButton = document.getElementById('clear-search');
            const searchButton = document.getElementById('search-button');

            clearButton.addEventListener('click', function() {
                searchInput.value = '';
                searchInput.focus();

                clearButton.style.display = 'none';
                searchButton.style.display = 'block';

                // Reload page to show proper filtered results
                window.location.reload();
            });

            searchButton.addEventListener('click', function() {
                // Reload page to trigger search with current parameters
                const currentUrl = new URL(window.location);
                const searchQuery = searchInput.value.trim();
                if (searchQuery) {
                    currentUrl.searchParams.set('q', searchQuery);
                }
                window.location.href = currentUrl.toString();
            });

            // Initialize button states based on current search
            const currentQuery = searchInput.value.trim();
            clearButton.style.display = currentQuery ? 'block' : 'none';
            searchButton.style.display = currentQuery ? 'none' : 'block';
        });

        // Initialize dropdowns
        function initDropdowns() {
            // Simple initialization - just ensure Bootstrap dropdowns work
            $('.dropdown-toggle').dropdown();

            // Simple click handlers without complex event management
            $('.edit-class').off('click').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const id = $(this).data('id');
                const name = $(this).data('name');
                const grade = $(this).data('grade');
                const major = $(this).data('major');
                const code = $(this).data('code');

                $('#class_id').val(id);
                $('#name').val(name);
                $('#grade').val(grade);
                $('#major').val(major);
                $('#academic_year').val('{{ \App\Helpers\AcademicYearHelper::getCurrentAcademicYear() }}');

                $('#form-method').val('PUT');
                $('#classModalLabel').text('Edit Kelas');
                $('#classModal').modal('show');
            });

            // Add click handlers for class cards
            $('.class-card, [data-class-id]').off('click.card').on('click.card', function(e) {
                // Don't navigate if dropdown or dropdown items were clicked
                if ($(e.target).closest('.dropdown').length > 0) {
                    return;
                }

                const classId = $(this).data('class-id');
                if (classId) {
                    window.location.href = '{{ url("classes") }}/' + classId;
                }
            });
        }

        // Initialize on document ready
        $(document).ready(function() {
            initDropdowns();
            initToggleArchived();

            // Re-initialize dropdowns after AJAX content load
            $(document).ajaxComplete(function() {
                initDropdowns();
            });
            
            // Reset form when modal is closed
            $('#classModal').on('hidden.bs.modal', function () {
                $('#classForm')[0].reset();
                $('#form-method').val('POST');
                $('#class_id').val('');
                $('#academic_year').val('{{ \App\Helpers\AcademicYearHelper::getCurrentAcademicYear() }}');
                $('.invalid-feedback').text('').addClass('d-none');
                $('.is-invalid').removeClass('is-invalid');
                $('#classModalLabel').text('Tambah Kelas');
            });

            // Handle form submission
            $('#classForm').on('submit', function(e) {
                e.preventDefault();
                
                const form = $(this);
                const url = form.attr('action');
                const method = $('#form-method').val();
                const submitBtn = $('#submitBtn');
                const originalText = submitBtn.html();
                
                // Show loading state
                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
                
                // Clear previous errors
                $('.invalid-feedback').text('').addClass('d-none');
                $('.is-invalid').removeClass('is-invalid');
                
                // Determine the URL based on create or update
                const actionUrl = method === 'PUT' 
                    ? '{{ url("classes") }}/' + $('#class_id').val()
                    : url;
                
                $.ajax({
                    url: actionUrl,
                    type: method,
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            $('#classModal').modal('hide');
                            swal({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message || 'Data berhasil disimpan',
                                timer: 2000,
                                buttons: false
                            }).then(() => {
                                // Force page reload to ensure new class appears
                                window.location.href = window.location.href;
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            // Validation errors
                            const errors = xhr.responseJSON.errors;
                            let errorMessage = 'Data yang dimasukkan tidak valid:\n\n';

                            $.each(errors, function(key, value) {
                                const input = $('#' + key);
                                const errorDiv = $('#' + key + '-error');

                                input.addClass('is-invalid');
                                errorDiv.text(value[0]).removeClass('d-none');

                                errorMessage += '• ' + value[0] + '\n';
                            });

                            swal({
                                icon: 'warning',
                                title: 'Data Tidak Valid!',
                                text: errorMessage
                            });
                        } else {
                            // Other errors
                            const errorMessage = xhr.responseJSON?.message || 'Terjadi kesalahan. Silakan coba lagi.';
                            swal({
                                icon: 'error',
                                title: 'Error!',
                                text: errorMessage
                            });
                        }
                    },
                    complete: function() {
                        submitBtn.prop('disabled', false).html(originalText);
                    }
                });
            });
        });
    </script>
@endpush
