<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\peliculas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;



class PeliculasController extends Controller
{
    //Crear la pelicula
    public function create(Request $request)
    {
        //Validar los datos
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'director' => 'required',
            'anio_publicacion' => 'required',
            'id_genero' => 'required',
            'id_user' => 'required'
        ]);
        //Si encuentra un error, devuelve una respuesta
        if ($validator->fails()) {
            return response()->json([
                'estatus' => 0,
                'mensaje' => $validator->errors()
            ]);
        }

        //Si no hay, crea la pelicula
        $peliculas = new peliculas();
        $peliculas->nombre = $request->nombre;
        $peliculas->director = $request->director;
        $peliculas->anio_publicacion = $request->anio_publicacion;
        $peliculas->id_genero = $request->id_genero;
        $peliculas->id_user = $request->id_user;
        $peliculas->save();

        return response()->json([
            'estatus' => 1,
            'mensaje' => "Pelicula Registrada",
            'data' => $peliculas

        ]);
    }

    //Obtener todas las peliculas
    public function PeliculasAll()
    {
        $peliculas= peliculas::where('eliminado',FALSE)
        ->with('genero')
        ->get();
        return response()->json([
            'estatus' => 1,
            'data' => $peliculas->map(function($pelicula){
                return [
                    'id' => $pelicula->id,
                    'nombre' => $pelicula->nombre,
                    'director' => $pelicula->director,
                    'anio_publicacion' => $pelicula->anio_publicacion,
                    'genero' => $pelicula->genero->nombre,
                ];
            })

        ]);
    }

    //Editar las peliculas
    public function update(Request $request, $id){
        // Busqueda de la pelicula
        $peliculas = peliculas::find($id);
        if(!$peliculas){
            return response()->json([
                'estatus' => 0,
                'mensaje' => 'Pelicula no encontrada'

            ]);
        }
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
        ]);
        if ($validator->fails()) {
            //Si tiene problemas genere una respuesta
            return response()->json([
                'estatus' => 0,
                'mensaje' => $validator->errors()

            ]);
        }
        $peliculas->nombre = $request->nombre;
        $peliculas->anio_publicacion = $request->anio_publicacion;
        $peliculas->director = $request->director;

        $peliculas->save();
        if( $peliculas->save()){
            return response()->json([
                'estatus' => 1,
                'mensaje' => 'Pelicula Actualizada'

            ]);
        }else{
            return response()->json([
                'estatus' => 0,
                'mensaje' => 'Pelicula No Actualizada'

            ]);
        }

    }

    //Eliminar pelicula
    public function delete($id){
        $peliculas = peliculas::find($id);
        //Cambiamos el campo de eliminado y guardamos el cambio
        $peliculas->eliminado = true;
        $peliculas->save();
        return response()->json([
            'estatus' => 1,
            'mensaje' => 'Pelicula Eliminada'
            ]);
    }

    //Obtener una pelicula
    // public function show($id){
    //     $peliculas = peliculas::with('genero')->find($id);
    //     return response()->json([
    //         'estatus' => 1,
    //         'mensaje' => $peliculas
    //     ]);
    // }

    public function show($id){
        $peliculas = peliculas::with('genero')->find($id);
        if (!$peliculas) {
            return response()->json([
                'estatus' => 0,
                'mensaje' => 'Pelicula no encontrada'
            ]);
        }
        return response()->json([
            'estatus' => 1,
            'data' => [
                'id' => $peliculas->id,
                'nombre' => $peliculas->nombre,
                'director' => $peliculas->director,
                'anio_publicacion' => $peliculas->anio_publicacion,
                'genero' => $peliculas->genero ? $peliculas->genero->nombre : null,
                'id_user' => $peliculas->id_user,
                'id_genero' => $peliculas->genero->id
            ]
        ]);


    }

}
