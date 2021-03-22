<?php

namespace App\Http\Controllers;

use App\Category;
use App\Comment;
use App\Like;
use App\Post;
use App\Tag;
use App\User;
use App\Http\Requests\CommentRequest;
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

    public function like($id) {
        $user = \Auth::user()->id;
        $like_user = Like::where([
            'user_id' => $user,
            'post_id' => $id,
        ])->first();
        $user_id = \Auth::user()->id;
        $post_id = $id;
        $like = new Like();
        $like->user_id = $user_id;
        $like->post_id = $post_id;
        $like->save();
        return redirect()->back();
    }

    /**
     * Страница просмотра отдельного поста блога
     */
    public function post(Post $post) {
        $comments = $post->comments()->with('user')
            ->published()
            ->orderBy('created_at')
            ->paginate();

        // Количество лайков
        $likePost = Post::find($post->id);
        $likeCount = Like::where([
            'post_id' => $likePost->id
        ])->count();

        // Количество просмотров
        $blogKey = 'blog_' . $post->id;
        if (!\Session::has($blogKey)) {
            $post->increment('view_count');
            \Session::put($blogKey, 1);
        }
        return view('blog.post', compact('post', 'comments', 'likeCount'));
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

    /**
     * Сохраняет новый комментарий в базу данных
     */
    public function comment(CommentRequest $request) {
        $request->merge(['user_id' => auth()->user()->id]);
        $message = 'Комментарий добавлен, будет доступен после проверки';
        if (auth()->user()->hasPermAnyWay('publish-comment')) {
            $request->merge(['published_by' => auth()->user()->id]);
            $message = 'Комментарий добавлен и уже доступен для просмотра';
        }
        $comment = Comment::create($request->all());
        // комментариев может быть много, поэтому есть пагинация; надо
        // перейти на последнюю страницу — новый комментарий будет там
        $page = $comment->post->comments()->published()->paginate()->lastPage();
        return redirect()
            ->route('blog.post', ['post' => $comment->post->slug, 'page' => $page])
            ->withFragment('comment-list')
            ->with('success', $message);
    }
}
