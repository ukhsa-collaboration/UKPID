<?php

namespace Database\Factories;

use App\Enums\Locations;
use App\Events\UserCreated;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'location' => array_rand(array_flip(Locations::names())),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole('User');
        });
    }

    /**
     * Indicate that the user is suspended.
     */
    public function withCreatedEvent(string $password = 'password'): Factory
    {
        return $this->state(fn (array $attributes) => [])
            ->afterCreating(function (User $user) use ($password) {
                UserCreated::dispatch($user, $password);
            });
    }
}
