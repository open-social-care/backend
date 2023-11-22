<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetTokens extends Model
{
    protected $primaryKey = 'email';

    public $incrementing = false;

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * check if the code is expire then delete
     *
     * @return bool
     */
    public function isExpire()
    {
        if ($this->created_at < now()) {
            $this->delete();

            return true;
        }

        return false;
    }
}
