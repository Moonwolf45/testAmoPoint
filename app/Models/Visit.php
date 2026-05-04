<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    protected $fillable = [
        'ip',
        'city',
        'country',
        'device',
        'browser',
        'os',
        'screen_resolution',
        'user_agent',
        'page_url',
        'visited_at'
    ];

    protected $casts = [
        'visited_at' => 'datetime'
    ];
}
