<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\tahfiz;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TahfizController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('tahfiz.index');
        
        $classes = Classes::orderBy('name')->get();
        
        return view('tahfiz.index', compact('classes'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $tahfiz = Tahfiz::with(['student', 'class'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $tahfiz
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tahfiz record not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get active students in a class
     */
    public function getStudents(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'class_id' => 'required|string|exists:classes,id',
                'academic_year' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $students = DB::table('student')
                ->select([
                    'student.id',
                    'student.name',
                    'student.nisn',
                    'student.no_absen'
                ])
                ->join('student_class', 'student.id', '=', 'student_class.student_id')
                ->where('student_class.class_id', $request->class_id)
                ->where('student_class.academic_year', $request->academic_year)
                ->where('student_class.status', 'active')
                ->whereNull('student.deleted_at')
                ->whereNull('student_class.deleted_at')
                ->orderByRaw('CAST(student.no_absen AS UNSIGNED) ASC')
                ->orderBy('student.name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $students,
                'count' => $students->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getStudents: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch students',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get tahfiz records for students
     */
    public function getTahfizRecords(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'class_id' => 'required|string|exists:classes,id',
                'month' => 'required|integer|min:1|max:12',
                'academic_year' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $academicYear = $request->academic_year ?: date('Y') . '/' . (date('Y') + 1);

            // Get students
            $students = DB::table('student')
                ->select([
                    'student.id as student_id',
                    'student.name as student_name',
                    'student.nisn as student_nisn',
                    'student.no_absen'
                ])
                ->join('student_class', 'student.id', '=', 'student_class.student_id')
                ->where('student_class.class_id', $request->class_id)
                ->where('student_class.academic_year', $academicYear)
                ->where('student_class.status', 'active')
                ->whereNull('student.deleted_at')
                ->whereNull('student_class.deleted_at')
                ->orderByRaw('CAST(student.no_absen AS UNSIGNED) ASC')
                ->orderBy('student.name')
                ->get();

            // Get existing tahfiz records
            $existingRecords = Tahfiz::where('class_id', $request->class_id)
                ->where('month', $request->month)
                ->where('academic_year', $academicYear)
                ->whereNull('deleted_at')
                ->get()
                ->keyBy('student_id');

            // Combine student data with tahfiz records
            $tahfizData = $students->map(function ($student) use ($existingRecords) {
                $record = $existingRecords->get($student->student_id);
                
                return [
                    'id' => $record->id ?? null,
                    'student_id' => $student->student_id,
                    'student_name' => $student->student_name,
                    'student_nisn' => $student->student_nisn,
                    'no_absen' => $student->no_absen,
                    'progres_tahfiz' => $record->progres_tahfiz ?? null,
                    'progres_tahsin' => $record->progres_tahsin ?? null,
                    'target_hafalan' => $record->target_hafalan ?? null,
                    'efektif_halaqoh' => $record->efektif_halaqoh ?? null,
                    'hadir' => $record->hadir ?? null,
                    'keatifan' => $record->keatifan ?? null,
                    'izin' => $record->izin ?? null,
                    'sakit' => $record->sakit ?? null,
                    'alpha' => $record->alpha ?? null,
                    'created_at' => $record->created_at ?? null,
                    'updated_at' => $record->updated_at ?? null,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $tahfizData,
                'count' => $tahfizData->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getTahfizRecords: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch tahfiz records',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'student_id' => 'required|string|exists:student,id',
                'class_id' => 'required|string|exists:classes,id',
                'academic_year' => 'required|string',
                'month' => 'required|integer|min:1|max:12',
                'progres_tahfiz' => 'nullable|string|max:255',
                'progres_tahsin' => 'nullable|string|max:255',
                'target_hafalan' => 'nullable|string|max:255',
                'efektif_halaqoh' => 'nullable|integer|min:0',
                'hadir' => 'nullable|integer|min:0',
                'keatifan' => 'nullable|integer|min:0|max:100',
                'izin' => 'nullable|integer|min:0',
                'sakit' => 'nullable|integer|min:0',
                'alpha' => 'nullable|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check if record already exists
            $existingRecord = Tahfiz::where([
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'academic_year' => $request->academic_year,
                'month' => $request->month
            ])->first();

            if ($existingRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tahfiz record for this student and month already exists'
                ], 409);
            }

            DB::beginTransaction();

            $monthNames = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            $tahfiz = Tahfiz::create([
                'id' => Str::uuid(),
                'student_id' => $request->student_id,
                'class_id' => $request->class_id,
                'academic_year' => $request->academic_year,
                'month' => $request->month,
                'month_name' => $monthNames[$request->month],
                'progres_tahfiz' => $request->progres_tahfiz,
                'progres_tahsin' => $request->progres_tahsin,
                'target_hafalan' => $request->target_hafalan,
                'efektif_halaqoh' => $request->efektif_halaqoh,
                'hadir' => $request->hadir,
                'keatifan' => $request->keatifan,
                'izin' => $request->izin,
                'sakit' => $request->sakit,
                'alpha' => $request->alpha,
                'created_by' => Auth::id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tahfiz record created successfully',
                'data' => $tahfiz
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create tahfiz record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'progres_tahfiz' => 'nullable|string|max:255',
                'progres_tahsin' => 'nullable|string|max:255',
                'target_hafalan' => 'nullable|string|max:255',
                'efektif_halaqoh' => 'nullable|integer|min:0',
                'hadir' => 'nullable|integer|min:0',
                'keatifan' => 'nullable|integer|min:0|max:100',
                'izin' => 'nullable|integer|min:0',
                'sakit' => 'nullable|integer|min:0',
                'alpha' => 'nullable|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $tahfiz = Tahfiz::findOrFail($id);

            DB::beginTransaction();

            $tahfiz->update([
                'progres_tahfiz' => $request->progres_tahfiz,
                'progres_tahsin' => $request->progres_tahsin,
                'target_hafalan' => $request->target_hafalan,
                'efektif_halaqoh' => $request->efektif_halaqoh,
                'hadir' => $request->hadir,
                'keatifan' => $request->keatifan,
                'izin' => $request->izin,
                'sakit' => $request->sakit,
                'alpha' => $request->alpha,
                'updated_by' => Auth::id()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tahfiz record updated successfully',
                'data' => $tahfiz
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update tahfiz record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $tahfiz = Tahfiz::findOrFail($id);

            DB::beginTransaction();

            $tahfiz->update(['deleted_by' => Auth::id()]);
            $tahfiz->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Tahfiz record deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete tahfiz record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $classes = Classes::orderBy('name')->get();
        
        return view('tahfiz.create', compact('classes'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $tahfiz = Tahfiz::with(['student', 'class'])->findOrFail($id);
        $classes = Classes::orderBy('name')->get();
        
        return view('tahfiz.edit', compact('tahfiz', 'classes'));
    }

    /**
     * Bulk update tahfiz records
     */
    public function bulkUpdate(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'records' => 'required|array',
                'records.*.student_id' => 'required|string|exists:student,id',
                'records.*.class_id' => 'required|string|exists:classes,id',
                'records.*.academic_year' => 'required|string',
                'records.*.month' => 'required|integer|min:1|max:12',
                'records.*.progres_tahfiz' => 'nullable|string|max:255',
                'records.*.progres_tahsin' => 'nullable|string|max:255',
                'records.*.target_hafalan' => 'nullable|string|max:255',
                'records.*.efektif_halaqoh' => 'nullable|integer|min:0',
                'records.*.hadir' => 'nullable|integer|min:0',
                'records.*.keatifan' => 'nullable|integer|min:0|max:100',
                'records.*.izin' => 'nullable|integer|min:0',
                'records.*.sakit' => 'nullable|integer|min:0',
                'records.*.alpha' => 'nullable|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            $monthNames = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            $updated = 0;
            $created = 0;

            foreach ($request->records as $recordData) {
                // Find existing record
                $existingRecord = Tahfiz::where([
                    'student_id' => $recordData['student_id'],
                    'class_id' => $recordData['class_id'],
                    'academic_year' => $recordData['academic_year'],
                    'month' => $recordData['month']
                ])->first();

                // Prepare update data
                $updateData = [
                    'progres_tahfiz' => $recordData['progres_tahfiz'] ?? null,
                    'progres_tahsin' => $recordData['progres_tahsin'] ?? null,
                    'target_hafalan' => $recordData['target_hafalan'] ?? null,
                    'efektif_halaqoh' => $recordData['efektif_halaqoh'] ?? null,
                    'hadir' => $recordData['hadir'] ?? null,
                    'keatifan' => $recordData['keatifan'] ?? null,
                    'izin' => $recordData['izin'] ?? null,
                    'sakit' => $recordData['sakit'] ?? null,
                    'alpha' => $recordData['alpha'] ?? null,
                ];

                if ($existingRecord) {
                    // Update existing record
                    $existingRecord->update(array_merge($updateData, [
                        'updated_by' => Auth::id()
                    ]));
                    $updated++;
                } else {
                    // Create new record only if there's at least one value
                    $hasValues = array_filter($updateData, function($value) { 
                        return $value !== null && $value !== ''; 
                    });
                    
                    if (count($hasValues) > 0) {
                        Tahfiz::create(array_merge($updateData, [
                            'id' => Str::uuid(),
                            'student_id' => $recordData['student_id'],
                            'class_id' => $recordData['class_id'],
                            'academic_year' => $recordData['academic_year'],
                            'month' => $recordData['month'],
                            'month_name' => $monthNames[$recordData['month']],
                            'created_by' => Auth::id()
                        ]));
                        $created++;
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Berhasil memperbarui {$updated} data dan membuat {$created} data tahfiz baru",
                'stats' => [
                    'updated' => $updated,
                    'created' => $created,
                    'total_processed' => count($request->records)
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            
            Log::error('Bulk update tahfiz error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data tahfiz',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
