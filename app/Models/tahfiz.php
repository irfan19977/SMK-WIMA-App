<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tahfiz extends Model
{

    protected $table = 'tahfiz';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'student_id',
        'class_id',
        'academic_year',
        'semester',
        'month',
        'month_name',
        'progres_tahfiz',
        'progres_tahsin',
        'target_hafalan',
        'efektif_halaqoh',
        'hadir',
        'keatifan',
        'izin',
        'sakit',
        'alpha',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'month' => 'integer',
        'efektif_halaqoh' => 'integer',
        'hadir' => 'integer',
        'keatifan' => 'integer',
        'izin' => 'integer',
        'sakit' => 'integer',
        'alpha' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the student that owns the tahfiz record.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    /**
     * Get the class that owns the tahfiz record.
     */
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id', 'id');
    }

    /**
     * Get the user who created the record.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get the user who updated the record.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    /**
     * Get the user who deleted the record.
     */
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }

    /**
     * Scope to filter by academic year.
     */
    public function scopeByAcademicYear($query, $academicYear)
    {
        return $query->where('academic_year', $academicYear);
    }

    /**
     * Scope to filter by semester.
     */
    public function scopeBySemester($query, $semester)
    {
        return $query->where('semester', $semester);
    }

    /**
     * Scope to filter by month.
     */
    public function scopeByMonth($query, $month)
    {
        return $query->where('month', $month);
    }

    /**
     * Scope to filter by class.
     */
    public function scopeByClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    /**
     * Scope to filter by student.
     */
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Get formatted month name.
     */
    public function getFormattedMonthAttribute()
    {
        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return $monthNames[$this->month] ?? 'Unknown';
    }

    /**
     * Calculate total attendance (hadir + izin + sakit + alpha).
     */
    public function getTotalAttendanceAttribute()
    {
        return ($this->hadir ?? 0) + ($this->izin ?? 0) + ($this->sakit ?? 0) + ($this->alpha ?? 0);
    }

    /**
     * Calculate attendance percentage.
     */
    public function getAttendancePercentageAttribute()
    {
        $total = $this->getTotalAttendanceAttribute();
        
        if ($total === 0) {
            return 0;
        }

        return round(($this->hadir / $total) * 100, 2);
    }

    /**
     * Check if student has good attendance (>= 80%).
     */
    public function hasGoodAttendanceAttribute()
    {
        return $this->getAttendancePercentageAttribute() >= 80;
    }
}
