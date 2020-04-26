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
 * Class Route
 * 
 * @property int $id
 * @property int $agency_id
 * @property string $short_name
 * @property string $long_name
 * @property string $desc
 * @property string $type
 * @property string $url
 * @property string $color
 * @property string $text_color
 * @property int $short_order
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property Agency $agency
 * @property Collection|Attribution[] $attributions
 * @property Collection|EntitySelector[] $entity_selectors
 * @property Collection|FareRule[] $fare_rules
 * @property Collection|Trip[] $trips
 *
 * @package App\Models
 */
class Route extends Model
{
	use SoftDeletes;
	protected $table = 'routes';

	protected $casts = [
		'agency_id' => 'int',
		'short_order' => 'int'
	];

	protected $fillable = [
		'agency_id',
		'short_name',
		'long_name',
		'desc',
		'type',
		'url',
		'color',
		'text_color',
		'short_order'
	];

	public function agency()
	{
		return $this->belongsTo(Agency::class);
	}

	public function attributions()
	{
		return $this->hasMany(Attribution::class);
	}

	public function entity_selectors()
	{
		return $this->hasMany(EntitySelector::class);
	}

	public function fare_rules()
	{
		return $this->hasMany(FareRule::class);
	}

	public function trips()
	{
		return $this->hasMany(Trip::class);
	}
}
