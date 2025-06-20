@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 col-md-11 col-lg-11 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4>Edit Guru</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('teachers.update', $teachers->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                    placeholder="Masukkan Nama" value="{{ old('name', $teachers->name) }}" autofocus>
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
                                    name="email" placeholder="Masukkan Email" value="{{ old('email', $teachers->user->email) }}">
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
                                    name="phone" placeholder="Masukkan Nomor Telepon" value="{{ old('phone', $teachers->user->phone) }}">
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>NIP</label>
                                <input type="number" class="form-control @error('nip') is-invalid @enderror"
                                    name="nip" placeholder="Masukkan nip" value="{{ old('nip', $teachers->nip) }}">
                                @error('nip')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>No Kartu</label>
                                <input type="text" class="form-control @error('no_card') is-invalid @enderror"
                                    name="no_card" placeholder="Masukkan No Kartu" id="no_card"
                                    value="{{ old('no_card', $teachers->no_card) }}" readonly>
                                @error('no_card')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">*Tap Kartu RFID</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Pendidikan</label>
                                <select class="form-control select2 @error('education_level') is-invalid @enderror" 
                                    name="education_level">
                                    <option value="">Pilih Pendidikan</option>
                                    <option value="D3" {{ old('education_level', $teachers->education_level) == 'D3' ? 'selected' : '' }}>D3</option>
                                    <option value="D4" {{ old('education_level', $teachers->education_level) == 'D4' ? 'selected' : '' }}>D4</option>
                                    <option value="S1" {{ old('education_level', $teachers->education_level) == 'S1' ? 'selected' : '' }}>S1</option>
                                    <option value="S2" {{ old('education_level', $teachers->education_level) == 'S2' ? 'selected' : '' }}>S2</option>
                                    <option value="S3" {{ old('education_level', $teachers->education_level) == 'S3' ? 'selected' : '' }}>S3</option>
                                </select>
                                @error('education_level')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jurusan</label>
                                <input type="text" class="form-control @error('education_major') is-invalid @enderror"
                                    name="education_major" id="education_major" 
                                    value="{{ old('education_major', $teachers->education_major) }}" 
                                    placeholder="Masukkan Jurusan">
                                @error('education_major')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Instritusi</label>
                                <input type="text" class="form-control @error('education_institution') is-invalid @enderror"
                                    name="education_institution" id="education_institution" 
                                    value="{{ old('education_institution', $teachers->education_institution) }}" 
                                    placeholder="Masukkan Institusi Pendidikan">
                                @error('education_institution')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <select class="form-control select2 @error('gender') is-invalid @enderror"
                                    name="gender">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="laki-laki" {{ old('gender', $teachers->gender) == 'laki-laki' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="perempuan" {{ old('gender', $teachers->gender) == 'perempuan' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                                @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Medical Info</label>
                        <textarea class="form-control @error('medical_info') is-invalid @enderror" name="medical_info"
                            placeholder="Masukkan Medical Info" rows="3">{{ old('medical_info', $teachers->medical_info) }}</textarea>
                        @error('medical_info')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                                <input type="hidden" name="province" id="province_name" value="{{ old('province', $teachers->province) }}">
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
                                <input type="hidden" name="regency" id="regency_name" value="{{ old('regency', $teachers->regency) }}">
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
                                <input type="hidden" name="district" id="district_name" value="{{ old('district', $teachers->district) }}">
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
                                <input type="hidden" name="village" id="village_name" value="{{ old('village', $teachers->village) }}">
                                @error('village')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" name="address"
                            placeholder="Masukkan Alamat" rows="3">{{ old('address', $teachers->address) }}</textarea>
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
                        @if($teachers->photo)
                        <div class="mt-3" id="current-photo">
                            <label class="form-label">Foto Saat Ini:</label>
                            <div class="text-center">
                                <img src="{{ Storage::url($teachers->photo) }}" alt="Current Photo"
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
                                {{ old('status', $teachers->user->status) ? 'checked' : '' }}>
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description">Aktif</span>
                        </label>
                    </div>

                    <div class="card-footer text-right">
                        <button class="btn btn-primary mr-1" type="submit">Update</button>
                        <a href="{{ route('teachers.index') }}" class="btn btn-secondary">Batal</a>
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