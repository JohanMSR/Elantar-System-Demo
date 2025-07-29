<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Notifications\NotificationEmailAppCreated;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class EmailAppCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email;
    public $data;
    public $pdfPath;
    public $tries = 3; // Número de intentos
    public $maxExceptions = 3;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $data, $pdfPath=null)    
    {
        $this->email = $email;
        $this->data = $data;
        $this->pdfPath = $pdfPath;
        //$this->aplicacion = $aplicacion;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Notification::route('mail', $this->email)->notify(new NotificationEmailAppCreated($this->data, $this->pdfPath));        
        
    }
}