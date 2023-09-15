<?php

namespace App\Console\Commands;

use App\Constants\Locations;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;
use Spatie\Permission\Models\Role;

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
        $name = text(
            label: "What is the user's name?",
            required: true
        );

        $email = text(
            label: "What is the user's email address?",
            required: true,
            validate: function (string $value) {
                $validator = Validator::make(['email' => $value], ['email' => ['email']]);

                return $validator->fails() ? 'Must be a valid email address.' : null;
            }
        );

        $location = select(
            label: 'Where is the user based?',
            options: array_flip(Locations::all()),
        );

        $role = select(
            label: 'What is the role of the user?',
            options: Role::all()->pluck('name', 'id'),
        );

        $password = text(
            label: 'Enter a password for the user. Leave blank to generate a random password.',
            placeholder: 'Minimum 8 characters',
            validate: function (string $value) {
                $validator = Validator::make(['password' => $value], ['password' => [Password::defaults()]]);

                return $validator->fails() ? implode(' | ', $validator->errors()->get('password')) : null;
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

        $user->assignRole($role);

        $this->info('User created!');
        $this->newLine();
        $this->line('ID: '.$user->id);
        $this->line('Name: '.$user->name);
        $this->line('Email: '.$user->email);
        $this->line('Location: '.$user->location['key']);
        $this->line('Role: '.$user->getRoleNames()->implode(', '));
        $this->line('Password: '.$password);
    }
}
