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
 * Class EntitySelector
 * 
 * @property int $id
 * @property int $agency_id
 * @property int $route_id
 * @property int $route_tipe
 * @property int $trip_descriptor_id
 * @property int $stop_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property Agency $agency
 * @property Route $route
 * @property Stop $stop
 * @property TripDescriptor $trip_descriptor
 * @property Collection|Alert[] $alerts
 *
 * @package App\Models
 */
class EntitySelector extends Model
{
	use SoftDeletes;
	protected $table = 'entity_selector';

	protected $casts = [
		'agency_id' => 'int',
		'route_id' => 'int',
		'route_tipe' => 'int',
		'trip_descriptor_id' => 'int',
		'stop_id' => 'int'
	];

	protected $fillable = [
		'agency_id',
		'route_id',
		'route_tipe',
		'trip_descriptor_id',
		'stop_id'
	];

	public function agency()
	{
		return $this->belongsTo(Agency::class);
	}

	public function route()
	{
		return $this->belongsTo(Route::class);
	}

	public function stop()
	{
		return $this->belongsTo(Stop::class);
	}

	public function trip_descriptor()
	{
		return $this->belongsTo(TripDescriptor::class);
	}

	public function alerts()
	{
		return $this->hasMany(Alert::class, 'informed_entity');
	}
}
