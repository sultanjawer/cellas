<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Companies extends Model
{
	use HasFactory, SoftDeletes;

	public $table = 'companies';

	protected $dates = [
		'created_at',
		'updated_at',
		'deleted_at',
	];

	protected $fillable = [
		'company_code',
		'company_name',
	];

	public function bank()
	{
		return $this->hasMany(Bank::class, 'company_id');
	}

	public function order()
	{
		return $this->hasMany(Order::class, 'company_id', 'id');
	}
}
