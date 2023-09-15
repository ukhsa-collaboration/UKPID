<?php

namespace Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_user_index_route_returns_users(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('Administrator');

        Passport::actingAs($user);

        $response = $this->getJson('/api/user/');

        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', 1)
                ->has('data.0', fn (AssertableJson $json) => $json
                    ->has('id')
                    ->has('name')
                    ->has('email')
                    ->has('location', fn (AssertableJson $json) => $json
                        ->has('id')
                        ->has('key')
                    )
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

        $user = $users->first();
        $user->syncRoles('Administrator');

        Passport::actingAs($user);

        $response = $this->getJson('/api/user/'.$users[1]->id);

        $response->assertStatus(200);
        $response
            ->assertJson(fn (AssertableJson $json) => $json
                ->has('data', fn (AssertableJson $json) => $json
                    ->has('id')
                    ->has('name')
                    ->has('email')
                    ->has('location', fn (AssertableJson $json) => $json
                        ->has('id')
                        ->has('key')
                    )
                    ->etc()
                )
            );
    }

    public function test_the_user_show_route_returns_unauthorized_when_unauthenticated(): void
    {
        $response = $this->getJson('/api/user/');
        $response->assertStatus(401);
    }
}
