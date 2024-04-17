<?php

namespace App\Models;

use App\Enums\RolesEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Converts the model to an array that will be indexed for the search.
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }

    /**
     * Override Model boot function
     */
    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($user) {
            $user->handleDetachUserRoleWhenRemoveOrganization();
            $user->organizations()->detach();
        });
    }

    public function handleDetachUserRoleWhenRemoveOrganization(): void
    {
        foreach ($this->organizations()->get() as $organization) {
            $organization->handleDetachUserRoleWhenRemoveOrganization();
        }
    }

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_users')->withTimestamps()->withPivot('role_id', 'organization_id');
    }

    public function roleUsers(): HasMany
    {
        return $this->hasMany(RoleUser::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_users')->withTimestamps();
    }

    public function formAnswers(): HasMany
    {
        return $this->hasMany(FormAnswer::class);
    }

    public function hasRoleByName(string $roleName): bool
    {
        $roles = Role::query()->where('name', $roleName)->pluck('id');
        $userHasRole = $this->roleUsers()->whereIn('role_id', $roles);

        return $userHasRole->get()->isNotEmpty();
    }

    public function hasRoleById(int $roleId): bool
    {
        return $this->roles()->where('role_id', $roleId)->exists();
    }

    public function hasOrganization(int $organizationId): bool
    {
        return $this->organizations()->where('organization_id', $organizationId)->exists();
    }

    public function isAdminSystem(): bool
    {
        $roleAdmin = Role::query()->firstWhere('name', RolesEnum::ADMIN->value);

        if ($roleAdmin) {
            $hasRoleAdmin = $this->hasRoleById($roleAdmin->id);
            $notAdminInOrganizations = ! $this->organizations()->wherePivot('role_id', $roleAdmin->id)->exists();

            return $hasRoleAdmin && $notAdminInOrganizations;
        }

        return false;
    }
}
