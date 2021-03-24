<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function getResults(Request $request) {

        $query = $request->input('query');

        $users = User::where('name', 'LIKE', "%$query%")
            ->with('posts')->with('comments')
            ->get();

        $posts = Post::where('name', 'LIKE', "%$query%")
            ->orWhere('excerpt', 'LIKE', "%$query%")
            ->orWhere('content', 'LIKE', "%$query%")
            ->with('tags')->with('user')->with('comments')
            ->paginate(15);

        $comments = Comment::where('content', 'LIKE', "%$query%")->with('post')->with('user')->get();


        $tags = Tag::where('name', 'LIKE', "%$query%")
            ->with('posts')
            ->get();


        return view('search.results', compact('users', 'posts', 'comments', 'tags'));
    }
}
