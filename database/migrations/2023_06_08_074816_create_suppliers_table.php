<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('suppliers', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('company', 50)->nullable();
			$table->string('lastName', 50)->nullable();
			$table->string('firstName', 50)->nullable();
			$table->string('emailAddress', 50)->nullable();
			$table->string('jobTitle', 50)->nullable();
			$table->string('businessPhone', 25)->nullable();
			$table->string('homePhone', 25)->nullable();
			$table->string('mobilePhone', 25)->nullable();
			$table->string('faxNumber', 25)->nullable();
			$table->mediumText('address')->nullable();
			$table->string('city', 50)->nullable();
			$table->string('stateProvince', 50)->nullable();
			$table->string('zipPostalCode', 15)->nullable();
			$table->string('countryRegion', 50)->nullable();
			$table->mediumText('webPage')->nullable();
			$table->mediumText('notes')->nullable();
			$table->mediumText('attachments')->nullable();
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
		Schema::dropIfExists('suppliers');
	}
}
