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
 * Class TripDescriptor
 *
 * @property int $id
 * @property int $user_id
 * @property int $trip_id
 * @property int $route_id
 * @property string $trip_status
 * @property int $direction_id
 * @property string $start_time
 * @property Carbon $start_date
 * @property string $end_time
 * @property Carbon $end_date
 * @property int $schedule_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property User $user
 * @property Collection|EntitySelector[] $entity_selectors
 * @property Collection|TripUpdate[] $trip_updates
 * @property Collection|VehiclePosition[] $vehicle_positions
 *
 * @package App\Models
 */
class TripDescriptor extends Model
{
	use SoftDeletes;
	protected $table = 'trip_descriptor';

	protected $appends = [
	    'current_position',
        'nearby_stop',
        'next_stop'
    ];

	protected $casts = [
		'user_id' => 'int',
		'trip_id' => 'int',
		'route_id' => 'int',
		'direction_id' => 'int',
		'schedule_id' => 'int'
	];

//	protected $dates = [
//		'start_date',
//		'end_date'
//	];

	protected $fillable = [
		'user_id',
		'trip_id',
		'route_id',
		'trip_status',
		'direction_id',
		'start_time',
		'start_date',
		'end_time',
		'end_date',
		'schedule_id'
	];

	public function user()
	{
		return $this->belongsTo(User::class)->select(['id', 'name', 'type_id']);
	}

	public function entity_selectors()
	{
		return $this->hasMany(EntitySelector::class);
	}

	public function trip_updates()
	{
		return $this->hasMany(TripUpdate::class)->select(['id', 'trip_descriptor_id', 'vehicle_id', 'stop_time_update_id']);
	}

	public function vehicle_positions()
	{
		return $this->hasMany(VehiclePosition::class)->select(['id', 'trip_descriptor_id', 'lat', 'lon', 'created_at'])->orderByDesc('id');
	}

    public function route()
    {
        return $this->belongsTo(Route::class)->select(['id', 'short_name', 'long_name']);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class)->select(['id', 'headsign', 'short_name', 'direction_id']);
    }

    public function getCurrentPositionAttribute(){
	    return (!empty($this->vehicle_positions->first()) ? $this->vehicle_positions()->select(['lat', 'lon', 'created_at'])->get()->first() : null);
    }

    public function getNearbyStopAttribute(){
        return (!empty($this->trip_updates->first()) ? $this->trip_updates->first()->stop_time_update->stop()->get(['id', 'name', 'lat', 'lon']) : null);
    }

    public function getNextStopAttribute(){
	    return (!empty($this->trip_updates->first()) ? (!empty(StopTime::where('trip_id', $this->trip_id)->where('stop_sequence', $this->trip_updates->first()->stop_time_update->stop_sequence + 1)->first())) ? StopTime::where('trip_id', $this->trip_id)->where('stop_sequence', $this->trip_updates->first()->stop_time_update->stop_sequence + 1)->first()->stop()->select(['id', 'name', 'lat', 'lon'])->get() : 'End Line' : null);
    }

    public function getCreatedAtAttribute($value) {
        return Carbon::createFromTimestamp(strtotime($value))->timezone(config('app.timezone'))->toDateTimeString();
    }

    public function getUpdatedAtAttribute($value) {
        return Carbon::createFromTimestamp(strtotime($value))->timezone(config('app.timezone'))->toDateTimeString();
    }
}
