<?php

namespace App\Http\Controllers;

use App\Category;
use App\Comment;
use App\Post;
use App\Tag;
use App\User;
use Illuminate\Http\Request;

class BlogController extends Controller {

    /**
     * Главная страница блога (список всех постов)
     */
    public function index() {
        $posts = Post::published()
            ->with('user')->with('tags')
            ->orderByDesc('created_at')
            ->paginate();
        return view('blog.index', compact('posts'));
    }

    /**
     * Страница просмотра отдельного поста блога
     */
    public function post(Post $post) {
        $comments = $post->comments()
            ->published()
            ->orderBy('created_at')
            ->paginate();
        return view('blog.post', compact('post', 'comments'));
    }

    /**
     * Список постов блога выбранной категории
     */
    public function category(Category $category) {
        $descendants = array_merge(Category::descendants($category->id), [$category->id]);
        $posts = Post::whereIn('category_id', $descendants)
            ->published()
            ->with('user')->with('tags')
            ->orderByDesc('created_at')
            ->paginate();
        return view('blog.category', compact('category', 'posts'));
    }

    /**
     * Список постов блога выбранного автора
     */
    public function author(User $user) {
        $posts = $user->posts()
            ->published()
            ->with('user')->with('tags')
            ->orderByDesc('created_at')
            ->paginate();
        return view('blog.author', compact('user', 'posts'));
    }

    /**
     * Список постов блога с выбранным тегом
     */
    public function tag(Tag $tag) {
        $posts = $tag->posts()
            ->published()
            ->with('user')->with('tags')
            ->orderByDesc('created_at')
            ->paginate();
        return view('blog.tag', compact('tag', 'posts'));
    }
}
