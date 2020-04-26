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
 * Class TimeRange
 * 
 * @property int $id
 * @property int $start
 * @property int $end
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property Collection|Alert[] $alerts
 *
 * @package App\Models
 */
class TimeRange extends Model
{
	use SoftDeletes;
	protected $table = 'time_range';

	protected $casts = [
		'start' => 'int',
		'end' => 'int'
	];

	protected $fillable = [
		'start',
		'end'
	];

	public function alerts()
	{
		return $this->hasMany(Alert::class, 'active_period');
	}
}
