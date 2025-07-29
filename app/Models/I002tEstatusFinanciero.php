<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class I002tEstatusFinanciero
 * 
 * @property int $co_estatus_financiero
 * @property string $tx_nombre
 * @property string $tx_descripcion
 * @property bool $bo_tipo_estatus
 * 
 * @property Collection|C001tAplicacione[] $c001t_aplicaciones
 *
 * @package App\Models
 */
class I002tEstatusFinanciero extends Model
{
	protected $table = 'i002t_estatus_financieros';
	protected $primaryKey = 'co_estatus_financiero';
	public $timestamps = false;

	protected $casts = [
		'bo_tipo_estatus' => 'bool'
	];

	protected $fillable = [
		'tx_nombre',
		'tx_descripcion',
		'bo_tipo_estatus'
	];

	public function c001t_aplicaciones()
	{
		return $this->hasMany(C001tAplicacione::class, 'co_estatus_financiero');
	}
}
