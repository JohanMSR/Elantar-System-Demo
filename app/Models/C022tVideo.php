<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class C022tVideo
 * 
 * @property int $co_video
 * @property string|null $tx_url
 * @property string $tx_titulo
 * @property string $tx_descripcion
 * @property string $tx_autor
 * @property string $tx_categoria
 * @property string $fe_registro
 * @property string $tx_agregado_por
 *
 * @package App\Models
 */
class C022tVideo extends Model
{
	protected $table = 'c022t_videos';
	protected $primaryKey = 'co_video';
	public $timestamps = false;

	protected $fillable = [
		'tx_url',
		'tx_titulo',
		'tx_descripcion',
		'tx_autor',
		'tx_categoria',
		'fe_registro',
		'tx_agregado_por'
	];
}
