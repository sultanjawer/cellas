<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customers', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->string('customer_id')->nullable();
			$table->string('account_no')->nullable();
			$table->string('company', 50)->nullable();
			$table->string('name', 50)->nullable();
			$table->string('email_address', 50)->nullable();
			$table->string('job_title', 50)->nullable();
			$table->string('business_phone', 25)->nullable();
			$table->string('home_phone', 25)->nullable();
			$table->string('mobile_phone', 25)->nullable();
			$table->string('fax_number', 25)->nullable();
			$table->mediumText('address')->nullable();
			$table->string('city', 50)->nullable();
			$table->string('state_province', 50)->nullable();
			$table->string('zip_postal_code', 15)->nullable();
			$table->string('country_region', 50)->nullable();
			$table->mediumText('web_page')->nullable();
			$table->mediumText('notes')->nullable();
			$table->mediumText('attachments')->nullable();
			$table->softDeletes();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('customers');
	}
}
