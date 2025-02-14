<?php

namespace App\Console\Commands;

use App\Models\Otp;
use Illuminate\Console\Command;

/**
 * @author Chaprel John Villegas <jv@synqup.com>
 */
class ExpiredOtpCleaner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expired-otp-cleaner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans expired OTPs from the database';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        # Delete OTPs where the expires_at column is less than the current time
        Otp::where('expires_at', '<', now())->delete();
    }
}
