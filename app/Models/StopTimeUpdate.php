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
 * Class StopTimeUpdate
 *
 * @property int $id
 * @property int $stop_sequence
 * @property int $stop_id
 * @property int $arrival_time
 * @property int $departure_time
 * @property int $schedule_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @property Collection|TripUpdate[] $trip_updates
 *
 * @package App\Models
 */
class StopTimeUpdate extends Model
{
	use SoftDeletes;
	protected $table = 'stop_time_update';

	protected $casts = [
		'stop_sequence' => 'int',
		'stop_id' => 'int',
		'arrival_time' => 'int',
		'departure_time' => 'int',
		'schedule_id' => 'int'
	];

	protected $fillable = [
		'stop_sequence',
		'stop_id',
		'arrival_time',
		'departure_time',
		'schedule_id'
	];

	public function trip_updates()
	{
		return $this->hasMany(TripUpdate::class);
	}

    public function stop()
    {
        return $this->belongsTo(Stop::class);
    }
}
