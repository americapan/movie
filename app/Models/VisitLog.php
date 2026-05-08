<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;
use Illuminate\Database\Eloquent\Model;

class VisitLog extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'visit_logs';

    protected $fillable = [
        'ip_address',
        'url',
        'method',
        'user_agent',
        'referer',
        'language',
        'device_type',
        'browser',
        'browser_version',
        'os',
        'route_name',
        'query_params',
        'session_id',
        'status_code',
        'request_duration',
        'country',
        'region',
        'city',
    ];

    protected $casts = [
        'query_params' => 'array',
        'status_code' => 'integer',
        'request_duration' => 'float',
    ];
}
