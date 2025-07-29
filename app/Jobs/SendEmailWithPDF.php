<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\Emails\UserEmailAppCreated;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class SendEmailWithPDF implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $clienteId;
    protected $pdfPath;
    public $tries = 3; // Número de intentos
    public $maxExceptions = 3;

    public function __construct($clienteId, $pdfPath)
    {
        $this->clienteId = $clienteId;
        $this->pdfPath = $pdfPath;
    }

    public function handle()
    {
        
        if (Storage::disk('public')->exists($this->pdfPath)) {
            $userEmail = new UserEmailAppCreated($this->clienteId, $this->pdfPath);
            $userEmail->sendNotificationEmailAppCreated();            
        } else {
            Log::error('Email no enviado  PDF no encontrado: ' . $this->pdfPath);
            throw new \Exception('Email no enviado PDF no encontrado');
        }
    }

    public function failed(\Throwable $exception)
    {
        Log::error('Error enviando email: ' . $exception->getMessage());
    }
}