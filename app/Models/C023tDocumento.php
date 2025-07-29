<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class C023tDocumento
 * 
 * @property int $co_documento
 * @property string|null $tx_url
 * @property string $tx_titulo
 * @property string $tx_descripcion
 * @property Carbon $fe_registro
 * @property string $tx_agregado_por
 *
 * @package App\Models
 */
class C023tDocumento extends Model
{
	protected $table = 'c023t_documentos';
	protected $primaryKey = 'co_documento';
	public $timestamps = false;

	protected $casts = [
		'fe_registro' => 'datetime'
	];

	protected $fillable = [
		'tx_url',
		'tx_titulo',
		'tx_descripcion',
		'fe_registro',
		'tx_agregado_por'
	];
}
