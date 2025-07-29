<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class I012tIdioma
 * 
 * @property int $co_idioma
 * @property string $tx_idioma
 * 
 * @property Collection|C001tAplicacione[] $c001t_aplicaciones
 *
 * @package App\Models
 */
class I012tIdioma extends Model
{
	protected $table = 'i012t_idiomas';
	protected $primaryKey = 'co_idioma';
	public $timestamps = false;

	protected $fillable = [
		'tx_idioma'
	];

	public function c001t_aplicaciones()
	{
		return $this->hasMany(C001tAplicacione::class, 'co_idioma');
	}
}
