<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostAnswerNote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'form_answer_id',
        'note',
        'created_at',
        'updated_at',
    ];

    public function formAnswer(): BelongsTo
    {
        return $this->belongsTo(FormAnswer::class, 'form_answer_id');
    }
}
