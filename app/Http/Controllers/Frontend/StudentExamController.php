<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\ExamSubmission;
use App\Models\ExamSubmissionAnswer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentExamController extends Controller
{
    /**
     * Display available exams for student
     */
    public function index()
    {
        $user = Auth::user();
        
        // Redirect admin and teacher to exam management
        if ($user->hasRole(['admin', 'Super Admin', 'teacher'])) {
            return redirect()->route('exams.index');
        }
        
        $student = $user->student;
        
        // Check if user has student record
        if (!$student) {
            return view('student.exams.index', ['exams' => collect()]);
        }
        
        // Check if student has active class
        $currentClass = $student->getCurrentClass();
        if (!$currentClass) {
            return view('student.exams.index', ['exams' => collect()]);
        }
        
        $exams = Exam::with(['subject', 'teacher'])
            ->where('class_id', $currentClass->id)
            ->where('status', 'published')
            ->orderBy('start_time', 'desc')
            ->get();

        // Check for existing submissions
        foreach ($exams as $exam) {
            $submission = ExamSubmission::where('exam_id', $exam->id)
                ->where('student_id', $student->id)
                ->first();
            
            $exam->submission = $submission;
            $exam->status_label = $this->getExamStatusLabel($exam, $submission);
        }

        return view('student.exams.index', compact('exams'));
    }

    /**
     * Show exam taking page
     */
    public function take($examId)
    {
        $user = Auth::user();
        
        // Redirect admin and teacher to exam management
        if ($user->hasRole(['admin', 'Super Admin', 'teacher'])) {
            return redirect()->route('exams.show', $examId);
        }
        
        $student = $user->student;
        
        // Check if user has student record
        if (!$student) {
            abort(403, 'Student record not found');
        }
        
        // Check if student has active class
        $currentClass = $student->getCurrentClass();
        if (!$currentClass) {
            abort(403, 'Student tidak terdaftar di kelas manapun');
        }
        
        $exam = Exam::with(['questions.options'])
            ->findOrFail($examId);

        // Validate exam access
        if ($exam->class_id !== $currentClass->id) {
            abort(403, 'Unauthorized access to this exam');
        }

        if ($exam->status !== 'published') {
            abort(403, 'Exam is not available');
        }

        if (now()->lt($exam->start_time)) {
            abort(403, 'Exam has not started yet');
        }

        if (now()->gt($exam->end_time)) {
            abort(403, 'Exam has expired');
        }

        // Check if already submitted
        $existingSubmission = ExamSubmission::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existingSubmission) {
            return redirect()->route('student.exams.result', $exam->id)
                ->with('info', 'Anda sudah mengerjakan ujian ini.');
        }

        // Order questions by type only: multiple_choice -> true_false -> essay
        $questions = $exam->questions->sortBy(function($question) {
            $typeOrder = [
                'multiple_choice' => 1,
                'true_false' => 2,
                'essay' => 3
            ];
            return $typeOrder[$question->question_type] ?? 4;
        })->values();

        return view('student.exams.take', compact('exam'));
    }

    /**
     * Submit exam answers
     */
    public function submit(Request $request, $examId)
    {
        $user = Auth::user();
        
        // Redirect admin and teacher
        if ($user->hasRole(['admin', 'Super Admin', 'teacher'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action for admin/teacher'], 403);
        }
        
        $student = $user->student;
        
        // Check if user has student record
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student record not found'], 403);
        }
        
        // Check if student has active class
        $currentClass = $student->getCurrentClass();
        if (!$currentClass) {
            return response()->json(['success' => false, 'message' => 'Student tidak terdaftar di kelas manapun'], 403);
        }
        
        $exam = Exam::findOrFail($examId);

        // Validate exam access
        if ($exam->class_id !== $currentClass->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Check if already submitted
        $existingSubmission = ExamSubmission::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existingSubmission) {
            return response()->json(['success' => false, 'message' => 'Anda sudah mengumpulkan jawaban'], 400);
        }

        try {
            DB::beginTransaction();

            // Create submission
            $submission = ExamSubmission::create([
                'exam_id' => $exam->id,
                'student_id' => $student->id,
                'start_time' => $request->start_time,
                'end_time' => now(),
                'score' => 0, // Will be calculated
            ]);

            $totalScore = 0;
            $totalPoints = 0;

            // Process answers
            foreach ($exam->questions as $question) {
                $answer = $request->input("answers.{$question->id}");
                
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

            return response()->json([
                'success' => true,
                'message' => 'Jawaban berhasil disimpan',
                'redirect' => route('student.exams.result', $exam->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan jawaban: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show exam result
     */
    public function result($examId)
    {
        $user = Auth::user();
        
        // Redirect admin and teacher to exam management
        if ($user->hasRole(['admin', 'Super Admin', 'teacher'])) {
            return redirect()->route('exams.show', $examId);
        }
        
        $student = $user->student;
        
        // Check if user has student record
        if (!$student) {
            abort(403, 'Student record not found');
        }
        
        // Check if student has active class
        $currentClass = $student->getCurrentClass();
        if (!$currentClass) {
            abort(403, 'Student tidak terdaftar di kelas manapun');
        }
        
        $exam = Exam::with(['questions.options'])
            ->findOrFail($examId);

        $submission = ExamSubmission::with(['answers.question'])
            ->where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        return view('student.exams.result', compact('exam', 'submission'));
    }

    /**
     * Review submitted answers
     */
    public function review($examId)
    {
        $user = Auth::user();
        
        // Redirect admin and teacher to exam management
        if ($user->hasRole(['admin', 'Super Admin', 'teacher'])) {
            return redirect()->route('exams.show', $examId);
        }
        
        $student = $user->student;
        
        // Check if user has student record
        if (!$student) {
            abort(403, 'Student record not found');
        }
        
        // Check if student has active class
        $currentClass = $student->getCurrentClass();
        if (!$currentClass) {
            abort(403, 'Student tidak terdaftar di kelas manapun');
        }
        
        $exam = Exam::with(['questions.options'])
            ->findOrFail($examId);

        $submission = ExamSubmission::with(['answers.question'])
            ->where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        return view('student.exams.review', compact('exam', 'submission'));
    }

    /**
     * Get exam status label
     */
    private function getExamStatusLabel($exam, $submission)
    {
        if ($submission) {
            return 'completed';
        }

        if (now()->lt($exam->start_time)) {
            return 'upcoming';
        }

        if (now()->gt($exam->end_time)) {
            return 'expired';
        }

        return 'available';
    }

    /**
     * Check if answer is correct
     */
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
}
