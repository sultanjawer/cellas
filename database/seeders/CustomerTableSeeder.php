<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerTableSeeder extends Seeder
{
	public function run()
	{
		$customer = [
			[
				'id'				=> 1,
				'name'			=> 'Mr. Munesh',
				'mobile_phone'	=> null,
			],
		];

		Customer::insert($customer);
	}
}
