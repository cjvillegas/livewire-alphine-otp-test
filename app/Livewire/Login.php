<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;

/**
 * @author Chaprel John Villegas <vchapreljohn1@gmail.com>
 */
class Login extends Component
{
    #[Validate('required', message: 'Email is required')]
    #[Validate('email', message: 'Email is invalid')]
    public string $email;

    #[Validate('min:6', message: 'Password must be at least 6 characters')]
    public string $password;

    #[Validate('bool', message: 'Invalid value for remember me')]
    public bool $remember = false;

    /**
     * @var string
     */
    public string $error = '';

    public function login()
    {
        # Login rate limiting attempts
        if (Session::get('failed_login_attempts') >= 5) {
            $this->error = "Too many failed login attempts. Please try again later.";
            return;
        }

        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            return redirect()->route('home');
        } else {
            # Increment failed attempts on failure
            Session::increment('failed_login_attempts');

            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
    }

    public function render()
    {
        return view('livewire.login');
    }
}
