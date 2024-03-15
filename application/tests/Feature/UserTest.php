<?php

namespace Feature;

use App\Enums\Locations;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Passport\Passport;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_user_index_route_returns_users(): void
    {
        $admin = User::factory()->create();
        $admin->syncRoles('System Administrator');

        Passport::actingAs($admin);

        $response = $this->getJson('/api/user/');

        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', 1)
                ->has('data.0', fn (AssertableJson $json) => $json
                    ->has('id')
                    ->has('name')
                    ->has('email')
                    ->has('location')
                    ->has('role')
                    ->has('created_at')
                    ->has('updated_at')
                    ->etc()
                )
            );
    }

    public function test_the_user_index_route_returns_unauthorized_when_unauthenticated(): void
    {
        $response = $this->getJson('/api/user/');
        $response->assertStatus(401);
    }

    public function test_the_user_show_route_returns_a_user(): void
    {
        $users = User::factory()->count(2)->create();

        $admin = $users->first();
        $admin->syncRoles('System Administrator');

        Passport::actingAs($admin);

        $response = $this->getJson('/api/user/'.$users[1]->id);

        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', fn (AssertableJson $json) => $json
                    ->has('id')
                    ->has('name')
                    ->has('email')
                    ->has('location')
                    ->has('role')
                    ->has('created_at')
                    ->has('updated_at')
                    ->etc()
                )
            );
    }

    public function test_the_user_show_route_returns_unauthorized_when_unauthenticated(): void
    {
        $response = $this->getJson('/api/user/');
        $response->assertStatus(401);
    }

    public function test_the_user_me_route_returns_a_user(): void
    {
        $users = User::factory()->count(2)->create();

        $admin = $users->last();
        $admin->syncRoles('System Administrator');

        Passport::actingAs($admin);

        $response = $this->getJson('/api/user/me');

        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', fn (AssertableJson $json) => $json
                    ->has('id')
                    ->where('name', $admin->name)
                    ->where('email', $admin->email)
                    ->has('location')
                    ->has('role')
                    ->has('created_at')
                    ->has('updated_at')
                    ->etc()
                )
            );
    }

    public function test_the_user_me_route_returns_unauthorized_when_unauthenticated(): void
    {
        $response = $this->getJson('/api/user/me');
        $response->assertStatus(401);
    }

    public function test_the_user_create_route_successfully_creates_a_user(): void
    {
        $admin = User::factory()->create([
            'location' => Locations::CARDIFF->name,
        ]);
        $admin->syncRoles('System Administrator');

        Passport::actingAs($admin);

        $userData = [
            'name' => fake()->name(),
            'email' => fake()->unique()->email(),
            'location' => Locations::CARDIFF->name,
            'role' => Role::firstWhere('name', 'Manager')->id,
        ];
        $response = $this->postJson('/api/user/', $userData);

        $response->assertStatus(201);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('user', fn (AssertableJson $json) => $json
                    ->has('id')
                    ->has('name')
                    ->has('email')
                    ->has('location')
                    ->has('role')
                    ->has('created_at')
                    ->has('updated_at')
                    ->etc()
                )
                ->has('password')
            );

        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
            'location' => $userData['location'],
        ]);

        $response = $this->postJson('/api/user/', [
            'name' => fake()->name(),
            'email' => fake()->unique()->email(),
            'role' => Role::firstWhere('name', 'System Administrator')->id,
        ]);

        $response->assertStatus(201);
    }

    public function test_users_without_the_create_users_permission_cannot_create_users(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('User');

        Passport::actingAs($user);

        $response = $this->postJson('/api/user/', [
            'name' => fake()->name(),
            'email' => fake()->unique()->email(),
            'role' => Role::firstWhere('name', 'User')->id,
        ]);

        $response->assertStatus(403);
    }

    public function test_users_cannot_create_a_new_user_with_a_role_above_theirs(): void
    {
        $manager = User::factory()->create();
        $manager->syncRoles('Manager');

        Passport::actingAs($manager);

        $response = $this->postJson('/api/user/', [
            'name' => fake()->name(),
            'email' => fake()->unique()->email(),
            'role' => Role::firstWhere('name', 'System Administrator')->id,
        ]);

        $response->assertInvalid(['role']);

        $response = $this->postJson('/api/user/', [
            'name' => fake()->name(),
            'email' => fake()->unique()->email(),
            'role' => Role::firstWhere('name', 'Manager')->id,
        ]);

        $response->assertStatus(201);

        $response = $this->postJson('/api/user/', [
            'name' => fake()->name(),
            'email' => fake()->unique()->email(),
            'role' => Role::firstWhere('name', 'User')->id,
        ]);

        $response->assertStatus(201);
    }

    public function test_only_admins_can_create_users_outside_their_location(): void
    {
        $admin = User::factory()->create([
            'location' => Locations::CARDIFF->name,
        ]);
        $admin->syncRoles('System Administrator');

        Passport::actingAs($admin);

        $response = $this->postJson('/api/user/', [
            'name' => fake()->name(),
            'email' => fake()->unique()->email(),
            'location' => Locations::BIRMINGHAM->name,
            'role' => Role::firstWhere('name', 'User')->id,
        ]);

        $response->assertStatus(201);

        $manager = User::factory()->create([
            'location' => Locations::CARDIFF->name,
        ]);
        $manager->syncRoles('Manager');

        Passport::actingAs($manager);

        $response = $this->postJson('/api/user/', [
            'name' => fake()->name(),
            'email' => fake()->unique()->email(),
            'location' => Locations::BIRMINGHAM->name,
            'role' => Role::firstWhere('name', 'User')->id,
        ]);

        $response->assertStatus(403);
    }

    public function test_the_user_is_created_at_the_same_location_as_the_creator(): void
    {
        $manager = User::factory()->create([
            'location' => Locations::CARDIFF->name,
        ]);
        $manager->syncRoles('Manager');

        Passport::actingAs($manager);

        $userData = [
            'name' => fake()->name(),
            'email' => fake()->unique()->email(),
            'role' => Role::firstWhere('name', 'User')->id,
        ];
        $response = $this->postJson('/api/user/', $userData);

        $response->assertStatus(201);
    }

    public function test_user_audit_logs_are_retrievable(): void
    {
        $admin = User::factory()->create([
            'location' => Locations::CARDIFF->name,
        ]);
        $admin->syncRoles('System Administrator');

        Passport::actingAs($admin);

        // Create new user
        $userData = [
            'name' => fake()->name(),
            'email' => fake()->unique()->email(),
            'location' => Locations::CARDIFF->name,
            'role' => Role::firstWhere('name', 'Manager')->id,
        ];
        $response = $this->postJson('/api/user/', $userData);
        $response->assertStatus(201);
        $manager = User::findOrFail($response->json()['user']['id']);

        $response = $this->getJson('/api/user/audits/');
        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data.1', fn (AssertableJson $json) => $json
                    ->has('id')
                    ->has('owner')
                    ->where('event', 'created')
                    ->where('target_id', $manager->id)
                    ->has('old_values')
                    ->has('new_values')
                    ->has('date')
                )
                ->etc()
            );

        $response = $this->getJson('/api/user/'.$manager->id.'/audit/');
        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data.0', fn (AssertableJson $json) => $json
                    ->has('id')
                    ->has('owner')
                    ->where('event', 'created')
                    ->where('target_id', $manager->id)
                    ->has('old_values')
                    ->has('new_values')
                    ->has('date')
                )
                ->etc()
            );
    }

    public function test_only_admins_can_access_user_audits(): void
    {
        $admin = User::factory()->create([
            'location' => Locations::CARDIFF->name,
        ]);
        $admin->syncRoles('System Administrator');

        $manager = User::factory()->create([
            'location' => Locations::CARDIFF->name,
        ]);
        $manager->syncRoles('Manager');

        Passport::actingAs($admin);

        $response = $this->getJson('/api/user/audits/');
        $response->assertStatus(200);

        $response = $this->getJson('/api/user/'.$manager->id.'/audit/');
        $response->assertStatus(200);

        Passport::actingAs($manager);

        $response = $this->getJson('/api/user/audits/');
        $response->assertStatus(403);

        $response = $this->getJson('/api/user/'.$manager->id.'/audit/');
        $response->assertStatus(403);
    }
}
