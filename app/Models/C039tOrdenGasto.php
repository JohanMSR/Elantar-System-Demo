<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class C039tOrdenGasto
 * 
 * @property int $co_tipo_gasto_inst
 * @property int $co_orden
 * @property float $nu_monto_gasto
 * 
 * @property I023tTipoGastoInst $i023t_tipo_gasto_inst
 * @property C038tOrdene $c038t_ordene
 *
 * @package App\Models
 */
class C039tOrdenGasto extends Model
{
	protected $table = 'c039t_orden_gasto';
	public $timestamps = false;

	protected $casts = [
		'co_orden' => 'int',
		'nu_monto_gasto' => 'float'
	];

	protected $fillable = [
		'nu_monto_gasto'
	];

	public function tipo_gasto_inst()
	{
		return $this->belongsTo(I023tTipoGastoInst::class, 'co_tipo_gasto_inst');
	}

	public function c038t_ordene()
	{
		return $this->belongsTo(C038tOrdenes::class, 'co_orden');
	}
}
