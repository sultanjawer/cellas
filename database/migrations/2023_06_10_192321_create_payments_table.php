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
		Schema::create('payments', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('customer_id')->nullable();
			$table->integer('company_id')->nullable();
			$table->decimal('amount', 12, 2)->nullable();
			$table->string('origin_bank')->nullable();
			$table->string('origin_account')->nullable();
			$table->integer('recipient_bank')->nullable();
			$table->date('slip_date')->nullable();
			$table->string('attachment')->nullable();
			$table->enum('status', ['unchecked', 'checked'])->default('unchecked')->nullable();
			$table->enum('validation', ['unvalidated', 'validated'])->default('unvalidated')->nullable();
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
		Schema::dropIfExists('payments');
	}
};
