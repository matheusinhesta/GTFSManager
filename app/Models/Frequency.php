<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Frequency
 * 
 * @property int $id
 * @property int $trip_id
 * @property Carbon $start_time
 * @property Carbon $end_time
 * @property int $headway_secs
 * @property bool $exact_times
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class Frequency extends Model
{
	use SoftDeletes;
	protected $table = 'frequencies';

	protected $casts = [
		'trip_id' => 'int',
		'headway_secs' => 'int',
		'exact_times' => 'bool'
	];

	protected $dates = [
		'start_time',
		'end_time'
	];

	protected $fillable = [
		'trip_id',
		'start_time',
		'end_time',
		'headway_secs',
		'exact_times'
	];
}
