<?php

namespace App\Console\Commands;

use App\Constants\Locations;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Hello!');
        $name = text(
            label: "What is the user's name?",
            required: true
        );

        $email = text(
            label: "What is the user's email address?",
            required: true,
            validate: fn (string $value) => match (true) {
                strlen($value) && strlen($value) < 8 => 'Must be a valid email address.',
                default => null
            },
        );

        $location = select(
            label: 'Where is the user based?',
            options: array_flip(Locations::all()),
        );

        $password = text(
            label: 'Enter a password for the user. Leave blank to generate a random password.',
            placeholder: 'Minimum 8 characters...',
            validate: function (string $value) {
                $validator = Validator::make(['email' => $value], ['email' => ['email']]);

                return $validator->fails();
            }
        );

        if (empty($password)) {
            $password = Str::password();
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'location' => $location,
            'password' => $password,
        ]);

        $this->info('User created!');
        $this->newLine();
        $this->line('ID: '.$user->id);
        $this->line('Name: '.$user->name);
        $this->line('Email: '.$user->email);
        $this->line('Location: '.$user->location['key']);
        $this->line('Password: '.$password);
    }
}
