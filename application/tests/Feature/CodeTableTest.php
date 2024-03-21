<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CodeTableTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_code_table_index_endpoint_returns_all_code_tables(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('User');

        Passport::actingAs($user);

        $response = $this->getJson('/api/code-table/');

        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data.0', fn (AssertableJson $json) => $json
                    ->has('id')
                    ->has('name')
                    ->has('default')
                    ->etc()
                )
                ->has('data.1', fn (AssertableJson $json) => $json
                    ->has('id')
                    ->has('name')
                    ->has('default')
                    ->etc()
                )
            );

        $user->syncRoles('System Administrator');
        $response = $this->getJson('/api/code-table/');
        $response->assertStatus(200);

        $user->syncRoles('Manager');
        $response = $this->getJson('/api/code-table/');
        $response->assertStatus(200);
    }

    public function test_the_role_index_endpoint_returns_unauthorized_when_unauthenticated(): void
    {
        $response = $this->getJson('/api/code-table/');
        $response->assertStatus(401);
    }

    public function test_the_code_table_show_endpoint_returns_a_single_code_table(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('User');

        Passport::actingAs($user);

        $response = $this->getJson('/api/code-table/1');

        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', fn (AssertableJson $json) => $json
                    ->where('id', 1)
                    ->has('id')
                    ->has('name')
                    ->has('default')
                    ->has('codes.0', fn (AssertableJson $json) => $json
                        ->has('id')
                        ->has('name')
                        ->has('additional_data')
                        ->etc()
                    )
                    ->etc()
                )
            );

        $user->syncRoles('System Administrator');
        $response = $this->getJson('/api/code-table/1');
        $response->assertStatus(200);

        $user->syncRoles('Manager');
        $response = $this->getJson('/api/code-table/1');
        $response->assertStatus(200);
    }

    public function test_the_code_table_show_endpoint_returns_unauthorized_when_unauthenticated(): void
    {
        $response = $this->getJson('/api/code-table/1');
        $response->assertStatus(401);
    }

    public function test_the_code_table_store_endpoint_successfully_stores_a_code_table(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('System Administrator');

        Passport::actingAs($user);

        $name = fake()->name();
        $response = $this->postJson('/api/code-table', [
            'name' => $name,
        ]);

        $response->assertStatus(201);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', fn (AssertableJson $json) => $json
                    ->has('id')
                    ->where('name', $name)
                    ->has('default')
                    ->etc()
                )
            );

        $this->assertDatabaseHas('code_tables', [
            'name' => $name,
        ]);

        $user->syncRoles('Manager');
        $name = fake()->name();
        $response = $this->postJson('/api/code-table', [
            'name' => $name,
        ]);
        $response->assertStatus(403);

        $user->syncRoles('User');
        $name = fake()->name();
        $response = $this->postJson('/api/code-table', [
            'name' => $name,
        ]);
        $response->assertStatus(403);
    }

    public function test_the_code_table_store_endpoint_returns_unauthorized_when_unauthenticated(): void
    {
        $response = $this->postJson('/api/code-table', [
            'name' => fake()->name(),
        ]);
        $response->assertStatus(401);
    }

    public function test_the_code_table_update_endpoint_successfully_updates_a_code_table(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('System Administrator');

        Passport::actingAs($user);

        $newName = fake()->name();
        $response = $this->putJson('/api/code-table/1', [
            'name' => $newName,
            'default' => null,
        ]);

        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', fn (AssertableJson $json) => $json
                    ->where('id', 1)
                    ->where('name', $newName)
                    ->where('default', null)
                    ->etc()
                )
            );

        $this->assertDatabaseHas('code_tables', [
            'id' => 1,
            'name' => $newName,
        ]);

        $user->syncRoles('Manager');
        $newName = fake()->name();
        $response = $this->putJson('/api/code-table/1', [
            'name' => $newName,
            'default' => null,
        ]);
        $response->assertStatus(403);

        $user->syncRoles('User');
        $newName = fake()->name();
        $response = $this->putJson('/api/code-table/1', [
            'name' => $newName,
            'default' => null,
        ]);
        $response->assertStatus(403);
    }

    public function test_the_code_table_update_endpoint_returns_unauthorized_when_unauthenticated(): void
    {
        $response = $this->putJson('/api/code-table/1', [
            'name' => fake()->name(),
        ]);
        $response->assertStatus(401);
    }

    public function test_code_table_audit_logs_are_retrievable(): void
    {
        $admin = User::factory()->create();
        $admin->syncRoles('System Administrator');

        Passport::actingAs($admin);

        $response = $this->getJson('/api/code-table/audits/');
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

        $response = $this->getJson('/api/code-table/1/audit/');
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

    public function test_only_admins_can_access_code_table_audits(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('System Administrator');

        Passport::actingAs($user);

        $response = $this->getJson('/api/code-table/audits/');
        $response->assertStatus(200);

        $response = $this->getJson('/api/code-table/1/audit/');
        $response->assertStatus(200);

        $user->syncRoles('Manager');

        $response = $this->getJson('/api/code-table/audits/');
        $response->assertStatus(403);

        $response = $this->getJson('/api/code-table/1/audit/');
        $response->assertStatus(403);
    }
}
