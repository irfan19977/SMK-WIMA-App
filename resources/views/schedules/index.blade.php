@extends('layouts.app')

@section('content')

<div class="card">
    <div class="card-header">
        <h4>Daftar Jadwal Pelajaran</h4>
        <!-- Ganti bagian form search dengan ini -->
        <div class="card-header-action">
            <div class="input-group">
                <a href="{{ route('schedules.create') }}" class="btn btn-primary" data-toggle="tooltip"
                    style="margin-right: 10px;" title="Tambah Data"><i class="fas fa-plus"></i></a>
                <input type="text" class="form-control" placeholder="Cari Jadwal (Nama, Kelas, Guru)" 
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
                        <th class="text-center">No.</th>
                        <th>Hari</th>
                        <th>Kelas</th>
                        <th>Pelajaran</th>
                        <th>Guru</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($schedules as $schedule)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $schedule->day }}</td>
                        <td>{{ $schedule->classRoom->name }}</td>
                        <td>{{ $schedule->subject->name }}</td>
                        <td>{{ $schedule->teacher->name }}</td>
                        <td>{{ $schedule->start_time }}</td>
                        <td>{{ $schedule->end_time }}</td>
                        <td>
                            <a href="{{ route('schedules.edit', $schedule->id) }}" class="btn btn-primary btn-action mr-1"
                                data-toggle="tooltip" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </a>

                            <form id="delete-form-{{ $schedule->id }}" action="{{ route('schedules.destroy', $schedule->id) }}"
                                method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-action" data-toggle="tooltip"
                                    title="Delete" onclick="confirmDelete('{{ $schedule->id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center">Tidak ada data guru</td>
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
            
            const currentPage = {{ $schedules->currentPage() }};
            const perPage = {{ $schedules->perPage() }};
            
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
                    const teacherId = this.dataset.id;
                    const isChecked = this.checked;
                    const descriptionElement = this.closest('.custom-switch').querySelector('.custom-switch-description');
                    
                    // Create form data
                    const formData = new FormData();
                    formData.append('_token', csrfToken);
                    
                    // Show loading indicator
                    descriptionElement.textContent = 'Memperbarui...';
                    
                    fetch(`/schedules/${teacherId}/toggle-active`, {
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
    const clearButton = document.getElementById('clear-search');
    const searchButton = document.getElementById('search-button');
    let searchTimeout;

    // Fungsi untuk melakukan pencarian
    function performSearch(query) {
        // Tampilkan loading
        tableBody.innerHTML = '<tr><td colspan="8" class="text-center">Mencari data...</td></tr>';
        
        // Kirim request AJAX
        fetch(`{{ route('schedules.index') }}?q=${encodeURIComponent(query)}`, {
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
            updateTable(data.schedules, data.currentPage, data.perPage);
        })
        .catch(error => {
            console.error('Error:', error);
            tableBody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Terjadi kesalahan saat mencari data</td></tr>';
        });
    }

    // Fungsi untuk update tabel
    function updateTable(schedules, currentPage = 1, perPage = 10) {
        if (schedules.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="8" class="text-center">Tidak ada data jadwal</td></tr>';
            return;
        }

        let html = '';
        schedules.forEach((schedule, index) => {
            const number = (currentPage - 1) * perPage + index + 1;
            
            html += `
                <tr>
                    <td class="text-center">${number}</td>
                    <td>${schedule.day}</td>
                    <td>${schedule.class_room ? schedule.class_room.name : '-'}</td>
                    <td>${schedule.subject ? schedule.subject.name : '-'}</td>
                    <td>${schedule.teacher ? schedule.teacher.name : '-'}</td>
                    <td>${schedule.start_time}</td>
                    <td>${schedule.end_time}</td>
                    <td>
                        <a href="/schedules/${schedule.id}/edit" class="btn btn-primary btn-action mr-1"
                            data-toggle="tooltip" title="Edit">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        <form id="delete-form-${schedule.id}" action="/schedules/${schedule.id}"
                            method="POST" style="display:inline;">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="button" class="btn btn-danger btn-action" data-toggle="tooltip"
                                title="Delete" onclick="confirmDelete('${schedule.id}')">
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
        
        // Show/hide buttons based on input
        if (query !== '') {
            clearButton.style.display = 'block';
            searchButton.style.display = 'none';
        } else {
            clearButton.style.display = 'none';
            searchButton.style.display = 'block';
        }
        
        // Set timeout baru untuk menghindari terlalu banyak request
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 300); // Delay 300ms
    });

    // Event listener untuk tombol clear
    clearButton.addEventListener('click', function() {
        searchInput.value = '';
        searchInput.focus();
        
        // Hide clear button, show search button
        clearButton.style.display = 'none';
        searchButton.style.display = 'block';
        
        // Trigger search untuk load semua data
        performSearch('');
    });
    
    // Event listener untuk tombol search (optional)
    searchButton.addEventListener('click', function() {
        const query = searchInput.value.trim();
        performSearch(query);
    });
    
    // Initialize button visibility
    clearButton.style.display = 'none';
    searchButton.style.display = 'block';
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
