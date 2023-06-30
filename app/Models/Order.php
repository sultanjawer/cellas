<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
	use HasFactory, SoftDeletes, Auditable;

	public $table = 'orders';

	protected $dates = [
		'created_at',
		'updated_at',
		'deleted_at',
	];

	protected $fillable = [
		'customer_id',
		'product_id',
		'company_id',
		'amount',
		'buy',
		'sell',
		'pcharges',
		'ccharges',
		'pfa',
		'cfa',
	];

	public function customer()
	{
		return $this->belongsTo(Customer::class, 'customer_id');
	}

	public function product()
	{
		return $this->belongsTo(Product::class, 'product_id', 'id');
	}

	public function bank()
	{
		return $this->belongsTo(Bank::class, 'bank_id');
	}

	public function designateBanks()
	{
		return $this->hasMany(DesignateBank::class, 'order_id');
	}

	public function company()
	{
		return $this->belongsTo(Companies::class, 'company_id', 'id');
	}
}
