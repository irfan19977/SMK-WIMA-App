<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Schedule::with(['classRoom', 'subject', 'teacher']);
        
        // Filter pencarian berdasarkan nama guru, nama kelas, dan mata pelajaran
        if ($request->has('q') && !empty($request->q)) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->whereHas('teacher', function($subQuery) use ($searchTerm) {
                    $subQuery->where('name', 'LIKE', '%' . $searchTerm . '%')
                            ->orWhere('nip', 'LIKE', '%' . $searchTerm . '%');
                })
                ->orWhereHas('classRoom', function($subQuery) use ($searchTerm) {
                    $subQuery->where('name', 'LIKE', '%' . $searchTerm . '%');
                })
                ->orWhereHas('subject', function($subQuery) use ($searchTerm) {
                    $subQuery->where('name', 'LIKE', '%' . $searchTerm . '%');
                })
                ->orWhere('day', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        
        $query->orderByRaw("FIELD(day, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')")
            ->orderBy('start_time');
        
        $schedules = $query->paginate(10);
        
        // Jika request AJAX, return JSON
        if ($request->ajax() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'schedules' => $schedules->items(),
                'pagination' => [
                    'current_page' => $schedules->currentPage(),
                    'per_page' => $schedules->perPage(),
                    'total' => $schedules->total(),
                    'last_page' => $schedules->lastPage(),
                ],
                'currentPage' => $schedules->currentPage(),
                'perPage' => $schedules->perPage(),
            ]);
        }
        
        return view('schedules.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = Teacher::all();
        $classes = Classes::all();
        $subjects = Subject::all();

        return view('schedules.create', compact('teachers', 'classes', 'subjects'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject_id' => 'required',
            'teacher_id' => 'required',
            'class_id' => 'required',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'academic_year' => 'required|string|max:10',
        ]);

        // Cek apakah jadwal bentrok dengan jadwal lain (guru mengajar di kelas lain pada waktu yang sama)
        $conflictingTeacherSchedule = Schedule::where('teacher_id', $request->teacher_id)
        ->where('day', $request->day)
        ->where(function ($query) use ($request) {
            $query->where(function($q) use ($request) {
                    $q->where('start_time', '<', $request->end_time)
                    ->where('end_time', '>', $request->start_time);
                });
        })->first();

        if ($conflictingTeacherSchedule) {
            return redirect()->back()
                ->with('error', 'Guru sudah memiliki jadwal mengajar pada waktu yang sama!')
                ->withInput();
        }

        // Cek apakah kelas sudah memiliki jadwal pada waktu yang sama
        $conflictingClassSchedule = Schedule::where('class_id', $request->class_id)
            ->where('day', $request->day)
            ->where(function ($query) use ($request) {
                $query->where(function($q) use ($request) {
                        $q->where('start_time', '<', $request->end_time)
                        ->where('end_time', '>', $request->start_time);
                    });
            })->first();

        if ($conflictingClassSchedule) {
            return redirect()->back()
                ->with('error', 'Kelas sudah memiliki jadwal pelajaran pada waktu yang sama!')
                ->withInput();
        }

        $schedules = Schedule::create([
            'subject_id'     => $request->input('subject_id'),
            'teacher_id'     => $request->input('teacher_id'),
            'class_id'  => $request->input('class_id'),
            'day'            => $request->input('day'),
            'start_time'     => $request->input('start_time'),
            'end_time'     => $request->input('end_time'),
            'academic_year'     => $request->input('academic_year'),
        ]);

        if ($schedules) {
            return redirect()->route('schedules.index')->with('success', 'Data Berhasil Disimpan');
        } else {
            return redirect()->route('schedules.index')->with('error', 'Data Gagal Disimpan');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $schedules = Schedule::findOrFail($id);
    
        // Ambil data untuk dropdown
        $subjects = Subject::all();
        $teachers = Teacher::all();
        $classes = Classes::all();
        
        return view('schedules.edit', compact('schedules', 'subjects', 'teachers', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input
        $validated = $request->validate([
            'subject_id' => 'required',
            'teacher_id' => 'required',
            'class_id' => 'required',
            'day' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'academic_year' => 'required|string|max:10',
        ]);

        $schedule = Schedule::findOrFail($id);
        
        // Cek apakah jadwal bentrok dengan jadwal lain (guru mengajar di kelas lain pada waktu yang sama)
        // Kecualikan jadwal yang sedang diedit dari pengecekan
        $conflictingTeacherSchedule = Schedule::where('teacher_id', $request->teacher_id)
            ->where('day', $request->day)
            ->where('id', '!=', $id)
            ->where(function ($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('start_time', '<', $request->end_time)
                    ->where('end_time', '>', $request->start_time);
                });
            })->first();

        if ($conflictingTeacherSchedule) {
            return redirect()->back()
                ->with('error', 'Guru sudah memiliki jadwal mengajar pada waktu yang sama!')
                ->withInput();
        }

        // Cek apakah kelas sudah memiliki jadwal pada waktu yang sama
        // Kecualikan jadwal yang sedang diedit dari pengecekan
        $conflictingClassSchedule = Schedule::where('class_id', $request->class_id)
            ->where('day', $request->day)
            ->where('id', '!=', $id)
            ->where(function ($query) use ($request) {
                $query->where(function($q) use ($request) {
                    $q->where('start_time', '<', $request->end_time)
                    ->where('end_time', '>', $request->start_time);
                });
            })->first();

        if ($conflictingClassSchedule) {
            return redirect()->back()
                ->with('error', 'Kelas sudah memiliki jadwal pelajaran pada waktu yang sama!')
                ->withInput();
        }

        // Update jadwal
        $updated = $schedule->update([
            'subject_id'    => $request->input('subject_id'),
            'teacher_id'    => $request->input('teacher_id'),
            'class_id'      => $request->input('class_id'),
            'day'           => $request->input('day'),
            'start_time'    => $request->input('start_time'),
            'end_time'      => $request->input('end_time'),
            'academic_year' => $request->input('academic_year'),
        ]);

        if ($updated) {
            return redirect()->route('schedules.index')->with('success', 'Data Berhasil Diperbarui');
        } else {
            return redirect()->route('schedules.index')->with('error', 'Data Gagal Diperbarui');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $schedule = Schedule::findOrFail($id);
            $schedule->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}
