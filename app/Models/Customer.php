<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
	use HasFactory, SoftDeletes, Auditable;

	protected $table = 'customers';

	protected $fillable = [
		'customer_id',
		'account_no',
		'company',
		'name',
		'email_address',
		'job_title',
		'business_phone',
		'home_phone',
		'mobile_phone',
		'fax_number',
		'address',
		'city',
		'state_province',
		'zip_postal_code',
		'country_region',
		'web_page',
		'notes',
		'attachments',
	];

	protected $dates = ['deleted_at'];

	public function payment()
	{
		return $this->hasMany(Payment::class, 'customer_id', 'id');
	}

	public function order()
	{
		return $this->hasMany(Order::class);
	}
}
