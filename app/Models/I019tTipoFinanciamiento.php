<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class I019tTipoFinanciamiento
 * 
 * @property int $co_tipo_financiamiento
 * @property int|null $co_financiera
 * @property string $tx_nombre
 * 
 * @property I018tFinanciera|null $i018t_financiera
 *
 * @package App\Models
 */
class I019tTipoFinanciamiento extends Model
{
	protected $table = 'i019t_tipo_financiamiento';
	protected $primaryKey = 'co_tipo_financiamiento';
	public $timestamps = false;

	protected $casts = [
		'co_tipo_financiamiento' => 'int',
	];

	protected $fillable = [		
		'tx_nombre'
	];
	
}
