<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\GeneroController;
use App\Http\Controllers\api\PeliculasController;



// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('genero')->group(function(){
    //Crear genero
    Route::post('',[GeneroController:: class, 'create']);
    //Obtener todos los generos
    Route::get('',[GeneroController:: class, 'generoAll']);
});


Route::prefix('peliculas')->group(function(){
    //Crear pelicula
    Route::post('',[PeliculasController:: class, 'create']);
    //Obtener todas las peliculas
    Route::get('',[PeliculasController:: class, 'PeliculasAll']);
    //actualizar pelicula
    Route::patch('/{id}', [PeliculasController::class, 'update'])->where('id','[0-9]+');
    //eliminar pelicula
    Route::delete('/{id}', [PeliculasController::class, 'delete'])->where('id','[0-9]+');
    //Obtener una pelicula
    Route::get('/{id}', [PeliculasController::class, 'show'])->where('id','[0-9]+');

});
