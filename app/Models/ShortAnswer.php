<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShortAnswer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'short_question_id',
        'form_answer_id',
        'subject_id',
        'answer',
        'created_at',
        'updated_at',
    ];

    public function shortQuestion(): BelongsTo
    {
        return $this->belongsTo(ShortQuestion::class, 'short_question_id');
    }

    public function formAnswer(): BelongsTo
    {
        return $this->belongsTo(FormAnswer::class, 'form_answer_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
