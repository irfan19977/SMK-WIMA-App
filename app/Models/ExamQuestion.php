<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ExamQuestion extends Model
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
        'exam_id',
        'question_text',
        'question_type',
        'points',
        'order',
        'explanation',
        'media_type',
        'media_path',
        'media_caption',
    ];

    protected $casts = [
        'points' => 'integer',
        'order' => 'integer',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function options()
    {
        return $this->hasMany(ExamQuestionOption::class);
    }

    public function submissionAnswers()
    {
        return $this->hasMany(ExamSubmissionAnswer::class);
    }

    public function correctOption()
    {
        return $this->hasOne(ExamQuestionOption::class)->where('is_correct', true);
    }

    public function correctOptions()
    {
        return $this->hasMany(ExamQuestionOption::class)->where('is_correct', true);
    }

    public function isComplexMultipleChoice()
    {
        return $this->question_type === 'multiple_choice_complex';
    }

    public function allowsMultipleCorrect()
    {
        return $this->allow_multiple_correct;
    }

    public function calculateScore($selectedOptions)
    {
        if (!$this->isComplexMultipleChoice()) {
            return $this->points;
        }

        $correctOptions = $this->correctOptions()->pluck('id')->toArray();
        $selectedArray = is_array($selectedOptions) ? $selectedOptions : [$selectedOptions];
        
        $totalCorrect = count($correctOptions);
        if ($totalCorrect === 0) {
            return 0;
        }
        
        $correctSelected = count(array_intersect($selectedArray, $correctOptions));
        
        // Simplified scoring: (correct_selected / total_correct) * total_points
        return ($correctSelected / $totalCorrect) * $this->points;
    }

    public function isMultipleChoice()
    {
        return $this->question_type === 'multiple_choice';
    }

    public function isEssay()
    {
        return $this->question_type === 'essay';
    }

    public function isTrueFalse()
    {
        return $this->question_type === 'true_false';
    }

    public function hasMedia()
    {
        return !empty($this->media_type) && !empty($this->media_path);
    }

    public function isImageMedia()
    {
        return $this->media_type === 'image';
    }

    public function isVideoMedia()
    {
        return $this->media_type === 'video';
    }

    public function isAudioMedia()
    {
        return $this->media_type === 'audio';
    }

    public function getMediaUrl()
    {
        if (!$this->hasMedia()) {
            return null;
        }
        
        return asset('storage/' . $this->media_path);
    }
}
