<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssignAsrama extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'assign_asrama';
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
    public function asrama()
    {
        return $this->belongsTo(Asrama::class, 'asrama_id');
    }
}
