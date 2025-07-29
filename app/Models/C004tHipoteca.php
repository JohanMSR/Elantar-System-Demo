<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class C004tHipoteca
 * 
 * @property int $co_hipoteca
 * @property int $co_estatus
 * @property string $tx_compania
 * @property int $nu_monto
 * @property int|null $nu_tiempo
 * 
 * @property I015tEstatusHipoteca $i015t_estatus_hipoteca
 * @property Collection|C001tAplicacione[] $c001t_aplicaciones
 *
 * @package App\Models
 */
class C004tHipoteca extends Model
{
	protected $table = 'c004t_hipoteca';
	protected $primaryKey = 'co_hipoteca';
	public $timestamps = false;

	protected $casts = [
		'co_estatus' => 'int',
		'nu_monto' => 'int',
		'nu_tiempo' => 'int'
	];

	protected $fillable = [
		'co_estatus',
		'tx_compania',
		'nu_monto',
		'nu_tiempo'
	];

	public function i015t_estatus_hipoteca()
	{
		return $this->belongsTo(I015tEstatusHipoteca::class, 'co_estatus');
	}

	public function c001t_aplicaciones()
	{
		return $this->hasMany(C001tAplicacione::class, 'co_hipoteca');
	}
}
