<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Classes;
use App\Models\Subject;
use App\Models\User;
use App\Models\Exam;
use App\Helpers\AcademicYearHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExamForm extends Component
{
    public $title;
    public $description;
    public $class_id;
    public $subject_id;
    public $teacher_id;
    public $teacher_name;
    public $duration_minutes = 60;
    public $passing_score = 70;
    public $start_time;
    public $end_time;
    public $academic_year;
    public $semester;

    public $classes;
    public $subjects;
    public $exam;

    protected $rules = [
        'title' => 'required|string|max:255',
        'class_id' => 'required|exists:classes,id',
        'subject_id' => 'required|exists:subject,id',
        'duration_minutes' => 'required|integer|min:5|max:720',
        'passing_score' => 'required|numeric|min:0|max:100',
        'start_time' => 'required|date|after:now',
        'end_time' => 'required|date|after:start_time',
    ];

    protected $messages = [
        'title.required' => 'Judul ujian wajib diisi.',
        'class_id.required' => 'Kelas wajib dipilih.',
        'subject_id.required' => 'Mata pelajaran wajib dipilih.',
        'duration_minutes.required' => 'Durasi ujian wajib diisi.',
        'passing_score.required' => 'Nilai kelulusan wajib diisi.',
        'start_time.required' => 'Waktu mulai wajib diisi.',
        'end_time.required' => 'Waktu selesai wajib diisi.',
    ];

    public function mount($exam = null)
    {
        $this->exam = $exam;
        $this->academic_year = AcademicYearHelper::getCurrentAcademicYear();
        $this->semester = AcademicYearHelper::getCurrentSemester();

        $this->loadClasses();

        if ($exam) {
            $this->title = $exam->title;
            $this->description = $exam->description;
            $this->class_id = $exam->class_id;
            $this->subject_id = $exam->subject_id;
            $this->teacher_id = $exam->teacher_id;
            $this->duration_minutes = $exam->duration_minutes;
            $this->passing_score = $exam->passing_score;
            $this->start_time = $exam->start_time->format('Y-m-d\TH:i');
            $this->end_time = $exam->end_time->format('Y-m-d\TH:i');

            $this->loadSubjects();
            $this->loadTeacher();
        }
    }

    public function loadClasses()
    {
        $this->classes = Classes::whereIn('id', function($query) {
            $query->select('class_id')
                ->from('schedule')
                ->where('academic_year', $this->academic_year)
                ->where('semester', $this->semester)
                ->distinct();
        })->get();
    }

    public function updatedClassId()
    {
        $this->loadSubjects();
        $this->subject_id = null;
        $this->teacher_id = null;
        $this->teacher_name = '';
    }

    public function updatedSubjectId()
    {
        $this->loadTeacher();
    }

    public function loadSubjects()
    {
        if (!$this->class_id) {
            $this->subjects = collect([]);
            return;
        }

        $this->subjects = Subject::whereIn('id', function($query) {
            $query->select('subject_id')
                ->from('schedule')
                ->where('class_id', $this->class_id)
                ->where('academic_year', $this->academic_year)
                ->where('semester', $this->semester)
                ->distinct();
        })->get();
    }

    public function loadTeacher()
    {
        if (!$this->class_id || !$this->subject_id) {
            $this->teacher_id = null;
            $this->teacher_name = '';
            return;
        }

        $schedule = \DB::table('schedule')
            ->where('class_id', $this->class_id)
            ->where('subject_id', $this->subject_id)
            ->where('academic_year', $this->academic_year)
            ->where('semester', $this->semester)
            ->first();

        if ($schedule) {
            $teacher = User::find($schedule->teacher_id);
            $this->teacher_id = $schedule->teacher_id;
            $this->teacher_name = $teacher ? $teacher->name : 'Tidak ada guru';
        } else {
            $this->teacher_id = null;
            $this->teacher_name = 'Tidak ada jadwal aktif';
        }
    }

    public function save()
    {
        $this->validate();

        try {
            DB::beginTransaction();
            
            $exam = Exam::create([
                'title' => $this->title,
                'description' => $this->description,
                'class_id' => $this->class_id,
                'subject_id' => $this->subject_id,
                'teacher_id' => $this->teacher_id,
                'duration_minutes' => $this->duration_minutes,
                'passing_score' => $this->passing_score,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'status' => 'draft',
                'created_by' => Auth::id(),
            ]);

            DB::commit();

            // Emit success event to parent
            $this->dispatch('examSaved', [
                'exam' => $exam,
                'message' => 'Ujian berhasil dibuat!'
            ]);

            // Show success message and close modal
            session()->flash('success', 'Ujian berhasil dibuat!');
            
            // Use JavaScript to close modal and redirect
            $this->dispatch('closeModalAndRedirect', [
                'url' => route('exams.index'),
                'message' => 'Ujian berhasil dibuat!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            session()->flash('error', 'Gagal membuat ujian: ' . $e->getMessage());
            $this->dispatch('showError', 'Gagal membuat ujian: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.exam-form');
    }
}
