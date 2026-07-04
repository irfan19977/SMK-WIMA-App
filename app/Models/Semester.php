<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Semester extends Model
{
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'academic_year',
        'semester_type',
        'start_date',
        'end_date',
        'is_active',
        'description',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    /**
     * Get the user who created the semester
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who updated the semester
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who deleted the semester
     */
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

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
     * Auto-detect and set active semester based on current date.
     * Ganjil = Juli-Desember, Genap = Januari-Juni.
     * Will auto-create the semester if it doesn't exist.
     */
    public static function autoSetActiveSemester()
    {
        $now = now();
        $month = (int) $now->format('m');
        $year = (int) $now->format('Y');

        // Determine semester type and academic year
        if ($month >= 7) {
            // Juli-Desember → Ganjil, tahun akademik year/(year+1)
            $semesterType = 'ganjil';
            $academicYear = $year . '/' . ($year + 1);
            $startDate = $year . '-07-01';
            $endDate = $year . '-12-31';
        } else {
            // Januari-Juni → Genap, tahun akademik (year-1)/year
            $semesterType = 'genap';
            $academicYear = ($year - 1) . '/' . $year;
            $startDate = $year . '-01-01';
            $endDate = $year . '-06-30';
        }

        // Check if this semester already exists
        $currentSemester = self::where('academic_year', $academicYear)
            ->where('semester_type', $semesterType)
            ->first();

        // Create if not exists
        if (!$currentSemester) {
            $typeLabel = $semesterType === 'ganjil' ? 'Ganjil' : 'Genap';
            $currentSemester = self::create([
                'academic_year' => $academicYear,
                'semester_type' => $semesterType,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'is_active' => true,
                'description' => "Semester {$typeLabel} {$academicYear} (otomatis)",
            ]);

            // Deactivate other semesters
            self::where('id', '!=', $currentSemester->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            return $currentSemester;
        }

        // Activate if not yet active
        if (!$currentSemester->is_active) {
            self::where('is_active', true)->update(['is_active' => false]);
            $currentSemester->update(['is_active' => true]);
            return $currentSemester;
        }

        return $currentSemester;
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
