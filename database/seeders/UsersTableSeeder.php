<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
	public function run()
	{
		$users = [
			[
				'id'             => 1,
				'name'           => 'Admin',
				'email'          => 'admin@admin.com',
				'password'       => bcrypt('password'),
				'remember_token' => null,
				'username'       => 'admin',
				'roleaccess'     => 1,
			],
			[
				'id'             => 2,
				'name'           => 'Cella',
				'email'          => 'cella@cella.com',
				'password'       => bcrypt('password'),
				'remember_token' => null,
				'username'       => 'cella',
				'roleaccess'     => 1,
			],
			[
				'id'             => 3,
				'name'           => 'user@user.com',
				'email'          => 'user@user.com',
				'password'       => bcrypt('password'),
				'remember_token' => null,
				'username'       => 'user1',
				'roleaccess'     => 1,
			],
		];
		User::insert($users);
	}
}
