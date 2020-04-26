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
 * Class Stop
 * 
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $desc
 * @property string $lat
 * @property string $lon
 * @property int $zone_id
 * @property string $url
 * @property string $location_type
 * @property int $parent_station
 * @property string $timezone
 * @property string $wheelchair_boarding
 * @property int $level_id
 * @property string $platform_code
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property Zone $zone
 * @property Collection|EntitySelector[] $entity_selectors
 * @property Collection|StopTime[] $stop_times
 *
 * @package App\Models
 */
class Stop extends Model
{
	use SoftDeletes;
	protected $table = 'stops';

	protected $casts = [
		'zone_id' => 'int',
		'parent_station' => 'int',
		'level_id' => 'int'
	];

	protected $fillable = [
		'code',
		'name',
		'desc',
		'lat',
		'lon',
		'zone_id',
		'url',
		'location_type',
		'parent_station',
		'timezone',
		'wheelchair_boarding',
		'level_id',
		'platform_code'
	];

	public function zone()
	{
		return $this->belongsTo(Zone::class);
	}

	public function entity_selectors()
	{
		return $this->hasMany(EntitySelector::class);
	}

	public function stop_times()
	{
		return $this->hasMany(StopTime::class);
	}
}
