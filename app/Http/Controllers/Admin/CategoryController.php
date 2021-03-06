<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller {

    public function __construct() {
        $this->middleware('perm:manage-categories')->only('index');
        $this->middleware('perm:create-category')->only(['create', 'store']);
        $this->middleware('perm:edit-category')->only(['edit', 'update']);
        $this->middleware('perm:delete-category')->only('destroy');
    }

    /**
     * Показывает список всех категорий
     */
    public function index() {
        $items = Category::all();
        return view('admin.category.index', compact('items'));
    }

    /**
     * Показывает форму для создания категории
     */
    public function create() {
        return view('admin.category.create');
    }

    /**
     * Сохраняет новую категорию в базу данных
     * @param CategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CategoryRequest $request) {
        $image = $request->file('image');
        if ($image) { // был загружен файл изображения
            $path = $image->store('category/admin/', 'public');
            $base = basename($path);
        }

        $data = $request->input();
        $data['image'] = $base ?? null;
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category = (new Category())->create($data);

        if ($category) {
            return redirect()->route('admin.category.index', ['category' => $category->id])
                ->with(['success' => 'Новая категория успешно создана']);
        } else {
            return back();
        }
    }

    /**
     * Показывает форму для редактирования категории
     */
    public function edit(Category $category) {
        return view('admin.category.edit', compact('category'));
    }

    /**
     * Обновляет категорию блога в базе данных
     * @param CategoryRequest $request
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CategoryRequest $request, Category $category) {
        if ($request->remove) { // если надо удалить изображение
            $old = $category->image;
            if ($old) {
                Storage::disk('public')->delete('category/admin/' . $old);
            }
        }
        $file = $request->file('image');
        if ($file) { // был загружен файл изображения
            $path = $file->store('category/admin/', 'public');
            $base = basename($path);
            // удаляем старый файл
            $old = $category->image;
            if ($old) {
                Storage::disk('public')->delete('category/admin/' . $old);
            }
        }
        $data = $request->input();
        $data['image'] = $base ?? null;
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $category->update($data);
        return redirect()
            ->route('admin.category.index')
            ->with('success', 'Категория была успешно исправлена');
    }

    /**
     * Удаляет категорию блога
     */
    public function destroy(Category $category) {
        if ($category->children->count()) {
            $errors[] = 'Нельзя удалить категорию с дочерними категориями';
        }
        if ($category->posts->count()) {
            $errors[] = 'Нельзя удалить категорию, которая содержит посты';
        }
        if (!empty($errors)) {
            return back()->withErrors($errors);
        }
        // удаляем файл изображения
        $image = $category->image;
        if ($image) {
            Storage::disk('public')->delete('category/source/' . $image);
        }
        // удаляем категорию блога
        $category->delete();
        return redirect()
            ->route('admin.category.index')
            ->with('success', 'Категория блога успешно удалена');
    }
}
