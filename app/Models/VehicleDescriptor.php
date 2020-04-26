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
 * Class VehicleDescriptor
 * 
 * @property int $id
 * @property int $agency_id
 * @property string $label
 * @property string $license_plate
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property Agency $agency
 * @property Collection|VehiclePosition[] $vehicle_positions
 *
 * @package App\Models
 */
class VehicleDescriptor extends Model
{
	use SoftDeletes;
	protected $table = 'vehicle_descriptor';

	protected $casts = [
		'agency_id' => 'int'
	];

	protected $fillable = [
		'agency_id',
		'label',
		'license_plate'
	];

	public function agency()
	{
		return $this->belongsTo(Agency::class);
	}

	public function vehicle_positions()
	{
		return $this->hasMany(VehiclePosition::class, 'vehicle_id');
	}
}
