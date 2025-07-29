<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Emails\UserEmailAppCreated;

class PruebaEmail extends Controller
{
    public function enviarEmail(){
        $userEmail = new UserEmailAppCreated('4758');
        $userEmail->sendNotificationEmailAppCreated();
    }
}
