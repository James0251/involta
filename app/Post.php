<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Post extends Model {

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'slug',
        'excerpt',
        'content',
        'image',
    ];

    /**
     * Возвращает true, если пользователь является автором
     */
    public function isAuthor() {
        return $this->user->id === auth()->user()->id;
    }

    /**
     * Количество постов на странице при пагинации
     */
    protected $perPage = 5;

    /**
     * Связь модели Post с моделью Tag, позволяет получить
     * все теги поста
     */
    public function tags() {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    /**
     * Связь модели Post с моделью Category, позволяет получить
     * родительскую категорию поста
     */
    public function category() {
        return $this->belongsTo(Category::class);
    }

    /**
     * Связь модели Post с моделью User, позволяет получить
     * автора поста
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Связь модели Post с моделью User, позволяет получить
     * администратора, который разрешил публикацию поста
     */
    public function editor() {
        return $this->belongsTo(User::class, 'published_by');
    }

    /**
     * Связь модели Post с моделью Comment, позволяет получить
     * все комментарии к посту
     */
    public function comments() {
        return $this->hasMany(Comment::class);
    }

    /**
     * Разрешить публикацию поста блога
     */
    public function enable() {
        $this->published_by = auth()->user()->id;
        $this->update();
    }

    /**
     * Запретить публикацию поста блога
     */
    public function disable() {
        $this->published_by = null;
        $this->update();
    }

    /**
     * Возвращает true, если публикация разрешена
     */
    public function isVisible() {
        return ! is_null($this->published_by);
    }

    /**
     * Выбирать из БД только опубликованные посты
     */
    public function scopePublished($builder) {
        return $builder->whereNotNull('published_by');
    }
}
