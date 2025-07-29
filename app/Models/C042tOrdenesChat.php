<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class C042tOrdenesChat
 * 
 * @property int $co_chat_ord
 * @property int $co_orden
 * @property int|null $co_usuario
 * @property string $tx_comentario
 * @property Carbon $fe_comentario
 * @property time without time zone $ho_comentario
 * @property int|null $co_tiponoti
 * @property int|null $reply_to
 * 
 * @property C038tOrdenes $c038t_ordene
 *
 * @package App\Models
 */
class C042tOrdenesChat extends Model
{
	protected $table = 'c042t_ordenes_chats';
	protected $primaryKey = 'co_chat_ord';
	public $timestamps = false;

	protected $casts = [
		'co_orden' => 'int',
		'co_usuario' => 'int',
		'fe_comentario' => 'datetime',
		'ho_comentario' => 'datetime',
		'co_tiponoti' => 'int',
		'reply_to' => 'int'
	];

	protected $fillable = [
		'co_orden',
		'co_usuario',
		'tx_comentario',
		'fe_comentario',
		'ho_comentario',
		'co_tiponoti',
		'reply_to'
	];

	public function ordenes()
	{
		return $this->belongsTo(C038tOrdenes::class, 'co_orden');
	}
	public function user()
	{
		return $this->belongsTo(User::class, 'co_usuario', 'id');
	}

	public function usuariosChat()
	{
		return $this->belongsTo(C044tUsuariosChatInstalaciones::class, 'co_usuario', 'co_usuario');
	}
	

	public function repliedMessage()
	{
    	return $this->belongsTo(C042tOrdenesChat::class, 'reply_to', 'co_chat_ord');		
	}
}
