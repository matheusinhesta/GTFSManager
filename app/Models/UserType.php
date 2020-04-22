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
 * Class UserType
 * 
 * @property int $id
 * @property string $description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class UserType extends Model
{
	use SoftDeletes;
	protected $table = 'user_types';

	protected $fillable = [
		'description'
	];

	public function users()
	{
		return $this->hasMany(User::class, 'type_id');
	}
}
