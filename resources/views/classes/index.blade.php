@extends('layouts.app')

@section('content')

<section class="section">
    <div class="card">
    <div class="card-header">
        <h4>Daftar Kelas</h4>
        <!-- Ganti bagian form search dengan ini -->
        <div class="card-header-action">
            <div class="input-group">
                <a href="{{ route('classes.create') }}" class="btn btn-primary" data-toggle="tooltip"
                    style="margin-right: 10px;" title="Tambah Data"><i class="fas fa-plus"></i></a>
                <input type="text" class="form-control" placeholder="Cari Siswa (Nama, NISN)" 
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
                        <th>Code</th>
                        <th>Grade</th>
                        <th>Jurusan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($classes as $class)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td><a href="{{ route('classes.show', $class->id) }}" class="text-secondery font-weight-bold">{{ $class->name }}</a></td>
                        <td>{{ $class->code }}</td>
                        <td>{{ $class->grade }}</td>
                        <td>{{ $class->major }}</td>
                        <td>
                            <a href="{{ route('classes.edit', $class->id) }}" class="btn btn-primary btn-action mr-1"
                                data-toggle="tooltip" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </a>

                            <form id="delete-form-{{ $class->id }}" action="{{ route('classes.destroy', $class->id) }}"
                                method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-action" data-toggle="tooltip"
                                    title="Delete" onclick="confirmDelete('{{ $class->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">Tidak ada data kelas</td>
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
</section>

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
            const tableBody = document.querySelector('#sortable-table tbody');
            const rows = tableBody.querySelectorAll('tr');
            
            const currentPage = {{ $classes->currentPage() }};
            const perPage = {{ $classes->perPage() }};
            
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
            const searchInput = document.querySelector('input[name="q"]');
            const tableBody = document.querySelector('#sortable-table tbody');
            let searchTimeout;

            // Fungsi untuk melakukan pencarian
            function performSearch(query) {
                // Tampilkan loading dengan colspan yang sesuai
                tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Mencari data...</td></tr>';
                
                // Kirim request AJAX
                fetch(`{{ route('classes.index') }}?q=${encodeURIComponent(query)}`, {
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
                    updateTable(data.classes, data.currentPage, data.perPage);
                })
                .catch(error => {
                    console.error('Error:', error);
                    tableBody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">Terjadi kesalahan saat mencari data</td></tr>';
                });
            }

            // Fungsi untuk update tabel
            function updateTable(classes, currentPage = 1, perPage = 10) {
                if (classes.length === 0) {
                    tableBody.innerHTML = '<tr><td colspan="6" class="text-center">Tidak ada data kelas</td></tr>';
                    return;
                }

                let html = '';
                classes.forEach((classItem, index) => {
                    const number = (currentPage - 1) * perPage + index + 1;
                    html += `
                        <tr>
                            <td class="text-center">${number}</td>
                            <td><a href="#" class="text-secondery font-weight-bold">${classItem.name}</a></td>
                            <td>${classItem.code}</td>
                            <td>${classItem.grade}</td>
                            <td>${classItem.major}</td>
                            <td>
                                <a href="/classes/${classItem.id}/edit" class="btn btn-primary btn-action mr-1"
                                    data-toggle="tooltip" title="Edit">
                                    <i class="fas fa-pencil-alt"></i>
                                </a>
                                <form id="delete-form-${classItem.id}" action="/classes/${classItem.id}"
                                    method="POST" style="display:inline;">
                                    <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="button" class="btn btn-danger btn-action" data-toggle="tooltip"
                                        title="Delete" onclick="confirmDelete('${classItem.id}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    `;
                });
                
                tableBody.innerHTML = html;
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
            
        });
    </script>

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
