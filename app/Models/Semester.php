<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year',
        'semester_type',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    /**
     * Get the display name of the semester
     */
    public function getDisplayNameAttribute()
    {
        $type = $this->semester_type === 'ganjil' ? 'Ganjil' : 'Genap';
        return "Semester {$type} {$this->academic_year}";
    }

    /**
     * Get the duration in days
     */
    public function getDurationDaysAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Check if semester is currently active based on dates
     */
    public function isCurrentlyActive()
    {
        $now = now();
        return $now->between($this->start_date, $this->end_date);
    }

    /**
     * Scope to get only active semesters
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get semesters by academic year
     */
    public function scopeByAcademicYear($query, $academicYear)
    {
        return $query->where('academic_year', $academicYear);
    }

    /**
     * Scope to get current semester based on dates
     */
    public function scopeCurrent($query)
    {
        $now = now();
        return $query->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
    }

    /**
     * Get current active semester based on current date
     */
    public static function getCurrentActiveSemester()
    {
        $now = now();
        
        // Cari semester yang aktif berdasarkan tanggal
        $currentSemester = self::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->first();
            
        // Jika tidak ada, coba cari yang is_active=true
        if (!$currentSemester) {
            $currentSemester = self::where('is_active', true)->first();
        }
        
        return $currentSemester;
    }

    /**
     * Auto-detect and set active semester based on current date
     */
    public static function autoSetActiveSemester()
    {
        $now = now();
        
        // Cari semester yang seharusnya aktif berdasarkan tanggal
        $currentSemester = self::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->first();
            
        if ($currentSemester && !$currentSemester->is_active) {
            // Nonaktifkan semua semester lain
            self::where('is_active', true)->update(['is_active' => false]);
            
            // Aktifkan semester ini
            $currentSemester->update(['is_active' => true]);
            
            return $currentSemester;
        }
        
        return null;
    }

    /**
     * Get semester info as array
     */
    public function getInfo()
    {
        return [
            'id' => $this->id,
            'display_name' => $this->display_name,
            'academic_year' => $this->academic_year,
            'semester_type' => $this->semester_type,
            'start_date' => $this->start_date->format('d M Y'),
            'end_date' => $this->end_date->format('d M Y'),
            'duration_days' => $this->duration_days,
            'is_active' => $this->is_active,
            'is_currently_active' => $this->isCurrentlyActive(),
        ];
    }
}
