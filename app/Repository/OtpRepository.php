<?php

namespace App\Repository;

use App\Models\Otp;
use App\Models\User;

/**
 * @author Chaprel John Villegas <vchapreljohn1@gmail.com>
 */
class OtpRepository
{
    /**
     * Retrieves a valid OTP for the given user and code.
     * The OTP must not be expired { @see Otp::SHELF_LIFE } or used to be considered valid.
     *
     * @param User $user
     * @param string $code
     *
     * @return Otp|null Returns the valid OTP instance if found, or null if none.
     */
    public function getValidOtp(User $user, string $code): ?Otp
    {
        return Otp::where('user_id', $user->id)
            ->where('code', $code)
            ->notExpired()
            ->notUsed()
            ->first();
    }

    /**
     * Grab an OTP regardless if it is used or expired that was created
     * in past hour.
     *
     * @param User $user
     * @param string $code
     * @param int $frequency
     *
     * @return Otp|null
     */
    public function getOtpInAnHourFrequency(User $user, string $code, int $frequency = 60): ?Otp
    {
        return Otp::where('user_id', $user->id)
            ->where('code', $code)
            ->where('created_at', '>=', now()->subMinutes($frequency))
            ->first();
    }
}
