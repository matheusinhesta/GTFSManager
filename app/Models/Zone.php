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
 * @property string $name
 * @property int $agency_id
 *
 * @property Agency $agency
 * @property Collection|FareRule[] $fare_rules
 * @property Collection|Stop[] $stops
 *
 * @package App\Models
 */
class Zone extends Model
{
	use SoftDeletes;
	protected $table = 'zones';

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

	public function fare_rules()
	{
		return $this->hasMany(FareRule::class, 'origin_id');
	}

	public function stops()
	{
		return $this->hasMany(Stop::class);
	}

    public function getCreatedAtAttribute($value) {
        return Carbon::createFromTimestamp(strtotime($value))->timezone(config('app.timezone'))->toDateTimeString();
    }

    public function getUpdatedAtAttribute($value) {
        return Carbon::createFromTimestamp(strtotime($value))->timezone(config('app.timezone'))->toDateTimeString();
    }
}
