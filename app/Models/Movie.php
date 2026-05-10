<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Movie extends Model
{
    use HasDateTimeFormatter, HasFactory;

    protected $table = 'movies';

    protected $fillable = [
        'title',
        'poster_url',
        'source_url',
        'publish_date',
        'douban_rating',
        'imdb_rating',
        'description',
        'collected_at',
    ];

    protected $casts = [
        'publish_date' => 'date',
        'douban_rating' => 'float',
        'imdb_rating' => 'float',
        'collected_at' => 'datetime',
    ];

    public function detail(): HasOne
    {
        return $this->hasOne(MovieDetail::class, 'movie_id');
    }
}
