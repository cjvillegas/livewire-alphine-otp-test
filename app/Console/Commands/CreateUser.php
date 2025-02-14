<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Facades\Validator;

class CreateUser extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-user
        {name : User\'s full name}
        {email : User\'s email address}
        {password}';

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
        $name = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');

        # Validate the email using the request Validator
        $validator = Validator::make(
            ['email' => $email],
            ['email' => 'email|unique:users,email'],
            ['email.unique' => 'The email address is already in use.']
        );

        # the email validation fails
        if ($validator->fails()) {
            $this->error($validator->errors()->first());
            return;
        }

        # Create the user
        User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ]);
    }
}
