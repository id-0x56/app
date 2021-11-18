<?php

namespace Tests\Feature;

use App\Models\Location;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LocationTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    private $users;

    private $locations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->users = User::factory(3)->create();
        $this->locations = Location::factory(10)->create();
    }

    public function test_database_tables()
    {
        $this->assertDatabaseCount('users', 3);
        $this->assertDatabaseCount('locations', 10);
    }

    public function test_route_index()
    {
        $response = $this->json('GET', '/api/locations', [
            'min_lat' => $this->faker->latitude(-90, -90),
            'max_lat' => $this->faker->latitude(90),
            'min_lng' => $this->faker->longitude(-180, -180),
            'max_lng' => $this->faker->longitude(180),
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'locations' => [
                '*' => [
                    'name',
                    'latitude',
                    'longitude',
                ],
            ],
        ]);
    }

    public function test_route_store()
    {
        Sanctum::actingAs(
            $this->users->random(1)->first(),
            ['*'],
        );

        $response = $this->postJson('/api/locations', [
            'name' => $this->faker->unique->sentence,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
        ]);

        $response->assertStatus(201);
    }

    public function test_route_show()
    {
        $response = $this->getJson('/api/locations/' . $this->locations->where('deleted_at', null)->random(1)->first()->id);
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'location' => [
                'name',
                'latitude',
                'longitude',
            ],
        ]);
    }

    public function test_route_update()
    {
        $location = $this->locations->where('deleted_at', null)->random(1)->first();

        Sanctum::actingAs(
            $location->user,
            ['*'],
        );

        $response = $this->putJson('/api/locations/' . $location->id, [
            'name' => $this->faker->unique->sentence,
//            'latitude' => $this->faker->latitude,
//            'longitude' => $this->faker->longitude,
        ]);

        $response->assertStatus(202);

        $response->assertJsonStructure([
            'location' => [
                'name',
                'latitude',
                'longitude',
            ],
        ]);
    }

    public function test_route_destroy()
    {
        $location = $this->locations->where('deleted_at', null)->random(1)->first();

        Sanctum::actingAs(
            $location->user,
            ['*'],
        );

        $response = $this->deleteJson('/api/locations/' . $location->id);

        $response->assertStatus(204);
    }

    public function test_route_restore()
    {
        $this->locations->where('deleted_at', '<>', null)->count() !== 0 ?: $this->locations->random(1)->first()->delete();

        $location = $this->locations->where('deleted_at', '<>', null)->random(1)->first();

        Sanctum::actingAs(
            $location->user,
            ['*'],
        );

        $response = $this->patchJson('/api/locations/' . $location->id . '/restore');

        $response->assertStatus(200);
    }

    public function test_route_force_delete()
    {
        $this->users->filter(fn($user) => $user->isRole('admin'))->count() !== 0 ?: $this->users->random(1)->first()->update(['name' => 'admin']);

        $user = $this->users->filter(fn($user) => $user->isRole('admin'))->first();

        Sanctum::actingAs(
            $user,
            ['*'],
        );

        $response = $this->deleteJson('/api/locations/' . $this->locations->random(1)->first()->id . '/force-delete');

        $response->assertStatus(204);
    }
}
