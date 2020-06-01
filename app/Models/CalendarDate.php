<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Traits\EnumManipulation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CalendarDate
 *
 * @property int $id
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
		return $this->belongsTo(Service::class)->select(['id', 'name', 'created_at', 'updated_at']);
	}

    public function getEnumKeyExceptionTypeAttribute(){
        return array_keys(EnumManipulation::getEnumValues('calendar_dates', 'exception_type'), $this->exception_type)[0];
    }

    public function getDateAttribute($value) {
        return Carbon::createFromTimestamp(strtotime($value))->timezone(config('app.timezone'))->toDateString();
    }

    public function getCreatedAtAttribute($value) {
        return Carbon::createFromTimestamp(strtotime($value))->timezone(config('app.timezone'))->toDateTimeString();
    }

    public function getUpdatedAtAttribute($value) {
        return Carbon::createFromTimestamp(strtotime($value))->timezone(config('app.timezone'))->toDateTimeString();
    }
}
