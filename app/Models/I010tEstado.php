<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class I010tEstado
 * 
 * @property int $co_estado
 * @property string $tx_nombre
 * @property string|null $tx_siglas
 * @property string|null $tx_capital
 * 
 * @property Collection|I011tCiudade[] $i011t_ciudades
 *
 * @package App\Models
 */
class I010tEstado extends Model
{
	protected $table = 'i010t_estados';
	protected $primaryKey = 'co_estado';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'co_estado' => 'int'
	];

	protected $fillable = [
		'tx_nombre',
		'tx_siglas',
		'tx_capital'
	];

	public function i011t_ciudades()
	{
		return $this->hasMany(I011tCiudade::class, 'co_estado');
	}
}
