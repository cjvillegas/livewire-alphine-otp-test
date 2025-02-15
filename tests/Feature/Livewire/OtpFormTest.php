<?php

namespace Tests\Feature\Livewire;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Volt\Volt;
use Tests\TestCase;
use Livewire\Livewire;

/**
 * @author Chaprel John Villegas <vchapreljohn1@gmail.com>
 */
class OtpFormTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_test_can_render(): void
    {
        $component = Volt::test('otp-form')
            ->set('user', $this->user);

        $component->assertSee('');
    }

    public function test_shows_validation_error_if_otp_is_empty()
    {
        # Assert that validation for 'required' is triggered
        Livewire::test('otp-form')
        ->set('otp', '')
            ->call('verifyOtp')
            ->assertHasErrors(['otp' => 'required']);
    }

    public function test_shows_validation_error_if_otp_is_not_numeric()
    {
        # Test with non-numeric OTP
        Livewire::test('otp-form')
            ->set('otp', 'abcdef')
            ->call('verifyOtp')
            ->assertHasErrors(['otp' => 'numeric']);
    }

    public function test_shows_validation_error_if_otp_is_not_6_digits()
    {
        # Test with OTP not exactly 6 digits
        Livewire::test('otp-form')
            ->set('otp', '12345')
            ->call('verifyOtp')
            ->assertHasErrors(['otp' => 'digits']);
    }

    public function test_verifies_otp_successfully_when_otp_is_valid()
    {
        $otpCode = rand(100000, 999999);

        # Create a valid OTP
        Otp::create([
            'user_id' => $this->user->id,
            'code' => $otpCode,
            'expires_at' => now()->addMinutes(Otp::SHELF_LIFE)
        ]);

        # Test passing in the valid OTP
        Livewire::test('otp-form')
            ->set('otp', $otpCode)
            ->call('verifyOtp')
            ->assertSee(['success' => true]); # The successful response
    }

    public function test_returns_error_if_otp_is_expired()
    {
        $otpCode = rand(100000, 999999);

        # Create an expired OTP
        $temp = Otp::create([
            'user_id' => $this->user->id,
            'code' => $otpCode,
            'expires_at' => now()->subMinutes(20), # expired OTP
        ]);

        # Test with expired OTP
        Livewire::test('otp-form')
            ->set('otp', $otpCode)
            ->call('verifyOtp')
            ->assertSee(['expired' => true, 'success' => false]);
    }

    public function test_returns_error_if_otp_has_been_used()
    {
        $otpCode = rand(100000, 999999);

        # Create a used OTP
        Otp::create([
            'user_id' => $this->user->id,
            'code' => $otpCode,
            'expires_at' => now()->addMinutes(Otp::SHELF_LIFE), # not expired
            'verified_at' => now()
        ]);

        # Test with used OTP
        Livewire::test('otp-form')
            ->set('otp', $otpCode)
            ->call('verifyOtp')
            ->assertSee(['used' => true, 'success' => false]);
    }

    public function test_returns_error_if_otp_is_invalid()
    {
        $otpCode = rand(100000, 999999);

        # Create an OTP (valid but with a different code)
        Otp::create([
            'user_id' => $this->user->id,
            'code' => $otpCode,
            'expires_at' => now()->addMinutes(Otp::SHELF_LIFE)
        ]);

        # Test with invalid OTP (does not match the created one)
        Livewire::test('otp-form')
            ->set('otp', '123456')
            ->call('verifyOtp')
            ->assertSee(['no_otp' => true]);
    }

    public function test_returns_success_when_otp_is_verified_successfully()
    {
        $otpCode = rand(100000, 999999);

        # Create a valid OTP for verification
        $validOtp = Otp::create([
            'user_id' => $this->user->id,
            'code' => $otpCode,
            'expires_at' => now()->addMinutes(Otp::SHELF_LIFE),
        ]);

        # Test with valid OTP
        Livewire::test('otp-form')
            ->set('otp', $otpCode)
            ->call('verifyOtp')
            ->assertSee(['success' => true]);

        # Ensure OTP is marked as verified
        $validOtp->refresh();
        $this->assertNotNull($validOtp->verified_at);
    }

    public function test_handles_edge_case_when_otp_is_empty_and_no_validation_errors()
    {
        # Test without setting any OTP and ensure it's empty
        Livewire::test('otp-form')
            ->set('otp', '')
            ->call('verifyOtp')
            ->assertSee(['message' => 'No OTP was sent to verify']);
    }
}
