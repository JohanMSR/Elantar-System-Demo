<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class I025tTipoCuenta
 * 
 * @property int $co_tipo_cuenta
 * @property string $tx_tipo_cuenta
 * 
 * @property Collection|C040tBasePrecalificacion[] $c040t_base_precalificacions
 *
 * @package App\Models
 */
class I025tTipoCuenta extends Model
{
	protected $table = 'i025t_tipo_cuenta';
	protected $primaryKey = 'co_tipo_cuenta';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'co_tipo_cuenta' => 'int'
	];

	protected $fillable = [
		'tx_tipo_cuenta'
	];

	public function c040t_base_precalificacions()
	{
		return $this->hasMany(C040tBasePrecalificacion::class, 'co_tipo_cuenta');
	}
}
