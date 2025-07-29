<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class I001tEstatusAplicacione
 * 
 * @property int $co_estatus_aplicacion
 * @property string $tx_nombre
 * @property string $tx_descripcion
 * 
 * @property Collection|C001tAplicacione[] $c001t_aplicaciones
 *
 * @package App\Models
 */
class I001tEstatusAplicacione extends Model
{
	protected $table = 'i001t_estatus_aplicaciones';
	protected $primaryKey = 'co_estatus_aplicacion';
	public $timestamps = false;

	protected $fillable = [
		'tx_nombre',
		'tx_descripcion'
	];

	public function c001t_aplicaciones()
	{
		return $this->hasMany(C001tAplicacione::class, 'co_estatus_aplicacion');
	}
}
