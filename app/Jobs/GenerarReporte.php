<?php

namespace App\Jobs;

use App\Http\Controllers\PrecalPdfController; // Importa el controlador
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerarReporte implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    
    public $co_cliente;
    public $tipo_reporte;
    public $co_aplicacion;
    public $pdfPath;
    /**
     * Create a new job instance.
     */
    public function __construct($co_cliente, $tipo_reporte,$pdfPath='',$co_aplicacion='0')
    {
        
        $this->co_cliente = $co_cliente;
        $this->tipo_reporte = $tipo_reporte;
        $this->pdfPath = $pdfPath;
        $this->co_aplicacion = $co_aplicacion;
        
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Log::info("Generando PDF Precalificacion ...", ['co_cliente' => $this->co_cliente, 'tipo_reporte' => $this->tipo_reporte]);

        $controlador = new PrecalPdfController();
        //$co_aplicacion = $controlador->getAplication($this->co_cliente);
        Log::info("100. Antes de Generar Reporte ...", ['co_aplicacion' => $this->co_aplicacion]);
        $controlador->generarReporte($this->co_cliente, $this->tipo_reporte,$this->pdfPath,$this->co_aplicacion);
        Log::info("101. Despues de Generar Reporte ...", ['co_aplicacion' => $this->co_aplicacion]);


    }
}
