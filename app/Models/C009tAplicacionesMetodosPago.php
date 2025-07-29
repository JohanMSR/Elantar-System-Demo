<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class C009tAplicacionesMetodosPago
 * 
 * @property int $co_pago
 * @property int $co_aplicacion
 * @property int $co_metodo_pago
 * @property int $nu_monto_pagado
 * @property Carbon $fe_pago
 * @property Carbon $fe_registro
 * @property string $tx_referencia
 * 
 * @property C001tAplicacione $c001t_aplicacione
 * @property I006tMetodosPago $i006t_metodos_pago
 *
 * @package App\Models
 */
class C009tAplicacionesMetodosPago extends Model
{
	protected $table = 'c009t_aplicaciones_metodos_pagos';
	protected $primaryKey = 'co_pago';
	public $timestamps = false;

	protected $casts = [
		'co_aplicacion' => 'int',
		'co_metodo_pago' => 'int',
		'nu_monto_pagado' => 'int',
		'fe_pago' => 'datetime',
		'fe_registro' => 'datetime'
	];

	protected $fillable = [
		'co_aplicacion',
		'co_metodo_pago',
		'nu_monto_pagado',
		'fe_pago',
		'fe_registro',
		'tx_referencia'
	];

	public function c001t_aplicacione()
	{
		return $this->belongsTo(C001tAplicacione::class, 'co_aplicacion');
	}

	public function i006t_metodos_pago()
	{
		return $this->belongsTo(I006tMetodosPago::class, 'co_metodo_pago');
	}
}
