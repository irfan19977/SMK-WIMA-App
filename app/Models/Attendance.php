<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use SoftDeletes;

    protected $table = 'attendance';
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
        // Relasi ke Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    // Relasi ke Class
    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }
    

}
