<?php

namespace App\Livewire;

use Illuminate\Http\RedirectResponse;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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

    public function login()
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            return redirect()->route('home');
        } else {
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
