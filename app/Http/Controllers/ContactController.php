<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;

class ContactController extends Controller
{
    public function sendMessage(Request $request)
    {
        /* Emails details */
            $emailDetails = [
                'title' => "Contacto de Cliente",
                'name'  => $request->input('name'),
                'email' => $request->input('email'),
                'subject' => $request->input('subject'),
                'msg'   => $request->input('msg')
            ];
            

        //Send mail
            $path = "emails.send-messages";

            Mail::send($path, $emailDetails, function($message) use ($emailDetails) {
                $message->from($emailDetails['email'], 'Efecto Granel');
                $message->to('krangel107@gmail.com');
                $message->subject('Efecto Granel');
            });

        // Return response
            return response()->json([
                'status' => 'ok',
                'msg' => 'Correo electrónico enviado'
            ]);
    }
}
