<?php

namespace Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Passport\Passport;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_role_index_endpoint_returns_all_roles(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('Administrator');

        Passport::actingAs($user);

        $response = $this->getJson('/api/role/');

        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', 3)
                ->has('data.0', fn (AssertableJson $json) => $json
                    ->where('id', 1)
                    ->where('name', 'Administrator')
                    ->etc()
                )
            );
    }

    public function test_the_role_index_endpoint_returns_roles_with_permissions(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('Administrator');

        Passport::actingAs($user);

        $response = $this->getJson('/api/role?with_permissions=1');

        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', 3)
                ->has('data.1', fn (AssertableJson $json) => $json
                    ->where('id', 2)
                    ->where('name', 'Manager')
                    ->has('permissions.0', fn (AssertableJson $json) => $json
                        ->where('name', 'user.create')
                        ->where('label', 'Create new users')
                        ->etc()
                    )
                    ->etc()
                )
            );
    }

    public function test_the_role_index_endpoint_returns_unauthorized_when_unauthenticated(): void
    {
        $response = $this->getJson('/api/role/');
        $response->assertStatus(401);
    }
}
