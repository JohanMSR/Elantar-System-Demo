<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class NotificationEmailAppCreated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $data;
    protected $pdfPath;
    /**
     * Create a new notification instance.
     */
    public function __construct($data, $pdfPath=null)
    {
        $this->data = $data;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable = null): array
    {
        return ['mail'];
    }
    


    /**
     * Get the mail representation of the notification.
     */
   
    public function toMail($notifiable = null): MailMessage
    {   
        $url = url('/');
        $logourl = url('mini-favicon.png');
        $clientName = $this->data->tx_primer_nombre . ' ' . $this->data->tx_primer_apellido;
        $applicationCode = $this->data->co_aplicacion;
        $analystName = $this->data->tx_nombre_analista;

       
        if ($this->pdfPath && Storage::disk('public')->exists($this->pdfPath)) {
           
            $fullPath = storage_path('app/public' . $this->pdfPath);
            $fileName = 'Orden_de_Trabajo_'.basename($fullPath);
            
            return (new MailMessage)
            ->subject('Se ha creado una nueva Aplicacion')
            ->markdown('emails.new_app', compact('url', 'logourl', 'clientName', 'applicationCode', 'analystName'))
            ->attach($fullPath, [
                'as' => $fileName,
                'mime' => 'application/pdf'
            ]);    
        }else{            
            $fileName = Str::afterLast($this->pdfPath, '/'); 
            Log::info('Email generado sin la orden de trabajo: '.$fileName);
            return (new MailMessage)
            ->subject('Se ha creado una nueva Aplicacion')
            ->markdown('emails.new_app', compact('url', 'logourl', 'clientName', 'applicationCode', 'analystName'));
        }
        
    }
   

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
