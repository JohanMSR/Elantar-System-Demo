<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class I009tZipcodeCiudad
 * 
 * @property int $co_zip
 * @property int $co_ciudad
 * 
 * @property I004tZipcode $i004t_zipcode
 * @property I011tCiudade $i011t_ciudade
 *
 * @package App\Models
 */
class I009tZipcodeCiudad extends Model
{
	protected $table = 'i009t_zipcode_ciudad';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'co_zip' => 'int',
		'co_ciudad' => 'int'
	];

	public function i004t_zipcode()
	{
		return $this->belongsTo(I004tZipcode::class, 'co_zip');
	}

	public function i011t_ciudade()
	{
		return $this->belongsTo(I011tCiudade::class, 'co_ciudad');
	}
}
