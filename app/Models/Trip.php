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
 * Class Trip
 *
 * @property int $id
 * @property int $route_id
 * @property int $service_id
 * @property string $headsign
 * @property string $short_name
 * @property string $direction_id
 * @property int $block_id
 * @property int $shape_id
 * @property string $wheelchair_accessible
 * @property string $bikes_allowed
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property Route $route
 * @property Service $service
 * @property Shape $shape
 * @property Collection|Attribution[] $attributions
 * @property Collection|StopTime[] $stop_times
 *
 * @package App\Models
 */
class Trip extends Model
{
	use SoftDeletes;
	protected $table = 'trips';

	protected $casts = [
		'route_id' => 'int',
		'service_id' => 'int',
		'block_id' => 'int',
		'shape_id' => 'int'
	];

	protected $fillable = [
		'route_id',
		'service_id',
		'headsign',
		'short_name',
		'direction_id',
		'block_id',
		'shape_id',
		'wheelchair_accessible',
		'bikes_allowed'
	];

	public function route()
	{
		return $this->belongsTo(Route::class)->select(['id', 'short_name', 'long_name']);
	}

	public function service()
	{
		return $this->belongsTo(Service::class)->select(['id', 'name']);
	}

	public function shape()
	{
		return $this->belongsTo(Shape::class);
	}

	public function attributions()
	{
		return $this->hasMany(Attribution::class);
	}

	public function stop_times()
	{
		return $this->hasMany(StopTime::class);
	}

    public function getEnumKeyDirectionIdAttribute(){
        return array_keys(EnumManipulation::getEnumValues('trips', 'direction_id'), $this->direction_id)[0];
    }

    public function getCreatedAtAttribute($value) {
        return Carbon::createFromTimestamp(strtotime($value))->timezone(config('app.timezone'))->toDateTimeString();
    }

    public function getUpdatedAtAttribute($value) {
        return Carbon::createFromTimestamp(strtotime($value))->timezone(config('app.timezone'))->toDateTimeString();
    }
}
