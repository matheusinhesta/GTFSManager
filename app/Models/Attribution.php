<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Attribution
 * 
 * @property int $id
 * @property int $agency_id
 * @property int $route_id
 * @property int $trip_id
 * @property string $organization_name
 * @property bool $is_producer
 * @property bool $is_operator
 * @property bool $is_authority
 * @property string $url
 * @property string $email
 * @property string $phone
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property Agency $agency
 * @property Route $route
 * @property Trip $trip
 *
 * @package App\Models
 */
class Attribution extends Model
{
	use SoftDeletes;
	protected $table = 'attributions';

	protected $casts = [
		'agency_id' => 'int',
		'route_id' => 'int',
		'trip_id' => 'int',
		'is_producer' => 'bool',
		'is_operator' => 'bool',
		'is_authority' => 'bool'
	];

	protected $fillable = [
		'agency_id',
		'route_id',
		'trip_id',
		'organization_name',
		'is_producer',
		'is_operator',
		'is_authority',
		'url',
		'email',
		'phone'
	];

	public function agency()
	{
		return $this->belongsTo(Agency::class);
	}

	public function route()
	{
		return $this->belongsTo(Route::class);
	}

	public function trip()
	{
		return $this->belongsTo(Trip::class);
	}
}
