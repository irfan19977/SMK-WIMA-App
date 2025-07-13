<div wire:poll.2s="autoUpdateAttendances">
    <div class="table-responsive">
        <table class="table table-striped" id="sortable-table">
            <thead>
                <tr class="text-center">
                    <th>No.</th>
                    <th>Hari</th>
                    <th>Tanggal</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    {{-- <th>Absen Kedatangan</th> --}}
                    <th>Status Masuk</th>
                    {{-- <th>Absen Kepulangan</th> --}}
                    <th>Status Pulang</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="attendance-tbody">
                @forelse ($attendances as $index => $attendance)
                <tr class="text-center">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->date)->locale('id')->translatedFormat('l') }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->date)->format('d F Y') }}</td>
                    <td>{{ $attendance->student_name }}</td>
                    <td>{{ $attendance->class_name }}</td>
                    
                    <!-- Absen Kedatangan -->
                    {{-- <td>
                        @if($attendance->check_in)
                            {{ \Carbon\Carbon::parse($attendance->check_in)->format('H:i') }}
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td> --}}
                    
                    <!-- Status Masuk -->
                    <td>
                        @if($attendance->check_in_status)
                            @php
                                $statusClass = match($attendance->check_in_status) {
                                    'tepat' => 'success',
                                    'terlambat' => 'warning', 
                                    'izin' => 'info',
                                    'sakit' => 'secondary',
                                    'alpha' => 'danger',
                                    default => 'light'
                                };
                                
                                $statusText = match($attendance->check_in_status) {
                                    'tepat' => 'Tepat Waktu',
                                    'terlambat' => 'Terlambat',
                                    'izin' => 'Izin',
                                    'sakit' => 'Sakit', 
                                    'alpha' => 'Alpha',
                                    default => $attendance->check_in_status
                                };
                            @endphp
                            <span class="badge badge-{{ $statusClass }}">{{ $statusText }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    
                    <!-- Absen Kepulangan -->
                    {{-- <td>
                        @if($attendance->check_out)
                            {{ \Carbon\Carbon::parse($attendance->check_out)->format('H:i') }}
                        @else
                            <span class="text-muted">Belum Absen</span>
                        @endif
                    </td> --}}
                    
                    <!-- Status Pulang -->
                    <td>
                        @if($attendance->check_out_status)
                            @php
                                $statusClass = match($attendance->check_out_status) {
                                    'tepat' => 'success',
                                    'lebih_awal' => 'warning',
                                    'tidak_absen' => 'danger',
                                    'izin' => 'info',
                                    'sakit' => 'secondary', 
                                    'alpha' => 'danger',
                                    default => 'light'
                                };
                                
                                $statusText = match($attendance->check_out_status) {
                                    'tepat' => 'Tepat Waktu',
                                    'lebih_awal' => 'Lebih Awal',
                                    'tidak_absen' => 'Tidak Absen',
                                    'izin' => 'Izin',
                                    'sakit' => 'Sakit',
                                    'alpha' => 'Alpha',
                                    default => $attendance->check_out_status
                                };
                            @endphp
                            <span class="badge badge-{{ $statusClass }}">{{ $statusText }}</span>
                        @else
                            <span class="badge badge-secondary">Belum Absen</span>
                        @endif
                    </td>
                    
                    <!-- Aksi -->
                    <td>
                        @can('attendances.edit')    
                            <button type="button" class="btn btn-sm btn-primary btn-action btn-edit mr-1"
                                data-toggle="tooltip" title="Edit"
                                onclick="editAttendance('{{ $attendance->attendance_id }}')">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                        @endcan

                        <button type="button" class="btn btn-sm btn-info btn-action mr-1"
                            data-toggle="tooltip" title="Detail"
                            onclick="showAttendanceDetail('{{ $attendance->attendance_id }}')">
                            <i class="fas fa-eye"></i>
                        </button>

                        @can('attendances.edit')    
                            <button type="button" class="btn btn-sm btn-danger btn-action" 
                                data-toggle="tooltip" title="Delete" 
                                onclick="confirmDeleteLivewire('{{ $attendance->attendance_id }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center">Tidak ada data absensi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @push('scripts')
<script>
    // Function untuk delete dengan Livewire
    function confirmDeleteLivewire(id) {
        swal({
            title: "Apakah Anda Yakin?",
            text: "Data ini akan dihapus secara permanen!",
            icon: "warning",
            buttons: ['Tidak', 'Ya, Hapus'],
            dangerMode: true,
        }).then(function(isConfirm) {
            if (isConfirm) {
                @this.call('deleteAttendance', id);
            }
        });
    }

    // Listen untuk alert dari Livewire v3
    document.addEventListener('livewire:init', () => {
        Livewire.on('show-alert', (event) => {
            const data = event[0]; // Event data ada di index 0
            swal({
                title: data.title,
                text: data.message,
                icon: data.type,
                timer: data.type === 'success' ? 3000 : undefined,
                buttons: data.type === 'success' ? false : true
            });
        });
    });

    // Re-initialize tooltips setelah Livewire update
    document.addEventListener('livewire:init', function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    document.addEventListener('livewire:navigated', function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endpush
</div>