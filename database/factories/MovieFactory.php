<?php

namespace Database\Factories;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Movie>
 */
class MovieFactory extends Factory
{
    protected $model = Movie::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'poster_url' => fake()->optional()->imageUrl(),
            'source_url' => fake()->unique()->url(),
            'publish_date' => fake()->optional()->date(),
            'douban_rating' => fake()->optional()->randomFloat(1, 1, 10),
            'imdb_rating' => fake()->optional()->randomFloat(1, 1, 10),
            'description' => fake()->optional()->text(200),
            'collected_at' => now(),
        ];
    }
}
