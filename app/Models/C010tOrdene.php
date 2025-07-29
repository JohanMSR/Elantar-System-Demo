<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class C010tOrdene
 * 
 * @property int $co_orden
 * @property int $co_plomero
 * @property bytea $im_foto1_orden
 * @property bytea $im_foto2_orden
 * @property bytea $im_foto3_orden
 * @property bytea $im_foto4_orden
 * @property Carbon $fe_registro
 * @property string $tx_registrado_por
 * 
 * @property C002tUsuario $c002t_usuario
 * @property Collection|C001tAplicacione[] $c001t_aplicaciones
 *
 * @package App\Models
 */
class C010tOrdene extends Model
{
	protected $table = 'c010t_ordenes';
	protected $primaryKey = 'co_orden';
	public $timestamps = false;

	protected $casts = [
		'co_plomero' => 'int',
		'im_foto1_orden' => 'bytea',
		'im_foto2_orden' => 'bytea',
		'im_foto3_orden' => 'bytea',
		'im_foto4_orden' => 'bytea',
		'fe_registro' => 'datetime'
	];

	protected $fillable = [
		'co_plomero',
		'im_foto1_orden',
		'im_foto2_orden',
		'im_foto3_orden',
		'im_foto4_orden',
		'fe_registro',
		'tx_registrado_por'
	];

	public function c002t_usuario()
	{
		return $this->belongsTo(C002tUsuario::class, 'co_plomero');
	}

	public function c001t_aplicaciones()
	{
		return $this->hasMany(C001tAplicacione::class, 'co_orden');
	}
}
