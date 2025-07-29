<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class C036tUsuariosNotificacionHi
 * 
 * @property int $co_usrnotificahis
 * @property int|null $co_usuario
 * @property int|null $co_tiponoti
 * @property string|null $tx_info_general
 * @property bool|null $bo_visto
 * @property Carbon|null $fe_registro
 * @property int|null $co_aplicacion
 *
 * @package App\Models
 */
class C036tUsuariosNotificacionHi extends Model
{
	protected $table = 'c036t_usuarios_notificacion_his';
	protected $primaryKey = 'co_usrnotificahis';
	public $timestamps = false;

	protected $casts = [
		'co_usuario' => 'int',
		'co_tiponoti' => 'int',
		'bo_visto' => 'bool',
		'fe_registro' => 'datetime',
		'co_aplicacion' => 'int',
		'co_usuario_log' => 'int'
	];

	protected $fillable = [
		'co_usuario',
		'co_tiponoti',
		'tx_info_general',
		'bo_visto',
		'fe_registro',
		'co_aplicacion',
		'co_usuario_log'
	];
}
