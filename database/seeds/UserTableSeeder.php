<?php

use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {

		User::create([
				'name'     => 'João Victor',
				'email'    => 'joao_victor.dp@hotmail.com',
				'password' => bcrypt('joao'),
			]);
	}
}
