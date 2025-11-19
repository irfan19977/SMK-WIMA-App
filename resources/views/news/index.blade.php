@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Daftar Berita</h4>
        <div class="card-header-action">
            <div class="input-group">
                <button class="btn btn-primary" id="btn-create" data-toggle="tooltip"
                    style="margin-right: 10px;" title="Tambah Berita">
                    <i class="fas fa-plus"></i>
                </button>
                <input type="text" class="form-control" placeholder="Cari berita..." 
                    name="q" id="search-input" autocomplete="off">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-primary" id="search-button" style="margin-top: 1px;">
                        <i class="fas fa-search"></i>
                    </button>
                    <button type="button" class="btn btn-primary" id="clear-search" title="Clear Search" 
                        style="display: none; margin-top: 1px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped" id="sortable-table">
                <thead>
                    <tr class="text-center">
                        <th class="text-center">No.</th>
                        <th>Konten Berita</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Info</th>
                        <th class="text-center" style="width: 150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($news as $item)
                    <tr>
                        <td class="text-center">{{ ($news->currentPage() - 1) * $news->perPage() + $loop->iteration }}</td>
                        <td>
                            <div class="media align-items-center">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" 
                                        class="mr-3 rounded" style="width: 64px; height: 48px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center mr-3 rounded" 
                                        style="width: 64px; height: 48px;">
                                        <i class="fas fa-image text-secondary"></i>
                                    </div>
                                @endif
                                <div class="media-body">
                                    <h6 class="mb-1">{{ $item->title }}</h6>
                                    <div class="mb-1">
                                        <span class="badge badge-info">{{ $item->category }}</span>
                                    </div>
                                    @if($item->excerpt)
                                        <small class="text-muted">{{ Str::limit($item->excerpt, 50) }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-center align-middle">
                            @if($item->is_published)
                                <span class="badge badge-success">Terbit</span>
                            @else
                                <span class="badge badge-warning">Draft</span>
                            @endif
                        </td>
                        <td class="text-center align-middle">
                            <div class="small">
                                <div class="mb-1">{{ $item->published_at ? $item->published_at->format('d M Y') : '-' }}</div>
                                <div class="text-muted">{{ $item->user->name ?? 'N/A' }}</div>
                                <div class="text-muted small">{{ $item->view_count ?? 0 }} views</div>
                            </div>
                        </td>
                        <td class="text-center align-middle">
                            <button class="btn btn-sm btn-primary btn-action mr-1 btn-edit"
                                data-id="{{ $item->id }}" data-toggle="tooltip" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </button>

                            <form id="delete-form-{{ $item->id }}" action="{{ route('news.destroy', $item->id) }}"
                                method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-danger btn-action" data-toggle="tooltip"
                                    title="Delete" onclick="confirmDelete('{{ $item->id }}', '{{ $item->title }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data berita</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal untuk Create/Edit -->
<div class="modal fade" id="newsModal" tabindex="-1" role="dialog" aria-labelledby="newsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newsModalLabel">Tambah Berita</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="newsForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Judul Berita <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" required>
                        <div class="invalid-feedback d-none" id="title-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="image">Gambar Sampul <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <small class="form-text text-muted">Ukuran maksimal 5MB. Format: JPG, PNG, GIF</small>
                        <div class="invalid-feedback d-none" id="image-error"></div>
                        <div id="image-preview" class="mt-2" style="display: none;">
                            <img src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="category">Kategori <span class="text-danger">*</span></label>
                        <select class="form-control" id="category" name="category" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Pendidikan">Pendidikan</option>
                            <option value="Kegiatan">Kegiatan</option>
                            <option value="Pengumuman">Pengumuman</option>
                            <option value="Prestasi">Prestasi</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                        <div class="invalid-feedback d-none" id="category-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="excerpt">Ringkasan</label>
                        <textarea class="form-control" id="excerpt" name="excerpt" rows="2" 
                            placeholder="Ringkasan singkat berita (opsional)"></textarea>
                        <div class="invalid-feedback d-none" id="excerpt-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="content">Isi Berita <span class="text-danger">*</span></label>
                        <textarea class="summernote" id="content" name="content" required></textarea>
                        <div class="invalid-feedback d-none" id="content-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="tags">Tag</label>
                        <input type="text" class="form-control inputtags" id="tags" name="tags" 
                            placeholder="Tambahkan tag berita (pisahkan dengan koma)">
                        <div class="invalid-feedback d-none" id="tags-error"></div>
                    </div>

                    <div class="form-group mt-3">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" id="is_published" name="is_published" value="1" checked>
                            <label class="custom-control-label" for="is_published">Publikasikan berita</label>
                        </div>
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
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet">
<style>
    .note-editor.note-frame .note-editing-area .note-editable {
        min-height: 200px;
    }
    .bootstrap-tagsinput {
        width: 100%;
        padding: 6px 12px;
    }
    .bootstrap-tagsinput .tag {
        padding: 3px 8px;
        border-radius: 3px;
        margin-right: 5px;
        margin-bottom: 5px;
    }
    /* Ensure modal content scrolls, not the background */
    .modal-open {
        overflow: hidden;
    }
    .modal .modal-body {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('newsModal');
    const form = document.getElementById('newsForm');
    const modalTitle = document.getElementById('newsModalLabel');
    const searchInput = document.getElementById('search-input');
    const searchButton = document.getElementById('search-button');
    const clearSearch = document.getElementById('clear-search');
    const tableBody = document.querySelector('#sortable-table tbody');
    let isEditMode = false;
    let editId = null;

    // Initialize Summernote
    $('.summernote').summernote({
        height: 250,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    // Initialize tagsinput
    $('.inputtags').tagsinput({
        tagClass: 'badge badge-primary',
        maxTags: 10,
        trimValue: true
    });

    // Reset form function
    function resetForm() {
        $('#newsForm')[0].reset();
        $('#news_id').val('');
        $('.summernote').summernote('code', '');
        if ($('.inputtags').data('tagsinput')) {
            $('.inputtags').tagsinput('removeAll');
        }
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('').addClass('d-none');
        $('#image-preview').hide();
        $('#image').prop('required', true);
    }

    // Create button click
    $('#btn-create').on('click', function() {
        resetForm();
        isEditMode = false;
        editId = null;
        $('#newsModalLabel').text('Tulis Berita Baru');
        $('#submitBtn').text('Simpan Berita');
        $('#newsModal').modal('show');
    });

    // PERBAIKAN: Event delegation untuk edit button dengan error handling
    $(document).on('click', '.btn-edit', function() {
        const id = $(this).data('id');
        
        // Show loading dengan SweetAlert v1
        swal({
            title: 'Memuat data...',
            text: 'Mohon tunggu sebentar',
            buttons: false,
            closeOnClickOutside: false,
            closeOnEsc: false
        });

        $.ajax({
            url: `/news/${id}/edit`,
            method: 'GET',
            dataType: 'json',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            success: function(response) {
                swal.close();
                
                resetForm();
                isEditMode = true;
                editId = id;
                
                $('#newsModalLabel').text('Edit Berita');
                $('#submitBtn').text('Update Berita');
                $('#title').val(response.title);
                $('#category').val(response.category).trigger('change');
                $('#excerpt').val(response.excerpt || '');
                $('.summernote').summernote('code', response.content);
                $('#is_published').prop('checked', response.is_published == 1);
                
                // Handle tags
                if (response.tags) {
                    $('.inputtags').tagsinput('removeAll');
                    const tags = response.tags.split(',');
                    tags.forEach(tag => {
                        $('.inputtags').tagsinput('add', tag.trim());
                    });
                }

                // Show image preview if exists
                if (response.image) {
                    $('#image-preview img').attr('src', '/storage/' + response.image);
                    $('#image-preview').show();
                }

                // Image not required on edit
                $('#image').prop('required', false);
                
                $('#newsModal').modal('show');
            },
            error: function(xhr, status, error) {
                swal.close();
                console.error('Edit Error:', xhr);
                
                let errorMessage = 'Gagal memuat data berita';
                
                // Check if response is JSON
                if (xhr.responseJSON) {
                    errorMessage += ': ' + (xhr.responseJSON.message || 'Terjadi kesalahan');
                } else if (xhr.status === 404) {
                    errorMessage = 'Data tidak ditemukan (404)';
                } else if (xhr.status === 500) {
                    errorMessage = 'Error server (500). Cek console untuk detail.';
                } else if (xhr.status === 419) {
                    errorMessage = 'Session expired. Silakan refresh halaman.';
                } else {
                    errorMessage += '. Status: ' + xhr.status;
                }
                
                swal({
                    title: 'Error!',
                    text: errorMessage,
                    icon: 'error'
                });
            }
        });
    });

    // PERBAIKAN: Delete dengan error handling
    window.confirmDelete = function(id, title) {
        swal({
            title: "Hapus Berita?",
            text: `Apakah Anda yakin ingin menghapus berita: ${title}?`,
            icon: "warning",
            buttons: {
                cancel: {
                    text: "Batal",
                    value: null,
                    visible: true
                },
                confirm: {
                    text: "Ya, Hapus!",
                    value: true,
                    className: "btn-danger"
                }
            },
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                const form = document.getElementById(`delete-form-${id}`);
                
                // Show loading state
                swal({
                    title: "Menghapus...",
                    text: "Mohon tunggu sebentar",
                    buttons: false,
                    closeOnClickOutside: false,
                    closeOnEsc: false
                });

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        '_method': 'DELETE',
                        '_token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Refresh the page instead of using performSearch
                        window.location.reload();
                    } else {
                        throw new Error(data.message || "Gagal menghapus berita");
                    }
                })
                .catch(error => {
                    console.error('Delete Error:', error);
                    swal({
                        title: "Error!",
                        text: "Gagal menghapus berita. Silakan coba lagi.",
                        icon: "error"
                    });
                });
            }
        });
    };

    // PERBAIKAN: Form submission dengan error handling
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const url = isEditMode ? `/news/${editId}` : '/news';
        
        if (isEditMode) {
            formData.append('_method', 'PUT');
        }

        // Clear previous errors
        document.querySelectorAll('.invalid-feedback').forEach(el => {
            el.classList.add('d-none');
            el.textContent = '';
        });
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        // Disable submit button
        const submitBtn = this.querySelector('#submitBtn');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Menyimpan...';

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => {
                    console.error('Submit Response:', text);
                    // Try to parse as JSON
                    try {
                        const json = JSON.parse(text);
                        throw { status: response.status, data: json };
                    } catch(e) {
                        throw { status: response.status, text: text };
                    }
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                $(modal).modal('hide');
                swal({
                    title: "Berhasil!",
                    text: isEditMode ? "Data berhasil diperbarui." : "Data berhasil ditambahkan.",
                    icon: "success",
                    timer: 3000,
                    buttons: false
                }).then(() => {
                    performSearch(searchInput.value);
                });
            }
        })
        .catch(error => {
            console.error('Submit Error:', error);
            
            if (error.status === 422 && error.data && error.data.errors) {
                const errors = error.data.errors;
                Object.keys(errors).forEach(field => {
                    const element = document.getElementById(field);
                    const errorDisplay = document.getElementById(`${field}-error`);
                    if (element && errorDisplay) {
                        element.classList.add('is-invalid');
                        errorDisplay.classList.remove('d-none');
                        errorDisplay.textContent = errors[field][0];
                    }
                });
                swal("Error", "Terdapat kesalahan pada inputan Anda!", "error");
            } else if (error.status === 419) {
                swal("Error", "Session expired. Silakan refresh halaman.", "error");
            } else if (error.text) {
                swal("Error", "Terjadi kesalahan pada server. Cek console untuk detail.", "error");
            } else {
                swal("Error", "Terjadi kesalahan: " + (error.message || 'Unknown error'), "error");
            }
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = isEditMode ? 'Update Berita' : 'Simpan Berita';
        });
    });

    // Search functionality
    let searchTimeout;
    
    function updateSearchButtons(query) {
        if (query !== '') {
            clearSearch.style.display = 'block';
            searchButton.style.display = 'none';
        } else {
            clearSearch.style.display = 'none';
            searchButton.style.display = 'block';
        }
    }

    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        updateSearchButtons(query);
        
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300);
    });

    searchButton.addEventListener('click', function() {
        performSearch(searchInput.value);
    });

    clearSearch.addEventListener('click', function() {
        searchInput.value = '';
        updateSearchButtons('');
        performSearch('');
    });

    // PERBAIKAN: Search dengan error handling yang lebih baik
    function performSearch(query) {
        tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Mencari data...</td></tr>';
        
        fetch(`/news?q=${encodeURIComponent(query)}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(response => {
            if (response.success && response.data) {
                updateTable(response.data);
            } else {
                throw new Error('Format data tidak valid');
            }
        })
        .catch(error => {
            console.error('Search Error:', error);
            tableBody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">
                Gagal memuat data: ${error.message}. 
                <button onclick="performSearch('${query}')" class="btn btn-sm btn-info ml-2">
                    <i class="fas fa-sync"></i> Coba lagi
                </button>
            </td></tr>`;
        });
    }

    function updateTable(items) {
        if (!Array.isArray(items) || items.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Tidak ada data berita</td></tr>';
            return;
        }

        const rows = items.map((item, index) => {
            const imageHtml = item.image 
                ? `<img src="/storage/${item.image}" alt="${item.title}" class="rounded mr-3" style="width: 64px; height: 48px; object-fit: cover;">` 
                : `<div class="bg-light d-flex align-items-center justify-content-center mr-3 rounded" style="width: 64px; height: 48px;"><i class="fas fa-image text-secondary"></i></div>`;

            // Escape title untuk mencegah XSS dan masalah quote
            const escapedTitle = (item.title || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');

            return `
                <tr>
                    <td class="text-center">${index + 1}</td>
                    <td>
                        <div class="media align-items-center">
                            ${imageHtml}
                            <div class="media-body">
                                <h6 class="mb-1">${item.title || ''}</h6>
                                <div class="mb-1">
                                    <span class="badge badge-info">${item.category || ''}</span>
                                </div>
                                ${item.excerpt ? `<small class="text-muted">${item.excerpt.substring(0, 50)}</small>` : ''}
                            </div>
                        </div>
                    </td>
                    <td class="text-center align-middle">
                        <span class="badge badge-${item.is_published ? 'success' : 'warning'}">
                            ${item.is_published ? 'Terbit' : 'Draft'}
                        </span>
                    </td>
                    <td class="text-center align-middle">
                        <div class="small">
                            <div class="mb-1">${formatDate(item.published_at) || '-'}</div>
                            <div class="text-muted">${item.user?.name || 'N/A'}</div>
                            <div class="text-muted small">${item.view_count || 0} views</div>
                        </div>
                    </td>
                    <td class="text-center align-middle">
                        <button class="btn btn-sm btn-primary btn-action mr-1 btn-edit"
                            data-id="${item.id}" data-toggle="tooltip" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </button>
                        <form id="delete-form-${item.id}" action="/news/${item.id}" method="POST" style="display:inline;">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="button" class="btn btn-sm btn-danger btn-action" data-toggle="tooltip"
                                title="Delete" onclick="confirmDelete('${item.id}', '${escapedTitle}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            `;
        }).join('');

        tableBody.innerHTML = rows;
        
        // Reinitialize tooltips jika menggunakan Bootstrap tooltip
        $('[data-toggle="tooltip"]').tooltip();
    }

    // Helper function to format date
    function formatDate(dateString) {
        if (!dateString) return null;
        
        // If date is already in the correct format (from server-side formatting)
        if (typeof dateString === 'string' && dateString.match(/^\d{2} \w{3} \d{4}$/)) {
            return dateString;
        }
        
        // If date is in ISO format (2025-10-30T09:05:35.000000Z)
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return dateString; // Return original if invalid date
        
        const options = { day: '2-digit', month: 'short', year: 'numeric' };
        return date.toLocaleDateString('id-ID', options);
    }

    // Make functions available globally
    window.performSearch = performSearch;
    window.formatDate = formatDate;
});
</script>
@endpush