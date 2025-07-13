@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Pengaturan Jam Sekolah</h4>
        <div class="card-header-action">
            <div class="input-group">
                <button class="btn btn-primary" id="btn-create" data-toggle="tooltip"
                    style="margin-right: 10px;" title="Tambah Data">
                    <i class="fas fa-plus"></i>
                </button>
                <input type="text" class="form-control" placeholder="Cari Hari" 
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
                        <th>Hari</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($settings as $setting)
                    <tr class="text-center">
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $setting->day }}</td>
                        <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $setting->start_time)->format('H:i') }}</td>
                        <td>{{ \Carbon\Carbon::createFromFormat('H:i:s', $setting->end_time)->format('H:i') }}</td>
                        <td>
                            <button type="button" class="btn btn-primary btn-action btn-edit mr-1"
                                data-toggle="tooltip" title="Edit"
                                data-id="{{ $setting->id }}">
                                <i class="fas fa-pencil-alt"></i>
                            </button>

                            <form id="delete-form-{{ $setting->id }}" action="{{ route('setting-schedule.destroy', $setting->id) }}"
                                method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-action" data-toggle="tooltip"
                                    title="Delete" onclick="confirmDelete('{{ $setting->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data Jam Masuk/Pulang</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal untuk Create/Edit -->
<div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-labelledby="scheduleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleModalLabel">Tambah Jam Masuk/Pulang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="scheduleForm">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="day">Hari <span class="text-danger">*</span></label>
                        <select class="form-control" id="day" name="day" required>
                            <option value="">-- Pilih Hari --</option>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                        </select>
                        <div class="invalid-feedback d-none" id="day-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="start_time">Jam Masuk <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" id="start_time" name="start_time" required>
                        <div class="invalid-feedback d-none" id="start_time-error"></div>
                    </div>
                    <div class="form-group">
                        <label for="end_time">Jam Pulang <span class="text-danger">*</span></label>
                        <input type="time" class="form-control" id="end_time" name="end_time" required>
                        <div class="invalid-feedback d-none" id="end_time-error"></div>
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

        // Fungsi untuk melakukan pencarian
        function performSearch(query) {
            // Tampilkan loading
            tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Mencari data...</td></tr>';
            
            // Kirim request AJAX
            fetch(`{{ route('setting-schedule.index') }}?q=${encodeURIComponent(query)}`, {
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
                updateTable(data.settings);
            })
            .catch(error => {
                console.error('Error:', error);
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Terjadi kesalahan saat mencari data</td></tr>';
            });
        }

        // Fungsi untuk update tabel
        function updateTable(settings) {
            if (settings.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Tidak ada data Jam Masuk/Pulang</td></tr>';
                return;
            }

            let html = '';
            settings.forEach((setting, index) => {
                const number = index + 1;
                const startTime = new Date(`1970-01-01T${setting.start_time}`).toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                const endTime = new Date(`1970-01-01T${setting.end_time}`).toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                
                html += `
                    <tr class="text-center">
                        <td>${number}</td>
                        <td>${setting.day}</td>
                        <td>${startTime}</td>
                        <td>${endTime}</td>
                        <td>
                            <button type="button" class="btn btn-primary btn-action btn-edit mr-1"
                                data-toggle="tooltip" title="Edit"
                                data-id="${setting.id}">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            <form id="delete-form-${setting.id}" action="/setting-schedule/${setting.id}"
                                method="POST" style="display:inline;">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="button" class="btn btn-danger btn-action" data-toggle="tooltip"
                                    title="Delete" onclick="confirmDelete('${setting.id}')">
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
        const modal = $('#scheduleModal');
        const form = document.getElementById('scheduleForm');
        const modalTitle = document.getElementById('scheduleModalLabel');
        const submitBtn = document.getElementById('submitBtn');

        // Create button event
        document.getElementById('btn-create').addEventListener('click', function() {
            resetForm();
            isEditMode = false;
            editId = null;
            modalTitle.textContent = 'Tambah Jam Masuk/Pulang';
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
            fetch(`/setting-schedule/${id}/edit`, {
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
                    document.getElementById('day').value = data.schedule.day;
                    document.getElementById('start_time').value = data.schedule.start_time;
                    document.getElementById('end_time').value = data.schedule.end_time;
                    
                    modal.modal('show');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                swal("Error", "Gagal memuat data", "error");
            });
        }

        // Form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const url = isEditMode ? `/setting-schedule/${editId}` : '/setting-schedule';
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
                console.error('Error:', error);
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