<div>
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form wire:submit.prevent="save">
        <div class="row">
            <div class="col-12">
                <div class="mb-3">
                    <label for="title" class="form-label">Judul Ujian <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           id="title" wire:model="title"
                           placeholder="Masukkan judul ujian" required>
                    @error('title')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="class_id" class="form-label">Kelas <span class="text-danger">*</span></label>
                    <select class="form-select @error('class_id') is-invalid @enderror" 
                            id="class_id" wire:model.live="class_id" required>
                        <option value="">Pilih Kelas</option>
                        @if($classes)
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('class_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="subject_id" class="form-label">Mata Pelajaran <span class="text-danger">*</span></label>
                    <select class="form-select @error('subject_id') is-invalid @enderror" 
                            id="subject_id" wire:model.live="subject_id"
                            @if(!$class_id) disabled @endif required>
                        <option value="">@if($class_id) Pilih Mata Pelajaran @else Pilih kelas terlebih dahulu @endif</option>
                        @if($subjects && $class_id)
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('subject_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    @if(!$class_id)
                        <div class="form-text">Pilih kelas terlebih dahulu</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="teacher_name" class="form-label">Pengajar</label>
                    <input type="text" class="form-control" id="teacher_name" 
                           value="{{ $teacher_name }}" readonly>
                    <input type="hidden" wire:model="teacher_id" id="teacher_id">
                    @if($teacher_name === 'Tidak ada jadwal aktif')
                        <div class="form-text text-warning">{{ $teacher_name }}</div>
                    @endif
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="duration_minutes" class="form-label">Durasi (menit) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('duration_minutes') is-invalid @enderror" 
                           id="duration_minutes" wire:model="duration_minutes"
                           placeholder="Contoh: 60" min="5" max="720" required>
                    <div class="form-text">Durasi minimal 5 menit, maksimal 720 menit (12 jam)</div>
                    @error('duration_minutes')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="passing_score" class="form-label">Nilai Kelulusan (%) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('passing_score') is-invalid @enderror" 
                           id="passing_score" wire:model="passing_score"
                           placeholder="Contoh: 70" min="0" max="100" required>
                    <div class="form-text">Nilai minimal untuk lulus ujian</div>
                    @error('passing_score')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
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
                    <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" 
                           id="start_time" wire:model="start_time" required>
                    @error('start_time')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="end_time" class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror" 
                           id="end_time" wire:model="end_time" required>
                    @error('end_time')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="mb-3">
                    <label for="description" class="form-label">Deskripsi Ujian</label>
                    <textarea class="form-control" id="description" wire:model="description" rows="3"
                              placeholder="Masukkan deskripsi atau instruksi ujian (opsional)"></textarea>
                    <div class="form-text">Deskripsi bersifat opsional, dapat berisi instruksi atau informasi tambahan</div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="mdi mdi-close"></i> Batal
                </button>
            </div>
            <div>
                <button type="submit" class="btn btn-primary">
                    <span wire:loading wire:target="save">
                        <i class="mdi mdi-loading mdi-spin"></i> Menyimpan...
                    </span>
                    <span wire:loading.remove wire:target="save">
                        <i class="mdi mdi-content-save"></i> Simpan Ujian
                    </span>
                </button>
            </div>
        </div>
    </form>

    <script>
        // Auto-set minimum datetime to now
        document.addEventListener('livewire:init', function() {
            const now = new Date();
            const localDateTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000)
                .toISOString().slice(0, 16);
            
            const startInput = document.getElementById('start_time');
            const endInput = document.getElementById('end_time');
            
            if (startInput) {
                startInput.min = localDateTime;
            }
            if (endInput) {
                endInput.min = localDateTime;
            }

            // Auto-adjust end time when start time changes
            if (startInput && endInput) {
                startInput.addEventListener('change', function() {
                    const startTime = new Date(this.value);
                    const duration = parseInt(document.getElementById('duration_minutes').value) || 60;
                    const endTime = new Date(startTime.getTime() + duration * 60000);
                    endInput.value = endTime.toISOString().slice(0, 16);
                    endInput.min = startTime.toISOString().slice(0, 16);
                });
            }

            // Listen for Livewire events
            Livewire.on('closeModalAndRedirect', function(data) {
                // Close modal
                const modal = document.querySelector('.modal.show');
                if (modal) {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }
                
                // Show success message
                if (data.message) {
                    // Create success notification
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
                    alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                    alert.innerHTML = `
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.body.appendChild(alert);
                    
                    // Auto remove after 3 seconds
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 3000);
                }
                
                // Redirect after a short delay
                setTimeout(() => {
                    window.location.href = data.url;
                }, 500);
            });

            Livewire.on('showError', function(message) {
                // Create error notification
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger alert-dismissible fade show position-fixed';
                alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                alert.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alert);
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 5000);
            });
        });
    </script>
</div>
