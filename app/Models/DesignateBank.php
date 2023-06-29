<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignateBank extends Model
{
	use HasFactory;

	public $table = 'designate_banks';

	protected $dates = [
		'created_at',
		'updated_at',
	];

	protected $fillable = [
		'order_id',
		'bank_id',
	];

	public function order()
	{
		return $this->belongsTo(Order::class, 'order_id');
	}
}
