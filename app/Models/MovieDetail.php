<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovieDetail extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'movie_details';

    protected $fillable = [
        'movie_id',
        'director',
        'writers',
        'casts',
        'genre',
        'country',
        'language',
        'release_date',
        'runtime',
        'imdb_id',
        'synopsis',
        'download_resources',
        'collected_at',
    ];

    protected $casts = [
        'download_resources' => 'array',
        'collected_at' => 'datetime',
    ];

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class, 'movie_id');
    }
}
