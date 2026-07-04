<!-- Exam Form -->
<form class="was-validated" action="{{ $action }}" method="POST" id="exam-form">
    @csrf
    @if($exam)
        @method('PUT')
    @endif
    
    <div class="row">
        <div class="col-12">
            <div class="mb-3">
                <label for="title" class="form-label">Judul Ujian <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="title" name="title"
                    value="{{ old('title', $exam ? $exam->title : '') }}" 
                    placeholder="Masukkan judul ujian" required>
                <div class="invalid-feedback">
                    Judul ujian wajib diisi.
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="class_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                <select class="form-select" id="class_id" name="class_id" required>
                    <option value="">Pilih Kelas</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" 
                            {{ old('class_id', $exam ? $exam->class_id : '') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
                <div class="invalid-feedback">
                    Kelas wajib dipilih.
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="subject_id" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                <select class="form-select" id="subject_id" name="subject_id" required>
                    <option value="">Pilih Mata Pelajaran</option>
                    @if($exam && $exam->class_id)
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" 
                                {{ old('subject_id', $exam ? $exam->subject_id : '') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    @else
                        <option value="">Pilih kelas terlebih dahulu</option>
                    @endif
                </select>
                <div class="invalid-feedback">
                    Mata pelajaran wajib dipilih.
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="teacher_name" class="form-label">Pengajar</label>
                <input type="text" class="form-control" id="teacher_name" readonly 
                    value="{{ $exam ? ($exam->teacher ? $exam->teacher->name : 'Tidak ada guru') : 'Akan muncul otomatis setelah memilih mata pelajaran' }}">
                <input type="hidden" id="teacher_id" name="teacher_id" 
                    value="{{ old('teacher_id', $exam ? $exam->teacher_id : '') }}">
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="duration_minutes" class="form-label">Durasi (menit) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="duration_minutes" name="duration_minutes"
                    value="{{ old('duration_minutes', $exam ? $exam->duration_minutes : '') }}" 
                    placeholder="Contoh: 60" min="5" max="720" required>
                <div class="form-text">Durasi minimal 5 menit, maksimal 720 menit (12 jam)</div>
                <div class="invalid-feedback">
                    Durasi ujian wajib diisi.
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="passing_score" class="form-label">Nilai Kelulusan (%) <span class="text-danger">*</span></label>
                <input type="number" class="form-control" id="passing_score" name="passing_score"
                    value="{{ old('passing_score', $exam ? $exam->passing_score : 70) }}" 
                    placeholder="Contoh: 70" min="0" max="100" step="0.01" required>
                <div class="form-text">Nilai minimal untuk lulus ujian (0-100)</div>
                <div class="invalid-feedback">
                    Nilai kelulusan wajib diisi.
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="academic_year" class="form-label">Tahun Ajaran</label>
                <input type="text" class="form-control" id="academic_year" readonly 
                    value="{{ \App\Helpers\AcademicYearHelper::getCurrentAcademicYear() }}">
                <div class="form-text">Tahun ajaran aktif saat ini</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="start_time" class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                <input type="datetime-local" class="form-control" id="start_time" name="start_time"
                    value="{{ old('start_time', $exam ? $exam->start_time->format('Y-m-d\TH:i') : '') }}" 
                    required>
                <div class="invalid-feedback">
                    Waktu mulai wajib diisi.
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="mb-3">
                <label for="end_time" class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                <input type="datetime-local" class="form-control" id="end_time" name="end_time"
                    value="{{ old('end_time', $exam ? $exam->end_time->format('Y-m-d\TH:i') : '') }}" 
                    required>
                <div class="invalid-feedback">
                    Waktu selesai wajib diisi.
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="mb-3">
                <label for="description" class="form-label">Deskripsi</label>
                <textarea class="form-control" id="description" name="description" rows="3"
                    placeholder="Masukkan deskripsi atau instruksi ujian (opsional)">{{ old('description', $exam ? $exam->description : '') }}</textarea>
                <div class="form-text">Deskripsi bersifat opsional, dapat berisi instruksi atau informasi tambahan</div>
            </div>
        </div>
    </div>

    <!-- Display Settings -->
    <div class="row">
        <div class="col-md-3">
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input type="hidden" name="show_results" value="0">
                    <input class="form-check-input" type="checkbox" id="show_results" name="show_results" 
                           value="1" {{ old('show_results', $exam ? $exam->show_results : true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_results">
                        <i class="mdi mdi-eye me-1"></i> Tampilkan Hasil
                    </label>
                    <div class="form-text">Siswa dapat melihat nilai mereka</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input type="hidden" name="show_status" value="0">
                    <input class="form-check-input" type="checkbox" id="show_status" name="show_status" 
                           value="1" {{ old('show_status', $exam ? $exam->show_status : true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_status">
                        <i class="mdi mdi-flag me-1"></i> Tampilkan Status
                    </label>
                    <div class="form-text">Siswa dapat melihat status LULUS/TIDAK LULUS</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input type="hidden" name="show_review" value="0">
                    <input class="form-check-input" type="checkbox" id="show_review" name="show_review" 
                           value="1" {{ old('show_review', $exam ? $exam->show_review : true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="show_review">
                        <i class="mdi mdi-find-replace me-1"></i> Tampilkan Review
                    </label>
                    <div class="form-text">Siswa dapat melihat jawaban dan pembahasan</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <div class="form-check form-switch">
                    <input type="hidden" name="randomize_questions" value="0">
                    <input class="form-check-input" type="checkbox" id="randomize_questions" name="randomize_questions" 
                           value="1" {{ old('randomize_questions', $exam ? $exam->randomize_questions : false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="randomize_questions">
                        <i class="mdi mdi-shuffle me-1"></i> Acak Soal
                    </label>
                    <div class="form-text">Urutan soal berbeda untuk setiap siswa</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <div>
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> {{ $exam ? 'Perbarui' : 'Simpan' }} Ujian
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="mdi mdi-close"></i> Batal
                    </button>
                </div>
                @if($exam)
                    <div>
                        <button type="button" class="btn btn-danger" onclick="deleteFromExamModal('{{ $exam->id }}', '{{ $exam->title }}')">
                            <i class="mdi mdi-delete"></i> Hapus
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</form>

<!-- JavaScript moved to parent page for event delegation -->
