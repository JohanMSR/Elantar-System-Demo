<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class C005tBanco
 * 
 * @property int $co_banco
 * @property string $tx_nombre
 * 
 * @property Collection|C015tBancosAplicacionesCliente[] $c015t_bancos_aplicaciones_clientes
 *
 * @package App\Models
 */
class C005tBanco extends Model
{
	protected $table = 'c005t_bancos';
	protected $primaryKey = 'co_banco';
	public $timestamps = false;

	protected $fillable = [
		'tx_nombre'
	];

	public function c015t_bancos_aplicaciones_clientes()
	{
		return $this->hasMany(C015tBancosAplicacionesCliente::class, 'co_banco');
	}
}
