<div wire:poll.2s="autoUpdateLessonAttendances">
    <div class="table-responsive">
        <table class="table table-striped" id="sortable-table">
            <thead>
                <tr class="text-center">
                    <th>No.</th>
                    <th>Hari</th>
                    <th>Tanggal</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Mata Pelajaran</th>
                    <th>Jam Masuk</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="lesson-attendance-tbody">
                @forelse ($lessonAttendances as $index => $lessonAttendance)
                <tr class="text-center">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($lessonAttendance->date)->locale('id')->translatedFormat('l') }}</td>
                    <td>{{ \Carbon\Carbon::parse($lessonAttendance->date)->format('d F Y') }}</td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="font-weight-bold">{{ $lessonAttendance->student_name }}</span>
                            @if(!empty($lessonAttendance->student_nisn))
                                <small class="text-muted">NISN: {{ $lessonAttendance->student_nisn }}</small>
                            @endif
                        </div>
                    </td>
                    <td>{{ $lessonAttendance->class_name }}</td>
                    <td>
                        <span class="badge badge-primary">{{ $lessonAttendance->subject_name }}</span>
                    </td>
                    <td>
                        @if($lessonAttendance->check_in)
                            <span class="font-weight-bold text-primary">
                                {{ \Carbon\Carbon::createFromFormat('H:i:s', $lessonAttendance->check_in)->format('H:i') }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($lessonAttendance->check_in_status)
                            @php
                                $statusClass = match($lessonAttendance->check_in_status) {
                                    'tepat' => 'success',
                                    'terlambat' => 'warning', 
                                    'izin' => 'info',
                                    'sakit' => 'secondary',
                                    'alpha' => 'danger',
                                    default => 'light'
                                };
                                
                                $statusText = match($lessonAttendance->check_in_status) {
                                    'tepat' => 'Tepat Waktu',
                                    'terlambat' => 'Terlambat',
                                    'izin' => 'Izin',
                                    'sakit' => 'Sakit', 
                                    'alpha' => 'Alpha',
                                    default => $lessonAttendance->check_in_status
                                };
                            @endphp
                            <span class="badge badge-{{ $statusClass }}">{{ $statusText }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    
                    <!-- Aksi -->
                    <td>
                        @can('lesson_attendances.edit')    
                            <button type="button" class="btn btn-sm btn-primary btn-action btn-edit mr-1"
                                data-toggle="tooltip" title="Edit"
                                onclick="editLessonAttendance('{{ $lessonAttendance->lesson_attendance_id }}')">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                        @endcan

                        <button type="button" class="btn btn-sm btn-info btn-action mr-1"
                            data-toggle="tooltip" title="Detail"
                            onclick="showLessonAttendanceDetail('{{ $lessonAttendance->lesson_attendance_id }}')">
                            <i class="fas fa-eye"></i>
                        </button>

                        @can('lesson_attendances.delete')    
                            <button type="button" class="btn btn-sm btn-danger btn-action" 
                                data-toggle="tooltip" title="Delete" 
                                onclick="confirmDeleteLessonAttendanceLivewire('{{ $lessonAttendance->lesson_attendance_id }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data absensi pelajaran</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @push('scripts')
<script>
    // Function untuk delete dengan Livewire
    function confirmDeleteLessonAttendanceLivewire(id) {
        swal({
            title: "Apakah Anda Yakin?",
            text: "Data absensi pelajaran ini akan dihapus secara permanen!",
            icon: "warning",
            buttons: ['Tidak', 'Ya, Hapus'],
            dangerMode: true,
        }).then(function(isConfirm) {
            if (isConfirm) {
                @this.call('deleteLessonAttendance', id);
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