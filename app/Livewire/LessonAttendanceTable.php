<?php

namespace App\Livewire;

use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class LessonAttendanceTable extends Component
{
    public $search = '';
    public $lessonAttendances = [];
    public $lastUpdated;
    public $isParent = false;
    public $parentStudentId = null;
    
    // Listener untuk event
    protected $listeners = [
        'refresh' => '$refresh',
        'refreshLessonAttendances' => 'autoUpdateLessonAttendances'
    ];

    public function mount()
    {
        $this->checkUserRole();
        $this->loadLessonAttendances();
        $this->lastUpdated = now();
    }

    public function checkUserRole()
    {
        $user = Auth::user();
        
        if ($user->hasRole('parent')) {
            $this->isParent = true;
            
            // Ambil student_id dari tabel parent
            $parent = DB::table('parent')
                ->where('user_id', $user->id)
                ->whereNull('deleted_at')
                ->first();
            
            if ($parent && $parent->student_id) {
                $this->parentStudentId = $parent->student_id;
            }
        }
    }

    public function updatedSearch()
    {
        // Jika parent, tidak bisa melakukan pencarian
        if ($this->isParent) {
            $this->search = '';
            return;
        }
        
        $this->loadLessonAttendances();
    }

    // Method yang diperlukan untuk auto update
    public function autoUpdateLessonAttendances()
    {
        $this->loadLessonAttendances();
        $this->lastUpdated = now();
    }

    public function loadLessonAttendances()
    {
        $query = $this->isParent ? '' : $this->search;
        
        // Query untuk data lesson attendance
        $lessonAttendancesQuery = DB::table('lesson_attendance as la')
            ->select([
                'la.id as lesson_attendance_id',
                'la.student_id',
                'la.class_id',
                'la.subject_id', 
                'la.date',
                's.name as student_name',
                's.nisn as student_nisn',
                'c.name as class_name',
                'sub.name as subject_name',
                'la.check_in',
                'la.check_in_status'
            ])
            ->join('student as s', 'la.student_id', '=', 's.id')
            ->join('classes as c', 'la.class_id', '=', 'c.id')
            ->join('subject as sub', 'la.subject_id', '=', 'sub.id')
            ->whereNull('la.deleted_at')
            ->whereNull('s.deleted_at')
            ->whereNull('c.deleted_at')
            ->whereNull('sub.deleted_at');
        
        // Jika user adalah parent, batasi hanya data anak mereka
        if ($this->isParent) {
            if ($this->parentStudentId) {
                $lessonAttendancesQuery->where('la.student_id', $this->parentStudentId);
            } else {
                // Jika parent tidak memiliki student_id, return empty array
                $this->lessonAttendances = [];
                return;
            }
        } else {
            // Jika bukan parent, bisa melakukan pencarian
            $lessonAttendancesQuery->when($query, function($q) use ($query) {
                return $q->where(function($subQuery) use ($query) {
                    $subQuery->where('s.name', 'LIKE', "%{$query}%")
                            ->orWhere('c.name', 'LIKE', "%{$query}%")
                            ->orWhere('sub.name', 'LIKE', "%{$query}%")
                            ->orWhere('la.date', 'LIKE', "%{$query}%");
                });
            });
        }
        
        $this->lessonAttendances = $lessonAttendancesQuery
            ->orderBy('la.date', 'desc')
            ->orderBy('la.check_in', 'desc')
            ->get()
            ->toArray();
    }

    public function deleteLessonAttendance($id)
    {
        // Parent tidak bisa menghapus data
        if ($this->isParent) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'title' => 'Akses Ditolak',
                'message' => 'Anda tidak memiliki izin untuk menghapus data absensi pelajaran'
            ]);
            return;
        }
        
        try {
            DB::beginTransaction();

            $lessonAttendance = Lesson::find($id);
            if (!$lessonAttendance) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'title' => 'Gagal',
                    'message' => 'Data tidak ditemukan'
                ]);
                return;
            }

            // Soft delete data lesson attendance
            $lessonAttendance->delete();

            DB::commit();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Data absensi pelajaran berhasil dihapus!'
            ]);

            $this->loadLessonAttendances();

        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatch('show-alert', [
                'type' => 'error',
                'title' => 'Gagal',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.lesson-attendance-table');
    }
}
