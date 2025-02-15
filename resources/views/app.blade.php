<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>OTP Livewire Test</title>

        <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
        <style>
            .otp-input {
                width: 5rem;
                height: 5rem;
                text-align: center;
                font-size: 2.5rem;
                border: 2px solid #d1d5db;
                border-radius: 0.375rem;
                background-color: #f9fafb;
                transition: border-color 0.3s, background-color 0.3s;
            }

            .otp-input:focus {
                border-color: #2563eb;
                background-color: #e0f2fe;
                outline: none;
            }

            .otp-input:disabled {
                background-color: #e5e7eb;
                cursor: not-allowed;
            }

            .notification-success {
                color: #10b981;
            }

            .notification-error {
                color: #f44336;
            }

            /* Chrome, Safari, Edge, Opera */
            input::-webkit-outer-spin-button,
            input::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            /* Firefox */
            input[type=number] {
                -moz-appearance: textfield;
            }
        </style>
    </head>

    <body>
        <div class="w-full h-screen">
            <livewire:otp-form />
        </div>
    </body>
</html>
