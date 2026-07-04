<div>
    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Make function globally available -->
    <script>
        window.confirmSubmitExam = function() {
            console.log('confirmSubmitExam called');
            
            Swal.fire({
                title: 'Konfirmasi Pengumpulan',
                text: 'Apakah Anda yakin ingin mengumpulkan ujian? Jawaban yang sudah Anda isi akan disimpan dan tidak dapat diubah lagi.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Kumpulkan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                console.log('SweetAlert result:', result);
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Mengumpulkan...',
                        text: 'Sedang menyimpan jawaban Anda',
                        icon: 'info',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    console.log('Calling submitExam...');
                    // Call Livewire method to submit
                    @this.call('submitExam');
                }
            });
        };
    </script>
    
    <div class="row">
        <!-- Question Navigation -->
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-compass me-2"></i>Navigasi Soal
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Timer -->
                    <div class="alert alert-info text-center mb-3">
                        <h4 class="mb-1">
                            <i class="mdi mdi-clock me-1"></i>
                            <span id="time-display" wire:ignore>{{ floor($timeLeft / 60) }}:{{ str_pad(($timeLeft % 60), 2, '0', STR_PAD_LEFT) }}</span>
                        </h4>
                        <small class="mb-0">Sisa Waktu</small>
                    </div>

                    <!-- Question Numbers -->
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Soal ({{ $totalQuestions }})</h6>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($questions as $index => $question)
                                <button wire:click="goToQuestion({{ $index }})" 
                                        class="btn btn-sm {{ $currentQuestionIndex == $index ? 'btn-primary' : ($this->isQuestionAnswered($question->id) ? 'btn-success' : 'btn-outline-secondary') }} question-number" 
                                        data-question-id="{{ $question->id }}"
                                        data-question-index="{{ $index }}">
                                    {{ $index + 1 }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Progress -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="fw-bold">Progress</small>
                            <span class="badge bg-secondary">{{ $this->answeredCount }}/{{ $totalQuestions }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $this->progressPercentage }}%" aria-valuenow="{{ $this->answeredCount }}" 
                                 aria-valuemin="0" aria-valuemax="{{ $totalQuestions }}">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="button" class="btn btn-success w-100 btn-lg" wire:click="submitExam">
                        <i class="mdi mdi-check-circle me-1"></i> Selesai & Kumpulkan
                    </button>
                </div>
            </div>
        </div>

        <style>
    .option-item {
        border: 2px solid #dee2e6;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .option-item:hover {
        border-color: #0d6efd;
        background: #f8f9fa;
    }
    
    .option-item.selected {
        border-color: #198754;
        background: #d1e7dd;
        box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
    }
    
    .option-item input[type="radio"] {
        display: none;
    }
    
    .option-label {
        margin: 0;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: normal !important;
    }
    
    .badge {
        font-size: 0.75em;
    }
    
    .nav-buttons .btn {
        font-size: 0.875rem !important;
    }
    
    .question-number {
        width: 40px !important;
        height: 40px !important;
        font-size: 0.875rem !important;
        font-weight: 600 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0 !important;
        border-radius: 8px !important;
    }
    
    @media (max-width: 768px) {
        .nav-buttons .btn {
            font-size: 0.75rem !important;
            padding: 0.5rem 1rem;
        }
        
        .question-number {
            width: 36px !important;
            height: 36px !important;
            font-size: 0.75rem !important;
        }
    }
</style>

<!-- Questions -->
        <div class="col-md-9 d-flex flex-column">
            <form wire:submit.prevent="confirmSubmit" class="flex-grow-1 d-flex flex-column">
                @if($currentQuestion = $this->getCurrentQuestion())
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-light border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-0 text-primary">
                                        <i class="mdi mdi-help-circle me-2"></i>Soal {{ $currentQuestionIndex + 1 }}
                                    </h5>
                                    <div class="fw-bold fs-6 text-dark">
                                        @switch($currentQuestion->question_type)
                                            @case('multiple_choice')
                                                <span>Pilihan Ganda</span>
                                                @break
                                            @case('multiple_choice_complex')
                                                <span>Pilihan Ganda Kompleks</span>
                                                @break
                                            @case('true_false')
                                                <span>Benar/Salah</span>
                                                @break
                                            @case('essay')
                                                <span>Uraian</span>
                                                @break
                                            @default
                                                <span>{{ $currentQuestion->question_type }}</span>
                                        @endswitch
                                        <span class="ms-2"><span class="badge bg-warning">{{ $currentQuestion->points }} poin</span></span>
                                    </div>
                                </div>
                                <span class="badge bg-primary rounded-pill">{{ $currentQuestionIndex + 1 }}/{{ $totalQuestions }}</span>
                            </div>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="mb-4">
                                <p class="fs-5 mb-0">{{ $currentQuestion->question_text }}</p>
                            </div>

                            <!-- Media Display -->
                            @if($currentQuestion->hasMedia())
                                <div class="mb-4">
                                    <div class="media-container">
                                        @if($currentQuestion->isImageMedia())
                                            <img src="{{ $currentQuestion->getMediaUrl() }}" class="img-fluid rounded" style="max-height: 300px; width: 100%; object-fit: contain;" alt="{{ $currentQuestion->media_caption }}">
                                        @elseif($currentQuestion->isVideoMedia())
                                            <video controls class="w-100 rounded" style="max-height: 300px;">
                                                <source src="{{ $currentQuestion->getMediaUrl() }}" type="video/mp4">
                                                Browser Anda tidak mendukung video tag.
                                            </video>
                                        @elseif($currentQuestion->isAudioMedia())
                                            <div class="audio-container p-3 bg-light rounded">
                                                <audio controls class="w-100">
                                                    <source src="{{ $currentQuestion->getMediaUrl() }}" type="audio/mpeg">
                                                    Browser Anda tidak mendukung audio tag.
                                                </audio>
                                            </div>
                                        @endif
                                        @if($currentQuestion->media_caption)
                                            <p class="text-muted mt-2 mb-0 small text-center">{{ $currentQuestion->media_caption }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @switch($currentQuestion->question_type)
                                @case('multiple_choice')
                                    <div class="options-container">
                                        @foreach($currentQuestion->options->sortBy('option_label') as $option)
                                            <div class="option-item {{ $answers[$currentQuestion->id] == $option->id ? 'selected' : '' }}" 
                                                 wire:click="$set('answers.{{ $currentQuestion->id }}', '{{ $option->id }}')">
                                                <label class="option-label fs-6">
                                                    <span class="badge bg-secondary me-2">{{ $option->option_label }}</span>
                                                    {{ $option->option_text }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @break

                                @case('multiple_choice_complex')
                                    <div class="options-container">
                                        <div class="alert alert-info mb-3">
                                            <i class="mdi mdi-information me-2"></i>
                                            <strong>Pilihan Ganda Kompleks:</strong> Pilih semua jawaban yang menurut Anda benar.
                                            <br>Penilaian: (jawaban benar / total jawaban benar) × {{ $currentQuestion->points }} poin
                                        </div>
                                        @foreach($currentQuestion->options->sortBy('option_label') as $option)
                                            <div class="option-item {{ in_array($option->id, $answers[$currentQuestion->id] ?? []) ? 'selected' : '' }}" 
                                                 wire:click="toggleComplexOption('{{ $currentQuestion->id }}', '{{ $option->id }}')">
                                                <label class="option-label fs-6">
                                                    <span class="badge bg-secondary me-2">{{ $option->option_label }}</span>
                                                    {{ $option->option_text }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    @break

                                @case('true_false')
                                    <div class="options-container">
                                        <div class="alert alert-info mb-3">
                                            <i class="mdi mdi-information me-2"></i>
                                            <strong>Benar/Salah:</strong> Tentukan apakah setiap pernyataan Benar atau Salah
                                        </div>
                                        @foreach($currentQuestion->options->sortBy('option_label') as $option)
                                            <div class="card mb-3">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-start">
                                                        <div class="me-3">
                                                            <span class="badge bg-primary fs-6">{{ $option->option_label }}</span>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="card-title mb-3">{{ $option->option_text }}</h6>
                                                            <div class="btn-group w-100" role="group">
                                                                    <input type="radio" class="btn-check" name="answers[{{ $currentQuestion->id }}][{{ $option->option_label }}]" id="benar_{{ $currentQuestion->id }}_{{ $option->option_label }}" value="true" wire:model.change="answers.{{ $currentQuestion->id }}.{{ $option->option_label }}">
                                                                    <label class="btn btn-outline-success" for="benar_{{ $currentQuestion->id }}_{{ $option->option_label }}">
                                                                        <i class="mdi mdi-check me-1"></i>Benar
                                                                    </label>
                                                                    
                                                                    <input type="radio" class="btn-check" name="answers[{{ $currentQuestion->id }}][{{ $option->option_label }}]" id="salah_{{ $currentQuestion->id }}_{{ $option->option_label }}" value="false" wire:model.change="answers.{{ $currentQuestion->id }}.{{ $option->option_label }}">
                                                                    <label class="btn btn-outline-danger" for="salah_{{ $currentQuestion->id }}_{{ $option->option_label }}">
                                                                        <i class="mdi mdi-close me-1"></i>Salah
                                                                    </label>
                                                                </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @break

                                @case('essay')
                                    <div class="options-container flex-grow-1">
                                        <div class="form-floating h-100">
                                            <textarea class="form-control" 
                                                      wire:model.lazy="answers.{{ $currentQuestion->id }}"
                                                      id="essay_{{ $currentQuestion->id }}"
                                                      rows="8" 
                                                      placeholder="Tulis jawaban Anda di sini..."
                                                      style="min-height: 150px;"></textarea>
                                            <label for="essay_{{ $currentQuestion->id }}" class="fs-6">Jawaban Anda</label>
                                        </div>
                                    </div>
                                    @break
                            @endswitch
                        </div>
                    </div>
                @endif

                <!-- Navigation Buttons -->
                <div class="card shadow-sm border-0 mt-auto">
                    <div class="card-body">
                        <div class="nav-buttons d-flex justify-content-between align-items-center mt-auto pt-3">
                            <button type="button" class="btn btn-secondary btn-lg flex-fill me-2" wire:click="previousQuestion" {{ $currentQuestionIndex == 0 ? 'disabled' : '' }}>
                                <i class="mdi mdi-arrow-left me-1"></i> <span class="d-none d-sm-inline">Sebelumnya</span>
                            </button>
                            
                            @if($currentQuestionIndex == $totalQuestions - 1)
                                <!-- Last Question - Show Submit Button -->
                                @if(!$this->allQuestionsAnswered)
                                    <div class="alert alert-warning mb-2">
                                        <i class="mdi mdi-alert me-2"></i>
                                        <strong>Perhatian:</strong> 
                                        @php
                                            $remainingQuestions = $totalQuestions - $this->answeredCount;
                                        @endphp
                                        Anda masih memiliki {{ $remainingQuestions }} soal yang belum dikerjakan. 
                                        Selesaikan semua soal sebelum mengumpulkan jawaban.
                                    </div>
                                @endif
                                <button type="button" 
                                        class="btn btn-success btn-lg flex-fill ms-2 {{ !$this->allQuestionsAnswered ? 'disabled' : '' }}" 
                                        onclick="{{ $this->allQuestionsAnswered ? 'confirmSubmitExam()' : 'false' }}"
                                        title="{{ !$this->allQuestionsAnswered ? 'Selesaikan semua soal terlebih dahulu' : 'Kumpulkan jawaban Anda' }}">
                                    <i class="mdi mdi-check-circle me-1"></i> <span class="d-none d-sm-inline">Selesai & Kumpulkan</span>
                                </button>
                            @else
                                <!-- Not Last Question - Show Next Button -->
                                <button type="button" class="btn btn-primary btn-lg flex-fill ms-2" wire:click="nextQuestion">
                                    <span class="d-none d-sm-inline">Selanjutnya</span> <i class="mdi mdi-arrow-right ms-1"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Timer Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let timeLeft = {{ $timeLeft }};
            const timeDisplay = document.getElementById('time-display');

            // Set initial display
            timeDisplay.textContent = `${Math.floor(timeLeft / 60)}:${(timeLeft % 60).toString().padStart(2, '0')}`;

            function updateTimer() {
                const minutes = Math.floor(timeLeft / 60);
                const seconds = timeLeft % 60;
                timeDisplay.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

                if (timeLeft > 0) {
                    timeLeft--;
                    setTimeout(updateTimer, 1000);
                } else {
                    // Auto-submit when time is up
                    @this.call('submitExam');
                }
            }

            updateTimer();

            // Prevent accidental navigation
            window.addEventListener('beforeunload', function(e) {
                if ({{ $this->answeredCount }} > 0 && isExamActive) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            });
            
            // Prevent copy/paste during exam
            document.addEventListener('copy', function(e) {
                if (isExamActive) {
                    e.preventDefault();
                    return false;
                }
            });
            
            document.addEventListener('paste', function(e) {
                if (isExamActive) {
                    e.preventDefault();
                    return false;
                }
            });
        });

        function confirmSubmitExam() {
            console.log('confirmSubmitExam called');
            
            Swal.fire({
                title: 'Konfirmasi Pengumpulan',
                text: 'Apakah Anda yakin ingin mengumpulkan ujian? Jawaban yang sudah Anda isi akan disimpan dan tidak dapat diubah lagi.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Kumpulkan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then((result) => {
                console.log('SweetAlert result:', result);
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Mengumpulkan...',
                        text: 'Sedang menyimpan jawaban Anda',
                        icon: 'info',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    console.log('Calling submitExam...');
                    // Call Livewire method to submit
                    @this.call('submitExam');
                }
            });
        }
    </script>
    <!-- Auto Fullscreen Script with Security -->
<script>
    // Global variables for fullscreen security
    let fullscreenExitCount = 0;
    const maxFullscreenExits = 3;
    let isExamActive = true;

    // Request fullscreen on page load
    function enterFullscreen() {
        const el = document.documentElement;
        if (el.requestFullscreen) {
            el.requestFullscreen();
        } else if (el.webkitRequestFullscreen) {
            el.webkitRequestFullscreen();
        } else if (el.mozRequestFullScreen) {
            el.mozRequestFullScreen();
        } else if (el.msRequestFullscreen) {
            el.msRequestFullscreen();
        }
    }

    // Handle fullscreen exit with warning system
    function handleFullscreenExit() {
        if (!isExamActive) return;

        fullscreenExitCount++;
        
        if (fullscreenExitCount >= maxFullscreenExits) {
            // Auto submit after 3 attempts
            Swal.fire({
                title: 'Ujian Dihentikan!',
                text: 'Anda telah 3 kali keluar dari mode fullscreen. Ujian akan dikumpulkan secara otomatis.',
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                allowEscapeKey: false,
            }).then(() => {
                isExamActive = false;
                @this.call('submitExam');
            });
        } else {
            // Show warning with remaining attempts
            const remaining = maxFullscreenExits - fullscreenExitCount;
            Swal.fire({
                title: 'Peringatan!',
                html: `Anda keluar dari mode fullscreen!<br>
                       <strong>Percobaan ke-${fullscreenExitCount} dari ${maxFullscreenExits}</strong><br>
                       Sisa percobaan: ${remaining}<br><br>
                       Kembali ke mode fullscreen untuk melanjutkan ujian.`,
                icon: 'warning',
                confirmButtonColor: '#ff6b6b',
                confirmButtonText: 'Kembali ke Layar Penuh',
                allowOutsideClick: false,
                allowEscapeKey: false,
            }).then(() => {
                if (isExamActive) {
                    enterFullscreen();
                }
            });
        }
    }

    // Check fullscreen status
    function handleFullscreenChange() {
        const isFullscreen = document.fullscreenElement
            || document.webkitFullscreenElement
            || document.mozFullScreenElement
            || document.msFullscreenElement;

        if (!isFullscreen && isExamActive) {
            handleFullscreenExit();
        }
    }

    // Block common shortcuts for cheating
    function blockShortcuts(e) {
        // Block F12, Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+Shift+C
        if (e.keyCode === 123 || 
            (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74 || e.keyCode === 67))) {
            e.preventDefault();
            return false;
        }
        
        // Block Ctrl+U (view source)
        if (e.ctrlKey && e.keyCode === 85) {
            e.preventDefault();
            return false;
        }
        
        // Block Alt+Tab (partially)
        if (e.altKey && e.keyCode === 9) {
            e.preventDefault();
            return false;
        }
    }

    // Block right click context menu
    function blockContextMenu(e) {
        e.preventDefault();
        return false;
    }

    // Initialize fullscreen and security
    document.addEventListener('DOMContentLoaded', function () {
        // Show confirmation dialog before entering fullscreen
        Swal.fire({
            title: 'Mulai Ujian',
            text: 'Ujian akan dimulai dalam mode fullscreen. Pastikan Anda siap!',
            icon: 'info',
            confirmButtonColor: '#28a745',
            confirmButtonText: 'Mulai Ujian',
            allowOutsideClick: false,
            allowEscapeKey: false,
        }).then(() => {
            // Enter fullscreen after confirmation
            enterFullscreen();
        });

        // Add event listeners for security
        document.addEventListener('fullscreenchange', handleFullscreenChange);
        document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
        document.addEventListener('mozfullscreenchange', handleFullscreenChange);
        document.addEventListener('MSFullscreenChange', handleFullscreenChange);
        
        // Block shortcuts and context menu
        document.addEventListener('keydown', blockShortcuts);
        document.addEventListener('contextmenu', blockContextMenu);
        
        // Prevent window focus loss
        window.addEventListener('blur', function() {
            if (isExamActive && document.fullscreenElement) {
                window.focus();
            }
        });
    });

    // Prevent back navigation
    window.addEventListener('popstate', function(e) {
        if (isExamActive) {
            e.preventDefault();
            history.pushState(null, null, location.href);
        }
    });

    // Push initial state to prevent back
    history.pushState(null, null, location.href);
</script>
</div>

<!-- Confirmation Modal -->
<script>
    window.addEventListener('livewire:init', () => {
        Livewire.on('showSubmitConfirmation', () => {
            Swal.fire({
                title: 'Kumpulkan Jawaban?',
                text: 'Pastikan Anda sudah menjawab semua soal. Tindakan ini tidak dapat dibatalkan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Kumpulkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.confirmSubmit();
                }
            });
        });

        Livewire.on('showError', (message) => {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: message
            });
        });
    });
</script>
