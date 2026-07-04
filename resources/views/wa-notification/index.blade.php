@extends('layouts.master')

@section('title')
    Notifikasi WhatsApp
@endsection

@section('page-title')
    Notifikasi WhatsApp
@endsection

@section('css')
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('body')
    <body data-sidebar="colored">
@endsection

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-md-3">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-2">Total Notifikasi</p>
                        <h4 class="mb-0" id="stat-total">{{ $stats['total'] }}</h4>
                    </div>
                    <div class="mini-stat-icon avatar-sm align-self-center rounded-circle bg-primary">
                        <span class="avatar-title">
                            <i class="mdi mdi-whatsapp font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-2">Terkirim</p>
                        <h4 class="mb-0 text-success" id="stat-sent">{{ $stats['sent'] }}</h4>
                    </div>
                    <div class="mini-stat-icon avatar-sm align-self-center rounded-circle bg-success">
                        <span class="avatar-title">
                            <i class="mdi mdi-check-circle font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-2">Gagal</p>
                        <h4 class="mb-0 text-danger" id="stat-failed">{{ $stats['failed'] }}</h4>
                    </div>
                    <div class="mini-stat-icon avatar-sm align-self-center rounded-circle bg-danger">
                        <span class="avatar-title">
                            <i class="mdi mdi-close-circle font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card mini-stats-wid">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-2">Hari Ini</p>
                        <h4 class="mb-0 text-info" id="stat-today">{{ $stats['today'] }}</h4>
                    </div>
                    <div class="mini-stat-icon avatar-sm align-self-center rounded-circle bg-info">
                        <span class="avatar-title">
                            <i class="mdi mdi-calendar-today font-size-24"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Configuration Status -->
<div class="row mb-3">
    <div class="col-12">
        @if(!$isConfigured)
            <div class="alert alert-warning d-flex align-items-center">
                <i class="mdi mdi-alert-circle me-2 font-size-20"></i>
                <div>
                    <strong>Fonnte API belum dikonfigurasi!</strong> 
                    Tambahkan <code>FONNTE_TOKEN</code> dan set <code>FONNTE_ENABLED=true</code> di file <code>.env</code> Anda.
                    Dapatkan token di <a href="https://fonnte.com" target="_blank">fonnte.com</a>
                </div>
            </div>
        @elseif(!$isEnabled)
            <div class="alert alert-info d-flex align-items-center">
                <i class="mdi mdi-information me-2 font-size-20"></i>
                <div>
                    <strong>Notifikasi WhatsApp dinonaktifkan.</strong> 
                    Set <code>FONNTE_ENABLED=true</code> di file <code>.env</code> untuk mengaktifkan.
                </div>
            </div>
        @else
            <div class="alert alert-success d-flex align-items-center">
                <i class="mdi mdi-check-circle me-2 font-size-20"></i>
                <strong>Notifikasi WhatsApp aktif dan terkonfigurasi.</strong>
            </div>
        @endif
    </div>
</div>

<div class="row">
    <!-- Actions -->
    <div class="col-lg-4">
        <!-- Test Connection -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="mdi mdi-connection me-1"></i> Test Koneksi</h5>
                <div class="mb-3">
                    <label class="form-label">Nomor HP (untuk test)</label>
                    <input type="text" class="form-control" id="test-phone" placeholder="08xxxxxxxxxx">
                </div>
                <button class="btn btn-primary w-100" id="btn-test" onclick="testConnection()">
                    <i class="mdi mdi-send me-1"></i> Kirim Test
                </button>
            </div>
        </div>

        <!-- Send Custom -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="mdi mdi-message-text me-1"></i> Kirim Pesan Custom</h5>
                <div class="mb-3">
                    <label class="form-label">Nomor HP</label>
                    <input type="text" class="form-control" id="custom-phone" placeholder="08xxxxxxxxxx">
                </div>
                <div class="mb-3">
                    <label class="form-label">Pesan</label>
                    <textarea class="form-control" id="custom-message" rows="4" placeholder="Tulis pesan..."></textarea>
                </div>
                <button class="btn btn-success w-100" onclick="sendCustom()">
                    <i class="mdi mdi-whatsapp me-1"></i> Kirim Pesan
                </button>
            </div>
        </div>

        <!-- Broadcast -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="mdi mdi-broadcast me-1"></i> Broadcast ke Orang Tua</h5>
                <div class="mb-3">
                    <label class="form-label">Kelas (opsional)</label>
                    <select class="form-select" id="broadcast-class">
                        <option value="">Semua Kelas</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pesan Broadcast</label>
                    <textarea class="form-control" id="broadcast-message" rows="4" placeholder="Tulis pesan broadcast..."></textarea>
                </div>
                <button class="btn btn-warning w-100" onclick="sendBroadcast()">
                    <i class="mdi mdi-send-circle me-1"></i> Kirim Broadcast
                </button>
            </div>
        </div>
    </div>

    <!-- Log Table -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="card-title mb-0"><i class="mdi mdi-history me-1"></i> Log Notifikasi</h5>
                    <button class="btn btn-sm btn-outline-danger" onclick="clearLogs()">
                        <i class="mdi mdi-delete-sweep me-1"></i> Hapus Log Lama
                    </button>
                </div>

                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <input type="text" class="form-control form-control-sm" id="filter-search" placeholder="Cari...">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select form-select-sm" id="filter-type">
                            <option value="">Semua Tipe</option>
                            <option value="attendance">Kehadiran</option>
                            <option value="late">Keterlambatan</option>
                            <option value="alpha">Alpha</option>
                            <option value="permission">Izin</option>
                            <option value="exam_result">Hasil Ujian</option>
                            <option value="broadcast">Broadcast</option>
                            <option value="custom">Custom</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select form-select-sm" id="filter-status">
                            <option value="">Semua Status</option>
                            <option value="sent">Terkirim</option>
                            <option value="failed">Gagal</option>
                            <option value="error">Error</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-sm btn-primary w-100" onclick="loadLogs()">
                            <i class="mdi mdi-magnify"></i> Filter
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0" id="log-table">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 50px">#</th>
                                <th>No. HP</th>
                                <th>Pesan</th>
                                <th>Tipe</th>
                                <th>Status</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody id="log-tbody">
                            @forelse($logs as $i => $log)
                                <tr>
                                    <td>{{ $logs->firstItem() + $i }}</td>
                                    <td><code>{{ $log->phone }}</code></td>
                                    <td>
                                        <span class="d-inline-block text-truncate" style="max-width: 200px;" 
                                              data-bs-toggle="tooltip" title="{{ Str::limit($log->message, 200) }}">
                                            {{ Str::limit($log->message, 50) }}
                                        </span>
                                    </td>
                                    <td><span class="badge {{ $log->type_badge }}">{{ $log->type_label }}</span></td>
                                    <td><span class="badge {{ $log->status_badge }}">{{ ucfirst($log->status) }}</span></td>
                                    <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="mdi mdi-whatsapp display-4 d-block mb-2"></i>
                                        Belum ada log notifikasi
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
    function testConnection() {
        const phone = document.getElementById('test-phone').value;
        if (!phone) {
            Swal.fire('Error', 'Masukkan nomor HP terlebih dahulu', 'error');
            return;
        }

        const btn = document.getElementById('btn-test');
        btn.disabled = true;
        btn.innerHTML = '<i class="mdi mdi-loading mdi-spin me-1"></i> Mengirim...';

        fetch('{{ route("wa-notification.test") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ phone: phone })
        })
        .then(r => r.json())
        .then(data => {
            Swal.fire(data.success ? 'Berhasil!' : 'Gagal', data.message, data.success ? 'success' : 'error');
        })
        .catch(err => Swal.fire('Error', 'Terjadi kesalahan', 'error'))
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="mdi mdi-send me-1"></i> Kirim Test';
        });
    }

    function sendCustom() {
        const phone = document.getElementById('custom-phone').value;
        const message = document.getElementById('custom-message').value;
        if (!phone || !message) {
            Swal.fire('Error', 'Nomor HP dan pesan harus diisi', 'error');
            return;
        }

        Swal.fire({
            title: 'Kirim Pesan?',
            text: `Kirim pesan ke ${phone}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kirim',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route("wa-notification.send-custom") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ phone, message })
                })
                .then(r => r.json())
                .then(data => {
                    Swal.fire(data.success ? 'Terkirim!' : 'Gagal', data.message, data.success ? 'success' : 'error');
                    if (data.success) {
                        document.getElementById('custom-message').value = '';
                        location.reload();
                    }
                })
                .catch(err => Swal.fire('Error', 'Terjadi kesalahan', 'error'));
            }
        });
    }

    function sendBroadcast() {
        const classId = document.getElementById('broadcast-class').value;
        const message = document.getElementById('broadcast-message').value;
        if (!message) {
            Swal.fire('Error', 'Pesan broadcast harus diisi', 'error');
            return;
        }

        Swal.fire({
            title: 'Kirim Broadcast?',
            text: classId ? 'Kirim ke semua orang tua di kelas terpilih?' : 'Kirim ke SEMUA orang tua?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kirim',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title: 'Mengirim broadcast...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });

                fetch('{{ route("wa-notification.broadcast") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ message, class_id: classId })
                })
                .then(r => r.json())
                .then(data => {
                    Swal.fire('Selesai!', data.message, 'success');
                    document.getElementById('broadcast-message').value = '';
                    location.reload();
                })
                .catch(err => Swal.fire('Error', 'Terjadi kesalahan', 'error'));
            }
        });
    }

    function clearLogs() {
        Swal.fire({
            title: 'Hapus Log Lama?',
            input: 'select',
            inputOptions: {
                '7': 'Lebih dari 7 hari',
                '30': 'Lebih dari 30 hari',
                '90': 'Lebih dari 90 hari',
            },
            inputPlaceholder: 'Pilih periode',
            showCancelButton: true,
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('{{ route("wa-notification.clear-logs") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ older_than: result.value })
                })
                .then(r => r.json())
                .then(data => {
                    Swal.fire('Berhasil', data.message, 'success');
                    location.reload();
                })
                .catch(err => Swal.fire('Error', 'Terjadi kesalahan', 'error'));
            }
        });
    }

    function loadLogs() {
        const params = new URLSearchParams({
            q: document.getElementById('filter-search').value,
            type: document.getElementById('filter-type').value,
            status: document.getElementById('filter-status').value,
        });
        window.location.href = '{{ route("wa-notification.index") }}?' + params.toString();
    }

    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(el) { return new bootstrap.Tooltip(el); });
    });
</script>
@endsection
