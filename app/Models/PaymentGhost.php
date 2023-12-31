<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentGhost extends Model
{
	use HasFactory, SoftDeletes, Auditable;

	public $table = 'paymentghost';

	protected $dates = [
		'created_at',
		'updated_at',
		'deleted_at'
	];

	protected $fillable = [
		'customer_id',
		'company_id',
		'amount',
		'origin_bank',
		'origin_account',
		'slip_date',
		'bank_id',
		'status',
		'validation',
		'attachment',
	];

	public function customer()
	{
		return $this->belongsTo(Customer::class, 'customer_id');
	}

	public function bank()
	{
		return $this->belongsTo(Bank::class, 'bank_id', 'id');
	}

	public function company()
	{
		return $this->belongsTo(Companies::class, 'company_id', 'id');
	}
}
