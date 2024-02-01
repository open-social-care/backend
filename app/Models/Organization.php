<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'document_type',
        'document',
        'created_at',
        'updated_at',
        'subject_ref',
    ];

    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'model');
    }

    public function organizationUsers(): HasMany
    {
        return $this->hasMany(OrganizationUser::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_users')->withTimestamps();
    }

    public function organizationFormTemplates(): HasMany
    {
        return $this->hasMany(OrganizationFormTemplate::class);
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'document' => $this->document,
        ];
    }

    public function setSubjectRefAttribute($value): void
    {
        $this->attributes['subject_ref'] = is_null($value) ? __('common.subject_ref') : $value;
    }
}
