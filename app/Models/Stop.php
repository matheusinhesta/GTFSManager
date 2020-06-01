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
 * Class Stop
 *
 * @property int $id
 * @property int $agency_id
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
 * @property Agency $agency
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
		'agency_id' => 'int',
		'zone_id' => 'int',
		'parent_station' => 'int',
		'level_id' => 'int'
	];

	protected $fillable = [
		'agency_id',
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

	public function agency()
	{
		return $this->belongsTo(Agency::class);
	}

	public function zone()
	{
		return $this->belongsTo(Zone::class)->select(['id', 'name']);
	}

	public function entity_selectors()
	{
		return $this->hasMany(EntitySelector::class);
	}

	public function stop_times()
	{
		return $this->hasMany(StopTime::class);
	}

    public function getEnumKeyLocationTypeAttribute(){
        return array_keys(EnumManipulation::getEnumValues('stops', 'location_type'), $this->location_type)[0];
    }

    public function getCreatedAtAttribute($value) {
        return Carbon::createFromTimestamp(strtotime($value))->timezone(config('app.timezone'))->toDateTimeString();
    }

    public function getUpdatedAtAttribute($value) {
        return Carbon::createFromTimestamp(strtotime($value))->timezone(config('app.timezone'))->toDateTimeString();
    }
}
