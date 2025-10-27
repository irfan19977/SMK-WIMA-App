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
                <div class="pricing pricing-highlight" style="position: relative;">
                    <!-- Dropdown Menu -->
                    <div class="dropdown" style="position: absolute; top: 15px; right: 15px; z-index: 10;">
                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownMenuButton{{ $class->id }}" 
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                style="padding: 5px 10px;" onclick="event.stopPropagation()">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton{{ $class->id }}">
                            <a class="dropdown-item" href="{{ route('classes.show', $class->id) }}" onclick="event.stopPropagation()">
                                <i class="fas fa-eye mr-2"></i> Lihat Detail
                            </a>
                            <a class="dropdown-item edit-class" href="#" 
                               data-id="{{ $class->id }}" 
                               data-name="{{ $class->name }}" 
                               data-code="{{ $class->code }}" 
                               data-grade="{{ $class->grade }}" 
                               data-major="{{ $class->major }}"
                               onclick="event.stopPropagation()">
                                <i class="fas fa-edit mr-2"></i> Edit
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-warning" href="#" onclick="event.stopPropagation();">
                                <i class="fas fa-archive mr-2"></i> Arsipkan
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger" href="#" 
                               onclick="event.stopPropagation(); confirmDelete('{{ $class->id }}')">
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
                                <div class="pricing-item-label">Grade: {{ $class->grade }}</div>
                            </div>
                            <div class="pricing-item">
                                <div class="pricing-item-icon"><i class="fas fa-book"></i></div>
                                <div class="pricing-item-label">Jurusan: {{ $class->major }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="pricing-cta">
                        <a href="{{ route('classes.show', $class->id) }}" class="btn btn-primary btn-block" onclick="event.stopPropagation();">
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
                    <i class="fas fa-info-circle"></i> Tidak ada data kelas
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
                        <label>Kode Kelas <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="code" name="code" required>
                        <div class="invalid-feedback" id="code-error"></div>
                    </div>
                    
                    <div class="form-group">
                        <label>Grade <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="grade" name="grade" required>
                        <div class="invalid-feedback" id="grade-error"></div>
                    </div>
                    
                    <div class="form-group">
                        <label>Jurusan <span class="text-danger">*</span></label>
                        <select class="form-control" id="major" name="major" required>
                            <option value="">Pilih Jurusan</option>
                            <option value="Teknik Komputer dan Jaringan">Teknik Komputer dan Jaringan</option>
                            <option value="Akuntansi">Akuntansi</option>
                            <option value="Design Komunikasi Visual">Design Komunikasi Visual</option>
                            <option value="Keperawatan">Keperawatan</option>
                        </select>
                        <div class="invalid-feedback" id="major-error"></div>
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

        // Handle search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="q"]');
            const classesContainer = document.getElementById('classes-container');
            let searchTimeout;

            function performSearch(query) {
                if (query.length < 1) {
                    // If search is empty, show all classes
                    document.querySelectorAll('.class-item').forEach(item => {
                        item.style.display = '';
                    });
                    return;
                }

                fetch(`{{ route('classes.search') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        updateClasses(data);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }

            function updateClasses(classes) {
                if (classes.length === 0) {
                    classesContainer.innerHTML = '<div class="col-12"><div class="alert alert-info text-center"><i class="fas fa-info-circle"></i> Tidak ada data kelas</div></div>';
                    return;
                }

                let html = '';
                classes.forEach((classItem) => {
                    html += `
                        <div class="col-12 col-md-6 col-lg-4 class-item">
                            <div class="pricing pricing-highlight" style="position: relative; cursor: pointer;" onclick="window.location.href='/classes/${classItem.id}'">
                                <div class="dropdown" style="position: absolute; top: 15px; right: 15px; z-index: 10;">
                                    <button class="btn btn-sm btn-light" type="button" 
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" 
                                            onclick="event.stopPropagation(); event.preventDefault(); this.parentElement.classList.toggle('show');" 
                                            style="padding: 5px 10px;">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a class="dropdown-item" href="/classes/${classItem.id}" onclick="event.stopPropagation();">
                                            <i class="fas fa-eye"></i> Lihat Detail
                                        </a>
                                        <a class="dropdown-item edit-class" href="#" data-id="${classItem.id}" data-name="${classItem.name}" data-grade="${classItem.grade}" data-major="${classItem.major}" data-code="${classItem.code}">
                                            <i class="fas fa-pencil-alt"></i> Edit
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item text-danger" href="#" onclick="event.stopPropagation(); confirmDelete('${classItem.id}');">
                                            <i class="fas fa-trash"></i> Hapus
                                        </a>
                                    </div>
                                </div>

                                <div class="pricing-title">
                                    ${classItem.name}
                                </div>
                                <div class="pricing-padding">
                                    <div class="pricing-price">
                                        <div>${classItem.grade}</div>
                                        <div>${classItem.major}</div>
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
                                    <a href="/classes/${classItem.id}" class="btn btn-primary btn-block" onclick="event.stopPropagation();">
                                        <i class="fas fa-eye"></i> Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                classesContainer.innerHTML = html;
            }

            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                clearTimeout(searchTimeout);
                
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300);
            });
        });

        // Handle clear search button
        document.addEventListener('DOMContentLoaded', function() {
            const clearButton = document.getElementById('clear-search');
            const searchButton = document.getElementById('search-button');
            const searchInput = document.getElementById('search-input');
            
            clearButton.addEventListener('click', function() {
                searchInput.value = '';
                searchInput.focus();
                
                clearButton.style.display = 'none';
                searchButton.style.display = 'block';
                
                const event = new Event('input', { bubbles: true });
                searchInput.dispatchEvent(event);
            });
            
            searchInput.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    clearButton.style.display = 'block';
                    searchButton.style.display = 'none';
                } else {
                    clearButton.style.display = 'none';
                    searchButton.style.display = 'block';
                }
            });
            
            searchButton.addEventListener('click', function() {
                const event = new Event('input', { bubbles: true });
                searchInput.dispatchEvent(event);
            });
            
            clearButton.style.display = 'none';
            searchButton.style.display = 'block';
        });

        // Initialize dropdowns
        function initDropdowns() {
            // Initialize Bootstrap dropdowns
            $('.dropdown-toggle').dropdown();
            
            // Handle click on dropdown toggle
            $(document).off('click', '.dropdown-toggle').on('click', '.dropdown-toggle', function(e) {
                e.stopPropagation();
                e.preventDefault();
                
                // Close other open dropdowns
                $('.dropdown-menu').not($(this).next('.dropdown-menu')).removeClass('show');
                
                // Toggle current dropdown
                $(this).next('.dropdown-menu').toggleClass('show');
            });
            
            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.dropdown').length) {
                    $('.dropdown-menu').removeClass('show');
                }
            });
            
            // Prevent dropdown from closing when clicking inside
            $('.dropdown-menu').on('click', function(e) {
                e.stopPropagation();
            });
            
            // Handle edit class click
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
                $('#code').val(code);
                
                $('#form-method').val('PUT');
                $('#classModalLabel').text('Edit Kelas');
                $('#classModal').modal('show');
                
                // Close the dropdown menu
                $(this).closest('.dropdown-menu').removeClass('show');
            });
        }
        
        // Initialize on document ready
        $(document).ready(function() {
            initDropdowns();
            
            // Re-initialize dropdowns after AJAX content load
            $(document).ajaxComplete(function() {
                initDropdowns();
            });
            
            // Reset form when modal is closed
            $('#classModal').on('hidden.bs.modal', function () {
                $('#classForm')[0].reset();
                $('#form-method').val('POST');
                $('#class_id').val('');
                $('.invalid-feedback').text('').addClass('d-none');
                $('.is-invalid').removeClass('is-invalid');
                $('#classModalLabel').text('Tambah Kelas');
            });

            // Handle edit button click
            $(document).on('click', '.edit-class', function(e) {
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
                $('#code').val(code);
                
                $('#form-method').val('PUT');
                $('#classModalLabel').text('Edit Kelas');
                $('#classModal').modal('show');
                
                // Close the dropdown menu
                $(this).closest('.dropdown-menu').removeClass('show');
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
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message || 'Data berhasil disimpan',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                const input = $('#' + key);
                                const errorDiv = $('#' + key + '-error');
                                
                                input.addClass('is-invalid');
                                errorDiv.text(value[0]).removeClass('d-none');
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan. Silakan coba lagi.'
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
