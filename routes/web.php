<?php

use App\Http\Controllers\FrontMovieController;
use App\Models\SearchLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontMovieController::class, 'home'])->name('home');
Route::get('/movies', [FrontMovieController::class, 'index'])->name('movies.index');
Route::get('/movies/page_{page}.html', [FrontMovieController::class, 'index'])->where('page', '[0-9]+')->name('movies.page');
Route::get('/movies/{id}.html', [FrontMovieController::class, 'show'])->name('movies.show')->where('id', '[0-9]+');
Route::get('/about', [FrontMovieController::class, 'about'])->name('about');
Route::get('/contact', [FrontMovieController::class, 'contact'])->name('contact');
Route::post('/search-log', function (Request $request) {
    $keyword = trim($request->input('q', ''));
    if ($keyword !== '' && mb_strlen($keyword) <= 200) {
        SearchLog::create([
            'keyword' => $keyword,
            'ip_address' => $request->ip(),
        ]);
    }

    return response()->json(['ok' => true]);
});
