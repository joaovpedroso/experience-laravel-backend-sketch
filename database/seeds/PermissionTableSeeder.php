<?php

use Illuminate\Database\Seeder;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Spatie\Permission\Models\Role::create([
            'name' => 'Admin'
        ]);

         \Spatie\Permission\Models\Role::create([
            'name' => 'Usuário'
        ]);

        \Spatie\Permission\Models\Permission::create([
            'name' => 'users',
            'translate' => 'Gerenciar Usuário'
        ]);

        \Spatie\Permission\Models\Permission::create([
            'name' => 'add permission users',
            'translate' => 'Administrar Permissões de Usuários'
        ]);

        \Spatie\Permission\Models\Permission::create([
            'name' => 'contact',
            'translate' => 'Contato'
        ]);

        \Spatie\Permission\Models\Permission::create([
            'name' => 'news',
            'translate' => 'Notícias'
        ]);

        \Spatie\Permission\Models\Permission::create([
            'name' => 'slide',
            'translate' => 'Slides'
        ]);

        \Spatie\Permission\Models\Permission::create([
            'name' => 'curriculum',
            'translate' => 'Curriculum'
        ]);
    }
}
