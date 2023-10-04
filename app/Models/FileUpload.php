<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'form_answer_id',
        'filepath',
        'mimetype',
        'title',
        'created_at',
        'updated_at',
    ];

    public function formAnswer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FormAnswer::class, 'form_answer_id');
    }
}