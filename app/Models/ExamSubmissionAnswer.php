<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ExamSubmissionAnswer extends Model
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
        'exam_submission_id',
        'exam_question_id',
        'answer',
        'answer_text', // Virtual field for backward compatibility
        'selected_option_id',
        'is_correct',
        'points_earned',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'points_earned' => 'decimal:2',
    ];

    // Accessor for backward compatibility
    public function getAnswerTextAttribute()
    {
        return $this->answer;
    }

    // Mutator for backward compatibility
    public function setAnswerTextAttribute($value)
    {
        $this->attributes['answer'] = $value;
    }

    public function submission()
    {
        return $this->belongsTo(ExamSubmission::class, 'exam_submission_id');
    }

    public function question()
    {
        return $this->belongsTo(ExamQuestion::class, 'exam_question_id');
    }

    public function selectedOption()
    {
        return $this->belongsTo(ExamQuestionOption::class, 'selected_option_id');
    }

    public function isAnswered()
    {
        return !empty($this->answer) || !empty($this->selected_option_id);
    }

    public function checkAnswer()
    {
        if ($this->question->isMultipleChoice()) {
            return $this->selectedOption && $this->selectedOption->is_correct;
        } elseif ($this->question->isTrueFalse()) {
            return $this->answer === $this->question->correctOption->option_text;
        } else {
            // Essay answers need manual grading
            return null;
        }
    }

    public function calculatePoints()
    {
        if ($this->checkAnswer() === true) {
            return $this->question->points;
        } elseif ($this->checkAnswer() === false) {
            return 0;
        }
        // Essay questions return null until graded
        return null;
    }
}
