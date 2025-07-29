<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class C016tMeta
 * 
 * @property int $co_usuario
 * @property Carbon $fe_año
 * @property Carbon $fe_mes
 * @property int $nu_meta
 * @property int $co_meta
 * @property Carbon|null $fe_registro
 * 
 * @property C002tUsuario $c002t_usuario
 *
 * @package App\Models
 */
class C016tMeta extends Model
{
	protected $table = 'c016t_metas';
	protected $primaryKey = 'co_meta';
	public $timestamps = false;

	protected $casts = [
		'co_usuario' => 'int',
		'fe_año' => 'datetime',
		'fe_mes' => 'datetime',
		'nu_meta' => 'int',
		'fe_registro' => 'datetime'
	];

	protected $fillable = [
		'co_usuario',
		'fe_año',
		'fe_mes',
		'nu_meta',
		'fe_registro'
	];

	public function c002t_usuario()
	{
		return $this->belongsTo(C002tUsuario::class, 'co_usuario');
	}
}
