<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MultipleChoiceOption extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'multiple_choice_question_id',
        'description',
        'created_at',
        'updated_at',
    ];

    public function multipleChoiceQuestion(): BelongsTo
    {
        return $this->belongsTo(MultipleChoiceQuestion::class, 'multiple_choice_question_id');
    }
}
