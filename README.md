# OTP Verification System

## Installation
 - Setup docker in your machine
 - Pull the project in your local machine
 - Navigate to the project's directory after cloning
 - Run `docker compose up -d`
 - After setting up the docker container, enter the sail shell via `./vedor/bin/sail shell`
 - Run `composer install`
 - After your dependency installation, run `php artisan migrate`
 - If you want to create your first user via a command you can run `php artisan app:create-user`
 - Run `php artisan key:generate`

## Testing
[Explain how to run tests]

## Assumptions
[List any assumptions made during development]

## Additional Features
[Describe any extra features implemented]

## Technical Decisions
[Explain key technical decisions made]
