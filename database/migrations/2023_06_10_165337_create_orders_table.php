<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('customer_id')->nullable();
			$table->string('product_id')->nullable();
			$table->decimal('amount', 12, 2)->nullable();
			$table->decimal('buy')->nullable();
			$table->decimal('sell')->nullable();
			$table->integer('charges')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('orders');
	}
};
