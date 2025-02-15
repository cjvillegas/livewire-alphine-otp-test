<?php

namespace Tests\Unit\Services;

use App\Models\Otp;
use App\Models\User;
use App\Repository\OtpRepository;
use App\Services\OtpService;
use Tests\Unit\UnitBaseTest;
use Mockery;

/**
 * @author Chaprel John Villegas <vchapreljohn1@gmail.com>
 */
class OtpServiceTest extends UnitBaseTest
{
    /** @var OtpService */
    protected OtpService $otpService;

    /** @var Mockery\MockInterface */
    protected Mockery\MockInterface $otpRepositoryMock;

    /** @var User */
    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        # Create a mock for the OtpRepository
        $this->otpRepositoryMock = Mockery::mock(OtpRepository::class);

        # Create the OtpService instance, injecting the mock repository
        $this->otpService = new OtpService();
        $this->otpService->setRepository($this->otpRepositoryMock);

        # Create a test user
        $this->user = User::factory()->create();
    }

    public function test_calls_repository_to_find_otp_by_code()
    {
        $code = rand(100000, 999999);

        # Mock the repository method to return a mocked OTP
        $mockedOtp = Mockery::mock(Otp::class);
        $this->otpRepositoryMock
            ->shouldReceive('getOtpInAnHourFrequency')
            ->with($this->user, $code)
            ->once()
            ->andReturn($mockedOtp);

        # Call the service method
        $result = $this->otpService->findOtpByCode($this->user, $code);

        # Assert that the result is the mocked OTP object
        $this->assertSame($mockedOtp, $result);
    }

    public function test_returns_null_when_no_otp_found()
    {
        $code = rand(100000, 999999);

        # Mock the repository method to return null (no OTP found)
        $this->otpRepositoryMock
            ->shouldReceive('getOtpInAnHourFrequency')
            ->with($this->user, $code)
            ->once()
            ->andReturn(null);

        # Call the service method
        $result = $this->otpService->findOtpByCode($this->user, $code);

        # Assert that the result is null
        $this->assertNull($result);
    }
}
