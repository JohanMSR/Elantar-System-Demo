<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class C013tTipoUsuario
 * 
 * @property int $co_tipo_usuario
 * @property string|null $tx_tipo_usuario
 * 
 * @property Collection|C002tUsuario[] $c002t_usuarios
 *
 * @package App\Models
 */
class C013tTipoUsuario extends Model
{
	protected $table = 'c013t_tipo_usuarios';
	protected $primaryKey = 'co_tipo_usuario';
	public $timestamps = false;

	protected $fillable = [
		'tx_tipo_usuario'
	];

	public function c002t_usuarios()
	{
		return $this->hasMany(C002tUsuario::class, 'co_tipo_usuario');
	}
}
