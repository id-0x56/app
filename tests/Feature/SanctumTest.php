<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SanctumTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    private $users;

    protected function setUp(): void
    {
        parent::setUp();

        $this->users = User::factory(3)->create([
            'password' => Hash::make('password'),
        ]);
    }

    public function test_database_table()
    {
        $this->assertDatabaseCount('users', 3);
    }

    public function test_get_bearer_token_bad_request()
    {
        $response = $this->postJson('/api/login', [
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '123456',
        ]);

        $response->assertStatus(400);
    }

    public function test_get_bearer_token_user_authorized()
    {
        $response = $this->postJson('/api/login', [
            'email' => $this->users->random(1)->first()->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'user' => [
                'id',
                'name',
                'email',
                'email_verified_at',
                'created_at',
                'updated_at',
            ],
            'token',
        ]);
    }

    public function test_get_bearer_token_user_unauthorized()
    {
        $response = $this->postJson('/api/login', [
            'email' => $this->users->random(1)->first()->email,
            'password' => '12345678',
        ]);

        $response->assertStatus(401);
    }

    public function test_user_authenticated_with_bearer_token()
    {
        Sanctum::actingAs(
            $this->users->random(1)->first(),
            ['*'],
        );

        $response = $this->getJson('/api/user');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'id',
            'name',
            'email',
            'email_verified_at',
            'created_at',
            'updated_at',
        ]);
    }

    public function test_user_unauthenticated_with_bearer_token()
    {
        $response = $this->getJson('/api/user');

        $response->assertStatus(400);
    }
}
