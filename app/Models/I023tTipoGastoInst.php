<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class I023tTipoGastoInst
 * 
 * @property int $co_tipo_gasto_inst
 * @property string $tx_nombre
 * 
 * @property Collection|C039tOrdenGasto[] $c039t_orden_gastos
 *
 * @package App\Models
 */
class I023tTipoGastoInst extends Model
{
	protected $table = 'i023t_tipo_gasto_inst';
	protected $primaryKey = 'co_tipo_gasto_inst';
	public $timestamps = false;

	protected $fillable = [
		'tx_nombre'
	];

	public function c039t_orden_gastos()
	{
		return $this->hasMany(C039tOrdenGasto::class, 'co_tipo_gasto_inst');
	}
}
