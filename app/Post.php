<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Post extends Model {

    /**
     * Количество постов на странице при пагинации
     */
    protected $perPage = 5;

    /**
     * Выбирать из БД только опубликовынные посты
     */
    public function scopePublished($builder) {
        return $builder->whereNotNull('published_by');
    }

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
}
