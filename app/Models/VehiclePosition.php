<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class VehiclePosition
 *
 * @property int $id
 * @property int $trip_descriptor_id
 * @property int $vehicle_id
 * @property float $lat
 * @property float $lon
 * @property float $bearing
 * @property float $odometer
 * @property float $speed
 * @property int $current_stop_sequence
 * @property string $current_status
 * @property string $congestion_level
 * @property string $occupancy_status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property TripDescriptor $trip_descriptor
 * @property VehicleDescriptor $vehicle_descriptor
 *
 * @package App\Models
 */
class VehiclePosition extends Model
{
	use SoftDeletes;
	protected $table = 'vehicle_position';

	protected $casts = [
		'trip_descriptor_id' => 'int',
		'vehicle_id' => 'int',
		'lat' => 'float',
		'lon' => 'float',
		'bearing' => 'float',
		'odometer' => 'float',
		'speed' => 'float',
		'current_stop_sequence' => 'int'
	];

	protected $fillable = [
		'trip_descriptor_id',
		'vehicle_id',
		'lat',
		'lon',
		'bearing',
		'odometer',
		'speed',
		'current_stop_sequence',
		'current_status',
		'congestion_level',
		'occupancy_status'
	];

	public function trip_descriptor()
	{
		return $this->belongsTo(TripDescriptor::class);
	}

	public function vehicle_descriptor()
	{
		return $this->belongsTo(VehicleDescriptor::class, 'vehicle_id');
	}

    public function getCreatedAtAttribute($value) {
        return Carbon::createFromTimestamp(strtotime($value))->timezone(config('app.timezone'))->toDateTimeString();
    }

    public function getUpdatedAtAttribute($value) {
        return Carbon::createFromTimestamp(strtotime($value))->timezone(config('app.timezone'))->toDateTimeString();
    }
}
