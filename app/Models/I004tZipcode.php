<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class I004tZipcode
 * 
 * @property int $co_zip
 * @property string|null $tx_zip
 * @property string|null $typezip
 * @property string|null $primary_city
 * @property string|null $co_primary_city
 * @property string|null $acceptable_cities
 * @property string|null $unacceptable_cities
 * @property string|null $tx_siglas
 * @property string|null $co_estate
 * 
 * @property Collection|C003tCliente[] $c003t_clientes
 *
 * @package App\Models
 */
class I004tZipcode extends Model
{
	protected $table = 'i004t_zipcodes';
	protected $primaryKey = 'co_zip';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'co_zip' => 'int'
	];

	protected $fillable = [
		'tx_zip',
		'typezip',
		'primary_city',
		'co_primary_city',
		'acceptable_cities',
		'unacceptable_cities',
		'tx_siglas',
		'co_estate'
	];

	public function c003t_clientes()
	{
		return $this->hasMany(C003tCliente::class, 'co_zip_trabajo');
	}
}
