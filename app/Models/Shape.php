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
 * Class Shape
 * 
 * @property int $id
 * @property string $pt_lat
 * @property string $pt_lon
 * @property int $pt_sequence
 * @property float $dist_traveled
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property Collection|Trip[] $trips
 *
 * @package App\Models
 */
class Shape extends Model
{
	use SoftDeletes;
	protected $table = 'shapes';

	protected $casts = [
		'pt_sequence' => 'int',
		'dist_traveled' => 'float'
	];

	protected $fillable = [
		'pt_lat',
		'pt_lon',
		'pt_sequence',
		'dist_traveled'
	];

	public function trips()
	{
		return $this->hasMany(Trip::class);
	}
}
