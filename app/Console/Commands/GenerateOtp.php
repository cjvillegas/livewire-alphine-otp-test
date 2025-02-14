<?php

namespace App\Console\Commands;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class GenerateOtp extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-otp
        {user_id : The user\'s ID from which we are going to attach the OTP}
        {otp? : If you want to add a specific OTP}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $userId = $this->argument('user_id');
        $staticOtp = $this->argument('otp');

        # Grab the user by provided ID
        $user = User::find($userId);

        # No user found by the provided ID
        if (!$user) {
            $this->error('User not found');
            return;
        }

        $otp = $staticOtp ?: rand(100000, 999999); # OTP 6-digit generator

        Otp::create([
            'user_id' => $user->id,
            'code' => $otp,
            'type' => Otp::TYPE_SMS,
            'expires_at' => now()->addMinutes(Otp::SHELF_LIFE)
        ]);
    }
}
