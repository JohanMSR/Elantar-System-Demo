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
class I032tVideoCategoria extends Model
{
	protected $table = 'i032t_video_categoria';
	protected $primaryKey = 'co_video';
	public $timestamps = false;

	protected $fillable = [
		'co_categoria',
		'fe_registro',
		'tx_agregado_por'
	];
}
