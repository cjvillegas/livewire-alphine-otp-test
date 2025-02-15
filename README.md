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
 - Make sure that the application is running. In our case we are using docker. So make sure that the app is running in docker.
 - Enter sail shell via `./vendor/bin/sail shell`
 - Run `php artisan test`
 - If you want to only run unit tests you can do `php artisan test --testsuite=Unit --stop-on-failure`
 - If you want to only run feature tests you can do `php artisan test --testsuite=Unit --stop-on-failure`

## Assumptions
 - The first major assumption I had was "this gonna be easy", but viola! It is not
 - 

## Additional Features
 - Backspace will clear current field and will automatically focus the previous field
 - Artisan command to generate user
 - Artisan command to generate OTP
 - Keyboard arrow navigation
 - Authentication logic
 - Login form for users
 - Unauthorized route protection

## Technical Decisions
 - I have to use Docker for ease of installation and distribution
