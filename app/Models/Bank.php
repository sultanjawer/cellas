<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
	use HasFactory, SoftDeletes;

	protected $table = 'banks';

	protected $dates = [
		'created_at',
		'updated_at',
		'deleted_at',
	];

	protected $fillable = [
		'company_id',
		'bank_name',
		'account',
		'acc_name',
	];

	public function company()
	{
		return $this->belongsTo(Companies::class, 'company_id');
	}

	public function payment()
	{
		return $this->hasMany(Payment::class, 'bank_id', 'id');
	}
}
