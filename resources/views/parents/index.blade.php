@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Daftar Wali Murid</h4>
        <!-- Ganti bagian form search dengan ini -->
        <div class="card-header-action">
            <div class="input-group">
                <a href="{{ route('parents.create') }}" class="btn btn-primary" data-toggle="tooltip"
                    style="margin-right: 10px;" title="Tambah Data"><i class="fas fa-plus"></i></a>
                <input type="text" class="form-control" placeholder="Cari Wali (Nama, NISN)" 
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
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Nama Anak</th>
                        <th>NISN</th>
                        <th>Kelas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($parents as $parent)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td><a href="#" class="text-secondery font-weight-bold">{{ $parent->name }}</a></td>
                        <td>{{ $parent->user->email }}</td>
                        <td>{{ $parent->user->phone }}</td>
                        <td>{{ $parent->student->name ?? '-' }}</td>
                        <td>{{ $parent->student->nisn ?? '-' }}</td>
                        <td>?????</td>
                        <td>
                            <div class="form-group" style="margin-top: 25px">
                                <label class="custom-switch mt-2">
                                    <input type="checkbox" class="custom-switch-input toggle-active"
                                        data-id="{{ $parent->user->id }}"
                                        {{ $parent->user->status ? 'checked' : '' }}>
                                    <span class="custom-switch-indicator"></span>
                                    <span
                                        class="custom-switch-description">{{ $parent->user->status ? 'Aktif' : 'Diblokir' }}</span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <a href="{{ route('parents.edit', $parent->id) }}" class="btn btn-primary btn-action mr-1"
                                data-toggle="tooltip" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </a>

                            <form id="delete-form-{{ $parent->id }}" action="{{ route('parents.destroy', $parent->id) }}"
                                method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-action" data-toggle="tooltip"
                                    title="Delete" onclick="confirmDelete('{{ $parent->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">Tidak ada data wali siswa</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    {{-- @if ($schedules->hasPages())
    <div class="card-footer text-center">
      <nav class="d-inline-block">
        {{ $schedules->links() }}
    </nav>
</div>
@endif --}}
</div>
@endsection

@push('scripts')
    <script>
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

                fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    _method: 'DELETE'
                })
                })
                .then(response => response.json())
                .then(data => {
                if (data.success) {
                    swal({
                    title: "Berhasil!",
                    text: "Data berhasil dihapus.",
                    icon: "success",
                    timer: 3000,
                    buttons: false
                    }).then(() => {
                    // Hapus baris tabel
                    const rowToRemove = document.querySelector(`#delete-form-${id}`).closest('tr');
                    rowToRemove.remove();

                    // Perbarui nomor urut
                    renumberTableRows();
                    });
                } else {
                    swal("Gagal", "Terjadi kesalahan saat menghapus data.", "error");
                }
                });
            }
            });
        }

        function renumberTableRows() {
            const tableBody = document.querySelector('#sortable-table tbody');
            const rows = tableBody.querySelectorAll('tr');
            
            const currentPage = {{ $parents->currentPage() }};
            const perPage = {{ $parents->perPage() }};
            
            rows.forEach((row, index) => {
            const numberCell = row.querySelector('td:first-child');
            if (numberCell) {
                numberCell.textContent = (currentPage - 1) * perPage + index + 1;
            }
            });
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get the CSRF token from meta tag
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const toggleSwitches = document.querySelectorAll('.toggle-active');
            
            toggleSwitches.forEach(switchElement => {
                switchElement.addEventListener('change', function() {
                    const parentId = this.dataset.id;
                    const isChecked = this.checked;
                    const descriptionElement = this.closest('.custom-switch').querySelector('.custom-switch-description');
                    
                    // Create form data
                    const formData = new FormData();
                    formData.append('_token', csrfToken);
                    
                    // Show loading indicator
                    descriptionElement.textContent = 'Memperbarui...';
                    
                    fetch(`/parents/${parentId}/toggle-active`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: formData,
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Update UI
                            descriptionElement.textContent = data.is_active ? 'Aktif' : 'Diblokir';
                            
                            swal({
                                title: "Berhasil!",
                                text: data.message,
                                icon: "success",
                                timer: 3000,
                                buttons: false
                            });
                        } else {
                            // Restore original state
                            this.checked = !isChecked;
                            descriptionElement.textContent = isChecked ? 'Diblokir' : 'Aktif';
                            
                            swal("Gagal", "Gagal memperbarui status", "error");
                        }
                    })
                    .catch(error => {
                        // Restore original state
                        this.checked = !isChecked;
                        descriptionElement.textContent = isChecked ? 'Diblokir' : 'Aktif';
                        
                        console.error('Error:', error);
                        swal("Error", "Terjadi kesalahan pada server", "error");
                    });
                });
            });
        });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="q"]');
        const tableBody = document.querySelector('#sortable-table tbody');
        let searchTimeout;

        // Fungsi untuk melakukan pencarian
        function performSearch(query) {
            // Tampilkan loading
            tableBody.innerHTML = '<tr><td colspan="9" class="text-center">Mencari data...</td></tr>';
            
            // Kirim request AJAX
            fetch(`{{ route('parents.index') }}?q=${encodeURIComponent(query)}`, {
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
                updateTable(data.parents, data.currentPage, data.perPage);
            })
            .catch(error => {
                console.error('Error:', error);
                tableBody.innerHTML = '<tr><td colspan="9" class="text-center text-danger">Terjadi kesalahan saat mencari data</td></tr>';
            });
        }

        // Fungsi untuk update tabel
        function updateTable(parents, currentPage = 1, perPage = 10) {
            if (parents.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="9" class="text-center">Tidak ada data wali siswa</td></tr>';
                return;
            }

            let html = '';
            parents.forEach((parent, index) => {
                const number = (currentPage - 1) * perPage + index + 1;
                html += `
                    <tr>
                        <td class="text-center">${number}</td>
                        <td><a href="#" class="text-secondery font-weight-bold">${parent.name}</a></td>
                        <td>${parent.user.email}</td>
                        <td>${parent.user.phone || '-'}</td>
                        <td>${parent.student ? parent.student.name : '-'}</td>
                        <td>${parent.student ? parent.student.nisn : '-'}</td>
                        <td>?????</td>
                        <td>
                            <div class="form-group" style="margin-top: 25px">
                                <label class="custom-switch mt-2">
                                    <input type="checkbox" class="custom-switch-input toggle-active"
                                        data-id="${parent.user.id}"
                                        ${parent.user.status ? 'checked' : ''}>
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description">${parent.user.status ? 'Aktif' : 'Diblokir'}</span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <a href="/parents/${parent.id}/edit" class="btn btn-primary btn-action mr-1"
                                data-toggle="tooltip" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <form id="delete-form-${parent.id}" action="/parents/${parent.id}"
                                method="POST" style="display:inline;">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="button" class="btn btn-danger btn-action" data-toggle="tooltip"
                                    title="Delete" onclick="confirmDelete('${parent.id}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                `;
            });
            
            tableBody.innerHTML = html;
            
            // Re-initialize toggle switches untuk data baru
            initializeToggleSwitches();
        }

        // Event listener untuk input pencarian
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            // Clear timeout sebelumnya
            clearTimeout(searchTimeout);
            
            // Set timeout baru untuk menghindari terlalu banyak request
            searchTimeout = setTimeout(() => {
                if (query === '') {
                    // Jika search kosong, load semua data
                    performSearch('');
                } else {
                    // Lakukan pencarian
                    performSearch(query);
                }
            }, 300); // Delay 300ms
        });

        // Fungsi untuk inisialisasi toggle switches
        function initializeToggleSwitches() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const toggleSwitches = document.querySelectorAll('.toggle-active');
            
            // Remove existing event listeners to prevent duplicates
            toggleSwitches.forEach(switchElement => {
                const newElement = switchElement.cloneNode(true);
                switchElement.parentNode.replaceChild(newElement, switchElement);
            });
            
            // Add new event listeners
            const newToggleSwitches = document.querySelectorAll('.toggle-active');
            newToggleSwitches.forEach(switchElement => {
                switchElement.addEventListener('change', function() {
                    const parentId = this.dataset.id;
                    const isChecked = this.checked;
                    const descriptionElement = this.closest('.custom-switch').querySelector('.custom-switch-description');
                    
                    const formData = new FormData();
                    formData.append('_token', csrfToken);
                    
                    descriptionElement.textContent = 'Memperbarui...';
                    
                    fetch(`/parents/${parentId}/toggle-active`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: formData,
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            descriptionElement.textContent = data.is_active ? 'Aktif' : 'Diblokir';
                            
                            swal({
                                title: "Berhasil!",
                                text: data.message,
                                icon: "success",
                                timer: 3000,
                                buttons: false
                            });
                        } else {
                            this.checked = !isChecked;
                            descriptionElement.textContent = isChecked ? 'Diblokir' : 'Aktif';
                            
                            swal("Gagal", "Gagal memperbarui status", "error");
                        }
                    })
                    .catch(error => {
                        this.checked = !isChecked;
                        descriptionElement.textContent = isChecked ? 'Diblokir' : 'Aktif';
                        
                        console.error('Error:', error);
                        swal("Error", "Terjadi kesalahan pada server", "error");
                    });
                });
            });
        }
    });
</script>

// Script untuk Clear Search Button
<script>
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
            
            // Trigger input event untuk menjalankan pencarian kosong
            const event = new Event('input', { bubbles: true });
            searchInput.dispatchEvent(event);
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
        
        // Optional: Event listener untuk tombol search (jika ingin bisa diklik)
        searchButton.addEventListener('click', function() {
            const event = new Event('input', { bubbles: true });
            searchInput.dispatchEvent(event);
        });
        
        // Initialize button visibility
        clearButton.style.display = 'none';
        searchButton.style.display = 'block';
    });
</script>
@endpush
