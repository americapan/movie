<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Support\StaticPagePaginator;

class FrontMovieController extends Controller
{
    private static function cleanTitle($title)
    {
        $patterns = [
            '/[百度]云.*/u',
            '/阿里云盘.*/u',
            '/夸[克克]网盘.*/u',
            '/迅雷云盘.*/u',
            '/下载[\.\s]*$/u',
            '/\[MP4.*$/iu',
            '/\[mkv.*$/iu',
            '/\[BD.*$/iu',
            '/\[HD.*$/iu',
            '/\[720p.*$/iu',
            '/\[1080p.*$/iu',
            '/\[4K.*$/iu',
            '/\（[0-9].*$/u',
        ];

        $title = preg_replace($patterns, '', $title);
        $title = trim($title);

        if (preg_match('/^(《[^》]+》).*$/', $title, $m)) {
            return trim($m[1]);
        }

        return $title;
    }

    public function home()
    {
        $heroMovies = Movie::whereNotNull('poster_url')
            ->whereNotNull('douban_rating')
            ->orderBy('publish_date', 'desc')
            ->take(5)
            ->get()
            ->each(fn ($m) => $m->title = self::cleanTitle($m->title));

        $latestMovies = Movie::orderBy('publish_date', 'desc')
            ->take(8)
            ->get()
            ->each(fn ($m) => $m->title = self::cleanTitle($m->title));

        $topRated = Movie::whereNotNull('douban_rating')
            ->orderBy('publish_date', 'desc')
            ->take(6)
            ->get()
            ->each(fn ($m) => $m->title = self::cleanTitle($m->title));

        $recentAdded = Movie::orderBy('publish_date', 'desc')
            ->skip(8)
            ->take(6)
            ->get()
            ->each(fn ($m) => $m->title = self::cleanTitle($m->title));

        $totalCount = cache()->remember('movie_total_count', 600, fn () => Movie::count());

        return view('home', compact(
            'heroMovies', 'latestMovies', 'topRated', 'recentAdded', 'totalCount'
        ));
    }

    public function index($page = null)
    {
        if ($page !== null && $page == 1) {
            $query = request()->getQueryString();

            return redirect('/movies'.($query ? '?'.$query : ''), 301);
        }

        if ($page !== null) {
            request()->merge(['page' => $page]);
        }

        $genre = request('genre');
        $moviesQuery = Movie::orderBy('publish_date', 'desc');

        if ($genre) {
            $moviesQuery->join('movie_details', 'movies.id', '=', 'movie_details.movie_id')
                ->where('movie_details.genre', 'like', "%{$genre}%")
                ->select('movies.*');
        }

        $paginator = $moviesQuery->paginate(20)
            ->through(fn ($m) => tap($m, fn ($x) => $x->title = self::cleanTitle($x->title)));

        $movies = new StaticPagePaginator(
            $paginator->items(),
            $paginator->total(),
            $paginator->perPage(),
            $paginator->currentPage(),
            ['path' => url('/movies')]
        );

        $totalCount = cache()->remember('movie_total_count', 600, fn () => Movie::count());
        $genres = cache()->remember('movie_genres_v2', 3600, function () {
            return Movie::join('movie_details', 'movies.id', '=', 'movie_details.movie_id')
                ->whereNotNull('movie_details.genre')
                ->selectRaw('movie_details.genre')
                ->distinct()
                ->pluck('genre')
                ->flatMap(fn ($g) => explode(' ', str_replace(['/', '|', ','], ' ', $g)))
                ->unique()
                ->values()
                ->take(12)
                ->toArray();
        });

        return view('movies.index', compact('movies', 'totalCount', 'genres', 'genre'));
    }

    public function show($id)
    {
        $movie = Movie::with('detail')->findOrFail($id);
        $movie->title = self::cleanTitle($movie->title);

        $related = Movie::where('id', '!=', $movie->id)
            ->orderBy('publish_date', 'desc')
            ->take(4)
            ->get()
            ->each(fn ($m) => $m->title = self::cleanTitle($m->title));

        return view('movies.show', compact('movie', 'related'));
    }

    public function about()
    {
        $totalCount = cache()->remember('movie_total_count', 600, fn () => Movie::count());

        return view('about', compact('totalCount'));
    }

    public function contact()
    {
        return view('contact');
    }
}
