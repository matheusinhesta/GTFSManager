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
 * 
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

	public function calendars()
	{
		return $this->hasMany(Calendar::class);
	}

	public function calendar_dates()
	{
		return $this->hasMany(CalendarDate::class);
	}

	public function trips()
	{
		return $this->hasMany(Trip::class);
	}
}
