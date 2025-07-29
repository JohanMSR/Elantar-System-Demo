<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class C017tTipoProducto
 * 
 * @property int $co_tipo_producto
 * @property string|null $tx_tipo_producto
 * @property string|null $tx_registrado_por
 * @property Carbon|null $fe_registro
 * 
 * @property Collection|I005tProducto[] $i005t_productos
 *
 * @package App\Models
 */
class C017tTipoProducto extends Model
{
	protected $table = 'c017t_tipo_producto';
	protected $primaryKey = 'co_tipo_producto';
	public $timestamps = false;

	protected $casts = [
		'fe_registro' => 'datetime'
	];

	protected $fillable = [
		'tx_tipo_producto',
		'tx_registrado_por',
		'fe_registro'
	];

	public function i005t_productos()
	{
		return $this->hasMany(I005tProducto::class, 'co_tipo_producto');
	}
}
