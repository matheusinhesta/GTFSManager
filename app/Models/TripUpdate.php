<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TripUpdate
 *
 * @property int $id
 * @property int $trip_descriptor_id
 * @property int $vehicle_id
 * @property int $stop_time_update_id
 * @property Carbon $delay
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property StopTimeUpdate $stop_time_update
 * @property TripDescriptor $trip_descriptor
 *
 * @package App\Models
 */
class TripUpdate extends Model
{
	use SoftDeletes;
	protected $table = 'trip_update';

	protected $casts = [
		'trip_descriptor_id' => 'int',
		'vehicle_id' => 'int',
		'stop_time_update_id' => 'int'
	];

	protected $dates = [
		'delay'
	];

	protected $fillable = [
		'trip_descriptor_id',
		'vehicle_id',
		'stop_time_update_id',
		'delay'
	];

	public function stop_time_update()
	{
		return $this->belongsTo(StopTimeUpdate::class)->select(['id', 'stop_sequence', 'stop_id']);
	}

	public function trip_descriptor()
	{
		return $this->belongsTo(TripDescriptor::class);
	}
}
