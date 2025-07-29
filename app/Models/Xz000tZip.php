<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Xz000tZip
 * 
 * @property int $co_zip
 * @property string|null $tx_zip
 * @property string|null $typezip
 * @property string|null $primary_city
 * @property string|null $co_primary_city
 * @property string|null $acceptable_cities
 * @property string|null $unacceptable_cities
 * @property string|null $tx_siglas
 * @property string|null $co_estate
 *
 * @package App\Models
 */
class Xz000tZip extends Model
{
	protected $table = 'xz000t_zip';
	protected $primaryKey = 'co_zip';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'co_zip' => 'int'
	];

	protected $fillable = [
		'tx_zip',
		'typezip',
		'primary_city',
		'co_primary_city',
		'acceptable_cities',
		'unacceptable_cities',
		'tx_siglas',
		'co_estate'
	];
}
