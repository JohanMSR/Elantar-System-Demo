<?php

namespace App\Services\Reports;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;
use \stdClass;

class TeamLevel extends Model 
{
    use HasFactory;

   
    public function getData()
    {
        $co_usuario_logueado = Auth()->id();
        $sql = "WITH RECURSIVE companies_tree(co_usuario, co_estatus_usuario, tx_primer_nombre, tx_primer_apellido,  co_usuario_padre, path, nivel, ruta) AS (
            SELECT 
                usr1.co_usuario, co_estatus_usuario, usr1.tx_primer_nombre, usr1.tx_primer_apellido,
                        usr1.co_usuario_padre, ARRAY[usr1.co_usuario] AS path, 1 as nivel, 
                        usr1.tx_primer_nombre || ' ' || usr1.tx_primer_apellido as ruta
                    FROM public.c002t_usuarios as usr1 WHERE co_usuario =  $co_usuario_logueado
            UNION ALL
            SELECT 
                c.co_usuario, c.co_estatus_usuario, c.tx_primer_nombre, COALESCE(c.tx_primer_apellido, ct.tx_primer_apellido),
                c.co_usuario_padre, path  || c.co_usuario, ct.nivel + 1, 
                        ruta || ' / ' || c.tx_primer_nombre || ' ' || c.tx_primer_apellido
                FROM public.c002t_usuarios c
                INNER JOIN companies_tree ct ON ct.co_usuario = c.co_usuario_padre WHERE c.co_estatus_usuario = 1 
            ) 
        SELECT ct1.co_usuario, ct1.tx_primer_nombre, ct1.tx_primer_apellido, ct1.ruta, 
                    COALESCE(ofic.tx_nombre, 'Sin Oficina Asignada') as oficina, rol2.tx_nombre as rol, rol2.co_rol, ct1.co_usuario_padre 
                
            FROM companies_tree AS ct1
            left JOIN c012t_usuarios_oficinas as Uofic ON (Uofic.co_usuario = ct1.co_usuario)
            left JOIN i008t_oficinas          as ofic  ON (Uofic.co_oficina = ofic.co_oficina)
            INNER JOIN c014t_usuarios_roles    as rol1  ON (rol1.co_usuario  = ct1.co_usuario) 
            INNER JOIN i007t_roles             as rol2  ON (rol1.co_rol      = rol2.co_rol)
            ORDER BY ruta";      
        
        $data = DB::select($sql);
        return $data;  
    }

    protected function tree($usuarios, $co_usuario_padre = 0, $nivel = 0) {
        $arbol=[];
        foreach ($usuarios as $item) {
          if ($item->co_usuario_padre == $co_usuario_padre) {
                $arbol[] = [
                    'text' => $item->tx_primer_nombre .' '. $item->tx_primer_apellido,
                    'children' => $this->tree($usuarios, $item->co_usuario, $nivel + 1)
                ];
                
            }
        }
        
        return $arbol;
    }

    public function getDataTree(){
        $users = $this->getData();
        $codigo_padre = $users[0]->co_usuario_padre;        
        $treeUser = $this->tree($users,$codigo_padre);        
        return $treeUser;
    }
}
