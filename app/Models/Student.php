<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Student extends Model
{
    use SoftDeletes;

    
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'student';
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = Str::uuid();
            }
        });
    }

    protected $casts = [
        'face_encoding' => 'array',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parents()
    {
        return $this->belongsTo(Parent::class, 'student_id');
    }

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'student_class', 'student_id', 'class_id')
                    ->withTimestamps()
                    ->withPivot('created_by', 'updated_by', 'deleted_by');
    }

    public function assignAsrama()
    {
        return $this->hasOne(AssignAsrama::class, 'student_id');
    }

    /**
     * Get the asrama through assignment.
     */
    public function asrama()
    {
        return $this->hasOneThrough(Asrama::class, AssignAsrama::class, 'student_id', 'id', 'id', 'asrama_id');
    } 
    
    
    public function ekstrakurikulerAssign()
    {
        return $this->hasMany(EkstrakurikulerAssign::class, 'student_id');
    }

    public function lessonAttendances()
    {
        return $this->hasMany(Lesson::class, 'student_id');
    }

    /**
     * Check if student has face registered
     */
    public function hasFaceRegistered()
    {
        return !is_null($this->face_encoding) && !is_null($this->face_photo);
    }

    /**
     * Get face photo URL
     */
    public function getFacePhotoUrlAttribute()
    {
        return $this->face_photo ? asset('storage/' . $this->face_photo) : null;
    }

    /**
     * Scope for students without face registration
     */
    public function scopeWithoutFaceRegistration($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('face_encoding')
              ->orWhereNull('face_photo');
        });
    }

    public function getRouteKeyName()
    {
        return 'id';
    }
}
