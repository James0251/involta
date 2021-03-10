<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        $this->call(UserTableSeeder::class);
        $this->command->info('Таблица пользователей загружена данными!');

        $this->call(CategoryTableSeeder::class);
        $this->command->info('Таблица категорий загружена данными!');

        $this->call(TagTableSeeder::class);
        $this->command->info('Таблица тегов загружена данными!');

        $this->call(PostTableSeeder::class);
        $this->command->info('Таблица постов загружена данными!');

        $this->call(PostTagTableSeeder::class);
        $this->command->info('Таблица пост-тег загружена данными!');

        $this->call(CommentTableSeeder::class);
        $this->command->info('Таблица комментариев загружена данными!');
    }
}
