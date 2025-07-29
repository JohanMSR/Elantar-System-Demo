<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class C044tUsuariosChatInstalacione
 * 
 * @property int|null $co_orden
 * @property int|null $co_usuario
 * @property int|null $co_rol
 * @property string|null $tx_perfil
 * @property int $co_chat
 * 
 * @property C002tUsuario|null $c002t_usuario
 * @property I007tRole|null $i007t_role
 *
 * @package App\Models
 */
class C044tUsuariosChatInstalaciones extends Model
{
	protected $table = 'c044t_usuarios_chat_instalaciones';
	protected $primaryKey = 'co_chat';
	public $timestamps = false;

	protected $casts = [
		'co_orden' => 'int',
		'co_usuario' => 'int',
		'co_rol' => 'int'
	];

	protected $fillable = [
		'co_orden',
		'co_usuario',
		'co_rol',
		'tx_perfil'
	];

	public function ordenes()
	{
		return $this->belongsTo(C038tOrdenes::class, 'co_orden');
		//return $this->belongsTo(C001tAplicacione::class, 'co_aplicacion');
	}

	public function c002t_usuario()
	{
		return $this->belongsTo(C002tUsuario::class, 'co_usuario');
	}

	public function i007t_role()
	{
		return $this->belongsTo(I007tRole::class, 'co_rol');
	}
}
