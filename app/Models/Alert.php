<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Alert
 * 
 * @property int $id
 * @property int $informed_entity
 * @property int $active_period
 * @property string $cause
 * @property string $effect
 * @property string $url
 * @property string $header_text
 * @property string $description_text
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $deleted_at
 * 
 * @property TimeRange $time_range
 * @property EntitySelector $entity_selector
 *
 * @package App\Models
 */
class Alert extends Model
{
	use SoftDeletes;
	protected $table = 'alert';

	protected $casts = [
		'informed_entity' => 'int',
		'active_period' => 'int'
	];

	protected $fillable = [
		'informed_entity',
		'active_period',
		'cause',
		'effect',
		'url',
		'header_text',
		'description_text'
	];

	public function time_range()
	{
		return $this->belongsTo(TimeRange::class, 'active_period');
	}

	public function entity_selector()
	{
		return $this->belongsTo(EntitySelector::class, 'informed_entity');
	}
}
