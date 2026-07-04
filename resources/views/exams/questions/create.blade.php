<!-- Form Content Only (No Layout) -->
<form id="questionForm" action="{{ $question ? route('exams.questions.update', $question->id) : route('exams.questions.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($question)
        @method('PUT')
    @endif
    
    <input type="hidden" name="exam_id" value="{{ $examId ?? $question->exam_id }}">

    <!-- Question Type Selection -->
    <div class="row mb-4">
        <div class="col-md-12">
            <label class="form-label">Tipe Soal <span class="text-danger">*</span></label>
            <select class="form-select" id="question_type" name="question_type" required>
                <option value="">Pilih Tipe Soal</option>
                <option value="multiple_choice" {{ $question && $question->question_type == 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda</option>
                <option value="multiple_choice_complex" {{ $question && $question->question_type == 'multiple_choice_complex' ? 'selected' : '' }}>Pilihan Ganda Kompleks</option>
                <option value="true_false" {{ $question && $question->question_type == 'true_false' ? 'selected' : '' }}>Benar/Salah</option>
                <option value="essay" {{ $question && $question->question_type == 'essay' ? 'selected' : '' }}>Essay</option>
            </select>
        </div>
    </div>

    <!-- Question Text -->
    <div class="row mb-4">
        <div class="col-md-12">
            <label class="form-label">Teks Soal <span class="text-danger">*</span></label>
            <textarea class="form-control" id="question_text" name="question_text" rows="4" required placeholder="Masukkan teks soal">{{ $question->question_text ?? '' }}</textarea>
        </div>
    </div>

    <!-- Media Upload -->
    <div class="row mb-4">
        <div class="col-md-12">
            <label class="form-label">Media (Opsional)</label>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Tipe Media</label>
                            <select class="form-select" id="media_type" name="media_type">
                                <option value="">Tanpa Media</option>
                                <option value="image" {{ $question && $question->media_type == 'image' ? 'selected' : '' }}>Gambar</option>
                                <option value="video" {{ $question && $question->media_type == 'video' ? 'selected' : '' }}>Video</option>
                                <option value="audio" {{ $question && $question->media_type == 'audio' ? 'selected' : '' }}>Audio</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">File Media</label>
                            <input type="file" class="form-control" id="media_file" name="media_file" accept="image/*,video/*,audio/*">
                            @if($question && $question->hasMedia())
                                <div class="mt-2">
                                    <small class="text-muted">File saat ini: {{ $question->media_caption ?? basename($question->media_path) }}</small>
                                    <button type="button" class="btn btn-sm btn-danger ms-2" onclick="removeMedia()">
                                        <i class="mdi mdi-delete"></i> Hapus
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="form-label">Caption Media (Opsional)</label>
                            <input type="text" class="form-control" id="media_caption" name="media_caption" value="{{ $question->media_caption ?? '' }}" placeholder="Masukkan caption untuk media">
                        </div>
                    </div>
                    @if($question && $question->hasMedia())
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <label class="form-label">Preview Media</label>
                                <div class="media-preview">
                                    @if($question->isImageMedia())
                                        <img src="{{ $question->getMediaUrl() }}" class="img-fluid" style="max-height: 200px;" alt="{{ $question->media_caption }}">
                                    @elseif($question->isVideoMedia())
                                        <video controls style="max-height: 200px; width: 100%;">
                                            <source src="{{ $question->getMediaUrl() }}" type="video/mp4">
                                            Browser Anda tidak mendukung video tag.
                                        </video>
                                    @elseif($question->isAudioMedia())
                                        <audio controls style="width: 100%;">
                                            <source src="{{ $question->getMediaUrl() }}" type="audio/mpeg">
                                            Browser Anda tidak mendukung audio tag.
                                        </audio>
                                    @endif
                                    @if($question->media_caption)
                                        <p class="text-muted mt-2">{{ $question->media_caption }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Points -->
    <div class="row mb-4">
        <div class="col-md-3">
            <label class="form-label">Poin <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="points" name="points" value="{{ $question->points ?? 1 }}" min="1" required>
        </div>
    </div>

    <!-- Multiple Choice Options -->
    <div id="options-container" style="display: {{ $question && $question->question_type == 'multiple_choice' ? 'block' : 'none' }};">
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label">Pilihan Jawaban</label>
                <button type="button" class="btn btn-sm btn-success" id="add-option">
                    <i class="mdi mdi-plus me-1"></i> Tambah Opsi
                </button>
            </div>
        </div>
        
        <div id="options-list">
            @if($question && $question->question_type == 'multiple_choice')
                @foreach($question->options->sortBy('option_label') as $index => $option)
                    <div class="row mb-2 option-item">
                        <div class="col-md-1">
                            <label class="form-label">Label</label>
                            <input type="text" class="form-control" name="option_labels[]" value="{{ $option->option_label }}" readonly>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Teks Opsi</label>
                            <input type="text" class="form-control" name="option_texts[]" value="{{ $option->option_text }}" placeholder="Masukkan teks opsi">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Benar?</label>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="is_correct" value="{{ $index }}" {{ $option->is_correct ? 'checked' : '' }}>
                                <label class="form-check-label">Ya</label>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label><br>
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeOption(this)">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Default options for new question -->
                <div class="row mb-2 option-item">
                    <div class="col-md-1">
                        <label class="form-label">Label</label>
                        <input type="text" class="form-control" name="option_labels[]" value="A" readonly>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Teks Opsi</label>
                        <input type="text" class="form-control" name="option_texts[]" placeholder="Masukkan teks opsi">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Benar?</label>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="radio" name="is_correct" value="0">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label><br>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeOption(this)">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </div>
                </div>
                <div class="row mb-2 option-item">
                    <div class="col-md-1">
                        <label class="form-label">Label</label>
                        <input type="text" class="form-control" name="option_labels[]" value="B" readonly>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Teks Opsi</label>
                        <input type="text" class="form-control" name="option_texts[]" placeholder="Masukkan teks opsi">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Benar?</label>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="radio" name="is_correct" value="1">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label><br>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeOption(this)">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Multiple Choice Complex Options -->
    <div id="complex-options-container" style="display: {{ $question && $question->question_type == 'multiple_choice_complex' ? 'block' : 'none' }};">
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label">Pilihan Ganda Kompleks</label>
                <div class="alert alert-info">
                    <i class="mdi mdi-information me-2"></i>
                    Pilihan ganda kompleks memungkinkan siswa memilih lebih dari satu jawaban benar.
                    <br>Penilaian otomatis: (jawaban benar / total jawaban benar) × poin soal
                </div>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label">Pilihan Jawaban</label>
                <button type="button" class="btn btn-sm btn-success" id="add-complex-option">
                    <i class="mdi mdi-plus me-1"></i> Tambah Opsi
                </button>
            </div>
        </div>
        
        <div id="complex-options-list">
            @if($question && $question->question_type == 'multiple_choice_complex')
                @foreach($question->options->sortBy('option_label') as $index => $option)
                    <div class="row mb-2 complex-option-item">
                        <div class="col-md-1">
                            <label class="form-label">Label</label>
                            <input type="text" class="form-control" name="option_labels[]" value="{{ $option->option_label }}" readonly>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Teks Opsi</label>
                            <input type="text" class="form-control" name="option_texts[]" value="{{ $option->option_text }}" placeholder="Masukkan teks opsi">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Benar?</label>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="is_correct_multiple[]" value="{{ $index }}" {{ $option->is_correct ? 'checked' : '' }}>
                                <label class="form-check-label">Ya</label>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label><br>
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeComplexOption(this)">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Default options for new question -->
                <div class="row mb-2 complex-option-item">
                    <div class="col-md-1">
                        <label class="form-label">Label</label>
                        <input type="text" class="form-control" name="option_labels[]" value="A" readonly>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Teks Opsi</label>
                        <input type="text" class="form-control" name="option_texts[]" placeholder="Masukkan teks opsi">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Benar?</label>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="is_correct_multiple[]" value="0">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label><br>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeComplexOption(this)">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </div>
                </div>
                <div class="row mb-2 complex-option-item">
                    <div class="col-md-1">
                        <label class="form-label">Label</label>
                        <input type="text" class="form-control" name="option_labels[]" value="B" readonly>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Teks Opsi</label>
                        <input type="text" class="form-control" name="option_texts[]" placeholder="Masukkan teks opsi">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Benar?</label>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="is_correct_multiple[]" value="1">
                            <label class="form-check-label">Ya</label>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label><br>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeComplexOption(this)">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- True/False Options -->
    <div id="true-false-container" style="display: {{ $question && $question->question_type == 'true_false' ? 'block' : 'none' }};">
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label">Pernyataan <span class="text-danger">*</span></label>
                <div class="alert alert-info">
                    <i class="mdi mdi-information me-2"></i>
                    Masukkan beberapa pernyataan terkait topik. Siswa akan menentukan apakah setiap pernyataan Benar atau Salah.
                </div>
            </div>
        </div>
        
        <div class="row mb-3">
            <div class="col-md-12">
                <label class="form-label">Daftar Pernyataan</label>
                <button type="button" class="btn btn-sm btn-success" id="add-statement">
                    <i class="mdi mdi-plus me-1"></i> Tambah Pernyataan
                </button>
            </div>
        </div>
        
        <div id="statements-list">
            @if($question && $question->question_type == 'true_false')
                @foreach($question->options->sortBy('option_label') as $index => $option)
                    <div class="row mb-2 statement-item">
                        <div class="col-md-1">
                            <label class="form-label">No.</label>
                            <input type="text" class="form-control" name="statement_numbers[]" value="{{ $index + 1 }}" readonly>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Pernyataan</label>
                            <input type="text" class="form-control" name="statement_texts[]" value="{{ $option->option_text }}" placeholder="Masukkan pernyataan">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Jawaban</label>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="radio" name="statement_{{ $index }}_answer" value="true" {{ $option->is_correct ? 'checked' : '' }}>
                                <label class="form-check-label">Benar</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="statement_{{ $index }}_answer" value="false" {{ !$option->is_correct ? 'checked' : '' }}>
                                <label class="form-check-label">Salah</label>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label><br>
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeStatement(this)">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Default statements for new question -->
                <div class="row mb-2 statement-item">
                    <div class="col-md-1">
                        <label class="form-label">No.</label>
                        <input type="text" class="form-control" name="statement_numbers[]" value="1" readonly>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Pernyataan</label>
                        <input type="text" class="form-control" name="statement_texts[]" placeholder="Masukkan pernyataan">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Jawaban</label>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="radio" name="statement_0_answer" value="true">
                            <label class="form-check-label">Benar</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="statement_0_answer" value="false">
                            <label class="form-check-label">Salah</label>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label><br>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeStatement(this)">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </div>
                </div>
                <div class="row mb-2 statement-item">
                    <div class="col-md-1">
                        <label class="form-label">No.</label>
                        <input type="text" class="form-control" name="statement_numbers[]" value="2" readonly>
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Pernyataan</label>
                        <input type="text" class="form-control" name="statement_texts[]" placeholder="Masukkan pernyataan">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Jawaban</label>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="radio" name="statement_1_answer" value="true">
                            <label class="form-check-label">Benar</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="statement_1_answer" value="false">
                            <label class="form-check-label">Salah</label>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label><br>
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeStatement(this)">
                            <i class="mdi mdi-delete"></i>
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Explanation -->
    <div class="row mb-4">
        <div class="col-md-12">
            <label class="form-label">Penjelasan (Opsional)</label>
            <textarea class="form-control" id="explanation" name="explanation" rows="3" placeholder="Masukkan penjelasan untuk jawaban benar">{{ $question->explanation ?? '' }}</textarea>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary" onclick="handleSubmit(event)">
                <i class="mdi mdi-content-save me-1"></i> {{ $question ? 'Update Soal' : 'Simpan Soal' }}
            </button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="mdi mdi-close me-1"></i> Batal
            </button>
        </div>
    </div>
</form>

<script>
// Question form functions
function updateQuestionForm(type) {
    const optionsContainer = document.getElementById('options-container');
    const complexOptionsContainer = document.getElementById('complex-options-container');
    const trueFalseContainer = document.getElementById('true-false-container');
    
    // Hide all containers first
    optionsContainer.style.display = 'none';
    complexOptionsContainer.style.display = 'none';
    trueFalseContainer.style.display = 'none';
    
    // Show relevant container based on type
    if (type === 'multiple_choice') {
        optionsContainer.style.display = 'block';
    } else if (type === 'multiple_choice_complex') {
        complexOptionsContainer.style.display = 'block';
    } else if (type === 'true_false') {
        trueFalseContainer.style.display = 'block';
    }
}

function addOption() {
    const container = document.getElementById('options-container');
    const optionsList = document.getElementById('options-list');
    const optionCount = optionsList.children.length;
    
    const optionDiv = document.createElement('div');
    optionDiv.className = 'row mb-2 option-item';
    optionDiv.innerHTML = `
        <div class="col-md-1">
            <label class="form-label">Label</label>
            <input type="text" class="form-control" name="option_labels[]" value="${String.fromCharCode(65 + optionCount)}" readonly>
        </div>
        <div class="col-md-8">
            <label class="form-label">Teks Opsi</label>
            <input type="text" class="form-control" name="option_texts[]" placeholder="Masukkan teks opsi">
        </div>
        <div class="col-md-2">
            <label class="form-label">Benar?</label>
            <div class="form-check mt-2">
                <input class="form-check-input" type="radio" name="is_correct" value="${optionCount}">
                <label class="form-check-label">Ya</label>
            </div>
        </div>
        <div class="col-md-1">
            <label class="form-label">&nbsp;</label><br>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeOption(this)">
                <i class="mdi mdi-delete"></i>
            </button>
        </div>
    `;
    
    optionsList.appendChild(optionDiv);
}

function removeOption(button) {
    button.closest('.option-item').remove();
}

function addComplexOption() {
    const container = document.getElementById('complex-options-container');
    const optionsList = document.getElementById('complex-options-list');
    const optionCount = optionsList.children.length;
    
    const optionDiv = document.createElement('div');
    optionDiv.className = 'row mb-2 complex-option-item';
    optionDiv.innerHTML = `
        <div class="col-md-1">
            <label class="form-label">Label</label>
            <input type="text" class="form-control" name="option_labels[]" value="${String.fromCharCode(65 + optionCount)}" readonly>
        </div>
        <div class="col-md-8">
            <label class="form-label">Teks Opsi</label>
            <input type="text" class="form-control" name="option_texts[]" placeholder="Masukkan teks opsi">
        </div>
        <div class="col-md-2">
            <label class="form-label">Benar?</label>
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="is_correct_multiple[]" value="${optionCount}">
                <label class="form-check-label">Ya</label>
            </div>
        </div>
        <div class="col-md-1">
            <label class="form-label">&nbsp;</label><br>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeComplexOption(this)">
                <i class="mdi mdi-delete"></i>
            </button>
        </div>
    `;
    
    optionsList.appendChild(optionDiv);
}

function removeComplexOption(button) {
    button.closest('.complex-option-item').remove();
}

function removeMedia() {
    document.getElementById('media_type').value = '';
    document.getElementById('media_file').value = '';
    document.getElementById('media_caption').value = '';
    
    // Hide preview if exists
    const preview = document.querySelector('.media-preview');
    if (preview) {
        preview.closest('.row').style.display = 'none';
    }
}

function addStatement() {
    const statementsList = document.getElementById('statements-list');
    const statementCount = statementsList.querySelectorAll('.statement-item').length + 1;
    
    const statementDiv = document.createElement('div');
    statementDiv.className = 'row mb-2 statement-item';
    statementDiv.innerHTML = `
        <div class="col-md-1">
            <label class="form-label">No.</label>
            <input type="text" class="form-control" name="statement_numbers[]" value="${statementCount}" readonly>
        </div>
        <div class="col-md-8">
            <label class="form-label">Pernyataan</label>
            <input type="text" class="form-control" name="statement_texts[]" placeholder="Masukkan pernyataan">
        </div>
        <div class="col-md-2">
            <label class="form-label">Jawaban</label>
            <div class="form-check mt-2">
                <input class="form-check-input" type="radio" name="statement_${statementCount}_answer" value="true">
                <label class="form-check-label">Benar</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="statement_${statementCount}_answer" value="false" checked>
                <label class="form-check-label">Salah</label>
            </div>
        </div>
        <div class="col-md-1">
            <label class="form-label">&nbsp;</label><br>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeStatement(this)">
                <i class="mdi mdi-delete"></i>
            </button>
        </div>
    `;
    
    statementsList.appendChild(statementDiv);
}

function removeStatement(button) {
    button.closest('.statement-item').remove();
    
    // Renumber remaining statements
    const statements = document.querySelectorAll('.statement-item');
    statements.forEach((statement, index) => {
        const numberInput = statement.querySelector('input[name="statement_numbers[]"]');
        const answerRadios = statement.querySelectorAll('input[type="radio"]');
        
        if (numberInput) {
            numberInput.value = index + 1;
        }
        
        // Update radio button names
        answerRadios.forEach(radio => {
            const currentName = radio.name;
            const newName = `statement_${index + 1}_answer`;
            radio.name = newName;
        });
    });
}

function handleSubmit(event) {
    event.preventDefault();
    saveQuestion();
}

function saveQuestion() {
    const form = document.getElementById('questionForm');
    if (!form) {
        console.log('Form not found!');
        return;
    }
    
    const formData = new FormData(form);
    
    // Handle media file upload
    const mediaFile = document.getElementById('media_file').files[0];
    const mediaType = document.getElementById('media_type').value;
    
    if (mediaFile && mediaType) {
        formData.append('media_file', mediaFile);
        formData.append('media_type', mediaType);
    } else if (!mediaType) {
        // Clear media fields if no media type selected
        formData.append('media_type', '');
        formData.append('media_caption', '');
    }
    
    // Process form data based on question type
    const questionType = document.getElementById('question_type').value;
    
    if (questionType === 'multiple_choice') {
        // Handle multiple choice options - FormData already has the data from form fields
        // Just validate it before sending
        const optionTexts = formData.getAll('option_texts[]');
        
        // Get radio button value using querySelector instead of formData
        const isCorrectRadio = document.querySelector('input[name="is_correct"]:checked');
        const isCorrectIndex = isCorrectRadio ? isCorrectRadio.value : null;
        
        // Check if any option text is filled
        const hasFilledOptions = optionTexts.some(text => text.trim());
        if (!hasFilledOptions) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Isi minimal satu pilihan jawaban'
            });
            return;
        }
        
        // Check if correct answer is selected
        if (isCorrectIndex === null) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Pilih jawaban yang benar'
            });
            return;
        }
        
        // Ensure the is_correct value is properly set in FormData
        formData.set('is_correct', isCorrectIndex);
    } else if (questionType === 'true_false') {
        // Handle true/false - collect all statements
        const statementNumbers = formData.getAll('statement_numbers[]');
        const statementTexts = formData.getAll('statement_texts[]');
        const statements = [];
        
        for (let i = 0; i < statementNumbers.length; i++) {
            const statementNum = statementNumbers[i];
            const statementText = statementTexts[i];
            const answer = formData.get(`statement_${statementNum}_answer`);
            
            if (statementText && answer) {
                statements.push({
                    text: statementText,
                    is_correct: answer === 'true'
                });
            }
        }
        
        // Remove old true/false data and add new statements
        formData.delete('statement_numbers[]');
        formData.delete('statement_texts[]');
        
        // Add each statement as an option
        statements.forEach((statement, index) => {
            formData.append(`option_labels[${index}]`, String.fromCharCode(65 + index)); // A, B, C, etc.
            formData.append(`option_texts[${index}]`, statement.text);
            formData.append(`is_correct[${index}]`, statement.is_correct ? '1' : '0');
        });
    }
    
    fetch(form.action, {
        method: form.method,
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: data.message,
                timer: 1500,
                showConfirmButton: false
            });
            setTimeout(() => {
                window.parent.location.reload();
            }, 1500);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: data.message || 'Terjadi kesalahan'
            });
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Gagal menyimpan soal'
        });
    });
}

// Initialize form when script loads
(function() {
    const questionType = document.getElementById('question_type');
    const addOptionBtn = document.getElementById('add-option');
    const addComplexOptionBtn = document.getElementById('add-complex-option');
    const addStatementBtn = document.getElementById('add-statement');
    
    if (questionType) {
        questionType.addEventListener('change', function() {
            updateQuestionForm(this.value);
        });
        updateQuestionForm(questionType.value);
    }
    
    if (addOptionBtn) {
        addOptionBtn.addEventListener('click', function() {
            addOption();
        });
    }
    
    if (addComplexOptionBtn) {
        addComplexOptionBtn.addEventListener('click', function() {
            addComplexOption();
        });
    }
    
    if (addStatementBtn) {
        addStatementBtn.addEventListener('click', function() {
            addStatement();
        });
    }
})();
</script>
