<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
                $model->id = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

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

}
