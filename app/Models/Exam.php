<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Exam extends Model
{
    use SoftDeletes;

    protected $keyType = 'uuid';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid();
            }
        });
    }

    protected $fillable = [
        'title',
        'description',
        'subject_id',
        'class_id',
        'teacher_id',
        'duration_minutes',
        'total_questions',
        'passing_score',
        'start_time',
        'end_time',
        'status',
        'is_active',
        'show_results',
        'show_status',
        'show_review',
        'randomize_questions',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'passing_score' => 'decimal:2',
        'is_active' => 'boolean',
        'show_results' => 'boolean',
        'show_status' => 'boolean',
        'show_review' => 'boolean',
        'randomize_questions' => 'boolean',
    ];

    // Relationships
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(\App\Models\Teacher::class, 'teacher_id');
    }

    public function questions()
    {
        return $this->hasMany(ExamQuestion::class);
    }

    public function submissions()
    {
        return $this->hasMany(ExamSubmission::class);
    }

    public function publishedSubmissions()
    {
        return $this->submissions()->where('status', 'submitted');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Methods
    public function isPublished()
    {
        return $this->status === 'published';
    }

    public function isOngoing()
    {
        return $this->status === 'ongoing';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function canBeEdited()
    {
        return in_array($this->status, ['draft', 'published']);
    }

    public function canBeDeleted()
    {
        return in_array($this->status, ['draft', 'published']);
    }

    public function canBePublished()
    {
        return $this->status === 'draft' && $this->questions()->count() > 0;
    }

    public function updateTotalQuestions()
    {
        $this->update(['total_questions' => $this->questions()->count()]);
    }

    public function getAverageScore()
    {
        return $this->submissions()->avg('score');
    }

    public function getPassCount()
    {
        return $this->submissions()->where('score', '>=', $this->passing_score)->count();
    }

    public function getFailCount()
    {
        return $this->submissions()->where('score', '<', $this->passing_score)->count();
    }
}
