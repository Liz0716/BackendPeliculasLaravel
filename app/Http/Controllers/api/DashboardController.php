<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\genero;
use App\Models\peliculas;
use App\Models\User;

class DashboardController extends Controller
{
    public function getPeliculasPorGenero()
    {
        $user = auth()->user();

        $query = peliculas::select('id_genero')
            ->selectRaw('count(*) as total')
            ->where('eliminado', 0)
            ->groupBy('id_genero');



        $resultados = $query->get();

        $datos = $resultados->map(function ($item) {
            $genero = genero::find($item->id_genero);
            return [
                'genero' => $genero ? $genero->nombre : 'Desconocido',
                'total' => $item->total,
            ];
        });

        return response()->json([
            'estatus' => true,
            'data' => $datos
        ]);
    }


    public function getDashboardData()
    {
        $usuariosRegistrados = User::where('eliminado', 0)->count();
        $peliculasRegistradas = peliculas::where('eliminado', 0)->count();

        return response()->json([
            'usuarios' => $usuariosRegistrados,
            'peliculas' => $peliculasRegistradas,
        ]);
    }
}
