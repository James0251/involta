<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Illuminate\Http\Request;

class IndexController extends Controller {
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request) {
        $posts = Post::published()
            ->with('user')->with('tags')
            ->orderByDesc('created_at')
            ->paginate();

        return view('index', compact('posts'));
    }
}
