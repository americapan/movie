<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'search_logs';

    protected $fillable = [
        'keyword',
        'ip_address',
    ];
}
