<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content'  => $this->faker->sentence(),
            'location' => $this->faker->randomElement(['Room A', 'Zone 1', 'Library', 'Hall 3', 'North Zone']),
        ];
    }
}
