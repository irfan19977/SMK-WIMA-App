@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 col-md-11 col-lg-11 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4>Tambah Guru</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('schedules.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="subject_id">Mata Pelajaran</label>
                                <select name="subject_id" class="form-control select2 @error('subject_id') is-invalid @enderror" required>
                                    <option value="">--Pilih Mata Pelajaran--</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->code }} - {{ $subject->name }}</option>
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
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
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
                                        <option value="{{ $classRoom->id }}">{{ $classRoom->name }}</option>
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
                                    <option value="Senin">Senin</option>
                                    <option value="Selasa">Selasa</option>
                                    <option value="Rabu">Rabu</option>
                                    <option value="Kamis">Kamis</option>
                                    <option value="Jumat">Jumat</option>
                                    <option value="Sabtu">Sabtu</option>
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
                                <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" required>
                                @error('start_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="end_time">Waktu Selesai</label>
                                <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" required>
                                @error('end_time')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="academic_year">Tahun Akademik</label>
                                <input type="text" name="academic_year" class="form-control @error('academic_year') is-invalid @enderror" placeholder="Masukkan Tahun Akademik" required>
                                @error('academic_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <button class="btn btn-primary mr-1" type="submit">Simpan</button>
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
    });
</script>
@endpush
