<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class C050tOrdenesAlmacen
 * 
 * @property int $id_ordenes_almacen
 * @property int $id_orden_trabajo
 * @property string $co_producto
 * @property int $precio_producto
 * @property int $cantidad_producto
 * @property string $estatus_orden
 *
 * @package App\Models
 */
class C050tOrdenesAlmacen extends Model
{
	protected $table = 'c050t_ordenes_almacen';
	protected $primaryKey = 'id_ordenes_almacen';
	public $timestamps = false;

	protected $casts = [
		'id_orden_trabajo' => 'int',
		'precio_producto' => 'int',
		'cantidad_producto' => 'int'
	];

	protected $fillable = [
		'id_orden_trabajo',
		'co_producto',
		'precio_producto',
		'cantidad_producto',
		'estatus_orden'
	];
}
