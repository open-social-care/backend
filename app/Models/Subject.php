<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Subject extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'birth_date',
        'nationality',
        'phone',
        'father_name',
        'mother_name',
        'cpf',
        'rg',
        'skin_color',
        'relative_relation_type',
        'relative_name',
        'relative_phone',
        'created_at',
        'updated_at',
        'organization_id',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'datetime',
    ];

    public function formAnswers(): HasMany
    {
        return $this->hasMany(FormAnswer::class);
    }

    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'model');
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
