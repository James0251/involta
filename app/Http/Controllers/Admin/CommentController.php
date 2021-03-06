<?php

namespace App\Http\Controllers\Admin;

use App\Comment;
use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Like;
use App\Post;
use App\User;
use Illuminate\Http\Request;

class CommentController extends Controller {

    public function __construct() {
        $this->middleware('auth');
        $this->middleware('perm:manage-comments')->only(['index', 'show']);
        $this->middleware('perm:edit-comment')->only('update');
        $this->middleware('perm:publish-comment')->only(['enable', 'disable']);
        $this->middleware('perm:delete-comment')->only('destroy');
    }

    /**
     * Показывает список всех комментариев
     */
    public function index() {
        $users = User::paginate(8);
        $posts = Post::orderBy('created_at', 'desc')->paginate();
        $comments = Comment::orderBy('created_at', 'desc')->paginate();
        return view('admin.comment.index', compact('comments', 'posts', 'users'));
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
     * Просмотр комментария к посту блога
     */
    public function show(Comment $comment) {

        // Количество лайков
        $likePost = Comment::find($comment->post_id);
        $likeCount = Like::where([
            'post_id' => $likePost->id
        ])->count();

        // сигнализирует о том, что это режим пред.просмотра
        session()->flash('preview', 'yes');
        // это тот пост блога, к которому оставлен комментарий
        $post = $comment->post;
        // коллекция всех комментариев к этому посту блога
        $comments = $post->comments()->orderBy('created_at')->paginate();
        // используем шаблон предварительного просмотра поста
        return view('admin.post.show', compact('post', 'comments', 'likeCount'));
    }

    /**
     * Показывает форму редактирования комментария
     */
    public function edit(Comment $comment) {
        // нужно сохранить flash-переменную, которая сигнализирует о том,
        // что кнопка редактирования была нажата в режиме пред.просмотра
        session()->keep('preview');
        return view('admin.comment.edit', compact('comment'));
    }

    /**
     * Обновляет комментарий в базе данных
     */
    public function update(CommentRequest $request, Comment $comment) {
        $comment->update($request->all());
        return $this->redirectAfterUpdate($comment);
    }

    /**
     * Разрешить публикацию комментария
     */
    public function enable(Comment $comment) {
        $comment->enable();
        $redirect = back();
        if (session('preview')) {
            $redirect = $redirect->withFragment('comment-list');
        }
        return $redirect->with('success', 'Комментарий был опубликован');
    }

    /**
     * Запретить публикацию комментария
     */
    public function disable(Comment $comment) {
        $comment->disable();
        $redirect = back();
        if (session('preview')) {
            $redirect = $redirect->withFragment('comment-list');
        }
        return $redirect->with('success', 'Комментарий снят с публикации');
    }

    /**
     * Удаляет комментарий из базы данных
     */
    public function destroy(Comment $comment) {
        $comment->delete();
        $redirect = back();
        if (session('preview')) {
            $redirect = $redirect->withFragment('comment-list');
        }
        return $redirect->with('success', 'Комментарий успешно удален');
    }

    /**
     * Выполянет редирект после обновления
     */
    private function redirectAfterUpdate(Comment $comment) {
        // кнопка редактирования может быть нажата в режиме пред.просмотра
        // или в панели управления блогом, поэтому и редирект будет разный
        $redirect = redirect();
        if (session('preview')) {
            $redirect = $redirect->route(
                'admin.comment.show',
                ['comment' => $comment->id, 'page' => $comment->adminPageNumber()]
            )->withFragment('comment-list');
        } else {
            $redirect = $redirect->route('admin.comment.index');
        }
        return $redirect->with('success', 'Комментарий был успешно исправлен');
    }
}
