<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentPermission extends Model
{
    use SoftDeletes;
    
    protected $table = 'student_permissions';
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

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeForDate($query, $date)
    {
        return $query->where(function($q) use ($date) {
            $q->where('start_date', '<=', $date)
              ->where(function($q) use ($date) {
                  $q->whereNull('end_date')
                    ->orWhere('end_date', '>=', $date);
              });
        });
    }

    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'sakit' => 'Izin Sakit',
            'agenda' => 'Izin Ada Agenda',
            'pulang_awal' => 'Izin Pulang Lebih Awal',
            default => $this->type,
        };
    }

    public function getTypeBadgeAttribute()
    {
        return match($this->type) {
            'sakit' => 'bg-danger',
            'agenda' => 'bg-warning',
            'pulang_awal' => 'bg-info',
            default => 'bg-secondary',
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => $this->status,
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            default => 'bg-secondary',
        };
    }

    /**
     * Cek apakah izin aktif pada tanggal tertentu
     */
    public function isActiveOnDate($date)
    {
        $checkDate = is_string($date) ? \Carbon\Carbon::parse($date) : $date;
        $startDate = \Carbon\Carbon::parse($this->start_date);
        
        // Cek jika tanggal berada dalam range izin
        if ($checkDate->lt($startDate)) {
            return false;
        }
        
        if ($this->end_date) {
            $endDate = \Carbon\Carbon::parse($this->end_date);
            return $checkDate->lte($endDate);
        }
        
        // Jika tidak ada end_date, izin berlaku untuk start_date saja
        return $checkDate->eq($startDate);
    }

    /**
     * Mendapatkan izin aktif untuk siswa pada tanggal tertentu
     */
    public static function getActivePermission($studentId, $date)
    {
        return self::where('student_id', $studentId)
            ->where('status', 'approved')
            ->where('start_date', '<=', $date)
            ->where(function($query) use ($date) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', $date);
            })
            ->first();
    }

    /**
     * Mendapatkan status checkout berdasarkan jenis izin
     */
    public function getCheckoutStatusAttribute()
    {
        return match($this->type) {
            'sakit' => 'sakit',
            'pulang_awal' => 'izin',
            'agenda' => 'izin',
            default => 'tepat',
        };
    }
}
