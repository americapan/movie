<?php

namespace Tests\Unit;

use App\Models\Movie;
use App\Models\MovieDetail;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class MovieModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_movie_has_detail_relationship(): void
    {
        $movie = Movie::factory()->create();
        $detail = MovieDetail::factory()->create(['movie_id' => $movie->id]);

        $this->assertInstanceOf(MovieDetail::class, $movie->detail);
        $this->assertEquals($detail->id, $movie->detail->id);
    }

    public function test_movie_detail_belongs_to_movie(): void
    {
        $movie = Movie::factory()->create();
        $detail = MovieDetail::factory()->create(['movie_id' => $movie->id]);

        $this->assertInstanceOf(Movie::class, $detail->movie);
        $this->assertEquals($movie->id, $detail->movie->id);
    }

    public function test_movie_fillable_attributes(): void
    {
        $movie = new Movie;

        $fillable = $movie->getFillable();

        $this->assertContains('title', $fillable);
        $this->assertContains('poster_url', $fillable);
        $this->assertContains('source_url', $fillable);
        $this->assertContains('douban_rating', $fillable);
        $this->assertContains('imdb_rating', $fillable);
    }

    public function test_movie_casts(): void
    {
        $movie = Movie::factory()->create([
            'publish_date' => '2025-06-15',
            'douban_rating' => '8.5',
            'imdb_rating' => '7.8',
        ]);

        $this->assertInstanceOf(Carbon::class, $movie->publish_date);
        $this->assertEquals('2025-06-15', $movie->publish_date->format('Y-m-d'));
        $this->assertIsFloat($movie->douban_rating);
        $this->assertIsFloat($movie->imdb_rating);
    }

    public function test_movie_detail_download_resources_is_array(): void
    {
        $movie = Movie::factory()->create();
        $detail = MovieDetail::factory()->create([
            'movie_id' => $movie->id,
            'download_resources' => [
                ['name' => '阿里网盘', 'url' => 'https://example.com/dl1'],
                ['name' => '夸克网盘', 'url' => 'https://example.com/dl2'],
            ],
        ]);

        $this->assertIsArray($detail->download_resources);
        $this->assertCount(2, $detail->download_resources);
    }

    public function test_movie_source_url_is_unique(): void
    {
        Movie::factory()->create(['source_url' => 'https://example.com/movie/1']);

        $this->expectException(QueryException::class);

        Movie::factory()->create(['source_url' => 'https://example.com/movie/1']);
    }

    public function test_movie_without_detail_returns_null(): void
    {
        $movie = Movie::factory()->create();

        $this->assertNull($movie->detail);
    }
}
