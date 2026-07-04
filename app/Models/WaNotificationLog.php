<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaNotificationLog extends Model
{
    protected $table = 'wa_notification_logs';

    protected $fillable = [
        'phone',
        'message',
        'type',
        'reference_id',
        'status',
        'response',
    ];

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'attendance' => 'Kehadiran',
            'late' => 'Keterlambatan',
            'alpha' => 'Alpha',
            'permission' => 'Izin Siswa',
            'exam_result' => 'Hasil Ujian',
            'broadcast' => 'Broadcast',
            'custom' => 'Custom',
            default => ucfirst($this->type),
        };
    }

    public function getTypeBadgeAttribute(): string
    {
        return match($this->type) {
            'attendance' => 'bg-success',
            'late' => 'bg-warning',
            'alpha' => 'bg-danger',
            'permission' => 'bg-info',
            'exam_result' => 'bg-primary',
            'broadcast' => 'bg-secondary',
            'custom' => 'bg-dark',
            default => 'bg-secondary',
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'sent' => 'bg-success',
            'failed' => 'bg-danger',
            'error' => 'bg-warning',
            'pending' => 'bg-secondary',
            default => 'bg-secondary',
        };
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
