<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentGrades extends Model
{
    use SoftDeletes;

    protected $table = 'student_grades';
    protected $guarded = [];
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    protected $casts = [
        'h1' => 'decimal:2',
        'h2' => 'decimal:2',
        'h3' => 'decimal:2',
        'k1' => 'decimal:2',
        'k2' => 'decimal:2',
        'k3' => 'decimal:2',
        'ck' => 'decimal:2',
        'p' => 'decimal:2',
        'k' => 'decimal:2',
        'aktif' => 'decimal:2',
        'nilai' => 'decimal:2',
        'month' => 'integer',
        // New grading schema
        'tugas1' => 'decimal:2',
        'tugas2' => 'decimal:2',
        'sikap' => 'decimal:2',
        'uts' => 'decimal:2',
        'uas' => 'decimal:2'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Relationship with Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    /**
     * Relationship with Class
     */
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id', 'id');
    }

    /**
     * Relationship with Subject
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    /**
     * Relationship with User (Created By)
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Relationship with User (Updated By)
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    /**
     * Relationship with User (Deleted By)
     */
    public function deletedBy()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }

    /**
     * Scope for filtering by academic year
     */
    public function scopeAcademicYear($query, $academicYear)
    {
        return $query->where('academic_year', $academicYear);
    }

    /**
     * Scope for filtering by semester
     */
    public function scopeSemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    /**
     * Scope for filtering by month
     */
    public function scopeMonth($query, $month)
    {
        return $query->where('month', $month);
    }

    /**
     * Scope for filtering by class
     */
    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    /**
     * Scope for filtering by subject
     */
    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    /**
     * Scope for filtering by student
     */
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Calculate final grade from all components
     */
    public function calculateFinalGrade()
    {
        $gradeComponents = [
            $this->h1, $this->h2, $this->h3,
            $this->k1, $this->k2, $this->k3,
            $this->ck, $this->p, $this->k, $this->aktif
        ];

        // Filter out null values
        $validGrades = array_filter($gradeComponents, function($grade) {
            return $grade !== null;
        });

        if (empty($validGrades)) {
            return null;
        }

        return round(array_sum($validGrades) / count($validGrades), 2);
    }

    /**
     * Get grade status (passed/failed)
     */
    public function getGradeStatusAttribute()
    {
        if ($this->nilai === null) {
            return 'incomplete';
        }

        return $this->nilai >= 70 ? 'passed' : 'failed';
    }

    /**
     * Get grade category
     */
    public function getGradeCategoryAttribute()
    {
        if ($this->nilai === null) {
            return 'incomplete';
        }

        if ($this->nilai >= 90) {
            return 'excellent';
        } elseif ($this->nilai >= 80) {
            return 'good';
        } elseif ($this->nilai >= 70) {
            return 'fair';
        } else {
            return 'poor';
        }
    }

    /**
     * Get month name in Indonesian
     */
    public function getMonthNameIndonesianAttribute()
    {
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return $monthNames[$this->month] ?? '';
    }

    /**
     * Check if grade is complete (has at least one component)
     */
    public function isComplete()
    {
        return !empty(array_filter([
            $this->h1, $this->h2, $this->h3,
            $this->k1, $this->k2, $this->k3,
            $this->ck, $this->p, $this->k, $this->aktif
        ]));
    }

    /**
     * Get formatted grades for display
     */
    public function getFormattedGradesAttribute()
    {
        return [
            'harian' => [
                'h1' => $this->h1 ? number_format($this->h1, 2) : '-',
                'h2' => $this->h2 ? number_format($this->h2, 2) : '-',
                'h3' => $this->h3 ? number_format($this->h3, 2) : '-',
            ],
            'kuis' => [
                'k1' => $this->k1 ? number_format($this->k1, 2) : '-',
                'k2' => $this->k2 ? number_format($this->k2, 2) : '-',
                'k3' => $this->k3 ? number_format($this->k3, 2) : '-',
            ],
            'others' => [
                'ck' => $this->ck ? number_format($this->ck, 2) : '-',
                'p' => $this->p ? number_format($this->p, 2) : '-',
                'k' => $this->k ? number_format($this->k, 2) : '-',
                'aktif' => $this->aktif ? number_format($this->aktif, 2) : '-',
            ],
            'final' => $this->nilai ? number_format($this->nilai, 2) : '-'
        ];
    }
}
