<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\genero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class GeneroController extends Controller
{
    public function getGeneros()
    {
        $genero = genero::all();

        return response()->json([
            'estatus' => true,
            'tipo_apoyo' => $genero
        ]);
    }

    public function insertGenero(Request $request)
    {
        $data = $request->only(['nombre']);
        $genero = genero::create($data);

        return response()->json(['estatus' => true, 'id' => $genero->id]);
    }
}
