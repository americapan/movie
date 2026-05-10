<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\MovieDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MovieDetail>
 */
class MovieDetailFactory extends Factory
{
    protected $model = MovieDetail::class;

    public function definition(): array
    {
        return [
            'movie_id' => Movie::factory(),
            'director' => fake()->name(),
            'writers' => fake()->name(),
            'casts' => fake()->name().' / '.fake()->name(),
            'genre' => fake()->randomElement(['动作', '喜剧', '科幻', '爱情', '悬疑', '动画']),
            'country' => fake()->country(),
            'language' => fake()->languageCode(),
            'release_date' => fake()->date(),
            'runtime' => fake()->numberBetween(80, 180).'分钟',
            'imdb_id' => 'tt'.fake()->numerify('#######'),
            'synopsis' => fake()->paragraph(),
            'download_resources' => [
                ['name' => '阿里网盘', 'url' => fake()->url()],
            ],
            'collected_at' => now(),
        ];
    }
}
