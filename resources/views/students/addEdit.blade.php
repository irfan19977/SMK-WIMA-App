@extends('layouts.master')
@section('title')
    {{ isset($student) ? __('index.edit_student') : __('index.add_student') }}
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-title')
    {{ isset($student) ? __('index.edit_student') : __('index.add_student') }}
@endsection
@section('body')

    <body data-sidebar="colored">
@endsection
@section('content')
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">{{ isset($student) ? __('index.edit_student') : __('index.add_student') }}</h4>
                        <p class="card-title-desc">{{ isset($student) ? __('index.edit_student_description') : __('index.add_student_description') }} {{ __('index.form_validation_description') }}.</p>
                        
                        <form class="was-validated" action="{{ isset($student) ? route('students.update', $student->id) : route('students.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @isset($student)
                                @method('PUT')
                            @endisset
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">{{ __('index.full_name') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name', isset($student) ? $student->name : '') }}" 
                                            placeholder="{{ __('index.enter_full_name') }}" required>
                                        <div class="invalid-feedback">
                                            {{ __('index.please_enter_full_name') }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">{{ __('index.email') }} <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ old('email', isset($student) ? $student->user->email : '') }}" 
                                            placeholder="{{ __('index.enter_email') }}" required>
                                        <div class="invalid-feedback">
                                            {{ __('index.please_enter_valid_email') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nik" class="form-label">{{ __('index.nik') }}</label>
                                        <input type="number" class="form-control" id="nik" name="nik"
                                            value="{{ old('nik', isset($student) ? $student->nik : '') }}" 
                                            placeholder="{{ __('index.enter_16_digit_nik') }}" maxlength="16">
                                        <div class="form-text">Kosongkan jika tidak ada</div>
                                        <div class="invalid-feedback">
                                            {{ __('index.please_enter_16_digit_nik') }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nisn" class="form-label">{{ __('index.nisn') }}</label>
                                        <input type="number" class="form-control" id="nisn" name="nisn"
                                            value="{{ old('nisn', isset($student) ? $student->nisn : '') }}" 
                                            placeholder="{{ __('index.optional_10_digit_nisn') }}" maxlength="10">
                                        <div class="form-text">Kosongkan jika tidak ada</div>
                                        <div class="invalid-feedback">
                                            {{ __('index.nisn_must_10_digit') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">{{ __('index.phone_number') }}</label>
                                        <input type="tel" class="form-control" id="phone" name="phone"
                                            value="{{ old('phone', isset($student) ? $student->phone : '') }}"
                                            placeholder="{{ __('index.optional_phone_number') }}" required>
                                        <div class="invalid-feedback">
                                            {{ __('index.invalid_phone_number') }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="gender" class="form-label">{{ __('index.gender') }} <span class="text-danger">*</span></label>
                                        <select class="form-select" id="gender" name="gender" required>
                                            <option value="">{{ __('index.select_gender') }}</option>
                                            <option value="laki-laki" {{ old('gender', isset($student) ? $student->gender : '') == 'laki-laki' ? 'selected' : '' }}>{{ __('index.male') }}</option>
                                            <option value="perempuan" {{ old('gender', isset($student) ? $student->gender : '') == 'perempuan' ? 'selected' : '' }}>{{ __('index.female') }}</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            {{ __('index.please_select_gender') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="parent_name" class="form-label">Nama Orang Tua/Wali</label>
                                        <input type="text" class="form-control" id="parent_name" name="parent_name"
                                            value="{{ old('parent_name', isset($student) ? $student->parent_name : '') }}" 
                                            placeholder="Masukkan nama orang tua/wali">
                                        <div class="form-text">Kosongkan jika tidak ada</div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="parent_phone" class="form-label">Nomor Telepon Orang Tua/Wali</label>
                                        <input type="tel" class="form-control" id="parent_phone" name="parent_phone"
                                            value="{{ old('parent_phone', isset($student) ? $student->parent_phone : '') }}" 
                                            placeholder="Masukkan nomor telepon orang tua/wali">
                                        <div class="form-text">Kosongkan jika tidak ada</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="create_parent_account" name="create_parent_account" value="1">
                                            <label class="form-check-label" for="create_parent_account">
                                                <strong>Buat akun orangtua otomatis</strong><br>
                                                <small class="text-muted">Akan membuat akun orangtua dengan nama dan nomor HP di atas. Email akan dibuat otomatis.</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="birth_place" class="form-label">{{ __('index.birth_place') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="birth_place" name="birth_place"
                                            value="{{ old('birth_place', isset($student) ? $student->birth_place : '') }}" 
                                            placeholder="{{ __('index.enter_birth_place') }}" required>
                                        <div class="invalid-feedback">
                                            {{ __('index.please_enter_birth_place') }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="birth_date" class="form-label">{{ __('index.birth_date') }} <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="birth_date" name="birth_date"
                                            value="{{ old('birth_date', isset($student) ? $student->birth_date : '') }}" 
                                            required>
                                        <div class="invalid-feedback">
                                            {{ __('index.please_select_birth_date') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="religion" class="form-label">{{ __('index.religion') }} <span class="text-danger">*</span></label>
                                        <select class="form-select" id="religion" name="religion" required>
                                            <option value="">{{ __('index.select_religion') }}</option>
                                            <option value="Islam" {{ old('religion', isset($student) ? $student->religion : '') == 'Islam' ? 'selected' : '' }}>{{ __('index.islam') }}</option>
                                            <option value="Kristen" {{ old('religion', isset($student) ? $student->religion : '') == 'Kristen' ? 'selected' : '' }}>{{ __('index.christian') }}</option>
                                            <option value="Katolik" {{ old('religion', isset($student) ? $student->religion : '') == 'Katolik' ? 'selected' : '' }}>{{ __('index.catholic') }}</option>
                                            <option value="Hindu" {{ old('religion', isset($student) ? $student->religion : '') == 'Hindu' ? 'selected' : '' }}>{{ __('index.hindu') }}</option>
                                            <option value="Buddha" {{ old('religion', isset($student) ? $student->religion : '') == 'Buddha' ? 'selected' : '' }}>{{ __('index.buddha') }}</option>
                                            <option value="Konghucu" {{ old('religion', isset($student) ? $student->religion : '') == 'Konghucu' ? 'selected' : '' }}>{{ __('index.confucianism') }}</option>
                                        </select>
                                        <div class="invalid-feedback">
                                            {{ __('index.please_select_religion') }}
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="no_absen" class="form-label">{{ __('index.no_absen') }}</label>
                                        <input type="text" class="form-control" id="no_absen" name="no_absen"
                                            value="{{ old('no_absen', isset($student) ? $student->no_absen : '') }}" 
                                            placeholder="{{ __('index.optional_no_absen') }}">
                                        <div class="form-text">{{ __('index.leave_empty_if_no_absen') }}</div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="no_card" class="form-label">{{ __('index.no_card') }}</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="no_card" name="no_card"
                                                value="{{ old('no_card', isset($student) ? $student->no_card : '') }}" 
                                                placeholder="{{ __('index.optional_no_card') }}" readonly>
                                            <button class="btn btn-outline-primary" type="button" id="detectRfidBtn" onclick="detectRfidCard()">
                                                <i class="mdi mdi-rfid"></i> Deteksi
                                            </button>
                                        </div>
                                        <div class="form-text">{{ __('index.leave_empty_if_no_card') }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="address" class="form-label">{{ __('index.address') }} <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="address" name="address" rows="3"
                                            placeholder="{{ __('index.enter_address') }}" required>{{ old('address', isset($student) ? $student->address : '') }}</textarea>
                                        <div class="invalid-feedback">
                                            {{ __('index.please_enter_address') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="photo_path" class="form-label">{{ __('index.profile_photo') }}</label>
                                        <input type="file" class="form-control" id="photo_path" name="photo_path"
                                            accept="image/jpeg,image/png,image/jpg,image/gif">
                                        <div class="form-text">{{ __('index.max_500kb_jpg_png_gif') }}</div>
                                        @if(isset($student) && $student->user->photo_path)
                                            <div class="mt-2">
                                                <small>{{ __('index.current_photo') }} 
                                                    <img src="{{ asset('storage/' . $student->user->photo_path) }}" alt="Foto" style="max-height: 50px; max-width: 50px;" class="rounded">
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="ijazah" class="form-label">{{ __('index.ijazah') }}</label>
                                        <input type="file" class="form-control" id="ijazah" name="ijazah"
                                            accept=".pdf,.jpg,.jpeg,.png">
                                        <div class="form-text">{{ __('index.max_500kb_pdf_jpg_png') }}</div>
                                        @if(isset($student) && $student->ijazah)
                                            <div class="mt-2">
                                                <small><a href="{{ asset('storage/' . $student->ijazah) }}" target="_blank">{{ __('index.view_ijazah') }}</a></small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="kartu_keluarga" class="form-label">{{ __('index.kartu_keluarga') }}</label>
                                        <input type="file" class="form-control" id="kartu_keluarga" name="kartu_keluarga"
                                            accept=".pdf,.jpg,.jpeg,.png">
                                        <div class="form-text">{{ __('index.max_500kb_pdf_jpg_png') }}</div>
                                        @if(isset($student) && $student->kartu_keluarga)
                                            <div class="mt-2">
                                                <small><a href="{{ asset('storage/' . $student->kartu_keluarga) }}" target="_blank">{{ __('index.view_kartu_keluarga') }}</a></small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="akte_lahir" class="form-label">{{ __('index.akte_lahir') }}</label>
                                        <input type="file" class="form-control" id="akte_lahir" name="akte_lahir"
                                            accept=".pdf,.jpg,.jpeg,.png">
                                        <div class="form-text">{{ __('index.max_500kb_pdf_jpg_png') }}</div>
                                        @if(isset($student) && $student->akte_lahir)
                                            <div class="mt-2">
                                                <small><a href="{{ asset('storage/' . $student->akte_lahir) }}" target="_blank">{{ __('index.view_akte_lahir') }}</a></small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="ktp" class="form-label">{{ __('index.ktp') }}</label>
                                        <input type="file" class="form-control" id="ktp" name="ktp"
                                            accept=".pdf,.jpg,.jpeg,.png">
                                        <div class="form-text">{{ __('index.max_500kb_pdf_jpg_png') }}</div>
                                        @if(isset($student) && $student->ktp)
                                            <div class="mt-2">
                                                <small><a href="{{ asset('storage/' . $student->ktp) }}" target="_blank">{{ __('index.view_ktp') }}</a></small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sertifikat" class="form-label">{{ __('index.sertifikat') }}</label>
                                        <input type="file" class="form-control" id="sertifikat" name="sertifikat"
                                            accept=".pdf,.jpg,.jpeg,.png">
                                        <div class="form-text">{{ __('index.max_500kb_pdf_jpg_png') }}</div>
                                        @if(isset($student) && $student->sertifikat)
                                            <div class="mt-2">
                                                <small><a href="{{ asset('storage/' . $student->sertifikat) }}" target="_blank">{{ __('index.view_sertifikat') }}</a></small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <button class="btn btn-primary" type="submit">
                                                <i class="mdi mdi-content-save"></i> {{ isset($student) ? __('index.update_student') : __('index.save_student') }}
                                            </button>
                                            <a href="{{ route('students.index') }}" class="btn btn-secondary">
                                                <i class="mdi mdi-arrow-left"></i> {{ __('index.back') }}
                                            </a>
                                        </div>
                                        <div>
                                            @isset($student)
                                                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                                    <i class="mdi mdi-delete"></i> {{ __('index.delete_student') }}
                                                </button>
                                            @endisset
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- end row -->
@endsection
@section('scripts')
        <!-- Sweet Alerts js -->
        <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
        
        <script>
            // Delete confirmation (only for edit)
            @isset($student)
            function confirmDelete() {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Siswa \"{{ $student->name }}\" akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create form for delete
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '{{ route("students.destroy", $student->id) }}';
                        form.style.display = 'none';
                        
                        // Add CSRF token
                        const csrfToken = document.querySelector('meta[name="csrf-token"]');
                        if (csrfToken) {
                            const csrfInput = document.createElement('input');
                            csrfInput.type = 'hidden';
                            csrfInput.name = '_token';
                            csrfInput.value = csrfToken.getAttribute('content');
                            form.appendChild(csrfInput);
                        }
                        
                        // Add method override for DELETE
                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        form.appendChild(methodInput);
                        
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }
            @endisset

            // RFID Card Detection
            function detectRfidCard() {
                const btn = document.getElementById('detectRfidBtn');
                const cardInput = document.getElementById('no_card');
                
                // Show loading state
                btn.disabled = true;
                btn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Scanning...';
                
                let scanningTime = 0;
                let scanInterval;
                let initialUid = null;
                
                // Clear cache first and get initial state
                fetch('/api/rfid/clear-cache', {
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(() => {
                    // Get initial UID after clearing cache
                    return fetch('/api/rfid/latest', {
                        method: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                })
                .then(response => response.json())
                .then(data => {
                    initialUid = data.uid || null;
                    
                    // Show SweetAlert for scanning with real-time updates
                    const swalInstance = Swal.fire({
                        title: 'Scanning Kartu RFID',
                        html: `
                            <div style="text-align: center;">
                                <i class="mdi mdi-rfid mdi-spin" style="font-size: 48px; color: #007bff;"></i>
                                <p style="margin-top: 15px;">Silakan tempelkan kartu RFID ke reader...</p>
                                <p style="color: #6c757d; font-size: 14px;">Scanning: <span id="scan-time">0</span>s</p>
                            </div>
                        `,
                        icon: false,
                        showConfirmButton: true,
                        confirmButtonText: 'Stop Scanning',
                        showCancelButton: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            // Start scanning timer
                            scanInterval = setInterval(() => {
                                scanningTime++;
                                const timeElement = document.getElementById('scan-time');
                                if (timeElement) {
                                    timeElement.textContent = scanningTime;
                                }
                            }, 1000);
                            
                            // Start polling for RFID card
                            startRfidPolling();
                        }
                    }).then((result) => {
                        // User clicked stop or closed dialog
                        if (result.dismiss === Swal.DismissReason.confirm || result.dismiss === Swal.DismissReason.close) {
                            stopScanning();
                        }
                    });
                    
                    function startRfidPolling() {
                        let pollCount = 0;
                        const maxPolls = 30; // 30 detik max
                        
                        const pollInterval = setInterval(() => {
                            pollCount++;
                            
                            // Check if we should stop polling
                            if (pollCount > maxPolls) {
                                stopScanning();
                                Swal.fire({
                                    title: 'Timeout',
                                    text: 'Tidak ada kartu RFID yang terdeteksi dalam 30 detik',
                                    icon: 'warning',
                                    confirmButtonText: 'OK'
                                });
                                return;
                            }
                            
                            // Check for new RFID card
                            fetch('/api/rfid/latest', {
                                method: 'GET',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success' && data.uid) {
                                    // Check if this is a NEW card (different from initial state and current input)
                                    if (data.uid !== initialUid && data.uid !== cardInput.value) {
                                        // Validate if card is not used by another student
                                        fetch('/api/rfid/validate-card', {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                            },
                                            body: JSON.stringify({
                                                uid: data.uid
                                            })
                                        })
                                        .then(response => response.json())
                                        .then(validation => {
                                            if (validation.valid) {
                                                // Success! Card detected and valid
                                                stopScanning();
                                                cardInput.value = data.uid;
                                                Swal.fire({
                                                    title: 'Berhasil!',
                                                    html: `
                                                        <div style="text-align: center;">
                                                            <i class="mdi mdi-check-circle" style="font-size: 48px; color: #28a745;"></i>
                                                            <p style="margin-top: 15px;">Kartu RFID terdeteksi:</p>
                                                            <p style="font-weight: bold; color: #007bff;">${data.uid}</p>
                                                        </div>
                                                    `,
                                                    icon: false,
                                                    timer: 2000,
                                                    showConfirmButton: false
                                                });
                                            } else {
                                                // Card already used by another student
                                                Swal.fire({
                                                    title: 'Kartu Sudah Digunakan',
                                                    text: validation.message || 'Kartu ini sudah terdaftar pada siswa lain',
                                                    icon: 'warning',
                                                    confirmButtonText: 'OK'
                                                });
                                            }
                                        })
                                        .catch(error => {
                                            console.error('Validation error:', error);
                                        });
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Polling error:', error);
                            });
                        }, 1000); // Poll every 1 second
                        
                        // Store interval for cleanup
                        swalInstance.pollInterval = pollInterval;
                    }
                    
                    function stopScanning() {
                        // Clear intervals
                        if (scanInterval) {
                            clearInterval(scanInterval);
                        }
                        if (swalInstance.pollInterval) {
                            clearInterval(swalInstance.pollInterval);
                        }
                        
                        // Reset button state
                        btn.disabled = false;
                        btn.innerHTML = '<i class="mdi mdi-rfid"></i> Deteksi';
                    }
                })
                .catch(error => {
                    console.error('Error clearing cache:', error);
                    btn.disabled = false;
                    btn.innerHTML = '<i class="mdi mdi-rfid"></i> Deteksi';
                });
            }

            // Manual input toggle for no_card field
            document.addEventListener('DOMContentLoaded', function() {
                const cardInput = document.getElementById('no_card');
                const detectBtn = document.getElementById('detectRfidBtn');
                
                // Auto-fill if rfid_uid parameter exists
                const urlParams = new URLSearchParams(window.location.search);
                const rfidUid = urlParams.get('rfid_uid');
                if (rfidUid) {
                    cardInput.value = rfidUid;
                    cardInput.readOnly = true;
                    detectBtn.style.display = 'none';
                    
                    // Show notification
                    Swal.fire({
                        icon: 'info',
                        title: 'Kartu RFID Terdeteksi',
                        html: `
                            <div style="text-align: center;">
                                <i class="mdi mdi-rfid" style="font-size: 48px; color: #007bff;"></i>
                                <p style="margin-top: 15px;"><strong>UID Kartu: ${rfidUid}</strong></p>
                                <p style="color: #6c757d;">Silakan lengkapi data siswa</p>
                            </div>
                        `,
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
                
                // Add double-click to enable manual input
                cardInput.addEventListener('dblclick', function() {
                    if (this.readOnly) {
                        Swal.fire({
                            title: 'Input Manual',
                            text: 'Apakah Anda ingin input nomor kartu secara manual?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Ya',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                this.readOnly = false;
                                this.focus();
                                detectBtn.style.display = 'block';
                            }
                        });
                    }
                });
            });
        </script>
@endsection
