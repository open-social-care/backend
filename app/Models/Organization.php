<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

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

    /**
     * Override Model boot function
     */
    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($organization) {
            $organization->handleDetachUserRoleWhenRemoveOrganization();
            $organization->users()->detach();
        });
    }

    public function handleDetachUserRoleWhenRemoveOrganization(): void
    {
        foreach ($this->users()->get() as $user) {
            $userOrganizationRoleId = $user->organizations()->firstWhere('organization_id', $this->id)->pivot->role_id;

            $userHasRoleInOtherOrganization = $user->organizations()
                ->wherePivot('role_id', $userOrganizationRoleId)
                ->wherePivot('organization_id', '!=', $this->id)
                ->exists();

            if (! $userHasRoleInOtherOrganization) {
                $user->roles()->detach();
            }
        }
    }

    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'model');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_users')->withTimestamps()->withPivot('role_id', 'user_id');
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
