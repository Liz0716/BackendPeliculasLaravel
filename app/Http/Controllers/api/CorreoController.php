<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;


class CorreoController extends Controller
{
    public function sendEmail($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'estatus' => false,
                'mensaje' => 'No se encontro el usuario'
            ]);
        }

        $data = [
            'subject' => '¡Bienvenido a nuestra plataforma de peliculas!',
            'content' => "Hola {$user->name}, espero puedas disfrutar de la variedad de peliculas que tenemos para ti."
        ];

        Mail::raw($data['content'], function($message) use ($data, $user) {
            $message->to($user->email)
                    ->subject($data['subject']);
        });

        return response()->json(['estatus' => true, 'mensaje' => 'Correo enviado correctamente']);
    }
}
