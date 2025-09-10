@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Daftar Ekstrakurikuler</h4>
        <div class="card-header-action">
            <div class="input-group">
                <button class="btn btn-primary" id="btn-create" data-toggle="tooltip"
                    style="margin-right: 10px;" title="Tambah Data">
                    <i class="fas fa-plus"></i>
                </button>
                <input type="text" class="form-control" placeholder="Cari Asrama (Nama)" 
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
                        <th>Nama Ekstrakurikuler</th>
                        <th>Pengurus</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ekstrakurikulers as $ekstrakurikuler)
                    <tr class="text-center">
                        <td class="text-center">{{ ($ekstrakurikulers->currentPage() - 1) * $ekstrakurikulers->perPage() + $loop->iteration }}</td>
                        <td><a href="{{ route('ekstrakurikuler.show', $ekstrakurikuler->id) }}" class="text-secondery font-weight-bold">{{ $ekstrakurikuler->name }}</a></td>
                        <td>{{ $ekstrakurikuler->teacher ? $ekstrakurikuler->teacher->name : '-' }}</td>
                        <td>
                            <button class="btn btn-primary btn-action mr-1 btn-edit"
                                data-id="{{ $ekstrakurikuler->id }}" data-toggle="tooltip" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </button>

                            <form id="delete-form-{{ $ekstrakurikuler->id }}" action="{{ route('ekstrakurikuler.destroy', $ekstrakurikuler->id) }}"
                                method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-action" data-toggle="tooltip"
                                    title="Delete" onclick="confirmDelete('{{ $ekstrakurikuler->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data ekstrakurikuler</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal untuk Create/Edit -->
<div class="modal fade" id="ekstrakurikulerModal" tabindex="-1" role="dialog" aria-labelledby="ekstrakurikulerModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ekstrakurikulerModalLabel">Tambah Ekstrakurikuler</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="ekstrakurikulerForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nama Ekstrakurikuler <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Masukkan nama ekstrakurikuler" required>
                        <div class="invalid-feedback d-none" id="name-error"></div>
                    </div>
                    {{-- buatkan untuk memilih guru --}}
                    <div class="form-group">
                        <label for="teacher_id">Pengajar <span class="text-danger">*</span></label>
                        <select class="form-control select2" id="teacher_id" name="teacher_id" required>
                            <option value="">-- Pilih Pengajar --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback d-none" id="teacher_id-error"></div>
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

@push('scripts')
    <script>
        let isEditMode = false;
        let editId = null;

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

                    // Opsi 1: Menggunakan FormData (Recommended)
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
                                // Reload table data
                                performSearch(document.getElementById('search-input').value);
                            });
                        } else {
                            swal("Gagal", data.message || "Terjadi kesalahan saat menghapus data.", "error");
                        }
                    })
                    .catch(error => {
                        swal("Gagal", "Terjadi kesalahan saat menghapus data.", "error");
                    });
                }
            });
        }

        function renumberTableRows() {
            const tableBody = document.querySelector('#sortable-table tbody');
            const rows = tableBody.querySelectorAll('tr');
            
            const currentPage = {{ $ekstrakurikulers->currentPage() }};
            const perPage = {{ $ekstrakurikulers->perPage() }};
            
            rows.forEach((row, index) => {
                const numberCell = row.querySelector('td:first-child');
                if (numberCell) {
                    numberCell.textContent = (currentPage - 1) * perPage + index + 1;
                }
            });
        }

        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('input[name="q"]');
            const tableBody = document.querySelector('#sortable-table tbody');
            let searchTimeout;

            // Fungsi untuk melakukan pencarian
            function performSearch(query) {
                // Tampilkan loading
                tableBody.innerHTML = '<tr><td colspan="3" class="text-center">Mencari data...</td></tr>';
                
                // Kirim request AJAX
                fetch(`{{ route('ekstrakurikuler.index') }}?q=${encodeURIComponent(query)}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Update tabel dengan data hasil pencarian
                    updateTable(data.ekstrakurikulers, data.currentPage, data.perPage);
                })
                .catch(error => {
                    tableBody.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Terjadi kesalahan saat mencari data</td></tr>';
                });
            }

            // Fungsi untuk update tabel
            function updateTable(ekstrakurikulers, currentPage = 1, perPage = 10) {
                if (ekstrakurikulers.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="4" class="text-center">Tidak ada data ekstrakurikuler</td></tr>';
                    return;
                }

                let html = '';
                ekstrakurikulers.forEach((ekstrakurikuler, index) => {
                    const number = (currentPage - 1) * perPage + index + 1;
                    
                    // Handle teacher name dengan fallback
                    let teacherName = '-';
                    if (ekstrakurikuler.teacher && ekstrakurikuler.teacher.name) {
                        teacherName = ekstrakurikuler.teacher.name;
                    } else if (ekstrakurikuler.teacher && ekstrakurikuler.teacher.user && ekstrakurikuler.teacher.user.name) {
                        teacherName = ekstrakurikuler.teacher.user.name;
                    }
                    
                    html += `
                        <tr class="text-center">
                            <td>${number}</td>
                            <td><a href="/ekstrakurikuler/${ekstrakurikuler.id}" class="text-secondery font-weight-bold">${ekstrakurikuler.name}</a></td>
                            <td>${teacherName}</td>
                            <td>
                                <button class="btn btn-primary btn-action mr-1 btn-edit"
                                    data-id="${ekstrakurikuler.id}" data-toggle="tooltip" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </button>
                                <form id="delete-form-${ekstrakurikuler.id}" action="/ekstrakurikuler/${ekstrakurikuler.id}"
                                    method="POST" style="display:inline;">
                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="button" class="btn btn-danger btn-action" data-toggle="tooltip"
                                        title="Delete" onclick="confirmDelete('${ekstrakurikuler.id}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    `;
                });
                
                tableBody.innerHTML = html;
                
                // Re-initialize edit buttons
                initializeEditButtons();
            }

            // Event listener untuk input pencarian
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                // Clear timeout sebelumnya
                clearTimeout(searchTimeout);
                
                // Set timeout baru untuk menghindari terlalu banyak request
                searchTimeout = setTimeout(() => {
                    performSearch(query);
                }, 300); // Delay 300ms
            });

            // Make performSearch available globally
            window.performSearch = performSearch;
        });

        // Clear search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const clearButton = document.getElementById('clear-search');
            const searchButton = document.getElementById('search-button');
            const searchInput = document.getElementById('search-input');
            
            // Event listener untuk tombol clear
            clearButton.addEventListener('click', function() {
                searchInput.value = '';
                searchInput.focus();
                
                // Hide clear button, show search button
                clearButton.style.display = 'none';
                searchButton.style.display = 'block';
                
                // Trigger search
                window.performSearch('');
            });
            
            // Show/hide buttons based on input
            searchInput.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    clearButton.style.display = 'block';
                    searchButton.style.display = 'none';
                } else {
                    clearButton.style.display = 'none';
                    searchButton.style.display = 'block';
                }
            });
            
            // Event listener untuk tombol search
            searchButton.addEventListener('click', function() {
                window.performSearch(searchInput.value);
            });
            
            // Initialize button visibility
            clearButton.style.display = 'none';
            searchButton.style.display = 'block';
        });

        // Modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            const modal = $('#ekstrakurikulerModal');
            const form = document.getElementById('ekstrakurikulerForm');
            const modalTitle = document.getElementById('ekstrakurikulerModalLabel');
            const submitBtn = document.getElementById('submitBtn');

            // Initialize Select2 after modal is shown
            modal.on('shown.bs.modal', function() {
                $('.select2').select2({
                    dropdownParent: $('#ekstrakurikulerModal'),
                    width: '100%'
                });
            });

            // Create button event
            document.getElementById('btn-create').addEventListener('click', function() {
                resetForm();
                isEditMode = false;
                editId = null;
                modalTitle.textContent = 'Tambah Asrama';
                submitBtn.textContent = 'Simpan';
                modal.modal('show');
            });

            // Edit button events (delegated)
            function initializeEditButtons() {
                document.querySelectorAll('.btn-edit').forEach(btn => {
                    btn.addEventListener('click', function() {
                        const id = this.getAttribute('data-id');
                        loadEditData(id);
                    });
                });
            }

            // Initialize edit buttons on page load
            initializeEditButtons();

            // Load edit data
            function loadEditData(id) {
                fetch(`/ekstrakurikuler/${id}/edit`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        resetForm();
                        isEditMode = true;
                        editId = id;
                        modalTitle.textContent = data.title;
                        submitBtn.textContent = 'Update';
                        
                        // Fill form with data
                        document.getElementById('name').value = data.ekstrakurikulers.name;
                        
                        const teacherSelect = document.getElementById('teacher_id');
                        if (data.ekstrakurikulers.teacher_id) {
                            teacherSelect.value = data.ekstrakurikulers.teacher_id;
                            
                            // Trigger change event untuk Select2
                            $(teacherSelect).trigger('change');
                        }

                        modal.modal('show');
                    }
                })
                .catch(error => {
                    swal("Error", "Gagal memuat data", "error");
                });
            }

            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                const url = isEditMode ? `/ekstrakurikuler/${editId}` : '/ekstrakurikuler';
                const method = 'POST';
                
                if (isEditMode) {
                    formData.append('_method', 'PUT');
                }

                // Disable submit button
                submitBtn.disabled = true;
                submitBtn.textContent = 'Menyimpan...';

                fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => response.json())
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
                        // Reload table data
                        window.performSearch(document.getElementById('search-input').value);
                        });
                    } else {
                        // Handle validation errors
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
                    swal("Error", "Terjadi kesalahan pada server", "error");
                })
                .finally(() => {
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.textContent = isEditMode ? 'Update' : 'Simpan';
                });
            });

            // Reset form function
            function resetForm() {
                form.reset();
                // Clear validation errors
                document.querySelectorAll('.invalid-feedback').forEach(el => {
                    el.classList.add('d-none');
                    el.textContent = '';
                });
                document.querySelectorAll('.is-invalid').forEach(el => {
                    el.classList.remove('is-invalid');
                });
            }

            // Make initializeEditButtons available globally
            window.initializeEditButtons = initializeEditButtons;
        });
    </script>
@endpush