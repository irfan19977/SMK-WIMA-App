@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 col-md-11 col-lg-11 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4>Edit Guru</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('schedules.update', $schedules->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subject_id">Mata Pelajaran</label>
                                <select name="subject_id" class="form-control select2 @error('subject_id') is-invalid @enderror" required>
                                    <option value="">--Pilih Mata Pelajaran--</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id', $schedules->subject_id) == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->code }} - {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('subject_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="teacher_id">Guru</label>
                                <select name="teacher_id" class="form-control select2 @error('teacher_id') is-invalid @enderror" required>
                                    <option value="">--Pilih Guru--</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id', $schedules->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('teacher_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="class_id">Kelas</label>
                                <select name="class_id" class="form-control select2 @error('class_id') is-invalid @enderror" required>
                                    <option value="">--Pilih Kelas--</option>
                                    @foreach($classes as $classRoom)
                                        <option value="{{ $classRoom->id }}" {{ old('class_id', $schedules->class_id) == $classRoom->id ? 'selected' : '' }}>
                                            {{ $classRoom->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('class_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="day">Hari</label>
                                <select name="day" class="form-control select2 @error('day') is-invalid @enderror" required>
                                    <option value="">--Pilih Hari--</option>
                                    <option value="senin" {{ old('day', $schedules->day) == 'senin' ? 'selected' : '' }}>Senin</option>
                                    <option value="selasa" {{ old('day', $schedules->day) == 'selasa' ? 'selected' : '' }}>Selasa</option>
                                    <option value="rabu" {{ old('day', $schedules->day) == 'rabu' ? 'selected' : '' }}>Rabu</option>
                                    <option value="kamis" {{ old('day', $schedules->day) == 'kamis' ? 'selected' : '' }}>Kamis</option>
                                    <option value="jumat" {{ old('day', $schedules->day) == 'jumat' ? 'selected' : '' }}>Jumat</option>
                                    <option value="sabtu" {{ old('day', $schedules->day) == 'sabtu' ? 'selected' : '' }}>Sabtu</option>
                                </select>
                                @error('day')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="start_time">Waktu Mulai</label>
                                <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" value="{{ old('start_time', $schedules->start_time) }}" required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="end_time">Waktu Selesai</label>
                                <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" value="{{ old('end_time', $schedules->end_time) }}" required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="academic_year">Tahun Akademik</label>
                                <input type="text" name="academic_year" class="form-control @error('academic_year') is-invalid @enderror" placeholder="Masukkan Tahun Akademik" value="{{ old('academic_year', $schedules->academic_year) }}" required>
                                @error('academic_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <button class="btn btn-primary mr-1" type="submit">Update</button>
                        <a href="{{ route('schedules.index') }}" class="btn btn-secondary">Batal</a>
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