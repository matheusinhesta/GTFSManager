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
 * Class User
 * 
 * @property int $id
 * @property int $type_id
 * @property int $agency_id
 * @property string $name
 * @property string $email
 * @property Carbon $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property Agency $agency
 * @property UserType $user_type
 * @property Collection|TripDescriptor[] $trip_descriptors
 *
 * @package App\Models
 */
class User extends Model
{
	use SoftDeletes;
	protected $table = 'users';

	protected $casts = [
		'type_id' => 'int',
		'agency_id' => 'int'
	];

	protected $dates = [
		'email_verified_at'
	];

	protected $hidden = [
		'password',
		'remember_token'
	];

	protected $fillable = [
		'type_id',
		'agency_id',
		'name',
		'email',
		'email_verified_at',
		'password',
		'remember_token'
	];

	public function agency()
	{
		return $this->belongsTo(Agency::class);
	}

	public function user_type()
	{
		return $this->belongsTo(UserType::class, 'type_id');
	}

	public function trip_descriptors()
	{
		return $this->hasMany(TripDescriptor::class);
	}
}