<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class I020tTipoFuenteCliente
 * 
 * @property int $co_fuente
 * @property string $tx_nombre
 * @property string $tx_descripcion
 * @property Carbon $fe_registro
 * @property string $tx_agregado_por
 * 
 * @property Collection|C003tCliente[] $c003t_clientes
 *
 * @package App\Models
 */
class I020tTipoFuenteCliente extends Model
{
	protected $table = 'i020t_tipo_fuente_cliente';
	protected $primaryKey = 'co_fuente';
	public $timestamps = false;

	protected $casts = [
		'fe_registro' => 'datetime'
	];

	protected $fillable = [
		'tx_nombre',
		'tx_descripcion',
		'fe_registro',
		'tx_agregado_por'
	];

	public function c003t_clientes()
	{
		return $this->hasMany(C003tCliente::class, 'co_fuente');
	}
}
