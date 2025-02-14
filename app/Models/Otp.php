<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @autor Chaprel John Villegas <vchapreljohn1@gmail.com>
 */
class Otp extends Model
{
    /**
     * Determines the shelf-life of an OTP. Means how many minutes
     * before it expires
     *
     * @var int - value in minutes
     */
    const SHELF_LIFE = 15;

    /**
     * @var string
     */
    const TYPE_EMAIL = 'email';

    /**
     * @var string
     */
    const TYPE_SMS = 'sms';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'code',
        'type',
        'otp',
        'expires_at',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
