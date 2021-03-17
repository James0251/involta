<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Tag;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller {

    public function __construct() {
        $this->middleware('perm:manage-tags')->only('index');
        $this->middleware('perm:create-tag')->only(['create', 'store']);
        $this->middleware('perm:edit-tag')->only(['edit', 'update']);
        $this->middleware('perm:delete-tag')->only('destroy');
    }

    /**
     * Показывает список всех тегов блога
     */
    public function index() {
        $items = Tag::paginate(15);
        return view('admin.tag.index', compact('items'));
    }

    /**
     * Показывает форму для создания тега
     */
    public function create() {
        return view('admin.tag.create');
    }

    /**
     * Сохраняет новый тег в базу данных
     */
    public function store(Request $request) {

        $this->validator($request->all(), null)->validate();

        $data = $request->input();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $tag = (new Tag())->create($data);
        if ($tag) {
            return redirect()->route('admin.tag.index', ['tag' => $tag->id])
                ->with('success', 'Новый тег блога успешно создан');
        } else {
            return back();
        }
    }

    /**
     * Показывает форму для редактирования тега
     */
    public function edit(Tag $tag) {
        return view('admin.tag.edit', compact('tag'));
    }

    /**
     * Обновляет тег блога в базе данных
     */
    public function update(Request $request, Tag $tag) {
        $this->validator($request->all(), $tag->id)->validate();

        $data = $request->input();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $tag->update($data);
        return redirect()
            ->route('admin.tag.index')
            ->with('success', 'Тег блога был успешно исправлен');
    }

    /**
     * Удаляет тег блога из базы данных
     */
    public function destroy(Tag $tag) {
        $tag->delete();
        return redirect()
            ->route('admin.tag.index')
            ->with('success', 'Тег блога был успешно удален');
    }

    /**
     * Возвращает объект валидатора с нужными правилами
     */
    private function validator($data, $id) {
        $unique = 'unique:tags,slug';
        if ($id) {
            // проверка на уникальность slug тега при редактировании,
            // исключая этот тег по идентифкатору в таблице БД tags
            $unique = 'unique:tags,slug,'.$id.',id';
        }
        $rules = [
            'name' => [
                'required',
                'string',
                'max:50',
            ],
            'slug' => [
                'max:50',
                $unique,
            ]
        ];
        $messages = [
            'required' => 'Поле «:attribute» обязательно для заполнения',
            'max' => 'Поле «:attribute» должно быть не больше :max символов',
        ];
        $attributes = [
            'name' => 'Наименование',
            'slug' => 'ЧПУ (англ.)'
        ];
        return Validator::make($data, $rules, $messages, $attributes);
    }
}
