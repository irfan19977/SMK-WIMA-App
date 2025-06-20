@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 col-md-11 col-lg-11 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4>Edit Guru</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('parents.update', $parents->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                    placeholder="Masukkan Nama" value="{{ old('name', $parents->name) }}" autofocus>
                                @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" placeholder="Masukkan Email" value="{{ old('email', $parents->user->email) }}">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Password <small class="text-muted">(Kosongkan jika tidak ingin mengubah)</small></label>
                                <div class="position-relative">
                                    <input id="password-input" type="password" class="form-control @error('password') is-invalid @enderror"
                                        name="password" placeholder="Masukkan Password Baru" style="padding-right: 40px;">
                                    <span class="position-absolute" id="password-addon" 
                                        style="right: 10px; top: 55%; transform: translateY(-50%); cursor: pointer;">
                                        <i class="ri-eye-fill" style="color: #666;"></i>
                                    </span>
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nomor Telepon</label>
                                <input type="number" class="form-control @error('phone') is-invalid @enderror"
                                    name="phone" placeholder="Masukkan Nomor Telepon" value="{{ old('phone', $parents->user->phone) }}">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Anak</label>
                                <select class="form-control select2 @error('student_id') is-invalid @enderror" name="student_id">
                                    <option value="">Pilih Siswa</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ $parents->student_id == $student->id ? 'selected' : '' }}>
                                            {{ $student->name }} - {{ $student->nisn }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">*Masukkan NISN atau Nama</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Peran</label>
                                <select class="form-control select2 @error('status') is-invalid @enderror" 
                                    name="status">
                                    <option value="">Pilih Peran</option>
                                    <option value="ayah" {{ old('status', $parents->status) == 'ayah' ? 'selected' : '' }}>Ayah</option>
                                    <option value="ibu" {{ old('status', $parents->status) == 'ibu' ? 'selected' : '' }}>Ibu</option>
                                    <option value="wali" {{ old('status', $parents->status) == 'wali' ? 'selected' : '' }}>Wali</option>
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- API-based Location Dropdowns -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Provinsi</label>
                                <select id="province"
                                    class="form-control select2 @error('province') is-invalid @enderror">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                                <input type="hidden" name="province" id="province_name" value="{{ old('province', $parents->province) }}">
                                @error('province')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kota/Kabupaten</label>
                                <select id="regency" class="form-control select2 @error('regency') is-invalid @enderror">
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                                <input type="hidden" name="regency" id="regency_name" value="{{ old('regency', $parents->regency) }}">
                                @error('regency')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Kecamatan</label>
                                <select id="district"
                                    class="form-control select2 @error('district') is-invalid @enderror">
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                                <input type="hidden" name="district" id="district_name" value="{{ old('district', $parents->district) }}">
                                @error('district')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Desa/Kelurahan</label>
                                <select id="village"
                                    class="form-control select2 @error('village') is-invalid @enderror">
                                    <option value="">Pilih Desa/Kelurahan</option>
                                </select>
                                <input type="hidden" name="village" id="village_name" value="{{ old('village', $parents->village) }}">
                                @error('village')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" name="address"
                            placeholder="Masukkan Alamat" rows="3">{{ old('address', $parents->address) }}</textarea>
                        @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Foto</label>
                        <input type="file" class="form-control @error('photo') is-invalid @enderror" name="photo"
                            accept="image/*" id="photo-input">
                        @error('photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <!-- Current Photo Display -->
                        @if($parents->photo)
                        <div class="mt-3" id="current-photo">
                            <label class="form-label">Foto Saat Ini:</label>
                            <div class="text-center">
                                <img src="{{ Storage::url($parents->photo) }}" alt="Current Photo"
                                    style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 8px; object-fit: cover;">
                            </div>
                        </div>
                        @endif

                        <!-- Preview Container -->
                        <div class="mt-3" id="photo-preview-container" style="display: none;">
                            <label class="form-label">Preview:</label>
                            <div class="text-center">
                                <img id="photo-preview" src="" alt="Preview"
                                    style="max-width: 200px; max-height: 200px; border: 1px solid #ddd; border-radius: 8px; object-fit: cover;">
                                <div class="mt-2">
                                    <button type="button" class="btn btn-sm btn-danger" id="remove-photo">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="control-label">Status Aktif</div>
                        <input type="hidden" name="status" value="0">
                        <label class="custom-switch mt-2">
                            <input type="checkbox" name="status" class="custom-switch-input" value="1"
                                {{ old('status', $parents->user->status) ? 'checked' : '' }}>
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">Aktif</span>
                        </label>
                    </div>

                    <div class="card-footer text-right">
                        <button class="btn btn-primary mr-1" type="submit">Update</button>
                        <a href="{{ route('parents.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/user-create.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordToggle = document.getElementById('password-addon');
        const passwordField = document.getElementById('password-input');
            
        passwordToggle.addEventListener('click', function(e) {
            console.log("Toggle password button clicked!");
            e.preventDefault();
            e.stopPropagation();
                
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
                
            const iconElement = this.querySelector('i');
            if (type === 'text') {
                iconElement.classList.remove('ri-eye-fill');
                iconElement.classList.add('ri-eye-off-fill');
            } else {
                iconElement.classList.remove('ri-eye-off-fill');
                iconElement.classList.add('ri-eye-fill');
            }
                
            // Hilangkan fokus/kursor dari field password
            passwordField.blur();
        });
            
        passwordToggle.addEventListener('mousedown', function(e) {
            e.preventDefault();
        });

        // Photo preview functionality
        const photoInput = document.getElementById('photo-input');
        const photoPreview = document.getElementById('photo-preview');
        const photoPreviewContainer = document.getElementById('photo-preview-container');
        const removePhotoBtn = document.getElementById('remove-photo');
        const currentPhoto = document.getElementById('current-photo');

        photoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.src = e.target.result;
                    photoPreviewContainer.style.display = 'block';
                    if (currentPhoto) {
                        currentPhoto.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        removePhotoBtn.addEventListener('click', function() {
            photoInput.value = '';
            photoPreviewContainer.style.display = 'none';
            if (currentPhoto) {
                currentPhoto.style.display = 'block';
            }
        });
    });
</script>
@endpush