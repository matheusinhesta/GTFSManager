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
 * Class Service
 *
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 * @property string $name
 * @property int $agency_id
 *
 * @property Agency $agency
 * @property Collection|Calendar[] $calendars
 * @property Collection|CalendarDate[] $calendar_dates
 * @property Collection|Trip[] $trips
 *
 * @package App\Models
 */
class Service extends Model
{
	use SoftDeletes;
	protected $table = 'services';

	protected $casts = [
		'agency_id' => 'int'
	];

	protected $fillable = [
		'name',
		'agency_id'
	];

	public function agency()
	{
		return $this->belongsTo(Agency::class);
	}

	public function calendars()
	{
		return $this->hasMany(Calendar::class);
	}

	public function calendar_dates()
	{
		return $this->hasMany(CalendarDate::class)->select(['id', 'date', 'exception_type', 'created_at', 'updated_at']);
	}

	public function trips()
	{
		return $this->hasMany(Trip::class);
	}

    public function getCreatedAtAttribute($value) {
        return Carbon::createFromTimestamp(strtotime($value))->timezone(config('app.timezone'))->toDateTimeString();
    }

    public function getUpdatedAtAttribute($value) {
        return Carbon::createFromTimestamp(strtotime($value))->timezone(config('app.timezone'))->toDateTimeString();
    }
}
