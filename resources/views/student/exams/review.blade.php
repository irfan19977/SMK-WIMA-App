@extends('layouts.master')

@section('title')
    Review Jawaban - {{ $exam->title }}
@endsection

@section('page-title')
    Review Jawaban
@endsection

@section('body')
    <body data-sidebar="colored">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Review Jawaban</h4>
                    <p class="text-muted mb-0">{{ $exam->title }}</p>
                </div>
                <div class="card-body">
                    <?php 
                    // Apply the same ordering logic as the exam-taking component
                    $questions = $exam->questions->sortBy(function($question) {
                        $typeOrder = [
                            'multiple_choice' => 1,
                            'multiple_choice_complex' => 2,
                            'true_false' => 3,
                            'essay' => 4
                        ];
                        return [$typeOrder[$question->question_type] ?? 5, $question->order];
                    })->values();
                    
                    // Randomize if exam setting is enabled (same as exam-taking)
                    if ($exam->randomize_questions) {
                        $questions = $questions->shuffle()->values();
                    }
                    ?>
                    
                    @foreach($questions as $index => $question)
                        <?php 
                        $answer = $submission->answers->where('exam_question_id', $question->id)->first();
                        $isCorrect = false;
                        
                        if ($question->question_type !== 'essay') {
                            if ($question->question_type === 'multiple_choice') {
                                $correctOption = $question->options->where('is_correct', true)->first();
                                $isCorrect = $correctOption && $correctOption->id == $answer->answer;
                            } elseif ($question->question_type === 'multiple_choice_complex') {
                                $selectedOptions = json_decode($answer->answer, true) ?: [];
                                $correctOptions = $question->options->where('is_correct', true)->pluck('id')->toArray();
                                
                                // Check if all selected options are correct and all correct options are selected
                                $isCorrect = !empty($selectedOptions) && 
                                           count(array_intersect($selectedOptions, $correctOptions)) === count($correctOptions) &&
                                           count(array_intersect($selectedOptions, $correctOptions)) === count($selectedOptions);
                            } elseif ($question->question_type === 'true_false') {
                                $correctOption = $question->options->where('is_correct', true)->first();
                                // Check if student actually answered and the answer matches the correct option
                                $studentAnswer = $answer->answer ?? null;
                                $hasAnswer = !empty($studentAnswer) && $studentAnswer !== '[]' && $studentAnswer !== '[[]]';
                                
                                if ($hasAnswer) {
                                    // Student answered, check if it's correct
                                    $decodedAnswer = json_decode($studentAnswer, true);
                                    // Handle different answer formats
                                    $actualAnswer = null;
                                    if (is_array($decodedAnswer)) {
                                        // Check if it's an associative array like {"A":"true","B":"false"}
                                        if (isset($decodedAnswer['A'])) {
                                            $actualAnswer = $decodedAnswer['A']; // Get the first option's answer
                                        } elseif (isset($decodedAnswer[0])) {
                                            $actualAnswer = $decodedAnswer[0]; // Handle indexed array
                                        }
                                    }
                                    $isCorrect = $correctOption && 
                                               (($correctOption->option_text === 'Benar' && $actualAnswer === 'true') ||
                                                ($correctOption->option_text === 'Salah' && $actualAnswer === 'false'));
                                } else {
                                    // Student didn't answer, it's incorrect
                                    $isCorrect = false;
                                }
                            }
                        }
                        ?>
                        
                        <div class="question-card card mb-4">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        Soal {{ $index + 1 }}
                                        <span class="badge bg-secondary ms-2">{{ $question->question_type }}</span>
                                        <span class="badge bg-primary ms-1">{{ $question->points }} poin</span>
                                        @if($question->question_type !== 'essay')
                                            @if($isCorrect)
                                                <span class="badge bg-success ms-2">Benar</span>
                                            @else
                                                <span class="badge bg-danger ms-2">Salah</span>
                                            @endif
                                        @endif
                                    </h5>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Question Text -->
                                <div class="mb-3">
                                    <p class="mb-0 fw-medium">{{ $question->question_text }}</p>
                                </div>

                                <!-- Student's Answer -->
                                <div class="mb-3">
                                    <h6 class="text-primary">Jawaban Anda:</h6>
                                    @switch($question->question_type)
                                        @case('multiple_choice')
                                            <?php 
                                            $selectedOptionId = $answer->answer ?? null;
                                            $selectedOption = $selectedOptionId ? $question->options->where('id', $selectedOptionId)->first() : null;
                                            $correctOption = $question->options->where('is_correct', true)->first();
                                            ?>
                                            
                                            <!-- Show all options with indicators -->
                                            <div class="options-list">
                                                @foreach($question->options->sortBy('option_label') as $option)
                                                    <div class="p-3 mb-2 rounded d-flex align-items-center 
                                                        @if($selectedOption && $option->id == $selectedOptionId && $option->is_correct)
                                                            bg-success bg-opacity-25 border border-success
                                                        @elseif($selectedOption && $option->id == $selectedOptionId)
                                                            bg-danger bg-opacity-25 border border-danger
                                                        @elseif($selectedOption && $option->is_correct)
                                                            bg-success bg-opacity-10 border border-success
                                                        @else
                                                            bg-light
                                                        @endif">
                                                        
                                                        <div class="me-3">
                                                            @if($selectedOption && $option->id == $selectedOptionId && $option->is_correct)
                                                                <i class="mdi mdi-check-circle text-success fs-5"></i>
                                                                <span class="badge bg-success ms-1">Anda ✓ Benar</span>
                                                            @elseif($selectedOption && $option->id == $selectedOptionId)
                                                                <i class="mdi mdi-close-circle text-danger fs-5"></i>
                                                                <span class="badge bg-danger ms-1">Anda ✗</span>
                                                            @elseif($selectedOption && $option->is_correct)
                                                                <i class="mdi mdi-check-circle-outline text-success fs-5"></i>
                                                                <span class="badge bg-success ms-1">Benar</span>
                                                            @else
                                                                <i class="mdi mdi-circle-outline text-muted fs-5"></i>
                                                            @endif
                                                        </div>
                                                        
                                                        <div class="flex-grow-1">
                                                            <strong>{{ $option->option_label }}.</strong> {{ $option->option_text }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @break

                                        @case('multiple_choice_complex')
                                            <?php 
                                            $selectedOptions = json_decode($answer->answer, true) ?: [];
                                            $correctOptions = $question->options->where('is_correct', true)->pluck('id')->toArray();
                                            ?>
                                            
                                            <div class="alert alert-info mb-3">
                                                <i class="mdi mdi-information me-2"></i>
                                                <strong>Pilihan Ganda Kompleks:</strong> Pilih semua jawaban yang benar
                                            </div>
                                            
                                            <!-- Show all options with indicators -->
                                            <div class="options-list">
                                                @foreach($question->options->sortBy('option_label') as $option)
                                                    <?php 
                                                    $isSelected = in_array($option->id, $selectedOptions);
                                                    $isCorrect = $option->is_correct;
                                                    ?>
                                                    
                                                    <div class="p-3 mb-2 rounded d-flex align-items-center 
                                                        @if($isSelected && $isCorrect)
                                                            bg-success bg-opacity-25 border border-success
                                                        @elseif($isSelected && !$isCorrect)
                                                            bg-danger bg-opacity-25 border border-danger
                                                        @else
                                                            bg-light
                                                        @endif">
                                                        
                                                        <div class="me-3">
                                                            @if($isSelected && $isCorrect)
                                                                <i class="mdi mdi-check-circle text-success fs-5"></i>
                                                                <span class="badge bg-success ms-1">Anda ✓ Benar</span>
                                                            @elseif($isSelected && !$isCorrect)
                                                                <i class="mdi mdi-close-circle text-danger fs-5"></i>
                                                                <span class="badge bg-danger ms-1">Anda ✗ Salah</span>
                                                            @else
                                                                <i class="mdi mdi-circle-outline text-muted fs-5"></i>
                                                            @endif
                                                        </div>
                                                        
                                                        <div class="flex-grow-1">
                                                            <strong>{{ $option->option_label }}.</strong> {{ $option->option_text }}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            @break

                                        @case('true_false')
                                            <?php 
                                            // Group options by question (for statement-based true/false)
                                            $questionStatements = [];
                                            foreach($question->options as $option) {
                                                // Parse the student's answer to get the actual value
                                                $studentAnswer = $answer->answer ?? null;
                                                $hasAnswer = !empty($studentAnswer) && $studentAnswer !== '[]' && $studentAnswer !== '[[]]';
                                                $actualAnswer = null;
                                                
                                                if ($hasAnswer) {
                                                    $decodedAnswer = json_decode($studentAnswer, true);
                                                    if (is_array($decodedAnswer)) {
                                                        if (isset($decodedAnswer[$option->option_label])) {
                                                            $actualAnswer = $decodedAnswer[$option->option_label];
                                                        } elseif (isset($decodedAnswer[0])) {
                                                            $actualAnswer = $decodedAnswer[0];
                                                        }
                                                    }
                                                }
                                                
                                                $questionStatements[] = [
                                                    'question' => $option->option_text,
                                                    'is_correct' => $option->is_correct,
                                                    'student_answer' => $actualAnswer
                                                ];
                                            }
                                            ?>
                                            
                                            @foreach($questionStatements as $statementIndex => $statement)
                                                <div class="mb-4 p-3 border rounded">
                                                    <div class="mb-2">
                                                        <strong>Pernyataan {{ $statementIndex + 1 }}:</strong> 
                                                        <span class="text-muted">{{ $statement['question'] }}</span>
                                                    </div>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="mb-4">
                                                                <h6>Jawaban Anda:</h6>
                                                                <div class="mb-3 p-3 border rounded">
                                                                    <div class="mb-2">
                                                                        <strong>Pernyataan {{ $statementIndex + 1 }}:</strong> 
                                                                        <span class="text-muted">{{ $statement['question'] }}</span>
                                                                    </div>
                                                                    
                                                                    <div class="d-flex gap-2 mb-2">
                                                                        <div class="flex-grow-1 p-2 rounded 
                                                                            @if($statement['student_answer'] === 'true' && $statement['is_correct'])
                                                                                bg-success bg-opacity-25 border border-success
                                                                            @elseif($statement['student_answer'] === 'true' && !$statement['is_correct'])
                                                                                bg-danger bg-opacity-25 border border-danger
                                                                            @else
                                                                                bg-light
                                                                            @endif">
                                                                            <div class="text-center">
                                                                                <strong>Benar</strong>
                                                                            </div>
                                                                        </div>
                                                                        
                                                                        <div class="flex-grow-1 p-2 rounded 
                                                                            @if($statement['student_answer'] === 'false' && !$statement['is_correct'])
                                                                                bg-success bg-opacity-25 border border-success
                                                                            @elseif($statement['student_answer'] === 'false' && $statement['is_correct'])
                                                                                bg-danger bg-opacity-25 border border-danger
                                                                            @else
                                                                                bg-light
                                                                            @endif">
                                                                            <div class="text-center">
                                                                                <strong>Salah</strong>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                            @break

                                        @case('essay')
                                            <div class="p-3 bg-light rounded">
                                                {{ $answer->answer ?: 'Tidak ada jawaban' }}
                                            </div>
                                            @break
                                    @endswitch
                                </div>

                                <!-- Correct Answer (for non-essay questions) -->
                                @if($question->question_type !== 'essay')
                                    <div class="mb-3">
                                        <h6 class="text-success">Kunci Jawaban:</h6>
                                        @switch($question->question_type)
                                            @case('multiple_choice')
                                                <?php 
                                                $correctOption = $question->options->where('is_correct', true)->first();
                                                ?>
                                                @if($correctOption)
                                                    <div class="p-3 bg-success bg-opacity-10 rounded border border-success">
                                                        <i class="mdi mdi-check-circle text-success me-2"></i>
                                                        <strong>{{ $correctOption->option_label }}.</strong> {{ $correctOption->option_text }}
                                                    </div>
                                                @endif
                                                @break

                                            @case('multiple_choice_complex')
                                                <?php 
                                                $correctOptions = $question->options->where('is_correct', true);
                                                ?>
                                                @if($correctOptions->count() > 0)
                                                    <div class="p-3 bg-success bg-opacity-10 rounded border border-success">
                                                        <i class="mdi mdi-check-circle text-success me-2"></i>
                                                        <strong>Semua jawaban benar:</strong>
                                                        <div class="mt-2">
                                                            @foreach($correctOptions->sortBy('option_label') as $option)
                                                                <div class="mb-1">
                                                                    <strong>{{ $option->option_label }}.</strong> {{ $option->option_text }}
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                                @break

                                            @case('true_false')
                                                <?php 
                                                // Group options by question (for statement-based true/false)
                                                $questionStatements = [];
                                                foreach($question->options as $option) {
                                                    $questionStatements[] = [
                                                        'question' => $option->option_text,
                                                        'is_correct' => $option->is_correct
                                                    ];
                                                }
                                                ?>
                                                @if($questionStatements)
                                                    <div class="p-3 bg-success bg-opacity-10 rounded border border-success">
                                                        <i class="mdi mdi-check-circle text-success me-2"></i>
                                                        <strong>Kunci Jawaban Pernyataan:</strong>
                                                        <div class="mt-2">
                                                            @foreach($questionStatements as $statementIndex => $statement)
                                                                <div class="mb-1">
                                                                    <strong>Pernyataan {{ $statementIndex + 1 }}:</strong> 
                                                                    @if($statement['is_correct'])
                                                                        <span class="badge bg-success">Benar</span>
                                                                    @else
                                                                        <span class="badge bg-success">Salah</span>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                                @break
                                        @endswitch
                                    </div>
                                @endif

                                <!-- Explanation -->
                                @if($question->explanation)
                                    <div class="mt-3">
                                        <h6 class="text-info">Penjelasan:</h6>
                                        <div class="p-3 bg-info bg-opacity-10 rounded">
                                            {{ $question->explanation }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <!-- Actions -->
                    <div class="text-center mt-4">
                        <a href="{{ route('student.exams.index') }}" class="btn btn-primary me-2">
                            <i class="mdi mdi-arrow-left me-1"></i> Kembali ke Daftar Ujian
                        </a>
                        <a href="{{ route('student.exams.result', $exam->id) }}" class="btn btn-info">
                            <i class="mdi mdi-chart-line me-1"></i> Lihat Hasil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
