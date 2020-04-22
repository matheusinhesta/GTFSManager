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
 * Class Zone
 * 
 * @property int $id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property Collection|FareRule[] $fare_rules
 * @property Collection|Stop[] $stops
 *
 * @package App\Models
 */
class Zone extends Model
{
	use SoftDeletes;
	protected $table = 'zones';

	public function fare_rules()
	{
		return $this->hasMany(FareRule::class, 'origin_id');
	}

	public function stops()
	{
		return $this->hasMany(Stop::class);
	}
}
