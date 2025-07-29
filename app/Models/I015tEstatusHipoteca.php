<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class I015tEstatusHipoteca
 * 
 * @property int $co_estatus
 * @property string|null $tx_estatus
 * 
 * @property Collection|C004tHipoteca[] $c004t_hipotecas
 *
 * @package App\Models
 */
class I015tEstatusHipoteca extends Model
{
	protected $table = 'i015t_estatus_hipoteca';
	protected $primaryKey = 'co_estatus';
	public $timestamps = false;

	protected $fillable = [
		'tx_estatus'
	];

	public function c004t_hipotecas()
	{
		return $this->hasMany(C004tHipoteca::class, 'co_estatus');
	}
}
