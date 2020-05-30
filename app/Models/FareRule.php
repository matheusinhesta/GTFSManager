<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FareRule
 *
 * @property int $id
 * @property int $fare_id
 * @property int $route_id
 * @property int $origin_id
 * @property int $destination_id
 * @property int $contains_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property Origin $origin
 * @property Destination $destination
 * @property Contain $contain
 * @property FareAttribute $fare_attribute
 * @property Route $route
 *
 * @package App\Models
 */
class FareRule extends Model
{
	use SoftDeletes;
	protected $table = 'fare_rules';

	protected $casts = [
		'fare_id' => 'int',
		'route_id' => 'int',
		'origin_id' => 'int',
		'destination_id' => 'int',
		'contains_id' => 'int'
	];

	protected $fillable = [
		'fare_id',
		'route_id',
		'origin_id',
		'destination_id',
		'contains_id'
	];

	public function origin()
	{
		return $this->belongsTo(Zone::class, 'origin_id')->select(['id', 'name', 'created_at', 'updated_at']);
	}

    public function destination()
    {
        return $this->belongsTo(Zone::class, 'destination_id')->select(['id', 'name', 'created_at', 'updated_at']);
    }

    public function contain()
    {
        return $this->belongsTo(Zone::class, 'contains_id')->select(['id', 'name', 'created_at', 'updated_at']);
    }

	public function fare_attribute()
	{
		return $this->belongsTo(FareAttribute::class, 'fare_id')->select(['id', 'price', 'currency_type', 'payment_method', 'transfers', 'transfer_duration', 'created_at', 'updated_at']);
	}

	public function route()
	{
		return $this->belongsTo(Route::class, 'route_id')->select(['id', 'short_name', 'long_name', 'desc', 'type', 'url', 'color', 'text_color', 'sort_order', 'created_at', 'updated_at']);
	}

    public function getCreatedAtAttribute($value) {
        return Carbon::createFromTimestamp(strtotime($value))->timezone(config('app.timezone'))->toDateTimeString();
    }

    public function getUpdatedAtAttribute($value) {
        return Carbon::createFromTimestamp(strtotime($value))->timezone(config('app.timezone'))->toDateTimeString();
    }
}
