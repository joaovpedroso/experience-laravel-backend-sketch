<?php

use Illuminate\Database\Seeder;

class ModuleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \App\Models\Module::create([
            'name' => 'Log',
            'position' => 6,
            'url' => '/log',
            'icon' => 'file-text-o',
            'slug' => 'log'
        ]);

        \App\Configurate::create([
            'noticia_categoria' => 'Não'
        ]);

        \App\Info::create([
            'name' => 'Meu sistema'
        ]);

    }
}
