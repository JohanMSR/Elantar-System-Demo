<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class C015tBancosAplicacionesCliente
 * 
 * @property int $co_aplicacion
 * @property int $co_banco
 * @property int $co_cliente
 * @property string $nu_ruta
 * @property int $nu_cuenta
 * 
 * @property C001tAplicacione $c001t_aplicacione
 * @property C005tBanco $c005t_banco
 * @property C003tCliente $c003t_cliente
 *
 * @package App\Models
 */
class C015tBancosAplicacionesCliente extends Model
{
	protected $table = 'c015t_bancos_aplicaciones_clientes';
	protected $primaryKey = 'co_aplicacion';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'co_aplicacion' => 'int',
		'co_banco' => 'int',
		'co_cliente' => 'int',
		'nu_cuenta' => 'int'
	];

	protected $fillable = [
		'co_banco',
		'co_cliente',
		'nu_ruta',
		'nu_cuenta'
	];

	public function c001t_aplicacione()
	{
		return $this->belongsTo(C001tAplicacione::class, 'co_aplicacion');
	}

	public function c005t_banco()
	{
		return $this->belongsTo(C005tBanco::class, 'co_banco');
	}

	public function c003t_cliente()
	{
		return $this->belongsTo(C003tCliente::class, 'co_cliente');
	}
}
