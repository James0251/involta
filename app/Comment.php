<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model {

    protected $fillable = [
        'user_id',
        'post_id',
        'published_by',
        'content',
    ];

    /**
     * Возвращает true, если пользователь является автором
     */
    public function isAuthor() {
        return $this->user->id === auth()->user()->id;
    }

    /**
     * Номер страницы пагинации, на которой расположен комментарий;
     * все опубликованные + не опубликованные этого пользователя
     */
    public function userPageNumber() {
        // все опубликованные комментарии других пользователей
        $others = $this->post->comments()->published();
        // и не опубликованные комментарии этого пользователя
        $comments = $this->post->comments()
            ->whereUserId(auth()->user()->id)
            ->whereNull('published_by')
            ->union($others)
            ->orderBy('created_at')
            ->get();
        if ($comments->count() == 0) {
            return 1;
        }
        if ($comments->count() <= $this->getPerPage()) {
            return 1;
        }
        foreach ($comments as $i => $comment) {
            if ($this->id == $comment->id) {
                break;
            }
        }
        return (int) ceil(($i+1) / $this->getPerPage());
    }

    public function post() {
        return $this->belongsTo(Post::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function isVisible() {
        return ! is_null($this->published_by);
    }

    public function disable() {
        $this->published_by = null;
        $this->update();
    }

    public function enable() {
        $this->published_by = auth()->user()->id;
        $this->update();
    }

    /**
     * Количество комментриев на странице при пагинации
     */
    protected $perPage = 15;

    /**
     * Выбирать из БД только опубликованные комментарии
     */
    public function scopePublished($builder) {
        return $builder->whereNotNull('published_by');
    }

    /**
     * Номер страницы пагинации, на которой расположен комментарий;
     * учитываются все комментарии, в том числе не опубликованные
     */
    public function adminPageNumber() {
        $comments = $this->post->comments()->orderBy('created_at')->get();
        if ($comments->count() == 0) {
            return 1;
        }
        if ($comments->count() <= $this->getPerPage()) {
            return 1;
        }
        foreach ($comments as $i => $comment) {
            if ($this->id == $comment->id) {
                break;
            }
        }
        return (int) ceil(($i+1) / $this->getPerPage());
    }
}
