<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use HasFactory, SoftDeletes;

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
        'phone',
        'created_at',
        'updated_at',
    ];

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function organizationUsers(): HasMany
    {
        return $this->hasMany(OrganizationUser::class);
    }

    public function organizationFormTemplates(): HasMany
    {
        return $this->hasMany(OrganizationFormTemplate::class);
    }
}
