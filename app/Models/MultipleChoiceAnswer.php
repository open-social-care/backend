<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MultipleChoiceAnswer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'form_answer_id',
        'answer',
        'multiple_choice_question_id',
        'subject_id',
    ];

    public function formAnswer(): BelongsTo
    {
        return $this->belongsTo(FormAnswer::class, 'form_answer_id');
    }

    public function multipleChoiceQuestion(): BelongsTo
    {
        return $this->belongsTo(MultipleChoiceQuestion::class, 'multiple_choice_question_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
