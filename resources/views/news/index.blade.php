@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Kelola Berita</h4>
        <div class="card-header-action">
            @can('classes.create')    
                <div class="input-group">
                    <button class="btn btn-primary" id="btn-create" data-toggle="modal" data-target="#newsModal" style="margin-right: 10px;" title="Tambah Berita">
                        <i class="fas fa-plus"></i> Tambah Berita
                    </button>
                    <input type="text" class="form-control" placeholder="Cari judul/kategori..." 
                        wire:model.debounce.500ms="search" autocomplete="off">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-primary" style="margin-top: 1px;">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            @endcan
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
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
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}" class="img-thumbnail" style="width: 80px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="width: 80px; height: 60px;">
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
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-info" onclick="viewNews({{ $item->id }})" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @can('news.edit')
                                <button type="button" class="btn btn-sm btn-primary" onclick="editNews({{ $item->id }})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                @endcan
                                @can('news.delete')
                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteNews({{ $item->id }})" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endcan
                            </div>
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
        <div class="mt-3">
            {{ $news->links() }}
        </div>
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
            <form id="newsForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="news_id" name="id">
                <div class="modal-body">
                    <div class="card-body">
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Title</label>
                      <div class="col-sm-12 col-md-7">
                        <input type="text" class="form-control">
                      </div>
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Category</label>
                      <div class="col-sm-12 col-md-7">
                        <select class="form-control selectric">
                          <option>Tech</option>
                          <option>News</option>
                          <option>Political</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Content</label>
                      <div class="col-sm-12 col-md-7">
                        <textarea class="summernote-simple"></textarea>
                      </div>
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Thumbnail</label>
                      <div class="col-sm-12 col-md-7">
                        <div id="image-preview" class="image-preview">
                          <label for="image-upload" id="image-label">Choose File</label>
                          <input type="file" name="image" id="image-upload" />
                        </div>
                      </div>
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Tags</label>
                      <div class="col-sm-12 col-md-7">
                        <input type="text" class="form-control inputtags">
                      </div>
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Status</label>
                      <div class="col-sm-12 col-md-7">
                        <select class="form-control selectric">
                          <option>Publish</option>
                          <option>Draft</option>
                          <option>Pending</option>
                        </select>
                      </div>
                    </div>
                    <div class="form-group row mb-4">
                      <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                      <div class="col-sm-12 col-md-7">
                        <button class="btn btn-primary">Create Post</button>
                      </div>
                    </div>
                  </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal untuk Preview -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewTitle">Judul Berita</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="previewContent">
                <!-- Content will be loaded via AJAX -->
                <div class="text-center my-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Memuat...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<style>
    .note-editor.note-frame .note-editing-area .note-editable {
        min-height: 200px;
    }
    #image-preview {
        max-width: 100%;
        max-height: 200px;
        margin-top: 10px;
        display: none;
    }
    #image-preview img {
        max-width: 100%;
        max-height: 200px;
        object-fit: contain;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Inisialisasi Summernote
    $(document).ready(function() {
        $('#content').summernote({
            height: 300,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']],
            ],
            callbacks: {
                onImageUpload: function(files) {
                    uploadImage(files[0]);
                }
            }
        });

        // Preview image before upload
        $('#image').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').html('<img src="' + e.target.result + '" class="img-fluid">').show();
                }
                reader.readAsDataURL(file);
            }
        });

        // Handle form submission
        $('#newsForm').on('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const isEdit = $('#news_id').val() !== '';
            const url = isEdit ? "/news/" + $('#news_id').val() : "/news";
            const method = isEdit ? 'POST' : 'POST';
            
            // Add _method for Laravel's form method spoofing
            if (isEdit) {
                formData.append('_method', 'PUT');
            }

            // Show loading state
            const submitBtn = $('#submitBtn');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

            // Clear previous errors
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');

            // Send AJAX request
            $.ajax({
                url: url,
                type: method,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    showAlert('success', 'Berhasil!', response.message);
                    $('#newsModal').modal('hide');
                    // Reload the page to see changes
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(field => {
                            const errorMessage = errors[field][0];
                            $(`#${field}`).addClass('is-invalid');
                            $(`#${field}-error`).text(errorMessage).removeClass('d-none');
                        });
                    } else {
                        showAlert('error', 'Error!', 'Terjadi kesalahan. Silakan coba lagi.');
                    }
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });
    });

    // Function to handle image upload in Summernote
    function uploadImage(file) {
        const formData = new FormData();
        formData.append('image', file);
        
        $.ajax({
            url: '/news/upload-image',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                const image = $('<img>').attr('src', response.url);
                $('#content').summernote('insertNode', image[0]);
            },
            error: function(xhr) {
                showAlert('error', 'Error!', 'Gagal mengunggah gambar. ' + (xhr.responseJSON?.message || ''));
            }
        });
    }

    // Function to view news
    function viewNews(id) {
        $('#previewModal').modal('show');
        $('#previewTitle').text('Memuat...');
        $('#previewContent').html(`
            <div class="text-center my-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Memuat...</span>
                </div>
            </div>
        `);

        $.get(`/news/${id}`, function(response) {
            $('#previewTitle').text(response.title);
            $('#previewContent').html(`
                <div class="text-center mb-4">
                    <img src="${response.image ? '/storage/' + response.image : 'https://via.placeholder.com/800x400?text=No+Image'}" 
                         class="img-fluid rounded" alt="${response.title}">
                </div>
                <div class="mb-3">
                    <span class="badge badge-info">${response.category}</span>
                    <span class="text-muted ml-2">
                        <i class="far fa-user"></i> ${response.user?.name || 'Admin'} | 
                        <i class="far fa-calendar-alt"></i> ${new Date(response.published_at).toLocaleDateString('id-ID', { 
                            day: 'numeric', 
                            month: 'long', 
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        })}
                    </span>
                </div>
                <div class="news-content">
                    ${response.content}
                </div>
            `);
        }).fail(function() {
            showAlert('error', 'Error!', 'Gagal memuat detail berita.');
            $('#previewModal').modal('hide');
        });
    }

    // Function to edit news
    function editNews(id) {
        $.get(`/news/${id}/edit`, function(response) {
            $('#newsModalLabel').text('Edit Berita');
            $('#news_id').val(response.id);
            $('#title').val(response.title);
            $('#category').val(response.category);
            $('#content').summernote('code', response.content);
            $('#published_at').val(response.published_at ? new Date(response.published_at).toISOString().slice(0, 16) : '');
            $('#is_published').prop('checked', response.is_published);
            
            // Show image preview if exists
            if (response.image) {
                $('#image-preview')
                    .html(`<img src="/storage/${response.image}" class="img-fluid">`)
                    .show();
            } else {
                $('#image-preview').hide().html('');
            }
            
            $('#newsModal').modal('show');
        }).fail(function() {
            showAlert('error', 'Error!', 'Gagal memuat data berita.');
        });
    }

    // Function to delete news
    function deleteNews(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Anda tidak akan dapat mengembalikan data ini!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/news/${id}`,
                    type: 'DELETE',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        showAlert('success', 'Dihapus!', response.message);
                        // Reload the page after a short delay
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    },
                    error: function(xhr) {
                        showAlert('error', 'Error!', 'Gagal menghapus berita. ' + (xhr.responseJSON?.message || ''));
                    }
                });
            }
        });
    }

    // Function to show create form
    function createNews() {
        $('#newsForm')[0].reset();
        $('#news_id').val('');
        $('#newsModalLabel').text('Tambah Berita Baru');
        $('#content').summernote('code', '');
        $('#image-preview').hide().html('');
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('');
        $('#newsModal').modal('show');
    }

    // Helper function to show alerts
    function showAlert(icon, title, text) {
        Swal.fire({
            icon: icon,
            title: title,
            text: text,
            timer: icon === 'success' ? 2000 : null,
            showConfirmButton: icon !== 'success'
        });
    }

    // Event listener for create button
    $('#btn-create').on('click', function() {
        createNews();
    });
</script>
@endpush