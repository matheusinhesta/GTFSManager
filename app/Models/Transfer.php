<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Transfer
 * 
 * @property int $id
 * @property int $from_stop_id
 * @property int $to_stop_id
 * @property string $transfer_type
 * @property int $min_transfer_time
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 *
 * @package App\Models
 */
class Transfer extends Model
{
	use SoftDeletes;
	protected $table = 'transfers';

	protected $casts = [
		'from_stop_id' => 'int',
		'to_stop_id' => 'int',
		'min_transfer_time' => 'int'
	];

	protected $fillable = [
		'from_stop_id',
		'to_stop_id',
		'transfer_type',
		'min_transfer_time'
	];
}
