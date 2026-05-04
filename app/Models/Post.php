<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'fetched_at'
    ];

    protected $casts = [
        'fetched_at' => 'datetime'
    ];
}
