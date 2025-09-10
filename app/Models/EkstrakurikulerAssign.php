<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EkstrakurikulerAssign extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'assign_ekstrakurikuler';
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

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Get the teacher that belongs to this assignment.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }

    /**
     * Get the asrama that belongs to this assignment.
     */
    public function ekstrakurikuler()
    {
        return $this->belongsTo(Ekstrakurikuler::class, 'ekstrakurikuler_id');
    }
}
