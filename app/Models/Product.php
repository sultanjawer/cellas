<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
	use HasFactory, SoftDeletes, Auditable;

	public $table = 'products';

	protected $fillable = [
		'product_code',
		'symbol',
		'currency',
	];

	protected $dates = [
		'created_at',
		'updated_at',
		'deleted_at',
	];
}
