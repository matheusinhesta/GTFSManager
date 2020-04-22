<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Calendar
 * 
 * @property int $id
 * @property int $service_id
 * @property bool $monday
 * @property bool $tuesday
 * @property bool $wednesday
 * @property bool $thursday
 * @property bool $friday
 * @property bool $saturday
 * @property bool $sunday
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property Service $service
 *
 * @package App\Models
 */
class Calendar extends Model
{
	use SoftDeletes;
	protected $table = 'calendar';

	protected $casts = [
		'service_id' => 'int',
		'monday' => 'bool',
		'tuesday' => 'bool',
		'wednesday' => 'bool',
		'thursday' => 'bool',
		'friday' => 'bool',
		'saturday' => 'bool',
		'sunday' => 'bool'
	];

	protected $dates = [
		'start_date',
		'end_date'
	];

	protected $fillable = [
		'service_id',
		'monday',
		'tuesday',
		'wednesday',
		'thursday',
		'friday',
		'saturday',
		'sunday',
		'start_date',
		'end_date'
	];

	public function service()
	{
		return $this->belongsTo(Service::class);
	}
}
