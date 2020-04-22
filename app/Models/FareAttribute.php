<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FareAttribute
 * 
 * @property int $id
 * @property int $agency_id
 * @property float $price
 * @property string $currency_type
 * @property bool $payment_method
 * @property string $transfers
 * @property int $transfer_duration
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property Agency $agency
 * @property Collection|FareRule[] $fare_rules
 *
 * @package App\Models
 */
class FareAttribute extends Model
{
	use SoftDeletes;
	protected $table = 'fare_attributes';

	protected $casts = [
		'agency_id' => 'int',
		'price' => 'float',
		'payment_method' => 'bool',
		'transfer_duration' => 'int'
	];

	protected $fillable = [
		'agency_id',
		'price',
		'currency_type',
		'payment_method',
		'transfers',
		'transfer_duration'
	];

	public function agency()
	{
		return $this->belongsTo(Agency::class);
	}

	public function fare_rules()
	{
		return $this->hasMany(FareRule::class, 'fare_id');
	}
}
