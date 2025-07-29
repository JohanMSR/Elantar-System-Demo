<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class I013tTiposAgua
 * 
 * @property int $co_tipo_agua
 * @property string $tx_tipo_agua
 * 
 * @property Collection|C001tAplicacione[] $c001t_aplicaciones
 *
 * @package App\Models
 */
class I013tTiposAgua extends Model
{
	protected $table = 'i013t_tipos_aguas';
	protected $primaryKey = 'co_tipo_agua';
	public $timestamps = false;

	protected $fillable = [
		'tx_tipo_agua'
	];

	public function c001t_aplicaciones()
	{
		return $this->hasMany(C001tAplicacione::class, 'co_tipo_agua');
	}
}
