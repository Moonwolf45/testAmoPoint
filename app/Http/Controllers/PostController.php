<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostController
{
    public function index() {
        $posts = Post::orderBy('fetched_at', 'desc')->get();

        return response()->json($posts);
    }
}
