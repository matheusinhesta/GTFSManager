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
 * Class Agency
 * 
 * @property int $id
 * @property string $name
 * @property string $url
 * @property string $timezone
 * @property string $lang
 * @property string $phone
 * @property string $fare_url
 * @property string $email
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property Collection|Attribution[] $attributions
 * @property Collection|EntitySelector[] $entity_selectors
 * @property Collection|FareAttribute[] $fare_attributes
 * @property Collection|Route[] $routes
 * @property Collection|User[] $users
 * @property Collection|VehicleDescriptor[] $vehicle_descriptors
 *
 * @package App\Models
 */
class Agency extends Model
{
	use SoftDeletes;
	protected $table = 'agency';

	protected $fillable = [
		'name',
		'url',
		'timezone',
		'lang',
		'phone',
		'fare_url',
		'email'
	];

	public function attributions()
	{
		return $this->hasMany(Attribution::class);
	}

	public function entity_selectors()
	{
		return $this->hasMany(EntitySelector::class);
	}

	public function fare_attributes()
	{
		return $this->hasMany(FareAttribute::class);
	}

	public function routes()
	{
		return $this->hasMany(Route::class);
	}

	public function users()
	{
		return $this->hasMany(User::class);
	}

	public function vehicle_descriptors()
	{
		return $this->hasMany(VehicleDescriptor::class);
	}
}
