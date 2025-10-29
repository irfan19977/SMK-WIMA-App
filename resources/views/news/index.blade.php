@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Kelola Berita</h4>
        <div class="card-header-action">
            @can('classes.create')    
                <div class="input-group">
                    <button class="btn btn-primary" id="btn-create" data-toggle="tooltip" 
                        style="margin-right: 10px;" title="Tambah Berita">
                        <i class="fas fa-plus"></i> Tambah Berita
                    </button>
                    <input type="text" class="form-control" placeholder="Cari judul/kategori..." 
                        id="search-input" autocomplete="off">
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
            @endcan
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped" id="news-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Gambar</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Penulis</th>
                        <th>Tanggal Terbit</th>
                        <th>Status</th>
                        <th>Dilihat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($news as $item)
                    <tr>
                        <td>{{ $loop->iteration + (($news->currentPage() - 1) * $news->perPage()) }}</td>
                        <td>
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" 
                                    class="img-thumbnail" style="width: 80px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" 
                                    style="width: 80px; height: 60px;">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $item->title }}</strong><br>
                            <small class="text-muted">{{ Str::limit($item->excerpt, 50) }}</small>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $item->category }}</span>
                        </td>
                        <td>{{ $item->user->name ?? 'N/A' }}</td>
                        <td>{{ $item->published_at ? $item->published_at->format('d M Y H:i') : '-' }}</td>
                        <td>
                            @if($item->is_published)
                                <span class="badge badge-success">Terbit</span>
                            @else
                                <span class="badge badge-warning">Draft</span>
                            @endif
                        </td>
                        <td>{{ $item->view_count ?? 0 }}</td>
                        <td>
                            <button class="btn btn-primary btn-action mr-1 btn-edit"
                                data-id="{{ $item->id }}" data-toggle="tooltip" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </button>

                            <button type="button" class="btn btn-danger btn-action btn-delete" 
                                data-id="{{ $item->id }}" 
                                data-title="{{ $item->title }}"
                                data-toggle="tooltip" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                            
                            <form id="delete-form-{{ $item->id }}" action="{{ route('news.destroy', $item->id) }}" 
                                method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Tidak ada data berita</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer">
        {{ $news->links() }}
    </div>
</div>

<!-- Modal untuk Create/Edit -->
<div class="modal fade" id="newsModal" tabindex="-1" role="dialog" aria-labelledby="newsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="newsModalLabel">Tulis Berita Baru</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="newsForm" enctype="multipart/form-data" method="POST">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">
                <input type="hidden" id="news_id" name="id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                    Judul <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-12 col-md-9">
                                    <input type="text" class="form-control" id="title" name="title" required>
                                    <div class="invalid-feedback" id="title-error"></div>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                    Gambar Sampul <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-12 col-md-9">
                                    <input type="file" name="image" id="image" class="form-control" 
                                        accept="image/jpeg,image/png,image/gif">
                                    <small class="form-text text-muted">Ukuran maksimal 5MB. Format: JPG, PNG, GIF</small>
                                    <div class="invalid-feedback" id="image-error"></div>
                                    <div id="image-preview" class="mt-2" style="display: none;">
                                        <img src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                    Kategori <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-12 col-md-9">
                                    <select class="form-control selectric" id="category" name="category" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        <option value="Pendidikan">Pendidikan</option>
                                        <option value="Kegiatan">Kegiatan</option>
                                        <option value="Pengumuman">Pengumuman</option>
                                        <option value="Prestasi">Prestasi</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                    <div class="invalid-feedback" id="category-error"></div>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                    Ringkasan
                                </label>
                                <div class="col-sm-12 col-md-9">
                                    <textarea class="form-control" id="excerpt" name="excerpt" rows="3" 
                                        placeholder="Ringkasan singkat berita (opsional)"></textarea>
                                    <div class="invalid-feedback" id="excerpt-error"></div>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                    Isi Berita <span class="text-danger">*</span>
                                </label>
                                <div class="col-sm-12 col-md-9">
                                    <textarea class="summernote" id="content" name="content" required></textarea>
                                    <div class="invalid-feedback" id="content-error"></div>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                    Status
                                </label>
                                <div class="col-sm-12 col-md-9">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" 
                                            id="is_published" name="is_published" value="1" checked>
                                        <label class="custom-control-label" for="is_published">Publikasikan</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-4">
                                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">
                                    Tag
                                </label>
                                <div class="col-sm-12 col-md-9">
                                    <input type="text" class="form-control inputtags" id="tags" name="tags" 
                                        placeholder="contoh: prestasi, lomba, juara">
                                    <small class="form-text text-muted">Pisahkan dengan koma</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">Simpan Berita</button>
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
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
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
            confirmKeys: [13, 44, 32],
            maxTags: 10,
            maxChars: 25,
            trimValue: true
        });

        // File input validation & preview
        $('#image').on('change', function() {
            const file = this.files[0];
            if (file) {
                // Validation
                if (file.size > 5 * 1024 * 1024) {
                    Swal.fire('Error', 'Ukuran file terlalu besar. Maksimal 5MB', 'error');
                    this.value = '';
                    return;
                }
                
                const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    Swal.fire('Error', 'Format file tidak didukung. Gunakan format JPG, PNG, atau GIF', 'error');
                    this.value = '';
                    return;
                }

                // Preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview img').attr('src', e.target.result);
                    $('#image-preview').show();
                }
                reader.readAsDataURL(file);
            }
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
            $('.invalid-feedback').text('');
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

        // Edit button click (Event Delegation)
        $(document).on('click', '.btn-edit', function() {
            const id = $(this).data('id');
            
            Swal.fire({
                title: 'Memuat data...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.get(`/news/${id}/edit`, function(response) {
                Swal.close();
                
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
            }).fail(function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Gagal memuat data berita: ' + (xhr.responseJSON?.message || 'Terjadi kesalahan')
                });
            });
        });

        // Delete button click (Event Delegation)
        $(document).on('click', '.btn-delete', function() {
            const id = $(this).data('id');
            const title = $(this).data('title');
            
            Swal.fire({
                title: 'Hapus Berita',
                html: `Apakah Anda yakin ingin menghapus berita: <br><b>${title}</b>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Menghapus...',
                        html: 'Sedang menghapus data berita',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const form = document.getElementById(`delete-form-${id}`);
                    const formData = new FormData(form);

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Berita berhasil dihapus',
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Gagal', data.message || 'Terjadi kesalahan saat menghapus berita', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Terjadi kesalahan saat menghapus berita', 'error');
                    });
                }
            });
        });

        // Form submission
        $('#newsForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const url = isEditMode ? `/news/${editId}` : '/news';
            
            Swal.fire({
                title: 'Mohon Tunggu!',
                html: 'Sedang memproses data...',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading()
                },
            });

            if (isEditMode) {
                formData.append('_method', 'PUT');
            }

            // Clear previous errors
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            // Disable submit button
            $('#submitBtn').prop('disabled', true);

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#newsModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.close();
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(field => {
                            const errorMessage = errors[field][0];
                            $(`#${field}`).addClass('is-invalid');
                            $(`#${field}-error`).text(errorMessage);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Terdapat kesalahan pada inputan Anda!'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan pada server!'
                        });
                    }
                },
                complete: function() {
                    $('#submitBtn').prop('disabled', false);
                }
            });
        });

        // Search functionality
        let searchTimeout;
        $('#search-input').on('input', function() {
            const query = $(this).value.trim();
            
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);

            // Toggle clear button
            if (query !== '') {
                $('#clear-search').show();
                $('#search-button').hide();
            } else {
                $('#clear-search').hide();
                $('#search-button').show();
            }
        });

        $('#search-button').on('click', function() {
            performSearch($('#search-input').val());
        });

        $('#clear-search').on('click', function() {
            $('#search-input').val('');
            $(this).hide();
            $('#search-button').show();
            performSearch('');
        });

        function performSearch(query) {
            const tableBody = $('#news-table tbody');
            tableBody.html('<tr><td colspan="9" class="text-center">Mencari data...</td></tr>');
            
            $.get('/news', {
                q: query,
                ajax: 1
            }, function(data) {
                updateTable(data.news, data.currentPage, data.perPage);
            }).fail(function() {
                tableBody.html('<tr><td colspan="9" class="text-center text-danger">Terjadi kesalahan saat mencari data</td></tr>');
            });
        }

        function updateTable(newsData, currentPage = 1, perPage = 10) {
            const tableBody = $('#news-table tbody');
            
            if (newsData.length === 0) {
                tableBody.html('<tr><td colspan="9" class="text-center">Tidak ada data berita</td></tr>');
                return;
            }

            let html = '';
            newsData.forEach((item, index) => {
                const number = (currentPage - 1) * perPage + index + 1;
                const imageHtml = item.image 
                    ? `<img src="/storage/${item.image}" alt="${item.title}" class="img-thumbnail" style="width: 80px; height: 60px; object-fit: cover;">`
                    : `<div class="bg-light d-flex align-items-center justify-content-center" style="width: 80px; height: 60px;"><i class="fas fa-image"></i></div>`;
                
                const statusBadge = item.is_published 
                    ? '<span class="badge badge-success">Terbit</span>'
                    : '<span class="badge badge-warning">Draft</span>';

                html += `
                    <tr>
                        <td>${number}</td>
                        <td>${imageHtml}</td>
                        <td>
                            <strong>${item.title}</strong><br>
                            <small class="text-muted">${item.excerpt ? item.excerpt.substring(0, 50) + '...' : ''}</small>
                        </td>
                        <td><span class="badge badge-info">${item.category}</span></td>
                        <td>${item.user?.name || 'N/A'}</td>
                        <td>${item.published_at || '-'}</td>
                        <td>${statusBadge}</td>
                        <td>${item.view_count || 0}</td>
                        <td>
                            <button class="btn btn-primary btn-action mr-1 btn-edit"
                                data-id="${item.id}" data-toggle="tooltip" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <button type="button" class="btn btn-danger btn-action btn-delete" 
                                data-id="${item.id}" 
                                data-title="${item.title}"
                                data-toggle="tooltip" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                            <form id="delete-form-${item.id}" action="/news/${item.id}" method="POST" style="display: none;">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                <input type="hidden" name="_method" value="DELETE">
                            </form>
                        </td>
                    </tr>
                `;
            });
            
            tableBody.html(html);
        }
    });
</script>
@endpush