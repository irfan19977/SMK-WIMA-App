<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamSubmission;
use App\Models\ExamSubmissionAnswer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentExamTake extends Component
{
    public $exam;
    public $questions;
    public $currentQuestionIndex = 0;
    public $answers = [];
    public $timeLeft;
    public $totalQuestions;
    public $answeredQuestions = [];
    public $startTime;
    public $timer;

    protected $listeners = ['timerTick', 'autoSave'];

    public function mount($examId)
    {
        $student = Auth::user()->student;
        
        // Check if student has active class
        $currentClass = $student->getCurrentClass();
        if (!$currentClass) {
            abort(403, 'Student tidak terdaftar di kelas manapun');
        }
        
        $this->exam = Exam::with(['questions.options'])
            ->findOrFail($examId);

        // Validate exam access
        if ($this->exam->class_id !== $currentClass->id) {
            abort(403, 'Unauthorized access to this exam');
        }

        if ($this->exam->status !== 'published') {
            abort(403, 'Exam is not available');
        }

        if (now()->lt($this->exam->start_time)) {
            abort(403, 'Exam has not started yet');
        }

        if (now()->gt($this->exam->end_time)) {
            abort(403, 'Exam has expired');
        }

        // Check if already submitted
        $existingSubmission = ExamSubmission::where('exam_id', $this->exam->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existingSubmission) {
            return redirect()->route('student.exams.result', $this->exam->id)
                ->with('info', 'Anda sudah mengerjakan ujian ini.');
        }

        // Order and randomize questions by type groups
        $questions = $this->exam->questions;
        
        if ($this->exam->randomize_questions) {
            // Group questions by type
            $groupedQuestions = $questions->groupBy('question_type');
            
            // Define type order
            $typeOrder = [
                'multiple_choice' => 1,
                'multiple_choice_complex' => 2,
                'true_false' => 3,
                'essay' => 4
            ];
            
            // Shuffle each group individually
            $shuffledGroups = [];
            foreach ($groupedQuestions as $type => $group) {
                $shuffledGroups[$type] = $group->shuffle()->values();
            }
            
            // Sort groups by type order and merge
            $sortedQuestions = collect();
            foreach ($typeOrder as $type => $order) {
                if (isset($shuffledGroups[$type])) {
                    $sortedQuestions = $sortedQuestions->merge($shuffledGroups[$type]);
                }
            }
            
            // Add any remaining questions with unknown types
            foreach ($shuffledGroups as $type => $group) {
                if (!isset($typeOrder[$type])) {
                    $sortedQuestions = $sortedQuestions->merge($group);
                }
            }
            
            $questions = $sortedQuestions->values();
        } else {
            // If not randomized, just sort by type order
            $questions = $questions->sortBy(function($question) {
                $typeOrder = [
                    'multiple_choice' => 1,
                    'multiple_choice_complex' => 2,
                    'true_false' => 3,
                    'essay' => 4
                ];
                return $typeOrder[$question->question_type] ?? 5;
            })->values();
        }

        $this->questions = $questions;

        $this->totalQuestions = $this->questions->count();
        $this->timeLeft = $this->exam->duration_minutes * 60;
        $this->startTime = now();

        // Initialize answers array
        foreach ($this->questions as $question) {
            if ($question->question_type === 'multiple_choice_complex') {
                $this->answers[$question->id] = []; // Initialize as array for complex multiple choice
            } elseif ($question->question_type === 'true_false') {
                // Initialize with empty array for each statement
                $this->answers[$question->id] = []; // Initialize as array for true/false statements
            } else {
                $this->answers[$question->id] = ''; // Initialize as empty string for other types
            }
        }
    }

    public function getCurrentQuestion()
    {
        return $this->questions[$this->currentQuestionIndex] ?? null;
    }

    public function goToQuestion($index)
    {
        if ($index >= 0 && $index < $this->totalQuestions) {
            $this->currentQuestionIndex = $index;
        }
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < $this->totalQuestions - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    public function updatedAnswers($value, $key)
    {
        // Extract question ID from nested key like "answers.123.1" or "answers.123"
        $keyParts = explode('.', $key);
        $questionId = $keyParts[1] ?? null;
        
        // Debug logging
        \Log::info('updatedAnswers called', [
            'key' => $key,
            'value' => $value,
            'keyParts' => $keyParts,
            'questionId' => $questionId
        ]);
        
        if ($questionId) {
            // Only mark as answered if the question is actually complete
            if ($this->isQuestionAnswered($questionId)) {
                $this->answeredQuestions[$questionId] = true;
                
                \Log::info('Question marked as answered', [
                    'questionId' => $questionId,
                    'answeredQuestions' => $this->answeredQuestions
                ]);
            } else {
                // Remove from answered if not complete
                unset($this->answeredQuestions[$questionId]);
                
                \Log::info('Question removed from answered', [
                    'questionId' => $questionId,
                    'answeredQuestions' => $this->answeredQuestions
                ]);
            }
        }
    }

    public function submitExam()
    {
        $student = Auth::user()->student;
        
        try {
            DB::beginTransaction();

            // Create submission
            $submission = ExamSubmission::create([
                'exam_id' => $this->exam->id,
                'student_id' => $student->id,
                'started_at' => $this->startTime,
                'finished_at' => now(),
                'score' => 0, // Will be calculated
            ]);

            $totalScore = 0;
            $totalPoints = 0;

            // Process answers
            foreach ($this->exam->questions as $question) {
                $answer = $this->answers[$question->id] ?? null;
                
                ExamSubmissionAnswer::create([
                    'exam_submission_id' => $submission->id,
                    'exam_question_id' => $question->id,
                    'answer' => is_array($answer) ? json_encode($answer) : $answer,
                ]);

                // Calculate score for auto-gradable questions
                if ($question->question_type !== 'essay') {
                    if ($question->question_type === 'multiple_choice_complex') {
                        $score = $this->calculateComplexScore($question, $answer);
                        $totalScore += $score;
                    } elseif ($question->question_type === 'true_false') {
                        // For new true/false format, checkAnswer returns percentage
                        $scorePercentage = $this->checkAnswer($question, $answer);
                        if (is_numeric($scorePercentage)) {
                            $totalScore += $scorePercentage * $question->points;
                        } else {
                            // Legacy format
                            $isCorrect = $scorePercentage;
                            if ($isCorrect) {
                                $totalScore += $question->points;
                            }
                        }
                    } else {
                        $isCorrect = $this->checkAnswer($question, $answer);
                        if ($isCorrect) {
                            $totalScore += $question->points;
                        }
                    }
                }
                
                $totalPoints += $question->points;
            }

            // Calculate final score
            $finalScore = $totalPoints > 0 ? round(($totalScore / $totalPoints) * 100, 2) : 0;
            
            $submission->update(['score' => $finalScore]);

            DB::commit();

            return redirect()->route('student.exams.result', $this->exam->id);

        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('showError', 'Gagal menyimpan jawaban: ' . $e->getMessage());
        }
    }

    private function checkAnswer($question, $answer)
    {
        if ($question->question_type === 'multiple_choice') {
            $correctOption = $question->options->where('is_correct', true)->first();
            return $correctOption && $correctOption->id == $answer;
        }

        if ($question->question_type === 'true_false') {
            // For new true/false format with statements
            if (is_array($answer)) {
                $totalStatements = $question->options->count();
                $correctAnswers = 0;
                
                foreach ($question->options as $option) {
                    $studentAnswer = $answer[$option->option_label] ?? null;
                    $isCorrect = $option->is_correct ? 'true' : 'false';
                    
                    if ($studentAnswer === $isCorrect) {
                        $correctAnswers++;
                    }
                }
                
                // Return score percentage (correct / total)
                return $totalStatements > 0 ? ($correctAnswers / $totalStatements) : 0;
            } else {
                // Legacy format (single true/false)
                $correctOption = $question->options->where('is_correct', true)->first();
                return $correctOption && $correctOption->option_text === ($answer ? 'Benar' : 'Salah');
            }
        }

        return false; // Essays are manually graded
    }

    private function calculateComplexScore($question, $answer)
    {
        if (!$question->isComplexMultipleChoice()) {
            return 0;
        }

        $selectedOptions = is_array($answer) ? $answer : [];
        return $question->calculateScore($selectedOptions);
    }

    public function toggleComplexOption($questionId, $optionId)
    {
        if (!isset($this->answers[$questionId])) {
            $this->answers[$questionId] = [];
        }

        $currentAnswers = $this->answers[$questionId];
        
        if (in_array($optionId, $currentAnswers)) {
            // Remove option if already selected
            $this->answers[$questionId] = array_diff($currentAnswers, [$optionId]);
        } else {
            // Add option if not selected
            $this->answers[$questionId][] = $optionId;
        }

        // Mark question as answered
        $this->answeredQuestions[$questionId] = true;
    }

    public function autoSave()
    {
        // Auto-save functionality - answers are already tracked by Livewire's wire:model
        // This method can be used for additional auto-save logic if needed
    }

    public function decrementTimer()
    {
        if ($this->timeLeft > 0) {
            $this->timeLeft--;
        } else {
            $this->submitExam();
        }
    }

    public function getAnsweredCountProperty()
    {
        // Count actually answered questions using the same logic as navigation
        $answeredCount = 0;
        foreach ($this->questions as $question) {
            if ($this->isQuestionAnswered($question->id)) {
                $answeredCount++;
            }
        }
        
        \Log::info('Answered count calculated', [
            'answeredCount' => $answeredCount,
            'totalQuestions' => $this->totalQuestions
        ]);
        
        return $answeredCount;
    }

    public function isQuestionAnswered($questionId)
    {
        $answer = $this->answers[$questionId] ?? null;
        
        // Debug logging
        \Log::info('isQuestionAnswered check', [
            'questionId' => $questionId,
            'answer' => $answer,
            'answerType' => gettype($answer),
            'isEmpty' => is_array($answer) ? empty($answer) : 'not_array'
        ]);
        
        if ($answer === null || $answer === '') {
            \Log::info('Answer is null or empty');
            return false;
        }
        
        // For true/false, check if all statements are answered with actual values
        if (is_array($answer)) {
            // Get the question to check how many options it should have
            $question = $this->questions->firstWhere('id', $questionId);
            if ($question && $question->question_type === 'true_false') {
                $expectedOptionsCount = $question->options->count();
                
                \Log::info('True/False question check', [
                    'expectedOptionsCount' => $expectedOptionsCount,
                    'actualAnswerCount' => count($answer),
                    'answer' => $answer
                ]);
                
                // Check if all expected options are answered
                $nonEmptyCount = 0;
                foreach ($answer as $statementKey => $statementAnswer) {
                    if ($statementAnswer !== null && $statementAnswer !== '') {
                        $nonEmptyCount++;
                    }
                }
                
                // Only consider answered if ALL expected options have values
                $isComplete = $nonEmptyCount === $expectedOptionsCount && $expectedOptionsCount > 0;
                
                \Log::info('True/False completeness check', [
                    'nonEmptyCount' => $nonEmptyCount,
                    'expectedOptionsCount' => $expectedOptionsCount,
                    'isComplete' => $isComplete
                ]);
                
                return $isComplete;
            }
            
            // For other array types (complex multiple choice), use original logic
            if (empty($answer)) {
                \Log::info('Answer array is empty');
                return false;
            }
            
            $nonEmptyCount = 0;
            $totalCount = count($answer);
            
            foreach ($answer as $statementKey => $statementAnswer) {
                if ($statementAnswer !== null && $statementAnswer !== '') {
                    $nonEmptyCount++;
                }
            }
            
            \Log::info('Statement counts', [
                'totalCount' => $totalCount,
                'nonEmptyCount' => $nonEmptyCount,
                'answer' => $answer
            ]);
            
            return $nonEmptyCount === $totalCount && $totalCount > 0;
        }
        
        // For other types, any non-empty value counts as answered
        return true;
    }

    public function getProgressPercentageProperty()
    {
        if ($this->totalQuestions == 0) return 0;
        return ($this->answeredCount / $this->totalQuestions) * 100;
    }

    public function getAllQuestionsAnsweredProperty()
    {
        $allAnswered = true;
        
        foreach ($this->questions as $question) {
            if (!$this->isQuestionAnswered($question->id)) {
                $allAnswered = false;
                break;
            }
        }
        
        \Log::info('All questions answered check', [
            'allAnswered' => $allAnswered,
            'totalQuestions' => $this->totalQuestions,
            'answeredCount' => $this->answeredCount
        ]);
        
        return $allAnswered;
    }

    public function render()
    {
        return view('livewire.student-exam-take');
    }
}
