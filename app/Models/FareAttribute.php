<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\EnumManipulation;
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
		return $this->belongsTo(Agency::class)->select(['id', 'name', 'timezone', 'lang', 'fare_url', 'phone', 'email', 'created_at', 'updated_at']);
	}

	public function fare_rules()
	{
		return $this->hasMany(FareRule::class, 'fare_id')->with('route', 'origin', 'destination', 'contain')->select(['id', 'route_id', 'fare_id', 'origin_id', 'destination_id', 'contains_id', 'created_at', 'updated_at']);
	}

    public function getEnumTransfersAttribute(){
        return EnumManipulation::getEnumValues('fare_attributes', 'transfers');
    }

    public function getEnumKeyTransfersAttribute(){
        return array_keys(EnumManipulation::getEnumValues('fare_attributes', 'transfers'), $this->transfers);
    }

    public function getCreatedAtAttribute($value) {
        return Carbon::createFromTimestamp(strtotime($value))->timezone(config('app.timezone'))->toDateTimeString();
    }

    public function getUpdatedAtAttribute($value) {
        return Carbon::createFromTimestamp(strtotime($value))->timezone(config('app.timezone'))->toDateTimeString();
    }
}
