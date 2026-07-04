<?php

namespace App\Http\Livewire;

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

        // Order questions by type only: multiple_choice -> true_false -> essay
        $this->questions = $this->exam->questions->sortBy(function($question) {
            $typeOrder = [
                'multiple_choice' => 1,
                'true_false' => 2,
                'essay' => 3
            ];
            return $typeOrder[$question->question_type] ?? 4;
        })->values();

        $this->totalQuestions = $this->questions->count();
        $this->timeLeft = $this->exam->duration_minutes * 60;
        $this->startTime = now();

        // Initialize answers array
        foreach ($this->questions as $question) {
            $this->answers[$question->id] = '';
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
        $questionId = str_replace(['answers[', ']'], '', $key);
        $this->answeredQuestions[$questionId] = true;
    }

    public function submitExam()
    {
        $this->dispatch('showSubmitConfirmation');
    }

    public function confirmSubmit()
    {
        $student = Auth::user()->student;
        
        try {
            DB::beginTransaction();

            // Create submission
            $submission = ExamSubmission::create([
                'exam_id' => $this->exam->id,
                'student_id' => $student->id,
                'start_time' => $this->startTime,
                'end_time' => now(),
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
                    $isCorrect = $this->checkAnswer($question, $answer);
                    if ($isCorrect) {
                        $totalScore += $question->points;
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
            $correctOption = $question->options->where('is_correct', true)->first();
            return $correctOption && $correctOption->option_text === ($answer ? 'Benar' : 'Salah');
        }

        return false; // Essays are manually graded
    }

    public function getAnsweredCountProperty()
    {
        return count(array_filter($this->answeredQuestions));
    }

    public function getProgressPercentageProperty()
    {
        if ($this->totalQuestions == 0) return 0;
        return ($this->answeredCount / $this->totalQuestions) * 100;
    }

    public function render()
    {
        return view('livewire.student-exam-take');
    }
}
