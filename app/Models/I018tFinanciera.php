<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class I018tFinanciera
 * 
 * @property int $co_financiera
 * @property string $tx_nombre
 * @property string|null $tx_siglas
 * @property string|null $tx_comentario
 * 
 * @property Collection|C001tAplicacione[] $c001t_aplicaciones
 * @property Collection|I019tTipoFinanciamiento[] $i019t_tipo_financiamientos
 *
 * @package App\Models
 */
class I018tFinanciera extends Model
{
	protected $table = 'i018t_financiera';
	protected $primaryKey = 'co_financiera';
	public $timestamps = false;

	protected $fillable = [
		'tx_nombre',
		'tx_siglas',
		'tx_comentario'
	];

	public function c001t_aplicaciones()
	{
		return $this->hasMany(C001tAplicacione::class, 'co_financiera');
	}
	
}
