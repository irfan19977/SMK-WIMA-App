<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classes extends Model
{
    use SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'classes';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_class', 'class_id', 'student_id')
                    ->withTimestamps()
                    ->withPivot('created_by', 'updated_by', 'deleted_by');
    }

    /**
     * Get unique students count (distinct by student_id)
     */
    public function getUniqueStudentsCountAttribute()
    {
        return \DB::table('student_class')
            ->where('class_id', $this->id)
            ->whereNull('deleted_at')
            ->distinct('student_id')
            ->count('student_id');
    }

    /**
     * Scope untuk mendapatkan kelas dengan unique students count
     */
    public function scopeWithUniqueStudentsCount($query)
    {
        return $query->addSelect([
            'unique_students_count' => \DB::table('student_class')
                ->selectRaw('COUNT(DISTINCT student_id)')
                ->whereColumn('class_id', 'classes.id')
                ->whereNull('deleted_at')
        ]);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'class_id');
    }

    /**
     * Parse class name to extract components
     * Returns array with grade, major_short, and academic_year
     */
    public function parseName()
    {
        // Pattern: ROMAWI SHORT_MAJOR ACADEMIC_YEAR
        // Example: X TKJ 2025/2026
        $pattern = '/^(X|XI|XII)\s+([A-Z]+)\s+(\d{4}\/\d{4})$/';
        
        if (preg_match($pattern, $this->name, $matches)) {
            return [
                'grade_roman' => $matches[1],
                'major_short' => $matches[2], 
                'academic_year' => $matches[3]
            ];
        }
        
        return null;
    }

    /**
     * Get grade in Roman numeral
     */
    public function getGradeRomanAttribute()
    {
        $parsed = $this->parseName();
        return $parsed ? $parsed['grade_roman'] : null;
    }

    /**
     * Get major short name
     */
    public function getMajorShortAttribute()
    {
        $parsed = $this->parseName();
        return $parsed ? $parsed['major_short'] : null;
    }
}
