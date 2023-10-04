<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormTemplate extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'has_file_uploads',
        'created_at',
        'updated_at',
    ];

    public function formAnswers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FormAnswer::class);
    }

    public function organizationFormTemplates(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrganizationFormTemplate::class);
    }

    public function shortQuestions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ShortQuestion::class);
    }

    public function multipleChoiceQuestions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MultipleChoiceQuestion::class);
    }
}
