<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class I011tCiudade
 * 
 * @property int $co_ciudad
 * @property string $tx_nombre
 * @property int $co_estado
 * 
 * @property I010tEstado $i010t_estado
 * @property Collection|I009tZipcodeCiudad[] $i009t_zipcode_ciudads
 *
 * @package App\Models
 */
class I011tCiudade extends Model
{
	protected $table = 'i011t_ciudades';
	protected $primaryKey = 'co_ciudad';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'co_ciudad' => 'int',
		'co_estado' => 'int'
	];

	protected $fillable = [
		'tx_nombre',
		'co_estado'
	];

	public function i010t_estado()
	{
		return $this->belongsTo(I010tEstado::class, 'co_estado');
	}

	public function i009t_zipcode_ciudads()
	{
		return $this->hasMany(I009tZipcodeCiudad::class, 'co_ciudad');
	}
}
