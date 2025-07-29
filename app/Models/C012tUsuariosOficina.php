<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class C012tUsuariosOficina
 * 
 * @property int $co_usuario
 * @property int $co_oficina
 * 
 * @property C002tUsuario $c002t_usuario
 * @property I008tOficina $i008t_oficina
 *
 * @package App\Models
 */
class C012tUsuariosOficina extends Model
{
	protected $table = 'c012t_usuarios_oficinas';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'co_usuario' => 'int',
		'co_oficina' => 'int'
	];

	public function c002t_usuario()
	{
		return $this->belongsTo(C002tUsuario::class, 'co_usuario');
	}

	public function i008t_oficina()
	{
		return $this->belongsTo(I008tOficina::class, 'co_oficina');
	}
}
