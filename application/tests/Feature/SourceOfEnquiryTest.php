<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Passport\Passport;
use Tests\TestCase;

class SourceOfEnquiryTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_source_of_enquiry_index_endpoint_returns_all_sources_of_enquiry(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('User');

        Passport::actingAs($user);

        $response = $this->getJson('/api/source-of-enquiry/');

        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data.0', fn (AssertableJson $json) => $json
                    ->has('id')
                    ->has('name')
                    ->has('hidden')
                    ->etc()
                )
                ->has('data.1', fn (AssertableJson $json) => $json
                    ->has('id')
                    ->has('name')
                    ->has('hidden')
                    ->etc()
                )
            );

        $user->syncRoles('System Administrator');
        $response = $this->getJson('/api/source-of-enquiry/');
        $response->assertStatus(200);

        $user->syncRoles('Manager');
        $response = $this->getJson('/api/source-of-enquiry/');
        $response->assertStatus(200);
    }

    public function test_the_role_index_endpoint_returns_unauthorized_when_unauthenticated(): void
    {
        $response = $this->getJson('/api/source-of-enquiry/');
        $response->assertStatus(401);
    }

    public function test_the_source_of_enquiry_show_endpoint_returns_a_single_source_of_enquiry(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('User');

        Passport::actingAs($user);

        $response = $this->getJson('/api/source-of-enquiry/1');

        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', fn (AssertableJson $json) => $json
                    ->where('id', 1)
                    ->has('name')
                    ->has('hidden')
                    ->etc()
                )
            );

        $user->syncRoles('System Administrator');
        $response = $this->getJson('/api/source-of-enquiry/1');
        $response->assertStatus(200);

        $user->syncRoles('Manager');
        $response = $this->getJson('/api/source-of-enquiry/1');
        $response->assertStatus(200);
    }

    public function test_the_source_of_enquiry_show_endpoint_returns_unauthorized_when_unauthenticated(): void
    {
        $response = $this->getJson('/api/source-of-enquiry/1');
        $response->assertStatus(401);
    }

    public function test_the_source_of_enquiry_store_endpoint_successfully_stores_a_source_of_enquiry(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('System Administrator');

        Passport::actingAs($user);

        $name = fake()->name();
        $response = $this->postJson('/api/source-of-enquiry', [
            'name' => $name,
        ]);

        $response->assertStatus(201);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', fn (AssertableJson $json) => $json
                    ->where('name', $name)
                    ->has('hidden')
                    ->etc()
                )
            );

        $this->assertDatabaseHas('source_of_enquiries', [
            'name' => $name,
        ]);

        $user->syncRoles('Manager');
        $name = fake()->name();
        $response = $this->postJson('/api/source-of-enquiry', [
            'name' => $name,
        ]);
        $response->assertStatus(403);

        $user->syncRoles('User');
        $name = fake()->name();
        $response = $this->postJson('/api/source-of-enquiry', [
            'name' => $name,
        ]);
        $response->assertStatus(403);
    }

    public function test_the_source_of_enquiry_store_endpoint_returns_unauthorized_when_unauthenticated(): void
    {
        $response = $this->postJson('/api/source-of-enquiry', [
            'name' => fake()->name(),
        ]);
        $response->assertStatus(401);
    }

    public function test_the_source_of_enquiry_update_endpoint_successfully_updates_a_source_of_enquiry(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('System Administrator');

        Passport::actingAs($user);

        $newName = fake()->name();
        $response = $this->putJson('/api/source-of-enquiry/1', [
            'name' => $newName,
            'hidden' => true,
        ]);

        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', fn (AssertableJson $json) => $json
                    ->where('id', 1)
                    ->where('name', $newName)
                    ->where('hidden', true)
                    ->etc()
                )
            );

        $this->assertDatabaseHas('source_of_enquiries', [
            'id' => 1,
            'name' => $newName,
        ]);

        $user->syncRoles('Manager');
        $newName = fake()->name();
        $response = $this->putJson('/api/source-of-enquiry/1', [
            'name' => $newName,
        ]);
        $response->assertStatus(403);

        $user->syncRoles('User');
        $newName = fake()->name();
        $response = $this->putJson('/api/source-of-enquiry/1', [
            'name' => $newName,
        ]);
        $response->assertStatus(403);
    }

    public function test_the_source_of_enquiry_update_endpoint_returns_unauthorized_when_unauthenticated(): void
    {
        $response = $this->putJson('/api/source-of-enquiry/1', [
            'name' => fake()->name(),
        ]);
        $response->assertStatus(401);
    }

    public function test_source_of_enquiry_audit_logs_are_retrievable(): void
    {
        $admin = User::factory()->create();
        $admin->syncRoles('System Administrator');

        Passport::actingAs($admin);

        $response = $this->getJson('/api/source-of-enquiry/audits/');
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

        $response = $this->getJson('/api/source-of-enquiry/1/audit/');
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

    public function test_only_admins_can_access_source_of_enquiry_audits(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('System Administrator');

        Passport::actingAs($user);

        $response = $this->getJson('/api/source-of-enquiry/audits/');
        $response->assertStatus(200);

        $response = $this->getJson('/api/source-of-enquiry/1/audit/');
        $response->assertStatus(200);

        $user->syncRoles('Manager');

        $response = $this->getJson('/api/source-of-enquiry/audits/');
        $response->assertStatus(403);

        $response = $this->getJson('/api/source-of-enquiry/1/audit/');
        $response->assertStatus(403);
    }
}
