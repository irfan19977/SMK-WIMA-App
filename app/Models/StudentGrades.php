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
        'tugas1' => 'decimal:2',
        'tugas2' => 'decimal:2',
        'tugas3' => 'decimal:2',
        'tugas4' => 'decimal:2',
        'tugas5' => 'decimal:2',
        'tugas6' => 'decimal:2',
        'sikap' => 'decimal:2',
        'uts' => 'decimal:2',
        'uas' => 'decimal:2',
        'nilai' => 'decimal:2',
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
     * Bobot: Tugas1 (15%) + Tugas2 (15%) + Sikap (10%) + UTS (30%) + UAS (30%)
     */
    public function calculateFinalGrade()
    {
        $components = [
            $this->tugas1,
            $this->tugas2,
            $this->sikap,
            $this->uts,
            $this->uas,
        ];

        // Filter out null values
        $validComponents = array_filter($components, function($value) {
            return $value !== null && $value !== '';
        });

        if (empty($validComponents)) {
            return null;
        }

        $tugas1 = floatval($this->tugas1 ?? 0) * 0.15;
        $tugas2 = floatval($this->tugas2 ?? 0) * 0.15;
        $sikap = floatval($this->sikap ?? 0) * 0.10;
        $uts = floatval($this->uts ?? 0) * 0.30;
        $uas = floatval($this->uas ?? 0) * 0.30;

        return round($tugas1 + $tugas2 + $sikap + $uts + $uas, 2);
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
     * Check if grade is complete (has at least one component)
     */
    public function isComplete()
    {
        return !empty(array_filter([
            $this->tugas1, $this->tugas2, $this->sikap, $this->uts, $this->uas
        ]));
    }

    /**
     * Get formatted grades for display
     */
    public function getFormattedGradesAttribute()
    {
        return [
            'tugas1' => $this->tugas1 ? number_format($this->tugas1, 2) : '-',
            'tugas2' => $this->tugas2 ? number_format($this->tugas2, 2) : '-',
            'sikap' => $this->sikap ? number_format($this->sikap, 2) : '-',
            'uts' => $this->uts ? number_format($this->uts, 2) : '-',
            'uas' => $this->uas ? number_format($this->uas, 2) : '-',
            'nilai' => $this->nilai ? number_format($this->nilai, 2) : '-'
        ];
    }
}
