<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classes extends Model
{
    use SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'classes';
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

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_class', 'class_id', 'student_id')
                    ->withTimestamps()
                    ->withPivot('created_by', 'updated_by', 'deleted_by');
    }
}
