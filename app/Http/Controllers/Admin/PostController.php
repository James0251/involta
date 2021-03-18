<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Post;
use App\Http\Requests\PostRequest;
use Illuminate\Http\Request;

class PostController extends Controller {

    public function __construct() {
        $this->middleware('perm:manage-posts')->only(['index', 'category', 'show']);
        $this->middleware('perm:edit-post')->only(['edit', 'update']);
        $this->middleware('perm:publish-post')->only(['enable', 'disable']);
        $this->middleware('perm:delete-post')->only('destroy');
    }

    /**
     * Список всех постов блога
     */
    public function index() {
        $roots = Category::where('parent_id', 0)->get();
        $posts = Post::orderBy('created_at', 'desc')->paginate();
        return view('admin.post.index', compact('roots', 'posts'));
    }

    public function store(PostRequest $request) {
        $image = $request->file('image');
        if ($image) { // был загружен файл изображения
            $path = $image->store('post/admin/', 'public');
            $base = basename($path);
        }

        $data = $request->input();
        $data['image'] = $base ?? null;
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $post = (new Post())->create($data);
        dd($data['image']);

        if ($post) {
            return redirect()->route('admin.category.index', ['post' => $post->id])
                ->with(['success' => 'Новая категория успешно создана']);
        } else {
            return back();
        }
    }

    /**
     * Список постов категории блога
     */
    public function category(Category $category) {
        $posts = $category->posts()->paginate();
        return view('admin.post.category', compact('category', 'posts'));
    }

    /**
     * Страница просмотра поста блога
     */
    public function show(Post $post) {
        $blogKey = 'blog_' . $post->id;
        if (!\Session::has($blogKey)) {
            $post->increment('view_count');
            \Session::put($blogKey, 1);
        }

        // сигнализирует о том, что это режим пред.просмотра
        session()->flash('preview', 'yes');
        return view('admin.post.show', compact('post'));
    }

    /**
     * Разрешить публикацию поста блога
     */
    public function enable(Post $post) {
        $post->enable();
        return back()->with('success', 'Пост блога был опубликован');
    }

    /**
     * Запретить публикацию поста блога
     */
    public function disable(Post $post) {
        $post->disable();
        return back()->with('success', 'Пост блога снят с публикации');
    }

    /**
     * Сохраняет новый пост в базу данных
     */

    /**
     * Показывает форму редактирования поста
     */
    public function edit(Post $post) {
        // нужно сохранить flash-переменную, которая сигнализирует о том,
        // что кнопка редактирования была нажата в режиме пред.просмотра
        session()->keep('preview');
        return view('admin.post.edit', compact('post' ));
    }

    /**
     * Обновляет пост блога в базе данных
     * @param PostRequest $request
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse
     */
    /**
     * Обновляет пост блога в базе данных
     */
    public function update(PostRequest $request, Post $post) {
        if ($request->remove) { // если надо удалить изображение
            $old = $post->image;
            if ($old) {
                Storage::disk('public')->delete('post/admin/' . $old);
            }
        }
        $file = $request->file('image');
        if ($file) { // был загружен файл изображения
            $path = $file->store('post/admin/', 'public');
            $base = basename($path);
            // удаляем старый файл
            $old = $post->image;
            if ($old) {
                Storage::disk('public')->delete('post/admin/' . $old);
            }
        }
        $data = $request->input();
        $data['image'] = $base ?? null;
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $post->update($data);
        $post->tags()->sync($request->tags);
        // кнопка редактирования может быть нажата в режиме пред.просмотра
        // или в панели управления блогом, так что и редирект будет разный
        $route = 'admin.post.index';
        $param = [];
        if (session('preview')) {
            $route = 'admin.post.show';
            $param = ['post' => $post->id];
        }
        return redirect()
            ->route($route, $param)
            ->with('success', 'Пост был успешно обновлен');
    }

    /**
     * Удаляет пост блога из базы данных
     */
    public function destroy(Post $post) {
        $post->delete();
        // пост может быть удален в режиме пред.просмотра или из панели
        // управления, так что и редирект после удаления будет разным
        $route = 'admin.post.index';
        if (session('preview')) {
            $route = 'blog.index';
        }
        return redirect()
            ->route($route)
            ->with('success', 'Пост блога успешно удален');
    }
}
