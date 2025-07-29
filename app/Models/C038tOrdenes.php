<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class C038tOrdenes
 * 
 * @property int $co_orden
 * @property int $co_plomero
 * @property int|null $co_aplicacion
 * @property int|null $co_estatus_orden
 * @property bool|null $bo_accion
 * @property int|null $co_orden_padre
 * @property int|null $co_manager
 * @property Carbon $fe_registro
 * @property string $co_usuario_logueado
 * 
 * @property C002tUsuario $c002t_usuario
 * @property Collection|C042tOrdenesChat[] $c042t_ordenes_chats
 * @property Collection|C044tUsuariosChatInstalacione[] $c044t_usuarios_chat_instalaciones
 *
 * @package App\Models
 */
class C038tOrdenes extends Model
{
	protected $table = 'c038t_ordenes';
	protected $primaryKey = 'co_orden';
	public $timestamps = false;

	protected $casts = [
		'co_plomero' => 'int',
		'co_aplicacion' => 'int',
		'co_estatus_orden' => 'int',
		'bo_accion' => 'bool',
		'co_orden_padre' => 'int',
		'co_manager' => 'int',
		'fe_registro' => 'datetime'
	];

	protected $fillable = [
		'co_plomero',
		'co_aplicacion',
		'co_estatus_orden',
		'bo_accion',
		'co_orden_padre',
		'co_manager',
		'fe_registro',
		'co_usuario_logueado'
	];

	public function usuario()
	{
		return $this->belongsTo(C002tUsuario::class, 'co_usuario');
	}

	public function ordenesChats()
	{
		return $this->hasMany(C042tOrdenesChat::class, 'co_orden');
	}

	public function usuariosChat()
	{
		return $this->hasMany(C044tUsuariosChatInstalaciones::class, 'co_orden');
	}
}
