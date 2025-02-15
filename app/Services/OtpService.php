<?php

namespace App\Services;

use App\Models\Otp;
use App\Models\User;
use App\Repository\OtpRepository;

/**
 * @author Chaprel John Villegas <vchapreljohn1@gmail.com>
 */
class OtpService
{
    /**
     * @var OtpRepository
     */
    private OtpRepository $repository;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->repository = new OtpRepository();
    }

    /**
     * @param OtpRepository $repository
     * @return void
     */
    public function setRepository(OtpRepository $repository): void
    {
        $this->repository = $repository;
    }

    /**
     * @return OtpRepository
     */
    public function getRepository(): OtpRepository
    {
        return $this->repository;
    }

    /**
     * Find an OTP by its code within a specific time frame for the authenticated user.
     *
     * @param string $code
     *
     * @return Otp|null
     */
    public function findOtpByCode(string $code): ?Otp
    {
        return $this->repository->getOtpInAnHourFrequency(auth()->user(), $code);
    }
}
