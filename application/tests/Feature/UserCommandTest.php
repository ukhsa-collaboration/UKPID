<?php

namespace Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_user_create_command(): void
    {
        $this->artisan('user:create')
            ->expectsQuestion("What is the user's name?", 'John Smith')
            ->expectsQuestion("What is the user's email address?", 'johnsmith@example.com')
            ->expectsQuestion('Where is the user based?', 'NEWCASTLE')
            ->expectsQuestion("What is the user's role?", 'Manager')
            ->expectsQuestion('Enter a password for the user. Leave blank to generate a random password.', '')
            ->expectsOutputToContain('User created!')
            ->expectsOutputToContain('Name: John Smith')
            ->expectsOutputToContain('Email: johnsmith@example.com')
            ->expectsOutputToContain('Location: NEWCASTLE')
            ->expectsOutputToContain('Role: Manager')
            ->expectsOutputToContain('Password: ')
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'name' => 'John Smith',
            'email' => 'johnsmith@example.com',
        ]);
    }
}
