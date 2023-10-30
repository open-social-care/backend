<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class MultipleChoiceQuestion extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'description',
        'form_template_id',
        'data_type',
        'answer_required',
        'created_at',
        'updated_at',
    ];

    public function formTemplate(): BelongsTo
    {
        return $this->belongsTo(FormTemplate::class, 'form_template_id');
    }

    public function multipleChoiceOptions(): HasMany
    {
        return $this->hasMany(MultipleChoiceOption::class);
    }

    public function multipleChoiceAnswers(): HasMany
    {
        return $this->hasMany(MultipleChoiceAnswer::class);
    }
}
