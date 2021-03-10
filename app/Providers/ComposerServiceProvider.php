<?php

namespace App\Providers;

use App\Tag;
use App\Category;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider {
    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        View::composer('layout.part.categories', function($view) {
            static $items = null;
            if (is_null($items)) {
                $items = Category::all();
            }
            $view->with(['items' => $items]);
        });
        View::composer('layout.part.popular-tags', function($view) {
            $view->with(['items' => Tag::popular()]);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {
        View::composer('layout.part.categories', function($view) {
            static $first = true;
            if ($first) {
                $view->with(['items' => Category::hierarchy()]);
            }
            $first = false;
        });
        View::composer('layout.part.popular-tags', function($view) {
            $view->with(['items' => Tag::popular()]);
        });
    }
}
