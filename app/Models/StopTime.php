<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class StopTime
 *
 * @property int $id
 * @property int $trip_id
 * @property int $stop_id
 * @property Carbon $arrival_time
 * @property Carbon $departure_time
 * @property int $stop_sequence
 * @property string $stop_headsign
 * @property string $pickup_type
 * @property string $drop_off_type
 * @property float $shape_dist_traveled
 * @property bool $timepoint
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property Stop $stop
 * @property Trip $trip
 *
 * @package App\Models
 */
class StopTime extends Model
{
	use SoftDeletes;
	protected $table = 'stop_times';

	protected $casts = [
		'trip_id' => 'int',
		'stop_id' => 'int',
		'stop_sequence' => 'int',
		'shape_dist_traveled' => 'float',
		'timepoint' => 'bool'
	];

	protected $fillable = [
		'trip_id',
		'stop_id',
		'arrival_time',
		'departure_time',
		'stop_sequence',
		'stop_headsign',
		'pickup_type',
		'drop_off_type',
		'shape_dist_traveled',
		'timepoint'
	];

	public function stop()
	{
		return $this->belongsTo(Stop::class)->select(['id', 'agency_id', 'code', 'name', 'desc', 'lat', 'lon', 'zone_id', 'url', 'location_type', 'parent_station', 'timezone', 'wheelchair_boarding', 'platform_code', 'created_at', 'updated_at']);
	}

	public function trip()
	{
		return $this->belongsTo(Trip::class)->select(['id', 'route_id', 'service_id', 'headsign', 'short_name', 'direction_id',
            'block_id', 'wheelchair_accessible', 'bikes_allowed', 'created_at', 'updated_at']);
	}

    public function getCreatedAtAttribute($value) {
        return Carbon::createFromTimestamp(strtotime($value))->timezone(config('app.timezone'))->toDateTimeString();
    }

    public function getUpdatedAtAttribute($value) {
        return Carbon::createFromTimestamp(strtotime($value))->timezone(config('app.timezone'))->toDateTimeString();
    }
}
