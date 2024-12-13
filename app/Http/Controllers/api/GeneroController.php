<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\genero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class GeneroController extends Controller
{
    //Crear un genero
    public function create(Request $request)
    {
        //Validar los datos
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',

        ]);

        //Si hay problemas, genera una respuesta
        if ($validator->fails()) {
            return response()->json([
                'estatus' => 0,
                'mensaje' => $validator->errors()

            ]);
        }

        //Si no hubo problemas, crea el genero
        $genero = new genero();
        $genero->nombre = $request->nombre;
        $genero->save();

        return response()->json([
            'estatus' => 1,
            'mensaje' => "Genero Registrado",
            'data' => $genero
        ]);
    }


    //Obtener todos los generos
    public function generoAll()
    {
        $genero= genero::all();
        return response()->json([
            'estatus' => 1,
            'data' => $genero

        ]);
    }
}
