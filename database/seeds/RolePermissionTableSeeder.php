<?php

use Illuminate\Database\Seeder;

class RolePermissionTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        // создать связи между ролями и правами
        foreach(App\Role::all() as $role) {
            if ($role->slug == 'admin') { // для роли админа все права
                foreach (App\Permission::all() as $perm) {
                    $role->permissions()->attach($perm->id);
                }
            }
            if ($role->slug == 'user') { // для обычного пользователя совсем чуть-чуть
                $slugs = ['create-post', 'create-comment', 'create-tag'];
                foreach ($slugs as $slug) {
                    $perm = App\Permission::where('slug', $slug)->first();
                    $role->permissions()->attach($perm->id);
                }
            }
        }
    }
}
