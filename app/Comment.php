<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {
    /**
     * Количество комментриев на странице при пагинации
     */
    protected $perPage = 5;

    /**
     * Выбирать из БД только опубликованные комментарии
     */
    public function scopePublished($builder) {
        return $builder->whereNotNull('published_by');
    }
    /* ... */
}
