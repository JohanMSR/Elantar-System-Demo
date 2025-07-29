<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class I006tMetodosPago
 * 
 * @property int $co_metodo_pago
 * @property string $tx_nombre
 * @property string $tx_descripcion
 * 
 * @property Collection|C009tAplicacionesMetodosPago[] $c009t_aplicaciones_metodos_pagos
 *
 * @package App\Models
 */
class I006tMetodosPago extends Model
{
	protected $table = 'i006t_metodos_pagos';
	protected $primaryKey = 'co_metodo_pago';
	public $timestamps = false;

	protected $fillable = [
		'tx_nombre',
		'tx_descripcion'
	];

	public function c009t_aplicaciones_metodos_pagos()
	{
		return $this->hasMany(C009tAplicacionesMetodosPago::class, 'co_metodo_pago');
	}
}
