<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CalendarDate
 * 
 * @property int $id
 * @property int $service_id
 * @property Carbon $date
 * @property string $exception_type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 * @property int $agency_id
 * 
 * @property Agency $agency
 * @property Service $service
 *
 * @package App\Models
 */
class CalendarDate extends Model
{
	use SoftDeletes;
	protected $table = 'calendar_dates';

	protected $casts = [
		'service_id' => 'int',
		'agency_id' => 'int'
	];

	protected $dates = [
		'date'
	];

	protected $fillable = [
		'service_id',
		'date',
		'exception_type',
		'agency_id'
	];

	public function agency()
	{
		return $this->belongsTo(Agency::class);
	}

	public function service()
	{
		return $this->belongsTo(Service::class);
	}
}
