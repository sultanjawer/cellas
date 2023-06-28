<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
	public function run()
	{
		$product = [
			[
				'id'				=> 1,
				'product_code'		=> null,
				'symbol'			=> '$',
				'currency'			=> 'USD',
			],
			[
				'id'				=> 2,
				'product_code'		=> null,
				'symbol'			=> '¥',
				'currency'			=> 'Renminbi',
			],
		];

		Product::insert($product);
	}
}
