<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class I021tTipoNotificacion
 * 
 * @property int $co_tiponoti
 * @property string $tx_nombre
 * @property string $tx_descripcion
 * @property Carbon $fe_registro
 * @property string $tx_agregado_por
 *
 * @package App\Models
 */
class I021tTipoNotificacion extends Model
{
	protected $table = 'i021t_tipo_notificacion';
	protected $primaryKey = 'co_tiponoti';
	public $timestamps = false;

	protected $casts = [
		'co_usuario' => 'integer',
		'co_tiponoti' => 'integer',
		'bo_visto' => 'boolean',
		'fe_registro' => 'datetime',
		'co_aplicacion' => 'integer',
	];

	protected $fillable = [
		'co_usuario',				
		'co_tiponoti',
		'tx_info_general',	
		'bo_visto',
		'fe_registro',		
		'co_aplicacion',
	];
}
