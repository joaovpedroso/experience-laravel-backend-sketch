<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = \App\Models\User::create([
            'name' => 'Alex',
            'email' => 'alexmpprog@gmail.com',
            'password' => bcrypt('123456789'),
        ]);


        $user->assignRole('Admin');
//        $user->givePermissionTo([
//            'users',
//            'add permission users',
//            'comunity',
//            'group reflection',
//            'camp',
//            'type camp',
//            'function',
//            'camper',
//            'descricao',
//        ]);
    }
}
