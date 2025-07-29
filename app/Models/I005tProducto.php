<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class I005tProducto
 * 
 * @property int $co_producto
 * @property string $tx_codigo
 * @property string $tx_nombre
 * @property string $tx_descripcion
 * @property int $nu_precio
 * @property int $nu_costo
 * @property int $co_tipo_producto
 * 
 * @property C017tTipoProducto $c017t_tipo_producto
 * @property Collection|C008tAplicacionesProducto[] $c008t_aplicaciones_productos
 *
 * @package App\Models
 */
class I005tProducto extends Model
{
	protected $table = 'i005t_productos';
	protected $primaryKey = 'co_producto';
	public $timestamps = false;

	protected $casts = [
		'nu_precio' => 'int',
		'nu_costo' => 'int',
		'co_tipo_producto' => 'int'
	];

	protected $fillable = [
		'tx_codigo',
		'tx_nombre',
		'tx_descripcion',
		'nu_precio',
		'nu_costo',
		'co_tipo_producto'
	];

	public function c017t_tipo_producto()
	{
		return $this->belongsTo(C017tTipoProducto::class, 'co_tipo_producto');
	}

	public function c008t_aplicaciones_productos()
	{
		return $this->hasMany(C008tAplicacionesProducto::class, 'co_producto');
	}
}
