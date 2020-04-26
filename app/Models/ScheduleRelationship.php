<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ScheduleRelationship
 * 
 * @property int $id
 * @property string $description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class ScheduleRelationship extends Model
{
	use SoftDeletes;
	protected $table = 'schedule_relationship';

	protected $fillable = [
		'description'
	];
}
