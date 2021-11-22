<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'name' => $this->faker->unique->sentence,
            'latitude' => $this->faker->latitude(-55, 85),
            'longitude' => $this->faker->longitude,
            'deleted_at' => rand(1, 5) === 5 ? Carbon::now() : null,
        ];
    }
}
