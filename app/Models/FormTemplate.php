<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class FormTemplate extends Model
{
    use HasFactory, SoftDeletes, Searchable;

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

    public function formAnswers(): HasMany
    {
        return $this->hasMany(FormAnswer::class);
    }

    public function organizationFormTemplates(): HasMany
    {
        return $this->hasMany(OrganizationFormTemplate::class);
    }

    public function shortQuestions(): HasMany
    {
        return $this->hasMany(ShortQuestion::class);
    }

    public function multipleChoiceQuestions(): HasMany
    {
        return $this->hasMany(MultipleChoiceQuestion::class);
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
        ];
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_form_templates');
    }
}
