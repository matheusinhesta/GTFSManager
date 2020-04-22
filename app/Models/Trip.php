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
		return $this->belongsTo(Route::class);
	}

	public function service()
	{
		return $this->belongsTo(Service::class);
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
}
