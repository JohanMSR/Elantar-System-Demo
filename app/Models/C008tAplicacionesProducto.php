<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class C008tAplicacionesProducto
 * 
 * @property int $co_aplicacion
 * @property int $co_producto
 * @property int $nu_cantidad
 * @property float $nu_precio
 * @property float $nu_costo
 * 
 * @property C001tAplicacione $c001t_aplicacione
 * @property I005tProducto $i005t_producto
 *
 * @package App\Models
 */
class C008tAplicacionesProducto extends Model
{
	protected $table = 'c008t_aplicaciones_productos';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'co_aplicacion' => 'int',
		'co_producto' => 'int',
		'nu_cantidad' => 'int',
		'nu_precio' => 'float',
		'nu_costo' => 'float'
	];

	protected $fillable = [
		'nu_cantidad',
		'nu_precio',
		'nu_costo'
	];

	public function c001t_aplicacione()
	{
		return $this->belongsTo(C001tAplicacione::class, 'co_aplicacion');
	}

	public function i005t_producto()
	{
		return $this->belongsTo(I005tProducto::class, 'co_producto');
	}
}
