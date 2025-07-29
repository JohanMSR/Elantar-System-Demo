<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class C024tPreguntasFrecuente
 * 
 * @property int $co_pregunta
 * @property string|null $tx_pregunta
 * @property string|null $tx_respuesta
 * @property Carbon|null $fe_registro
 * @property string|null $tx_agregado_por
 *
 * @package App\Models
 */
class C024tPreguntasFrecuente extends Model
{
	protected $table = 'c024t_preguntas_frecuentes';
	protected $primaryKey = 'co_pregunta';
	public $timestamps = false;

	protected $casts = [
		'fe_registro' => 'datetime'
	];

	protected $fillable = [
		'tx_pregunta',
		'tx_respuesta',
		'fe_registro',
		'tx_agregado_por'
	];
}
