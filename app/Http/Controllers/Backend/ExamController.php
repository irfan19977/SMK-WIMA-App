<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\User;
use App\Models\ExamSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ExamController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('exams.index');

        $exams = Exam::with(['subject', 'class', 'teacher'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('exams.index', compact('exams'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('exams.create');

        $currentAcademicYear = \App\Helpers\AcademicYearHelper::getCurrentAcademicYear();
        $currentSemester = \App\Helpers\AcademicYearHelper::getCurrentSemester();
        
        // Step 1: Get class_ids from schedule for current academic year and semester
        $classIds = \DB::table('schedule')
            ->where('academic_year', $currentAcademicYear)
            ->where('semester', $currentSemester)
            ->pluck('class_id')
            ->unique();
        
        // Step 2: Load classes based on those class_ids (only active classes)
        $classes = Classes::whereNull('deleted_at')
            ->whereIn('id', $classIds)
            ->get();
        
        // Step 3: Get ALL subjects (not filtered by class for now)
        $subjects = Subject::whereNull('deleted_at')->get();
        
        // Step 4: Create class-subject mapping for JavaScript
        $classSubjects = [];
        foreach ($classes as $class) {
            $subjectIds = \DB::table('schedule')
                ->where('class_id', $class->id)
                ->where('academic_year', $currentAcademicYear)
                ->where('semester', $currentSemester)
                ->pluck('subject_id')
                ->unique();
                
            $classSubjects[$class->id] = $subjects->whereIn('id', $subjectIds)->toArray();
        }
        
        // Log debug info
        \Log::info('Exam Form Debug', [
            'classes_count' => $classes->count(),
            'subjects_count' => $subjects->count(),
            'classSubjects_keys' => array_keys($classSubjects),
            'current_academic_year' => $currentAcademicYear,
            'current_semester' => $currentSemester
        ]);
        
        return response()->json([
            'success' => true,
            'html' => view('exams.form', [
                'subjects' => $subjects,
                'classes' => $classes,
                'classSubjects' => $classSubjects,
                'exam' => null,
                'action' => route('exams.store')
            ])->render(),
            'title' => 'Buat Ujian Baru'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subject,id',
            'teacher_id' => 'required|exists:teacher,id',
            'duration_minutes' => 'required|integer|min:5|max:720',
            'passing_score' => 'required|numeric|min:0|max:100',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'show_results' => 'required|boolean',
            'show_status' => 'required|boolean',
            'show_review' => 'required|boolean',
            'randomize_questions' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $exam = Exam::create([
                'title' => $request->title,
                'description' => $request->description,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'teacher_id' => $request->teacher_id,
                'duration_minutes' => $request->duration_minutes,
                'passing_score' => $request->passing_score,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'show_results' => $request->show_results,
                'show_status' => $request->show_status,
                'show_review' => $request->show_review,
                'randomize_questions' => $request->randomize_questions,
                'status' => 'draft',
                'created_by' => Auth::id(),
            ]);

            return redirect()
            ->route('exams.index')
            ->with('success', 'Ujian berhasil dibuat');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat ujian: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Exam $exam)
    {
        $this->authorize('exams.index');

        $exam->load(['questions' => function($query) {
            $query->orderByRaw("CASE 
                WHEN question_type = 'multiple_choice' THEN 1 
                WHEN question_type = 'true_false' THEN 2 
                WHEN question_type = 'essay' THEN 3 
                ELSE 4 
            END")->orderBy('order', 'asc');
        }, 'questions.options', 'submissions.student']);
        
        return view('exams.show', compact('exam'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exam $exam)
    {
        $this->authorize('exams.edit');

        // Check if exam is editable
        if ($exam->status === 'ongoing' || $exam->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Ujian tidak dapat diedit karena sudah ' . ($exam->status === 'ongoing' ? 'dimulai' : 'selesai')
            ], 403);
        }

        // Load exam with relationships
        $exam->load(['teacher', 'class', 'subject']);

        // Prepare data for the form (similar to create method)
        $currentAcademicYear = \App\Helpers\AcademicYearHelper::getCurrentAcademicYear();
        $currentSemester = \App\Helpers\AcademicYearHelper::getCurrentSemester();
        
        // Step 1: Get ALL classes for current academic year
        $classes = Classes::where('academic_year', $currentAcademicYear)
            ->whereNull('deleted_at')
            ->orderBy('name')
            ->get();
            
        // Step 2: Get subjects for the specific class
        $subjects = Subject::whereNull('deleted_at')->get();
        
        // Step 3: Create class-subject mapping for JavaScript
        $classSubjects = [];
        foreach ($classes as $class) {
            $subjectIds = \DB::table('schedule')
                ->where('class_id', $class->id)
                ->where('academic_year', $currentAcademicYear)
                ->where('semester', $currentSemester)
                ->pluck('subject_id')
                ->unique();
                
            $classSubjects[$class->id] = $subjects->whereIn('id', $subjectIds)->toArray();
        }

        return response()->json([
            'success' => true,
            'html' => view('exams.form', [
                'exam' => $exam,
                'classes' => $classes,
                'subjects' => $subjects,
                'classSubjects' => $classSubjects,
                'action' => route('exams.update', $exam->id)
            ])->render(),
            'title' => 'Edit Ujian'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Exam $exam)
    {
        // Check if exam is editable
        if ($exam->status === 'ongoing' || $exam->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Ujian tidak dapat diedit karena sudah ' . ($exam->status === 'ongoing' ? 'dimulai' : 'selesai')
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subject,id',
            'teacher_id' => 'required|exists:teacher,id',
            'duration_minutes' => 'required|integer|min:5|max:720',
            'passing_score' => 'required|numeric|min:0|max:100',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'show_results' => 'required|boolean',
            'show_status' => 'required|boolean',
            'show_review' => 'required|boolean',
            'randomize_questions' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $exam->update([
                'title' => $request->title,
                'description' => $request->description,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'teacher_id' => $request->teacher_id,
                'duration_minutes' => $request->duration_minutes,
                'passing_score' => $request->passing_score,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'show_results' => $request->show_results,
                'show_status' => $request->show_status,
                'show_review' => $request->show_review,
                'randomize_questions' => $request->randomize_questions,
                'updated_by' => Auth::id(),
            ]);

            return redirect()
                ->route('exams.index')
                ->with('success', 'Ujian berhasil diperbarui');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui ujian: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Publish exam
     */
    public function publish(Exam $exam)
    {
        // // Check if user owns this exam
        // if ($exam->teacher_id !== Auth::id()) {
        //     abort(403, 'Unauthorized');
        // }

        // Check if exam has questions
        if ($exam->questions()->count() === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Ujian harus memiliki minimal 1 soal sebelum dipublish'
            ], 400);
        }

        // Check if exam is already published
        if ($exam->status !== 'draft') {
            return response()->json([
                'success' => false,
                'message' => 'Ujian sudah dipublish'
            ], 400);
        }

        try {
            $exam->update([
                'status' => 'published',
                'updated_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ujian berhasil dipublish'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mempublish ujian: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show exam submission details
     */
    public function showSubmission(Exam $exam, ExamSubmission $submission)
    {
        $this->authorize('exams.index');

        // Verify submission belongs to this exam
        if ($submission->exam_id !== $exam->id) {
            abort(404, 'Submission not found for this exam');
        }

        // Load submission with all necessary relationships
        $submission->load([
            'student',
            'answers.question',
            'answers.question.options'
        ]);

        return view('exams.submissions.show', compact('exam', 'submission'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exam $exam)
    {

        // Check if exam is deletable
        if ($exam->status === 'ongoing' || $exam->status === 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Ujian tidak dapat dihapus karena sudah ' . ($exam->status === 'ongoing' ? 'dimulai' : 'selesai')
            ], 403);
        }

        try {
            DB::beginTransaction();
            
            // Delete related questions and options
            $exam->questions()->each(function($question) {
                $question->options()->delete();
                $question->delete();
            });
            
            $exam->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Ujian berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus ujian: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get teacher by schedule for dynamic form
     */
    public function getTeacherBySchedule($classId, $subjectId, Request $request)
    {
        $currentAcademicYear = \App\Helpers\AcademicYearHelper::getCurrentAcademicYear();
        $currentSemester = \App\Helpers\AcademicYearHelper::getCurrentSemester();
        
        $schedule = \DB::table('schedule')
            ->where('class_id', $classId)
            ->where('subject_id', $subjectId)
            ->where('academic_year', $currentAcademicYear)
            ->where('semester', $currentSemester)
            ->first();

        if ($schedule) {
            $teacher = \DB::table('teacher')->find($schedule->teacher_id);
            
            return response()->json([
                'success' => true,
                'teacher' => $teacher
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No schedule found for this class and subject'
            ]);
        }
    }

    /**
     * Get subjects by class for dynamic form
     */
    public function getSubjectsByClass($classId, Request $request)
    {
        // Bypass auth for testing
        $currentAcademicYear = \App\Helpers\AcademicYearHelper::getCurrentAcademicYear();
        $currentSemester = \App\Helpers\AcademicYearHelper::getCurrentSemester();
        
        \Log::info('getSubjectsByClass called', [
            'classId' => $classId,
            'academicYear' => $currentAcademicYear,
            'semester' => $currentSemester
        ]);
        
        // Step 1: Get subject_ids from schedule for this class
        $subjectIds = \DB::table('schedule')
            ->where('class_id', $classId)
            ->where('academic_year', $currentAcademicYear)
            ->where('semester', $currentSemester)
            ->pluck('subject_id')
            ->unique();
        
        \Log::info('Subject IDs from schedule', ['count' => $subjectIds->count(), 'ids' => $subjectIds->toArray()]);
        
        // Step 2: Load subjects based on those subject_ids (only active subjects)
        $subjects = Subject::whereNull('deleted_at')
            ->whereIn('id', $subjectIds)
            ->get();
        
        \Log::info('Subjects found', ['count' => $subjects->count()]);
        
        return response()->json([
            'success' => true,
            'subjects' => $subjects,
            'timestamp' => now()->timestamp
        ]);
    }

    /**
     * Show form for creating a new question
     */
    public function createQuestion(Request $request)
    {
        $examId = $request->get('exam_id');
        $exam = Exam::findOrFail($examId);
        
        return response()->json([
            'success' => true,
            'title' => 'Tambah Soal',
            'html' => view('exams.questions.create', [
                'examId' => $examId,
                'question' => null
            ])->render()
        ]);
    }

    /**
     * Store a newly created question
     */
    public function storeQuestion(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'exam_id' => 'required|exists:exams,id',
            'question_type' => 'required|in:multiple_choice,multiple_choice_complex,true_false,essay',
            'question_text' => 'required|string',
            'points' => 'required|integer|min:1',
            'explanation' => 'nullable|string',
            'media_type' => 'nullable|in:image,video,audio',
            'media_file' => 'required_if:media_type,image,video,audio|file|max:10240',
            'media_caption' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ]);
        }

        try {
            // Process options in controller instead of JavaScript
            if ($request->question_type == 'multiple_choice') {
                $optionLabels = $request->option_labels ?? [];
                $optionTexts = $request->option_texts ?? [];
                $isCorrectIndex = $request->is_correct;
                
                $options = [];
                foreach ($optionTexts as $index => $text) {
                    if (!empty(trim($text))) {
                        $options[] = [
                            'option_text' => $text,
                            'option_label' => $optionLabels[$index] ?? '',
                            'is_correct' => $index == $isCorrectIndex
                        ];
                    }
                }
                
                // Validate options
                if (empty($options)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pilihan jawaban tidak boleh kosong untuk soal pilihan ganda'
                    ]);
                }
                
                // Store options for later use
                $processedOptions = $options;
            } elseif ($request->question_type == 'multiple_choice_complex') {
                $optionLabels = $request->option_labels ?? [];
                $optionTexts = $request->option_texts ?? [];
                $isCorrectIndices = $request->is_correct_multiple ?? [];
                
                $options = [];
                $checkboxIndex = 0; // Separate index for checkbox values
                
                foreach ($optionTexts as $index => $text) {
                    if (!empty(trim($text))) {
                        $isCorrect = in_array((string)$checkboxIndex, $isCorrectIndices);
                        $options[] = [
                            'option_text' => $text,
                            'option_label' => $optionLabels[$index] ?? '',
                            'is_correct' => $isCorrect
                        ];
                        
                        $checkboxIndex++; // Increment checkbox index only for non-empty options
                    }
                }
                
                // Validate options
                if (empty($options)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pilihan jawaban tidak boleh kosong untuk soal pilihan ganda kompleks'
                    ]);
                }
                
                // Validate at least one correct answer
                $hasCorrectAnswer = false;
                foreach ($options as $option) {
                    if ($option['is_correct']) {
                        $hasCorrectAnswer = true;
                        break;
                    }
                }
                
                if (!$hasCorrectAnswer) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pilihan ganda kompleks harus memiliki setidaknya satu jawaban benar'
                    ]);
                }
                
                $processedOptions = $options;
            } elseif ($request->question_type == 'true_false') {
                // Process statements for true/false questions
                $statementNumbers = $request->statement_numbers ?? [];
                $statementTexts = $request->statement_texts ?? [];
                
                $options = [];
                foreach ($statementTexts as $index => $text) {
                    if (!empty(trim($text))) {
                        // Get answer for this statement
                        $answerField = 'statement_' . $index . '_answer';
                        $isCorrect = $request->$answerField === 'true';
                        
                        // Use original label from statement_numbers to maintain correct labeling
                        $originalLabel = $statementNumbers[$index] ?? ($index + 1);
                        
                        $options[] = [
                            'option_text' => $text,
                            'option_label' => (string)$originalLabel,
                            'is_correct' => $isCorrect
                        ];
                    }
                }
                
                // Validate statements
                if (empty($options)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pernyataan tidak boleh kosong untuk soal benar/salah'
                    ]);
                }
                
                $processedOptions = $options;
            } else {
                $processedOptions = [];
            }

            DB::beginTransaction();

            // Handle media upload
            $mediaPath = null;
            $mediaType = $request->media_type;
            
            if ($request->hasFile('media_file') && $mediaType) {
                $file = $request->file('media_file');
                
                // Validate file type based on media type
                $allowedTypes = [
                    'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                    'video' => ['mp4', 'avi', 'mov', 'wmv', 'flv'],
                    'audio' => ['mp3', 'wav', 'ogg', 'aac', 'm4a']
                ];
                
                $extension = strtolower($file->getClientOriginalExtension());
                
                if (!in_array($extension, $allowedTypes[$mediaType] ?? [])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tipe file tidak sesuai dengan tipe media yang dipilih'
                    ]);
                }
                
                // Create directory if it doesn't exist
                $directory = 'exam_media/' . $mediaType;
                $fullPath = storage_path('app/public/' . $directory);
                
                if (!file_exists($fullPath)) {
                    mkdir($fullPath, 0755, true);
                }
                
                // Store file
                $mediaPath = $file->store($directory, 'public');
            }

            // Get the highest order number for this exam
            $maxOrder = \App\Models\ExamQuestion::where('exam_id', $request->exam_id)
                ->max('order') ?? 0;

            $question = \App\Models\ExamQuestion::create([
                'exam_id' => $request->exam_id,
                'question_text' => $request->question_text,
                'question_type' => $request->question_type,
                'points' => $request->points,
                'order' => $maxOrder + 1,
                'explanation' => $request->explanation,
                'media_type' => $mediaType,
                'media_path' => $mediaPath,
                'media_caption' => $request->media_caption
            ]);

            // Handle options based on question type
            if ($request->question_type == 'multiple_choice') {
                // Use processed options from earlier
                $options = $processedOptions;
                
                foreach ($options as $optionData) {
                    \App\Models\ExamQuestionOption::create([
                        'exam_question_id' => $question->id,
                        'option_text' => $optionData['option_text'],
                        'option_label' => $optionData['option_label'],
                        'is_correct' => $optionData['is_correct']
                    ]);
                }
            } elseif ($request->question_type == 'multiple_choice_complex') {
                // Use processed options from earlier
                $options = $processedOptions;
                
                foreach ($options as $optionData) {
                    \App\Models\ExamQuestionOption::create([
                        'exam_question_id' => $question->id,
                        'option_text' => $optionData['option_text'],
                        'option_label' => $optionData['option_label'],
                        'is_correct' => $optionData['is_correct']
                    ]);
                }
            } elseif ($request->question_type == 'true_false') {
                // Create True option
                \App\Models\ExamQuestionOption::create([
                    'exam_question_id' => $question->id,
                    'option_text' => 'Benar',
                    'option_label' => 'A',
                    'is_correct' => $request->true_false_answer == 'true'
                ]);

                // Create False option
                \App\Models\ExamQuestionOption::create([
                    'exam_question_id' => $question->id,
                    'option_text' => 'Salah',
                    'option_label' => 'B',
                    'is_correct' => $request->true_false_answer == 'false'
                ]);
            }

            // Update exam total questions
            $exam = Exam::find($request->exam_id);
            $exam->updateTotalQuestions();

            DB::commit();

            return redirect()
                ->route('exams.show', ['exam' => $request->exam_id, 'tab' => 'questions'])
                ->with('success', 'Soal berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan soal: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Show form for editing a question
     */
    public function editQuestion($id)
    {
        $question = \App\Models\ExamQuestion::with('options')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'title' => 'Edit Soal',
            'html' => view('exams.questions.create', [
                'examId' => $question->exam_id,
                'question' => $question
            ])->render()
        ]);
    }

    /**
     * Update the specified question
     */
    public function updateQuestion(Request $request, $id)
    {
        $question = \App\Models\ExamQuestion::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'question_type' => 'required|in:multiple_choice,multiple_choice_complex,true_false,essay',
            'question_text' => 'required|string',
            'points' => 'required|integer|min:1',
            'explanation' => 'nullable|string',
            'media_type' => 'nullable|in:image,video,audio',
            'media_file' => 'nullable|file|max:10240',
            'media_caption' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ]);
        }

        try {
            DB::beginTransaction();

            // Handle media upload
            $mediaPath = $question->media_path; // Keep existing media by default
            $mediaType = $request->media_type ?? $question->media_type;
            
            if ($request->hasFile('media_file')) {
                $file = $request->file('media_file');
                
                // Validate file type based on media type
                $allowedTypes = [
                    'image' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                    'video' => ['mp4', 'avi', 'mov', 'wmv', 'flv'],
                    'audio' => ['mp3', 'wav', 'ogg', 'aac', 'm4a']
                ];
                
                $extension = strtolower($file->getClientOriginalExtension());
                
                if (!in_array($extension, $allowedTypes[$mediaType] ?? [])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tipe file tidak sesuai dengan tipe media yang dipilih'
                    ]);
                }
                
                // Create directory if it doesn't exist
                $directory = 'exam_media/' . $mediaType;
                $fullPath = storage_path('app/public/' . $directory);
                
                if (!file_exists($fullPath)) {
                    mkdir($fullPath, 0755, true);
                }
                
                // Delete old media file if exists
                if ($question->media_path && file_exists(storage_path('app/public/' . $question->media_path))) {
                    unlink(storage_path('app/public/' . $question->media_path));
                }
                
                // Store new file
                $mediaPath = $file->store($directory, 'public');
            } elseif (empty($mediaType)) {
                // If media type is empty, remove media
                if ($question->media_path && file_exists(storage_path('app/public/' . $question->media_path))) {
                    unlink(storage_path('app/public/' . $question->media_path));
                }
                $mediaPath = null;
                $mediaType = null;
            }

            $question->update([
                'question_text' => $request->question_text,
                'question_type' => $request->question_type,
                'points' => $request->points,
                'explanation' => $request->explanation,
                'media_type' => $mediaType,
                'media_path' => $mediaPath,
                'media_caption' => $request->media_caption
            ]);

            // Delete existing options
            $question->options()->delete();

            // Handle options based on question type
            if ($request->question_type == 'multiple_choice') {
                // Process options the same way as storeQuestion
                $optionLabels = $request->option_labels ?? [];
                $optionTexts = $request->option_texts ?? [];
                $isCorrectIndex = $request->is_correct;
                
                $options = [];
                foreach ($optionTexts as $index => $text) {
                    if (!empty(trim($text))) {
                        $options[] = [
                            'option_text' => $text,
                            'option_label' => $optionLabels[$index] ?? '',
                            'is_correct' => $index == $isCorrectIndex
                        ];
                    }
                }
                
                if (!empty($options)) {
                    foreach ($options as $optionData) {
                        \App\Models\ExamQuestionOption::create([
                            'exam_question_id' => $question->id,
                            'option_text' => $optionData['option_text'],
                            'option_label' => $optionData['option_label'],
                            'is_correct' => $optionData['is_correct']
                        ]);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pilihan jawaban tidak boleh kosong untuk soal pilihan ganda'
                    ]);
                }
            } elseif ($request->question_type == 'multiple_choice_complex') {
                // Process options for complex multiple choice
                $optionLabels = $request->option_labels ?? [];
                $optionTexts = $request->option_texts ?? [];
                $isCorrectIndices = $request->is_correct_multiple ?? [];
                
                $options = [];
                $checkboxIndex = 0; // Separate index for checkbox values
                
                foreach ($optionTexts as $index => $text) {
                    if (!empty(trim($text))) {
                        $isCorrect = in_array((string)$checkboxIndex, $isCorrectIndices);
                        $options[] = [
                            'option_text' => $text,
                            'option_label' => $optionLabels[$index] ?? '',
                            'is_correct' => $isCorrect
                        ];
                        
                        $checkboxIndex++; // Increment checkbox index only for non-empty options
                    }
                }
                
                if (!empty($options)) {
                    foreach ($options as $optionData) {
                        \App\Models\ExamQuestionOption::create([
                            'exam_question_id' => $question->id,
                            'option_text' => $optionData['option_text'],
                            'option_label' => $optionData['option_label'],
                            'is_correct' => $optionData['is_correct']
                        ]);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pilihan jawaban tidak boleh kosong untuk soal pilihan ganda kompleks'
                    ]);
                }
            } elseif ($request->question_type == 'true_false') {
                // Process statements for true/false questions
                $statementNumbers = $request->statement_numbers ?? [];
                $statementTexts = $request->statement_texts ?? [];
                
                $options = [];
                foreach ($statementTexts as $index => $text) {
                    if (!empty(trim($text))) {
                        // Get answer for this statement
                        $answerField = 'statement_' . $index . '_answer';
                        $isCorrect = $request->$answerField === 'true';
                        
                        // Use original label from statement_numbers to maintain correct labeling
                        $originalLabel = $statementNumbers[$index] ?? ($index + 1);
                        
                        $options[] = [
                            'option_text' => $text,
                            'option_label' => (string)$originalLabel,
                            'is_correct' => $isCorrect
                        ];
                    }
                }
                
                // Validate statements
                if (empty($options)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Pernyataan tidak boleh kosong untuk soal benar/salah'
                    ]);
                }
                
                foreach ($options as $optionData) {
                    \App\Models\ExamQuestionOption::create([
                        'exam_question_id' => $question->id,
                        'option_text' => $optionData['option_text'],
                        'option_label' => $optionData['option_label'],
                        'is_correct' => $optionData['is_correct']
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('exams.show', ['exam' => $question->exam_id, 'tab' => 'questions'])
                ->with('success', 'Soal berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui soal: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified question
     */
    public function destroyQuestion($id)
    {
        $question = \App\Models\ExamQuestion::findOrFail($id);
        $examId = $question->exam_id;

        try {
            DB::beginTransaction();

            $question->delete();

            // Update exam total questions
            $exam = Exam::find($examId);
            $exam->updateTotalQuestions();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Soal berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus soal: ' . $e->getMessage()
            ]);
        }
    }
}
