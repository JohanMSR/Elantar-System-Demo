<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class I014tEstatusUsuario
 * 
 * @property int $co_estatus_usuario
 * @property string $tx_estatus
 * 
 * @property Collection|C002tUsuario[] $c002t_usuarios
 *
 * @package App\Models
 */
class I014tEstatusUsuario extends Model
{
	protected $table = 'i014t_estatus_usuarios';
	protected $primaryKey = 'co_estatus_usuario';
	public $timestamps = false;

	protected $fillable = [
		'tx_estatus'
	];

	public function c002t_usuarios()
	{
		return $this->hasMany(C002tUsuario::class, 'co_estatus_usuario');
	}
}
