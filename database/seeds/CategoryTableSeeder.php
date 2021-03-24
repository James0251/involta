<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CategoryTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker) {
        $categories = [];

        $name = 'Без категории';
        $categories[] = [
            'name' => $name,
            'content' => $faker->realText(rand(200, 500)),
            'slug' => Str::slug($name),
            'parent_id' => 0
        ];

        for ($i = 1; $i <= 11; $i++) {
            $name = 'Категория №' . $i;
            $parentId = ($i > 2) ? rand(0, 4) : 0;
            $categories[] = [
                'name' => $name,
                'content' => $faker->realText(rand(200, 500)),
                'slug' => Str::slug($name),
                'parent_id' => $parentId
            ];
        }

        DB::table('categories')->insert($categories);
    }
}
