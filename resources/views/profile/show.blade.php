@extends('layouts.master')
@section('title')
    {{ auth()->user()->id === $user->id ? __('index.my_profile') : __('index.profile') . ' ' . $user->name }}
@endsection
@section('css')
    <!-- Sweet Alert-->
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('page-title')
    {{ auth()->user()->id === $user->id ? __('index.my_profile') : __('index.profile') . ' ' . $user->name }}
@endsection
@section('body')

    <body data-sidebar="colored">
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="profile-avatar mb-3">
                        @if($user->photo_path)
                            <img src="{{ asset('storage/' . $user->photo_path) }}" alt="Avatar" class="rounded-circle" width="120" height="120" style="object-fit: cover;">
                        @else
                            <div class="avatar-placeholder rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px; font-size: 48px;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <h4 class="card-title">{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>
                    @php
                        $profileData = $user->getProfileData();
                        
                        // Determine status display
                        if($user->hasRole('student') || $user->hasRole('Student')) {
                            // For students, show status from student table
                            if($profileData && isset($profileData->status)) {
                                $statusDisplay = ucfirst($profileData->status);
                                $statusBadgeClass = $profileData->status === 'calon siswa' ? 'warning' : ($profileData->status === 'siswa' ? 'success' : 'secondary');
                            } else {
                                $statusDisplay = 'Student';
                                $statusBadgeClass = 'primary';
                            }
                        } else {
                            // For other roles, show role name
                            $statusDisplay = ucfirst($user->roles->first()->name ?? 'User');
                            $statusBadgeClass = $user->hasRole('admin') ? 'danger' : ($user->hasRole('teacher') ? 'success' : 'primary');
                        }
                    @endphp
                    <p class="badge bg-{{ $statusBadgeClass }}">
                        {{ $statusDisplay }}
                    </p>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3">{{ __('index.account_information') }}</h5>
                    <div class="mb-3">
                        <label class="form-label text-muted">{{ __('index.email') }}</label>
                        <p class="mb-0">{{ $user->email }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">{{ __('index.phone_number') }}</label>
                        <p class="mb-0">{{ $user->phone ?? '-' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">{{ __('index.member_since') }}</label>
                        <p class="mb-0">{{ $user->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">{{ __('index.last_updated') }}</label>
                        <p class="mb-0">{{ $user->updated_at->format('d M Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="card-title mb-0">
                            {{ auth()->user()->id === $user->id ? __('index.edit_profile') : __('index.profile_details') }}
                        </h5>
                        <div class="d-flex gap-2">
                            @if(auth()->user()->id !== $user->id)
                                @if(($user->hasRole('student') || $user->hasRole('Student')) && $profileData && ($profileData->status ?? 'calon siswa') === 'calon siswa')
                                    <button type="button" class="btn btn-success btn-accept" 
                                            data-id="{{ $profileData->id ?? $user->id }}" 
                                            data-name="{{ $user->name }}" 
                                            data-jurusan-utama="{{ $profileData->jurusan_utama ?? '' }}" 
                                            data-jurusan-cadangan="{{ $profileData->jurusan_cadangan ?? '' }}">
                                        <i class="mdi mdi-check"></i> {{ __('index.accept') }}
                                    </button>
                                    <button type="button" class="btn btn-danger btn-reject" 
                                            data-id="{{ $profileData->id ?? $user->id }}" 
                                            data-name="{{ $user->name }}">
                                        <i class="mdi mdi-close"></i> {{ __('index.reject') }}
                                    </button>
                                @endif
                            @endif
                            @if(auth()->user()->id === $user->id)
                                <button type="button" class="btn btn-outline-primary" id="toggleEditForm">
                                    <i class="mdi mdi-pencil"></i> {{ __('index.edit_personal_data') }}
                                </button>
                            @endif
                        </div>
                    </div>
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Profile Data Table View -->
                    <div id="profileTable" class="profile-table-view">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th width="30%">{{ __('index.full_name') }}</th>
                                        <td>{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('index.email') }}</th>
                                        <td>{{ $user->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('index.phone_number') }}</th>
                                        <td>{{ $user->phone ?? '-' }}</td>
                                    </tr>
                                    
                                    @if($user->hasRole('student') || $user->hasRole('Student'))
                                        @if($profileData)
                                            <tr>
                                                <th>{{ __('index.absence_number') }}</th>
                                                <td>{{ $profileData->no_absen ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('index.card_number') }}</th>
                                                <td>{{ $profileData->no_card ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>NISN</th>
                                                <td>{{ $profileData->nisn ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>NIK</th>
                                                <td>{{ $profileData->nik ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('index.gender') }}</th>
                                                <td>{{ $profileData->gender ? ucfirst($profileData->gender) : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('index.birth_place') }}</th>
                                                <td>{{ $profileData->birth_place ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('index.birth_date') }}</th>
                                                <td>{{ $profileData->birth_date ? \Carbon\Carbon::parse($profileData->birth_date)->format('d M Y') : '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('index.religion') }}</th>
                                                <td>{{ $profileData->religion ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('index.primary_major') }}</th>
                                                <td>{{ $profileData->jurusan_utama ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('index.secondary_major') }}</th>
                                                <td>{{ $profileData->jurusan_cadangan ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('index.academic_year') }}</th>
                                                <td>{{ $profileData->academic_year ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('index.status_details') }}</th>
                                                <td>
                                                    @if($profileData && isset($profileData->status))
                                                        <span class="badge bg-{{ $profileData->status === 'calon siswa' ? 'warning' : ($profileData->status === 'siswa' ? 'success' : 'secondary') }}">
                                                            {{ $profileData->status }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ __('index.status_unknown') }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <th>{{ __('index.status') }}</th>
                                                <td>{{ __('index.student_data_not_found') }}</td>
                                            </tr>
                                        @endif
                                    @endif
                                    
                                    <tr>
                                        <th>{{ __('index.address') }}</th>
                                        <td>{{ $user->address ?? ($profileData->address ?? '-') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Edit Form (Hidden by default) -->
                    @if(auth()->user()->id === $user->id)
                        <div id="editForm" class="edit-form-view" style="display: none;">
                            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('PATCH')

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">{{ __('index.full_name') }}</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                               id="name" name="name" value="{{ $user->name }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">{{ __('index.email') }}</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                               id="email" name="email" value="{{ $user->email }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                @if($user->hasRole('student') || $user->hasRole('Student'))
                                    @if($profileData)
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="no_absen" class="form-label">{{ __('index.absence_number') }}</label>
                                                <input type="text" class="form-control @error('no_absen') is-invalid @enderror" 
                                                       id="no_absen" name="no_absen" value="{{ old('no_absen', $profileData->no_absen ?? '') }}">
                                                @error('no_absen')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="no_card" class="form-label">{{ __('index.card_number') }}</label>
                                                <input type="text" class="form-control @error('no_card') is-invalid @enderror" 
                                                       id="no_card" name="no_card" value="{{ old('no_card', $profileData->no_card ?? '') }}">
                                                @error('no_card')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="nisn" class="form-label">NISN</label>
                                                <input type="text" class="form-control @error('nisn') is-invalid @enderror" 
                                                       id="nisn" name="nisn" value="{{ old('nisn', $profileData->nisn ?? '') }}">
                                                @error('nisn')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="nik" class="form-label">NIK</label>
                                                <input type="text" class="form-control @error('nik') is-invalid @enderror" 
                                                       id="nik" name="nik" value="{{ old('nik', $profileData->nik ?? '') }}">
                                                @error('nik')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="gender" class="form-label">{{ __('index.gender') }}</label>
                                                <select class="form-control @error('gender') is-invalid @enderror" 
                                                        id="gender" name="gender">
                                                    <option value="">{{ __('index.select_gender') }}</option>
                                                    <option value="laki-laki" {{ old('gender', $profileData->gender ?? '') == 'laki-laki' ? 'selected' : '' }}>{{ __('index.male') }}</option>
                                                    <option value="perempuan" {{ old('gender', $profileData->gender ?? '') == 'perempuan' ? 'selected' : '' }}>{{ __('index.female') }}</option>
                                                </select>
                                                @error('gender')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="religion" class="form-label">{{ __('index.religion') }}</label>
                                                <input type="text" class="form-control @error('religion') is-invalid @enderror" 
                                                       id="religion" name="religion" value="{{ old('religion', $profileData->religion ?? '') }}">
                                                @error('religion')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="birth_place" class="form-label">{{ __('index.birth_place') }}</label>
                                                <input type="text" class="form-control @error('birth_place') is-invalid @enderror" 
                                                       id="birth_place" name="birth_place" value="{{ old('birth_place', $profileData->birth_place ?? '') }}">
                                                @error('birth_place')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="birth_date" class="form-label">{{ __('index.birth_date') }}</label>
                                                <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                                       id="birth_date" name="birth_date" value="{{ old('birth_date', $profileData->birth_date ?? '') }}">
                                                @error('birth_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="jurusan_utama" class="form-label">{{ __('index.primary_major') }}</label>
                                                <input type="text" class="form-control @error('jurusan_utama') is-invalid @enderror" 
                                                       id="jurusan_utama" name="jurusan_utama" value="{{ old('jurusan_utama', $profileData->jurusan_utama ?? '') }}">
                                                @error('jurusan_utama')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="jurusan_cadangan" class="form-label">{{ __('index.secondary_major') }}</label>
                                                <input type="text" class="form-control @error('jurusan_cadangan') is-invalid @enderror" 
                                                       id="jurusan_cadangan" name="jurusan_cadangan" value="{{ old('jurusan_cadangan', $profileData->jurusan_cadangan ?? '') }}">
                                                @error('jurusan_cadangan')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label for="academic_year" class="form-label">{{ __('index.academic_year') }}</label>
                                                <input type="text" class="form-control @error('academic_year') is-invalid @enderror" 
                                                       id="academic_year" name="academic_year" value="{{ old('academic_year', $profileData->academic_year ?? '') }}">
                                                @error('academic_year')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">{{ __('index.phone_number') }}</label>
                                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" name="phone" value="{{ $user->phone }}">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="avatar" class="form-label">{{ __('index.avatar') }}</label>
                                        <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                                               id="avatar" name="avatar" accept="image/*">
                                        @error('avatar')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label">{{ __('index.address') }}</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="3">{{ old('address', $user->address ?? ($user->getProfileData()->address ?? '')) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-secondary" id="cancelEdit">
                                        <i class="mdi mdi-close"></i> {{ __('index.cancel') }}
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="mdi mdi-content-save"></i> {{ __('index.save_changes') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                        <i class="mdi mdi-lock"></i> {{ __('index.change_password') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            @if(auth()->user()->id === $user->id)
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-4">{{ __('index.security') }}</h5>
                        
                        <div class="mb-4">
                            <h6 class="mb-3">{{ __('index.password') }}</h6>
                            <p class="text-muted mb-3">{{ __('index.change_password_for_account_security') }}</p>
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                <i class="mdi mdi-lock"></i> {{ __('index.change_password') }}
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Dokumen Lampiran: Full Width (for students) -->
    @if($user->hasRole('student') || $user->hasRole('Student'))
        @if($profileData)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ __('index.supporting_documents') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach([
                                ['label' => 'Ijazah', 'path' => $profileData->ijazah ?? null],
                                ['label' => 'Kartu Keluarga', 'path' => $profileData->kartu_keluarga ?? null],
                                ['label' => 'Akte Lahir', 'path' => $profileData->akte_lahir ?? null],
                                ['label' => 'KTP', 'path' => $profileData->ktp ?? null],
                                ['label' => 'Sertifikat', 'path' => $profileData->sertifikat ?? null],
                            ] as $doc)
                                <div class="col-sm-6 col-md-4 col-lg-3 mb-3">
                                    <div class="border rounded p-3 h-100 d-flex flex-column">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <strong>{{ $doc['label'] }}</strong>
                                            @if($doc['path'])
                                                <a href="{{ asset('storage/' . $doc['path']) }}" download class="btn btn-sm btn-outline-primary">
                                                    <i class="mdi mdi-download"></i>
                                                </a>
                                            @endif
                                        </div>
                                        <div class="flex-fill">
                                            @if($doc['path'])
                                                @php($ext = strtolower(pathinfo($doc['path'], PATHINFO_EXTENSION)))
                                                @if(in_array($ext, ['jpg','jpeg','png','gif']))
                                                    <img src="{{ asset('storage/' . $doc['path']) }}" alt="{{ $doc['label'] }}" class="img-fluid rounded preview-file" data-src="{{ asset('storage/' . $doc['path']) }}" data-title="{{ $doc['label'] }}" data-type="image">
                                                @elseif($ext === 'pdf')
                                                    <div class="text-center p-3 border rounded bg-light preview-file" data-src="{{ asset('storage/' . $doc['path']) }}" data-title="{{ $doc['label'] }}" data-type="pdf" style="cursor: pointer;">
                                                        <i class="mdi mdi-file-pdf fa-3x text-danger mb-2"></i>
                                                        <div class="small">{{ __('index.click_to_preview_pdf') }}</div>
                                                    </div>
                                                @else
                                                    <div class="text-muted">{{ __('index.document_available') }} ({{ strtoupper($ext) }})</div>
                                                @endif
                                            @else
                                                <div class="text-muted">Tidak ada file</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Face Recognition Photo Section (if available) -->
        @if($profileData->face_photo)
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">{{ __('index.face_recognition_photo') }}</h5>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset('storage/' . $profileData->face_photo) }}" alt="{{ __('index.face_recognition_photo') }}"
                             class="img-fluid rounded preview-file"
                             data-src="{{ asset('storage/' . $profileData->face_photo) }}"
                             data-title="{{ __('index.face_recognition_photo') }} - {{ $user->name }}"
                             data-type="image"
                             style="max-width: 300px; cursor: pointer;">
                        <p class="mt-2 text-muted">{{ __('index.photo_used_for_face_recognition_system') }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endif
    @endif

    @if(auth()->user()->id === $user->id)
                <!-- Change Password Modal -->
                <div class="modal fade" id="changePasswordModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ __('index.change_password') }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">{{ __('index.current_password') }}</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password" required>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('index.new_password') }}</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('index.confirm_new_password') }}</label>
                            <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                   id="password_confirmation" name="password_confirmation" required>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('index.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('index.change_password') }}</button>
                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
@endsection

@section('scripts')
    <!-- Sweet Alerts js -->
    <script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide success message after 3 seconds
            const successAlert = document.getElementById('success-alert');
            if (successAlert) {
                setTimeout(function() {
                    successAlert.classList.remove('show');
                    setTimeout(function() {
                        successAlert.remove();
                    }, 150);
                }, 3000);
            }

            // Toggle between table view and edit form
            const toggleEditBtn = document.getElementById('toggleEditForm');
            const cancelEditBtn = document.getElementById('cancelEdit');
            const profileTable = document.getElementById('profileTable');
            const editForm = document.getElementById('editForm');

            if (toggleEditBtn && profileTable && editForm) {
                toggleEditBtn.addEventListener('click', function() {
                    profileTable.style.display = 'none';
                    editForm.style.display = 'block';
                    toggleEditBtn.style.display = 'none';
                });
            }

            if (cancelEditBtn && profileTable && editForm && toggleEditBtn) {
                cancelEditBtn.addEventListener('click', function() {
                    profileTable.style.display = 'block';
                    editForm.style.display = 'none';
                    toggleEditBtn.style.display = 'inline-block';
                });
            }

            // Preview avatar before upload
            const avatarInput = document.getElementById('avatar');
            if (avatarInput) {
                avatarInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const avatarImg = document.querySelector('.profile-avatar img');
                            if (avatarImg) {
                                avatarImg.src = e.target.result;
                            } else {
                                const placeholder = document.querySelector('.avatar-placeholder');
                                if (placeholder) {
                                    placeholder.outerHTML = `<img src="${e.target.result}" alt="Avatar" class="rounded-circle" width="120" height="120">`;
                                }
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Modal preview functionality for documents
            const modal = createImageModal();
            const modalEl = document.getElementById('imagePreviewModal');
            const imgEl = document.getElementById('imagePreviewElement');
            const pdfEl = document.getElementById('pdfPreviewElement');
            const titleEl = document.getElementById('imagePreviewTitle');
            const downloadBtn = document.getElementById('imagePreviewDownload');

            function openModal(src, title, type = 'image') {
                titleEl.textContent = title || '{{ __('index.preview_file') }}';

                if (type === 'pdf') {
                    // Show PDF in iframe
                    pdfEl.src = src;
                    pdfEl.style.display = 'block';
                    imgEl.style.display = 'none';
                    downloadBtn.setAttribute('href', src);
                    downloadBtn.setAttribute('download', (title || 'document').replace(/\s+/g,'_') + '.pdf');
                } else {
                    // Show image
                    imgEl.src = src;
                    imgEl.style.display = 'block';
                    pdfEl.style.display = 'none';
                    downloadBtn.setAttribute('href', src);
                    downloadBtn.setAttribute('download', (title || 'image').replace(/\s+/g,'_'));
                }

                $(modalEl).modal('show');
            }

            document.querySelectorAll('.preview-file').forEach(function(el) {
                el.addEventListener('click', function(e) {
                    e.preventDefault();
                    const src = this.getAttribute('data-src');
                    const title = this.getAttribute('data-title');
                    const type = this.getAttribute('data-type') || 'image';
                    if (src) openModal(src, title, type);
                });
            });

            function createImageModal() {
                if (document.getElementById('imagePreviewModal')) return document.getElementById('imagePreviewModal');
                const html = `
                    <div class="modal fade" id="imagePreviewModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="imagePreviewTitle">{{ __('index.preview_file') }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <img id="imagePreviewElement" src="" alt="{{ __('index.preview') }}" class="img-fluid rounded" style="max-height: 70vh; display: none;">
                                    <iframe id="pdfPreviewElement" src="" style="width: 100%; height: 70vh; border: 1px solid #ddd; display: none;" title="{{ __('index.pdf_preview') }}"></iframe>
                                </div>
                                <div class="modal-footer">
                                    <a id="imagePreviewDownload" href="#" class="btn btn-primary" download>
                                        <i class="mdi mdi-download"></i> {{ __('index.download') }}
                                    </a>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('index.close') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>`;
                const container = document.createElement('div');
                container.innerHTML = html;
                document.body.appendChild(container.firstElementChild);
                return document.getElementById('imagePreviewModal');
            }

            // Accept button functionality
            document.querySelectorAll('.btn-accept').forEach(function(btn) {
                btn.addEventListener('click', async function () {
                    console.log('Accept button clicked');
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name') || '';
                    const jurusanUtama = this.getAttribute('data-jurusan-utama') || '';
                    const jurusanCadangan = this.getAttribute('data-jurusan-cadangan') || '';
                    
                    console.log('Data:', { id, name, jurusanUtama, jurusanCadangan });
                    
                    if (!id) {
                        console.error('No ID found');
                        return;
                    }

                    let optionsHtml = '';
                    if (jurusanUtama) {
                        optionsHtml += `<option value="${jurusanUtama}">${jurusanUtama}</option>`;
                    }
                    if (jurusanCadangan) {
                        optionsHtml += `<option value="${jurusanCadangan}">${jurusanCadangan}</option>`;
                    }

                    console.log('Showing Swal with options:', optionsHtml);

                    const { value: jurusan } = await Swal.fire({
                        title: '{{ __('index.accept_applicant') }}',
                        html: `
                            <p>{{ __('index.accept_applicant_to_major') }} <strong>${name}</strong> {{ __('index.to_major') }}:</p>
                            <select id="jurusan-select" class="swal2-select">
                                ${optionsHtml}
                            </select>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '{{ __('index.yes_accept') }}',
                        cancelButtonText: '{{ __('index.cancel') }}',
                        preConfirm: () => {
                            const select = document.getElementById('jurusan-select');
                            return select ? select.value : null;
                        }
                    });

                    console.log('Selected jurusan:', jurusan);

                    if (jurusan) {
                        try {
                            console.log('Sending accept request...');
                            const res = await fetch(`/pendaftaran-siswa/${id}/accept`, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({ jurusan: jurusan })
                            });

                            console.log('Response status:', res.status);

                            if (!res.ok) throw new Error('{{ __('index.failed_to_accept_applicant') }}');
                            
                            Swal.fire({
                                icon: 'success',
                                title: '{{ __('index.success') }}',
                                text: '{{ __('index.applicant_accepted_successfully') }}',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                            
                        } catch (e) {
                            console.error('Error:', e);
                            Swal.fire({
                                icon: 'error',
                                title: '{{ __('index.error') }}',
                                text: e.message || '{{ __('index.error_occurred') }}'
                            });
                        }
                    }
                });
            });

            // Reject button functionality
            document.querySelectorAll('.btn-reject').forEach(function(btn) {
                btn.addEventListener('click', async function () {
                    console.log('Reject button clicked');
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name') || '';
                    
                    console.log('Data:', { id, name });
                    
                    if (!id) {
                        console.error('No ID found');
                        return;
                    }

                    const result = await Swal.fire({
                        title: '{{ __('index.are_you_sure') }}',
                        text: `{{ __('index.applicant_will_be_rejected_and_data_deleted_permanently') }} "${name}"!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: '{{ __('index.yes_reject') }}',
                        cancelButtonText: '{{ __('index.cancel') }}'
                    });

                    console.log('Swal result:', result);

                    if (result.isConfirmed) {
                        try {
                            console.log('Sending reject request...');
                            const res = await fetch(`/pendaftaran-siswa/${id}/reject`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            });

                            console.log('Response status:', res.status);

                            if (!res.ok) throw new Error('{{ __('index.failed_to_reject_applicant') }}');
                            
                            Swal.fire({
                                icon: 'success',
                                title: '{{ __('index.success') }}',
                                text: '{{ __('index.applicant_rejected_successfully') }}',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = '/profile';
                            });
                            
                        } catch (e) {
                            console.error('Error:', e);
                            Swal.fire({
                                icon: 'error',
                                title: '{{ __('index.error') }}',
                                text: e.message || '{{ __('index.error_occurred') }}'
                            });
                        }
                    }
                });
            });
        });
    </script>
        
        <!-- App js -->
        <script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection
