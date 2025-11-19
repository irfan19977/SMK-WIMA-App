@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Galeri Kegiatan per Jurusan</h4>
        <div class="card-header-action">
            <div class="input-group">
                <button class="btn btn-primary" id="btn-create" data-toggle="tooltip"
                    style="margin-right: 10px;" title="Tambah Galeri">
                    <i class="fas fa-plus"></i>
                </button>
                <input type="text" class="form-control" placeholder="Cari galeri..." 
                    name="q" id="search-input" autocomplete="off">
                <div class="input-group-btn">
                    <button type="button" class="btn btn-primary" id="search-button" style="margin-top: 1px;">
                        <i class="fas fa-search"></i>
                    </button>
                    <button type="button" class="btn btn-primary" id="clear-search" title="Clear Search" style="display: none; margin-top: 1px;">
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
                        <th>No.</th>
                        <th>Konten</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($galleries as $item)
                    <tr>
                        <td class="text-center">{{ ($galleries->currentPage() - 1) * $galleries->perPage() + $loop->iteration }}</td>
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
                                        <span class="badge badge-info">{{ $item->jurusan }}</span>
                                    </div>
                                    @if($item->description)
                                        <small class="text-muted">{{ Str::limit(strip_tags($item->description), 80) }}</small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="small">
                                <div class="mb-1">{{ $item->created_at ? $item->created_at->format('d M Y') : '-' }}</div>
                                <div class="text-muted">Update: {{ $item->updated_at ? $item->updated_at->format('d M Y') : '-' }}</div>
                            </div>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-primary btn-action mr-1 btn-edit"
                                data-id="{{ $item->id }}" data-toggle="tooltip" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </button>

                            <form id="delete-form-{{ $item->id }}" action="{{ route('galleries.destroy', $item->id) }}"
                                method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-action" data-toggle="tooltip"
                                    title="Delete" onclick="confirmDelete('{{ $item->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data galeri</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal untuk Create/Edit -->
<div class="modal fade" id="galleryModal" tabindex="-1" role="dialog" aria-labelledby="galleryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="galleryModalLabel">Tambah Galeri</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="galleryForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="jurusan">Nama Jurusan <span class="text-danger">*</span></label>
                        <select class="form-control" id="jurusan" name="jurusan" required>
                            <option value="">-- Pilih Jurusan --</option>
                            <option value="Teknik Kendaraan Ringan">Teknik Kendaraan Ringan (TKR)</option>
                            <option value="Teknik Bisnis Sepeda Motor">Teknik Bisnis Sepeda Motor (TBSM)</option>
                            <option value="Teknik Kimia Industri">Teknik Kimia Industri (TKI)</option>
                            <option value="Teknik Komputer dan Jaringan">Teknik Komputer dan Jaringan (TKJ)</option>
                        </select>
                        <div class="invalid-feedback d-none" id="jurusan-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="title">Judul Gambar <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="title" name="title" required>
                        <div class="invalid-feedback d-none" id="title-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="description">Deskripsi Gambar (maks. 10 Kata) <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                        <small class="form-text text-muted">Jangan melebihi 10 kata!!</small>
                        <div class="invalid-feedback d-none" id="description-error"></div>
                    </div>

                    <div class="form-group">
                        <label for="image">Gambar <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <small class="form-text text-muted">Ukuran maksimal 200KB. Format: JPG, PNG, GIF. Rasio gambar akan di-crop menjadi 4:3 (bingkai fixed, geser & zoom gambar untuk menyesuaikan)</small>
                        <div class="invalid-feedback d-none" id="image-error"></div>
                    </div>

                    <!-- Cropper Container -->
                    <div id="crop-container" style="display: none;">
                        <div class="form-group">
                            <label>Crop Gambar (Rasio 4:3)</label>
                            <div style="max-height: 400px; overflow: hidden;">
                                <img id="image-cropper" style="max-width: 100%;">
                            </div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-secondary" id="btn-zoom-in">
                                    <i class="fas fa-search-plus"></i> Zoom In
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary" id="btn-zoom-out">
                                    <i class="fas fa-search-minus"></i> Zoom Out
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary" id="btn-rotate-left">
                                    <i class="fas fa-undo"></i> Putar Kiri
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary" id="btn-rotate-right">
                                    <i class="fas fa-redo"></i> Putar Kanan
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary" id="btn-flip-horizontal">
                                    <i class="fas fa-arrows-alt-h"></i> Flip Horizontal
                                </button>
                                <button type="button" class="btn btn-sm btn-secondary" id="btn-flip-vertical">
                                    <i class="fas fa-arrows-alt-v"></i> Flip Vertical
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" id="btn-cancel-crop">
                                    <i class="fas fa-times"></i> Batal
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Preview hasil crop -->
                    <div id="image-preview" class="mt-2" style="display: none;">
                        <label>Preview Hasil Crop:</label>
                        <div>
                            <img src="" alt="Preview" class="img-thumbnail" style="max-width: 300px;">
                        </div>
                    </div>

                    <input type="hidden" id="cropped-image" name="cropped_image">
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<style>
    /* Hilangkan titik-titik resize di pojok bingkai */
    #crop-container .cropper-view-box,
    #crop-container .cropper-face {
        border-radius: 0;
    }
    
    /* Hilangkan semua point resize */
    #crop-container .cropper-point {
        display: none !important;
    }
    
    /* Hilangkan line resize */
    #crop-container .cropper-line {
        display: none !important;
    }
    
    #crop-container {
        margin-top: 15px;
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 5px;
    }
    
    /* Buat cursor jadi move untuk menunjukkan gambar bisa digeser */
    #crop-container .cropper-canvas,
    #crop-container .cropper-crop-box {
        cursor: move !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
    let isEditMode = false;
    let editId = null;
    let cropper = null;

    // Helper function untuk escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, m => map[m]);
    }

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
                const form = document.getElementById(`delete-form-${id}`);
                const url = form.action;

                const formData = new FormData();
                formData.append('_method', 'DELETE');
                formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

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
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        throw new Error('Server tidak mengembalikan JSON');
                    }
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        swal({
                            title: "Berhasil!",
                            text: "Data berhasil dihapus.",
                            icon: "success",
                            timer: 3000,
                            buttons: false
                        }).then(() => {
                            performSearch(document.getElementById('search-input').value);
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

    // Search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="q"]');
        const tableBody = document.querySelector('#sortable-table tbody');
        let searchTimeout;

        function performSearch(query) {
            tableBody.innerHTML = '<tr><td colspan="4" class="text-center">Mencari data...</td></tr>';
            
            fetch(`{{ route('galleries.index') }}?q=${encodeURIComponent(query)}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Server tidak mengembalikan JSON');
                }
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                updateTable(data.data, data.pagination.current_page, data.pagination.per_page);
            })
            .catch(error => {
                console.error('Error:', error);
                tableBody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Terjadi kesalahan saat mencari data</td></tr>';
            });
        }

        function updateTable(galleries, currentPage = 1, perPage = 10) {
            if (galleries.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="4" class="text-center">Tidak ada data galeri</td></tr>';
                return;
            }

            let html = '';
            galleries.forEach((item, index) => {
                const number = (currentPage - 1) * perPage + index + 1;
                const imageHtml = item.image 
                    ? `<img src="/storage/${escapeHtml(item.image)}" alt="${escapeHtml(item.title)}" class="rounded mr-3" style="width: 64px; height: 48px; object-fit: cover;">`
                    : `<div class="bg-light d-flex align-items-center justify-content-center mr-3 rounded" style="width: 64px; height: 48px;"><i class="fas fa-image text-secondary"></i></div>`;
                
                const description = item.description ? `<small class="text-muted">${escapeHtml((item.description || '').substring(0, 80))}</small>` : '';
                
                html += `
                    <tr>
                        <td class="text-center">${number}</td>
                        <td>
                            <div class="media align-items-center">
                                ${imageHtml}
                                <div class="media-body">
                                    <h6 class="mb-1">${escapeHtml(item.title || '')}</h6>
                                    <div class="mb-1"><span class="badge badge-info">${escapeHtml(item.jurusan || '')}</span></div>
                                    ${description}
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="small">
                                <div class="mb-1">${escapeHtml(item.created_at || '-')}</div>
                                <div class="text-muted">${item.updated_at ? 'Update: ' + escapeHtml(item.updated_at) : ''}</div>
                            </div>
                        </td>
                        <td class="text-center">
                            <button class="btn btn-primary btn-action mr-1 btn-edit"
                                data-id="${item.id}" data-toggle="tooltip" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <form id="delete-form-${item.id}" action="/galleries/${item.id}"
                                method="POST" style="display:inline;">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="button" class="btn btn-danger btn-action" data-toggle="tooltip"
                                    title="Delete" onclick="confirmDelete('${item.id}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                `;
            });
            
            tableBody.innerHTML = html;
            initializeEditButtons();
        }

        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch(query);
            }, 300);
        });

        window.performSearch = performSearch;
    });

    // Clear search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const clearButton = document.getElementById('clear-search');
        const searchButton = document.getElementById('search-button');
        const searchInput = document.getElementById('search-input');
        
        clearButton.addEventListener('click', function() {
            searchInput.value = '';
            searchInput.focus();
            clearButton.style.display = 'none';
            searchButton.style.display = 'block';
            window.performSearch('');
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
            window.performSearch(searchInput.value);
        });
        
        clearButton.style.display = 'none';
        searchButton.style.display = 'block';
    });

    // Image Cropper functionality
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        const imageCropper = document.getElementById('image-cropper');
        const cropContainer = document.getElementById('crop-container');
        const imagePreview = document.getElementById('image-preview');
        const croppedImageInput = document.getElementById('cropped-image');

        // Handle image selection
        imageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Destroy existing cropper
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }

                // Hide preview and show crop container
                imagePreview.style.display = 'none';
                cropContainer.style.display = 'block';

                // Load image for cropping
                const reader = new FileReader();
                reader.onload = function(event) {
                    imageCropper.src = event.target.result;
                    
                    // Initialize Cropper with 4:3 aspect ratio (fixed)
                    cropper = new Cropper(imageCropper, {
                        aspectRatio: 4 / 3, // Fixed 4:3 ratio
                        viewMode: 1,
                        dragMode: 'move',
                        autoCropArea: 1,
                        restore: false,
                        guides: true,
                        center: true,
                        highlight: false,
                        cropBoxMovable: false, // Bingkai tidak bisa dipindah
                        cropBoxResizable: false, // Bingkai tidak bisa diresize
                        toggleDragModeOnDblclick: false,
                        zoomable: true, // Bisa zoom gambar
                        zoomOnWheel: true, // Zoom dengan scroll mouse
                        zoomOnTouch: true, // Zoom dengan touch
                        ready: function() {
                            // Auto crop when ready
                            updateCroppedImage();
                        },
                        crop: function() {
                            // Update preview on crop change
                            updateCroppedImage();
                        }
                    });
                };
                reader.readAsDataURL(file);
            }
        });

        // Rotate left button
        document.getElementById('btn-rotate-left').addEventListener('click', function() {
            if (cropper) {
                cropper.rotate(-45);
            }
        });

        // Rotate right button
        document.getElementById('btn-rotate-right').addEventListener('click', function() {
            if (cropper) {
                cropper.rotate(45);
            }
        });

        // Zoom in button
        document.getElementById('btn-zoom-in').addEventListener('click', function() {
            if (cropper) {
                cropper.zoom(0.1);
            }
        });

        // Zoom out button
        document.getElementById('btn-zoom-out').addEventListener('click', function() {
            if (cropper) {
                cropper.zoom(-0.1);
            }
        });

        // Flip horizontal button
        document.getElementById('btn-flip-horizontal').addEventListener('click', function() {
            if (cropper) {
                const imageData = cropper.getImageData();
                cropper.scaleX(imageData.scaleX === 1 ? -1 : 1);
            }
        });

        // Flip vertical button
        document.getElementById('btn-flip-vertical').addEventListener('click', function() {
            if (cropper) {
                const imageData = cropper.getImageData();
                cropper.scaleY(imageData.scaleY === 1 ? -1 : 1);
            }
        });

        // Cancel crop button
        document.getElementById('btn-cancel-crop').addEventListener('click', function() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            imageInput.value = '';
            cropContainer.style.display = 'none';
            imagePreview.style.display = 'none';
            croppedImageInput.value = '';
        });

        // Update cropped image preview
        function updateCroppedImage() {
            if (cropper) {
                const canvas = cropper.getCroppedCanvas({
                    width: 800, // Output width
                    height: 600, // Output height (4:3 ratio)
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high',
                });

                if (canvas) {
                    canvas.toBlob(function(blob) {
                        const reader = new FileReader();
                        reader.onloadend = function() {
                            const base64data = reader.result;
                            croppedImageInput.value = base64data;
                            
                            // Show preview
                            imagePreview.querySelector('img').src = base64data;
                            imagePreview.style.display = 'block';
                        };
                        reader.readAsDataURL(blob);
                    }, 'image/jpeg', 0.95);
                }
            }
        }
    });

    // Modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        const modal = $('#galleryModal');
        const form = document.getElementById('galleryForm');
        const modalTitle = document.getElementById('galleryModalLabel');
        const submitBtn = document.getElementById('submitBtn');

        document.getElementById('btn-create').addEventListener('click', function() {
            resetForm();
            isEditMode = false;
            editId = null;
            modalTitle.textContent = 'Tambah Galeri';
            submitBtn.textContent = 'Simpan';
            modal.modal('show');
        });

        function initializeEditButtons() {
            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    loadEditData(id);
                });
            });
        }

        initializeEditButtons();

        function loadEditData(id) {
            fetch(`/galleries/${id}/edit`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Server tidak mengembalikan JSON');
                }
                return response.json();
            })
            .then(data => {
                resetForm();
                isEditMode = true;
                editId = id;
                modalTitle.textContent = 'Edit Galeri';
                submitBtn.textContent = 'Update';
                
                document.getElementById('jurusan').value = data.jurusan;
                document.getElementById('title').value = data.title;
                document.getElementById('description').value = data.description || '';

                if (data.image) {
                    document.querySelector('#image-preview img').src = '/storage/' + data.image;
                    document.getElementById('image-preview').style.display = 'block';
                }
                document.getElementById('image').required = false;
                
                modal.modal('show');
            })
            .catch(error => {
                console.error('Error:', error);
                swal("Error", "Gagal memuat data: " + error.message, "error");
            });
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const url = isEditMode ? `/galleries/${editId}` : '/galleries';
            const method = 'POST';
            
            // Hapus file asli jika ada gambar yang di-crop
            if (document.getElementById('cropped-image').value) {
                formData.delete('image'); // Hapus file asli
                formData.append('is_cropped', '1'); // Tandai bahwa gambar sudah di-crop
            }
            
            if (isEditMode) {
                formData.append('_method', 'PUT');
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Menyimpan...';

            fetch(url, {
                method: method,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    throw new Error('Server tidak mengembalikan JSON');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    modal.modal('hide');
                    swal({
                        title: "Berhasil!",
                        text: isEditMode ? "Data berhasil diperbarui." : "Data berhasil ditambahkan.",
                        icon: "success",
                        timer: 3000,
                        buttons: false
                    }).then(() => {
                        window.performSearch(document.getElementById('search-input').value);
                    });
                } else {
                    if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            const errorElement = document.getElementById(key + '-error');
                            const inputElement = document.getElementById(key);
                            if (errorElement && inputElement) {
                                errorElement.textContent = data.errors[key][0];
                                errorElement.classList.remove('d-none');
                                inputElement.classList.add('is-invalid');
                            }
                        });
                    } else {
                        swal("Gagal", data.message || "Terjadi kesalahan", "error");
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                swal("Error", "Terjadi kesalahan: " + error.message, "error");
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = isEditMode ? 'Update' : 'Simpan';
            });
        });

        function resetForm() {
            form.reset();
            document.querySelectorAll('.invalid-feedback').forEach(el => {
                el.classList.add('d-none');
                el.textContent = '';
            });
            document.querySelectorAll('.is-invalid').forEach(el => {
                el.classList.remove('is-invalid');
            });
            document.getElementById('image-preview').style.display = 'none';
            document.getElementById('crop-container').style.display = 'none';
            document.getElementById('cropped-image').value = '';
            document.getElementById('image').required = true;
            
            // Destroy cropper if exists
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        }

        // Reset form when modal is closed
        modal.on('hidden.bs.modal', function() {
            resetForm();
        });

        window.initializeEditButtons = initializeEditButtons;
    });
</script>
@endpush