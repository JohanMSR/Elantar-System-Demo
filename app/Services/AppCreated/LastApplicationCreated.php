<?php

namespace App\Services\AppCreated;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;



class LastApplicationCreated extends Model 
{
    use HasFactory;

    protected $co_cliente;
    /***
    public function __construct($co_cliente){
        $this->co_cliente = $co_cliente;
    }
     */    


    public function getAplication($co_cliente){
        $sql = "SELECT co_aplicacion, fe_ultima_mod 
            FROM c001t_aplicaciones 
            WHERE co_cliente = $co_cliente
            ORDER BY fe_ultima_mod DESC
            LIMIT 1";
        $datos = DB::select($sql);
        if(count($datos)>0){
            $datos = $datos[0];
        }    
        return $datos;
    }    
    
}
