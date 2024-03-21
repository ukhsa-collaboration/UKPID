<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CodeTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_code_index_endpoint_returns_all_codes(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('User');

        Passport::actingAs($user);

        $response = $this->getJson('/api/code/');

        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data.0', fn (AssertableJson $json) => $json
                    ->has('id')
                    ->has('name')
                    ->has('additional_data')
                    ->etc()
                )
                ->has('data.1', fn (AssertableJson $json) => $json
                    ->has('id')
                    ->has('name')
                    ->has('additional_data')
                    ->etc()
                )
            );

        $user->syncRoles('System Administrator');
        $response = $this->getJson('/api/code/');
        $response->assertStatus(200);

        $user->syncRoles('Manager');
        $response = $this->getJson('/api/code/');
        $response->assertStatus(200);
    }

    public function test_the_role_index_endpoint_returns_unauthorized_when_unauthenticated(): void
    {
        $response = $this->getJson('/api/code/');
        $response->assertStatus(401);
    }

    public function test_the_code_show_endpoint_returns_a_single_code(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('User');

        Passport::actingAs($user);

        $response = $this->getJson('/api/code/1');

        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', fn (AssertableJson $json) => $json
                    ->where('id', 1)
                    ->has('id')
                    ->has('name')
                    ->has('additional_data')
                    ->has('code_table', fn (AssertableJson $json) => $json
                        ->has('id')
                        ->has('name')
                        ->has('default')
                        ->etc()
                    )
                    ->etc()
                )
            );

        $user->syncRoles('System Administrator');
        $response = $this->getJson('/api/code/1');
        $response->assertStatus(200);

        $user->syncRoles('Manager');
        $response = $this->getJson('/api/code/1');
        $response->assertStatus(200);
    }

    public function test_the_code_show_endpoint_returns_unauthorized_when_unauthenticated(): void
    {
        $response = $this->getJson('/api/code/1');
        $response->assertStatus(401);
    }

    public function test_the_code_store_endpoint_successfully_stores_a_code(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('System Administrator');

        Passport::actingAs($user);

        $name = fake()->name();
        $response = $this->postJson('/api/code', [
            'name' => $name,
            'code_table_id' => 1,
        ]);

        $response->assertStatus(201);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', fn (AssertableJson $json) => $json
                    ->has('id')
                    ->where('name', $name)
                    ->has('additional_data')
                    ->has('code_table', fn (AssertableJson $json) => $json
                        ->where('id', 1)
                        ->has('name')
                        ->has('default')
                        ->etc()
                    )
                    ->etc()
                )
            );

        $this->assertDatabaseHas('codes', [
            'name' => $name,
            'code_table_id' => 1,
        ]);

        $user->syncRoles('Manager');
        $name = fake()->name();
        $response = $this->postJson('/api/code', [
            'name' => $name,
            'code_table_id' => 1,
        ]);
        $response->assertStatus(403);

        $user->syncRoles('User');
        $name = fake()->name();
        $response = $this->postJson('/api/code', [
            'name' => $name,
            'code_table_id' => 1,
        ]);
        $response->assertStatus(403);
    }

    public function test_the_code_store_endpoint_returns_unauthorized_when_unauthenticated(): void
    {
        $response = $this->postJson('/api/code', [
            'name' => fake()->name(),
        ]);
        $response->assertStatus(401);
    }

    public function test_the_code_update_endpoint_successfully_updates_a_code(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('System Administrator');

        Passport::actingAs($user);

        $newName = fake()->name();
        $response = $this->putJson('/api/code/1', [
            'name' => $newName,
        ]);

        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', fn (AssertableJson $json) => $json
                    ->where('id', 1)
                    ->where('name', $newName)
                    ->has('additional_data')
                    ->has('code_table', fn (AssertableJson $json) => $json
                        ->where('id', 1)
                        ->has('name')
                        ->has('default')
                        ->etc()
                    )
                    ->etc()
                )
            );

        $this->assertDatabaseHas('codes', [
            'id' => 1,
            'name' => $newName,
        ]);

        $user->syncRoles('Manager');
        $newName = fake()->name();
        $response = $this->putJson('/api/code/1', [
            'name' => $newName,
        ]);
        $response->assertStatus(403);

        $user->syncRoles('User');
        $newName = fake()->name();
        $response = $this->putJson('/api/code/1', [
            'name' => $newName,
        ]);
        $response->assertStatus(403);
    }

    public function test_the_code_update_endpoint_returns_unauthorized_when_unauthenticated(): void
    {
        $response = $this->putJson('/api/code/1', [
            'name' => fake()->name(),
        ]);
        $response->assertStatus(401);
    }

    public function test_code_audit_logs_are_retrievable(): void
    {
        $admin = User::factory()->create();
        $admin->syncRoles('System Administrator');

        Passport::actingAs($admin);

        $response = $this->getJson('/api/code/audits/');
        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data.0', fn (AssertableJson $json) => $json
                    ->has('id')
                    ->has('owner')
                    ->has('event')
                    ->has('target_id')
                    ->has('old_values')
                    ->has('new_values')
                    ->has('date')
                )
                ->etc()
            );

        $response = $this->getJson('/api/code/1/audit/');
        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data.0', fn (AssertableJson $json) => $json
                    ->has('id')
                    ->has('owner')
                    ->has('event')
                    ->has('target_id')
                    ->has('old_values')
                    ->has('new_values')
                    ->has('date')
                )
                ->etc()
            );
    }

    public function test_only_admins_can_access_code_audits(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('System Administrator');

        Passport::actingAs($user);

        $response = $this->getJson('/api/code/audits/');
        $response->assertStatus(200);

        $response = $this->getJson('/api/code/1/audit/');
        $response->assertStatus(200);

        $user->syncRoles('Manager');

        $response = $this->getJson('/api/code/audits/');
        $response->assertStatus(403);

        $response = $this->getJson('/api/code/1/audit/');
        $response->assertStatus(403);
    }
}
