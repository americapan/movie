<?php

namespace Tests\Feature;

use App\Models\Movie;
use App\Models\MovieDetail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontMovieControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_returns_successful_response(): void
    {
        Movie::factory()->count(3)->create(['poster_url' => 'http://example.com/poster.jpg', 'douban_rating' => 8.5]);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewIs('home');
        $response->assertViewHas(['heroMovies', 'latestMovies', 'topRated', 'recentAdded', 'totalCount']);
    }

    public function test_home_page_handles_empty_database(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('totalCount', 0);
    }

    public function test_movies_index_returns_paginated_list(): void
    {
        Movie::factory()->count(25)->create();

        $response = $this->get('/movies');

        $response->assertStatus(200);
        $response->assertViewIs('movies.index');
        $response->assertViewHas('movies');
        $response->assertViewHas('totalCount', 25);
    }

    public function test_movies_page_redirects_page_1(): void
    {
        $response = $this->get('/movies/page_1.html');

        $response->assertRedirect('/movies');
    }

    public function test_movies_page_2_works(): void
    {
        Movie::factory()->count(25)->create();

        $response = $this->get('/movies/page_2.html');

        $response->assertStatus(200);
        $response->assertViewIs('movies.index');
    }

    public function test_movies_index_with_genre_filter(): void
    {
        $movie = Movie::factory()->create();
        MovieDetail::factory()->create([
            'movie_id' => $movie->id,
            'genre' => '动作',
        ]);

        $response = $this->get('/movies?genre=动作');

        $response->assertStatus(200);
        $response->assertViewIs('movies.index');
    }

    public function test_movie_show_returns_detail_page(): void
    {
        $movie = Movie::factory()->create(['title' => '测试电影']);
        MovieDetail::factory()->create(['movie_id' => $movie->id]);

        $response = $this->get("/movies/{$movie->id}.html");

        $response->assertStatus(200);
        $response->assertViewIs('movies.show');
        $response->assertViewHas('movie');
    }

    public function test_movie_show_404_for_nonexistent(): void
    {
        $response = $this->get('/movies/99999.html');

        $response->assertStatus(404);
    }

    public function test_movie_show_includes_related(): void
    {
        $movie = Movie::factory()->create(['title' => '目标电影', 'publish_date' => '2025-01-01']);
        MovieDetail::factory()->create(['movie_id' => $movie->id]);
        Movie::factory()->count(5)->create(['publish_date' => '2025-01-02']);

        $response = $this->get("/movies/{$movie->id}.html");

        $response->assertStatus(200);
        $response->assertViewHas('related');
    }

    public function test_about_page_returns_successful(): void
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertViewIs('about');
        $response->assertViewHas('totalCount');
    }

    public function test_contact_page_returns_successful(): void
    {
        $response = $this->get('/contact');

        $response->assertStatus(200);
        $response->assertViewIs('contact');
    }
}
