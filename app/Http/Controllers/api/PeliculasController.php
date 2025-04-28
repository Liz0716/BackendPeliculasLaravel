<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\genero;
use App\Models\peliculas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;



class PeliculasController extends Controller
{
    public function getPeliculas(Request $request)
    {
        $user = auth()->user();

        $query = peliculas::with(['genero'])
            ->where('eliminado', 0);

        if ($user->rol == 0) {
            $query->where('id_user', $user->id);

        }

        if ($request->filled('id_genero')) {
            $query->where('id_genero', $request->input('id_genero'));
        }

        $peliculas = $query->get();



        return response()->json([
            'estatus' => true,
            'peliculas' => $peliculas
        ]);
    }

    public function getPeliculaById($peliculaId)
    {
        $pelicula = peliculas::with(['genero'])->find($peliculaId);

        $array = $pelicula ? [
            'id' => $pelicula->id,
            'nombre' => $pelicula->nombre,
            'sinopsis' => $pelicula->sinopsis,
            'director' => $pelicula->director,
            'anio_publicacion' => $pelicula->anio_publicacion,
            'urlImagen' => $pelicula->urlImagen,
            'duracion' => $pelicula->duracion,
            'id_user' => $pelicula->id_user,
            'id_genero' => $pelicula->id_genero,


        ] : [];
        return response()->json($array);
    }

    public function insertPelicula(Request $request)
    {
        $data = $request->only(['nombre', 'sinopsis', 'director', 'anio_publicacion', 'urlImagen', 'duracion', 'id_user', 'id_genero']);
        $pelicula = peliculas::create($data);

        return response()->json(['estatus' => true, 'id' => $pelicula->id]);
    }

    public function updatePelicula(Request $request, $peliculaId)
    {
        $data = $request->only(['nombre', 'sinopsis', 'director', 'anio_publicacion', 'urlImagen', 'duracion', 'id_user', 'id_genero']);

        $pelicula = peliculas::find($peliculaId);

        if (!$pelicula) {
            return response()->json(['estatus' => false, 'mensaje' => 'Pelicula no encontrado']);
        }

        $pelicula->update($data);
        return response()->json(['estatus' => true]);
    }

    public function deletePelicula($peliculaId)
    {
        $espacio = peliculas::find($peliculaId);

        if (!$espacio) {
            return response()->json(['estatus' => false]);
        }

        $espacio->update(['eliminado' => 1]);

        return response()->json(['estatus' => true]);
    }


    public function getGeneros(){
        $user = auth()->user();

        $generos = genero::select('id', 'nombre')
            ->where('eliminado', 0)
            ->get();

        return response()->json(['generos' => $generos]);
    }

    public function getUsuarios()
    {
        $user = auth()->user();

        if ($user->rol == 0) {
            $usuarios = User::select('id', 'name')
                ->where('id', $user->id)
                ->get();
        } else {
            $usuarios = User::select('id', 'name')->get();
        }

        return response()->json(['usuarios' => $usuarios]);
    }


}
