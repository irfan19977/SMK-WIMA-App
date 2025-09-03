<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsramaGrades extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;
    protected $table = 'asrama_grades';
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
}
