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
				'name'     => 'Alex Morgado',
				'email'    => 'alexmpprog@gmail.com',
				'password' => bcrypt('123'),
			]);
	}
}
