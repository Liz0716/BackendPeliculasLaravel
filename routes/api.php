<?php

use App\Http\Controllers\api\CorreoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\GeneroController;
use App\Http\Controllers\api\PeliculasController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\DashboardController;

Route::get('mail', [CorreoController::class, 'sendEmail']);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [UserController::class, 'register']);
Route::post('user/login', [UserController::class, 'login']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::prefix('user')->group(function () {
        Route::post('/register', [UserController::class, 'register']);
        Route::put('/password/{id}', [UserController::class, 'password'])->where('id', '[0-9]+');
        Route::put('/editar/{id}', [UserController::class, 'update'])->where('id', '[0-9]+');
        Route::get('/user-profile', [UserController::class, 'userprofile']);
        Route::get('/logout', [UserController::class, 'logout']);
        Route::post('/index', [UserController::class, 'getUsuarios']);
        Route::get('/{id}', [UserController::class, 'getUsuariosById'])->where('id', '[0-9]+');
        Route::delete('/{id}', [UserController::class, 'delete'])->where('id', '[0-9]+');

    });

    Route::prefix('genero')->group(function(){
        Route::get('',[GeneroController:: class, 'getGeneros']);
        Route::post('',[GeneroController:: class, 'insertGenero']);
    });


    Route::prefix('peliculas')->group(function(){
        Route::get('/generos', [PeliculasController::class, 'getGeneros']);
        Route::get('/usuarios', [PeliculasController::class, 'getUsuarios']);
        Route::post('/index', [PeliculasController::class, 'getPeliculas']);
        Route::get('/{peliculaId}', [PeliculasController::class, 'getPeliculaById'])->where('peliculaId', '[0-9]+');
        Route::post('', [PeliculasController::class, 'insertPelicula']);
        Route::put('/{peliculaId}', [PeliculasController::class, 'updatePelicula'])->where('peliculaId', '[0-9]+');
        Route::delete('/{peliculaId}', [PeliculasController::class, 'deletePelicula'])->where('peliculaId', '[0-9]+');
    });

    Route::prefix('dashboard')->group(function(){
        Route::get('/peliculas', [DashboardController::class, 'getPeliculasPorGenero']);
        Route::get('/datos', [DashboardController::class, 'getDashboardData']);
    });

});




