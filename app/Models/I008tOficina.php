<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class I008tOficina
 * 
 * @property int $co_oficina
 * @property string $tx_nombre
 * @property string $co_zip
 * @property string|null $tx_direccion1
 * @property string|null $tx_direccion2
 * 
 * @property Collection|C012tUsuariosOficina[] $c012t_usuarios_oficinas
 *
 * @package App\Models
 */
class I008tOficina extends Model
{
	protected $table = 'i008t_oficinas';
	protected $primaryKey = 'co_oficina';
	public $timestamps = false;

	protected $fillable = [
		'tx_nombre',
		'co_zip',
		'tx_direccion1',
		'tx_direccion2'
	];

	public function c012t_usuarios_oficinas()
	{
		return $this->hasMany(C012tUsuariosOficina::class, 'co_oficina');
	}
}
