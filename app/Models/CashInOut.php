<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashInOut extends Model
{
	protected $fillable = [
		'customer_id',
		'transaction_date',
		'currency',
		'order',
		'charges',
		'deposit',
		'cashOut',
	];

	// Set the table to null to indicate that it's a non-persisted model
	protected $table = null;
}
