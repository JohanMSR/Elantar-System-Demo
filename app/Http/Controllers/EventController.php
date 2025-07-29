<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use Illuminate\Support\Collection;
class EventController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('IsActive');
    }
    //Ver detalles
    public function index(Request $request)
    {
        $sql = "SELECT
    e.co_evento AS codigo_general,
    B1.tx_url_adj,
    B1.tipo_adjunto,
    B1.in_orden_adj,
    e.tx_titulo,
    e.tx_descripcion,
    e.fe_registro,
    e.bo_prioridad
FROM c020t_eventos e
INNER JOIN c032t_adjunto_evento as B1 on  (B1.co_evento=e.co_evento)
ORDER BY e.in_orden_evento DESC, B1.in_orden_adj ASC";

        $eventos = DB::select($sql);

        $sql2 = "SELECT
            e.co_evento AS codigo_general,
            e.tx_titulo,
            e.tx_descripcion,
            e.fe_registro,
            e.bo_prioridad
        FROM c020t_eventos e
        ORDER BY e.in_orden_evento DESC";

        $eventos2 = DB::select($sql2);

        $eventosCollection = new Collection($eventos2);

        $eventosCollection = $eventosCollection->map(function ($evento) {
            $adjuntos = DB::table('c032t_adjunto_evento')
                ->select('tx_url_adj', 'tipo_adjunto') // Select both columns
                ->where('co_evento', $evento->codigo_general)
                ->orderBy('in_orden_adj', 'asc')
                ->get();

            $evento->adjuntos = $adjuntos;
            return $evento;
        });

        return view('dashboard.events.events')->with('eventos2', $eventos2)->with('eventos', $eventos);
    }
    
    /**
     * Obtener los adjuntos de un evento específico
     *
     * @param int $id ID del evento
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAdjuntos($id)
    {
        $adjuntos = DB::table('c032t_adjunto_evento')
            ->select('tx_url_adj', 'tipo_adjunto')
            ->where('co_evento', $id)
            ->orderBy('in_orden_adj', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'adjuntos' => $adjuntos
        ]);
    }
}
