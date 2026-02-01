<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ScreenShare extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'room_code',
        'teacher_id',
        'title',
        'status',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'id');
    }

    public function participants()
    {
        return $this->hasMany(ScreenShareParticipant::class);
    }

    public static function generateRoomCode()
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8));
        } while (self::where('room_code', $code)->exists());

        return $code;
    }
}
