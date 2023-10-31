<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\AccountCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_user_can_view_the_login_page(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    public function test_the_user_cannot_view_the_login_page_when_authenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get(route('login'));

        $response->assertRedirect('/');
    }

    public function test_the_user_can_log_in_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('testpassword'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'testpassword',
        ]);

        $response->assertRedirect('/');
        $response->assertValid(['email', 'password']);
        $this->assertAuthenticatedAs($user);
    }

    public function test_the_log_in_form_returns_an_error_when_using_incorrect_credentials(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('testpassword'),
        ]);

        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'notthepassword',
        ]);

        $response->assertInvalid(['email']);

        $response = $this->post(route('login'), [
            'email' => 'notauser@example.com',
            'password' => 'notthepassword',
        ]);

        $response->assertInvalid(['email']);
    }

    public function test_the_forgot_password_page_returns_a_successful_response(): void
    {
        $response = $this->get(route('password.email'));

        $response->assertStatus(200);
    }

    public function test_the_forgot_password_page_rejects_invalid_email_addresses(): void
    {
        $response = $this->post(route('password.email'), [
            'email' => 'not_a_valid_email',
        ]);

        $response->assertInvalid(['email']);
    }

    public function test_the_forgot_password_page_accepts_a_valid_email_addresses(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $response = $this->post(route('password.email'), [
            'email' => 'test@example.com',
        ]);

        $response->assertValid(['email']);
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        // Test that we get a valid response for a user that doesn't exist (user enumeration protection)
        $response = $this->post(route('password.email'), [
            'email' => 'notauser@example.com',
        ]);

        $response->assertValid(['email']);
    }

    public function test_the_user_can_reset_their_password(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('oldpassword'),
        ]);

        // Create a token
        $token = app('auth.password.broker')->createToken($user);

        // Test that the form loads
        $response = $this->get(route('password.reset', ['token' => $token]));
        $response->assertOk();

        // Now change the password
        $response = $this->post(route('password.store'), [
            'token' => $token,
            'email' => 'test@example.com',
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertRedirectToRoute('login');
        $response->assertValid(['email', 'password', 'token']);

        // Now try to log in
        $response = $this->post(route('login'), [
            'email' => 'test@example.com',
            'password' => 'newpassword',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_new_user_is_forced_to_change_their_temporary_password(): void
    {
        $admin = User::factory()->create();
        $admin->syncRoles('Administrator');

        Passport::actingAs($admin);

        Notification::fake();

        // Create the user
        $response = $this->postJson('/api/user/', [
            'name' => fake()->name(),
            'email' => fake()->unique()->email(),
            'role' => Role::firstWhere('name', 'User')->id,
        ]);

        $response->assertStatus(201);

        $newUser = User::firstWhere('email', $response->json()['user']['email']);
        Notification::assertSentTo([$newUser], AccountCreated::class);

        // Sign in as them
        $response = $this->post(route('login'), [
            'email' => $response->json()['user']['email'],
            'password' => $response->json()['password'],
        ]);

        // Follow the redirects to end up at the change password page
        $this->followRedirects($response)->assertSee('Change Password');
    }
}
