<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class X001tLog
 * 
 * @property int $co_log
 * @property string $tx_descripcion
 * @property string $tx_tabla
 * @property int $co_usuario
 * @property Carbon $fe_fecha
 *
 * @package App\Models
 */
class X001tLog extends Model
{
	protected $table = 'x001t_log';
	protected $primaryKey = 'co_log';
	public $timestamps = false;

	protected $casts = [
		'co_usuario' => 'int',
		'fe_fecha' => 'datetime'
	];

	protected $fillable = [
		'tx_descripcion',
		'tx_tabla',
		'co_usuario',
		'fe_fecha'
	];
}
