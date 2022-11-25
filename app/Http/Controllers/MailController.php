<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendCode;

class MailController extends Controller
{
    public function sendcode($code, $email)
    {
        return Mail::to($email)->send(new SendCode($code)); #
    }
}
