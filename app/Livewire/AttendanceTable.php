<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\Classes;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Str;

class AttendanceTable extends Component
{
    public $search = '';
    public $attendances = [];
    public $lastUpdated;
    public $isParent = false;
    public $parentStudentId = null;
    
    // Listener untuk event
    protected $listeners = [
        'refresh' => '$refresh',
        'refreshAttendances' => 'autoUpdateAttendances'
    ];

    public function mount()
    {
        $this->checkUserRole();
        $this->loadAttendances();
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
        
        $this->loadAttendances();
    }

    // Method yang diperlukan untuk auto update
    public function autoUpdateAttendances()
    {
        $this->loadAttendances();
        $this->lastUpdated = now();
    }

    public function loadAttendances()
    {
        $query = $this->isParent ? '' : $this->search;
        
        // Query untuk menggabungkan data check_in dan check_out dalam satu baris
        $attendancesQuery = DB::table('attendance as a1')
            ->select([
                'a1.id as attendance_id',
                'a1.student_id',
                'a1.class_id', 
                'a1.date',
                's.name as student_name',
                'c.name as class_name',
                // Data check in
                'a1.check_in',
                'a1.check_in_status',
                // Data check out
                'a2.check_out',
                'a2.check_out_status',
                'a2.id as checkout_id'
            ])
            ->leftJoin('attendance as a2', function($join) {
                $join->on('a1.student_id', '=', 'a2.student_id')
                     ->on('a1.class_id', '=', 'a2.class_id')
                     ->on('a1.date', '=', 'a2.date')
                     ->whereNotNull('a2.check_out')
                     ->whereNull('a2.deleted_at');
            })
            ->join('student as s', 'a1.student_id', '=', 's.id')
            ->join('classes as c', 'a1.class_id', '=', 'c.id')
            ->whereNotNull('a1.check_in')
            ->whereNull('a1.deleted_at')
            ->whereNull('s.deleted_at')
            ->whereNull('c.deleted_at');
        
        // Jika user adalah parent, batasi hanya data anak mereka
        if ($this->isParent) {
            if ($this->parentStudentId) {
                $attendancesQuery->where('a1.student_id', $this->parentStudentId);
            } else {
                // Jika parent tidak memiliki student_id, return empty array
                $this->attendances = [];
                return;
            }
        } else {
            // Jika bukan parent, bisa melakukan pencarian
            $attendancesQuery->when($query, function($q) use ($query) {
                return $q->where(function($subQuery) use ($query) {
                    $subQuery->where('s.name', 'LIKE', "%{$query}%")
                            ->orWhere('c.name', 'LIKE', "%{$query}%")
                            ->orWhere('a1.date', 'LIKE', "%{$query}%");
                });
            });
        }
        
        $this->attendances = $attendancesQuery
            ->orderBy('a1.date', 'desc')
            ->orderBy('a1.check_in', 'asc')
            ->get()
            ->toArray();
    }

    public function deleteAttendance($id)
    {
        // Parent tidak bisa menghapus data
        if ($this->isParent) {
            $this->dispatch('show-alert', [
                'type' => 'error',
                'title' => 'Akses Ditolak',
                'message' => 'Anda tidak memiliki izin untuk menghapus data absensi'
            ]);
            return;
        }
        
        try {
            DB::beginTransaction();

            $attendance = Attendance::find($id);
            if (!$attendance) {
                $this->dispatch('show-alert', [
                    'type' => 'error',
                    'title' => 'Gagal',
                    'message' => 'Data tidak ditemukan'
                ]);
                return;
            }

            // Soft delete semua data attendance untuk student, class, dan date yang sama
            Attendance::where([
                'student_id' => $attendance->student_id,
                'class_id' => $attendance->class_id,
                'date' => $attendance->date
            ])->whereNull('deleted_at')->delete();

            DB::commit();

            $this->dispatch('show-alert', [
                'type' => 'success',
                'title' => 'Berhasil!',
                'message' => 'Data absensi berhasil dihapus!'
            ]);

            $this->loadAttendances();

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
        return view('livewire.attendance-table');
    }
}