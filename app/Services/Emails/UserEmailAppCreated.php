<?php

namespace App\Services\Emails;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Jobs\EmailAppCreated;
use App\Services\AppCreated\LastApplicationCreated;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class UserEmailAppCreated extends Model 
{
    use HasFactory;

    protected $co_cliente;
    protected $pdfPath;

    public function __construct($co_cliente, $pdfPath=null){
        $this->co_cliente = $co_cliente;
        $this->pdfPath = $pdfPath;
    }    
   
    public function getData($co_aplicacion)
    {
        $sql ="SELECT
                a.co_aplicacion,
                a.fe_instalacion,
                c1.tx_primer_nombre,
                c1.tx_primer_apellido,
                u1.tx_primer_nombre || ' ' || u1.tx_primer_apellido as tx_nombre_analista,
                TRIM(TRAILING ', ' FROM 
                    COALESCE(c1.tx_email || ', ', '') ||
                    COALESCE(c2.tx_email || ', ', '') ||
                    COALESCE(u1.tx_email || ', ', '') ||
                    COALESCE(u2.tx_email || ', ', '') ||
                    COALESCE(om.tx_email || ', ', '') ||
                    COALESCE(pu.tx_email || ', ', '') ||
                    COALESCE(
                        (SELECT STRING_AGG(tx_email, ', ') FROM public.c002t_usuarios WHERE co_tipo_usuario = 3),
                        ''
                    )
                ) AS destinatarios
                FROM
                public.c001t_aplicaciones AS a
            LEFT JOIN
                public.c003t_clientes AS c1
            ON
                a.co_cliente = c1.co_cliente
            LEFT JOIN
                public.c003t_clientes AS c2
            ON
                a.co_cliente2 = c2.co_cliente
            LEFT JOIN
            public.c002t_usuarios AS u1
            ON
            a.co_usuario = u1.co_usuario
            LEFT JOIN
            public.c002t_usuarios AS u2
            ON
            a.co_usuario_2 = u2.co_usuario
            LEFT JOIN 
                public.c002t_usuarios AS om 
            ON 
                u1.co_office_manager = om.co_usuario
            LEFT JOIN
                public.c002t_usuarios AS pu
            ON
                u1.co_usuario_padre = pu.co_usuario
            WHERE
                a.co_aplicacion = $co_aplicacion";        
        
        $data = DB::select($sql);
        if(count($data)> 0){
            $data = $data[0];
        }
        return $data;  
    }

    public function getUsers($userDestinity){
       
        $listFinal = [];
        if(!empty($userDestinity)){
            $list = explode(',',$userDestinity);                
            foreach($list as $item){
                $listFinal[]= trim($item);
            }           
            $listFinal = array_unique($listFinal, SORT_REGULAR);
        }
        return $listFinal;
    }

    public function sendNotificationEmailAppCreated(){
        $objApplication = new LastApplicationCreated();
        $aplicacion = $objApplication->getAplication($this->co_cliente);
        $listUsuarios = $this->getData($aplicacion->co_aplicacion);
        $usuarios = $this->getUsers($listUsuarios->destinatarios);
    
        foreach ($usuarios as $usuario) {
           dispatch(new EmailAppCreated($usuario, $listUsuarios, $this->pdfPath))->delay(now()->addSeconds(16));           
            
        }
    }


    

    
}
