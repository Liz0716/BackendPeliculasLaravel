<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class peliculas extends Model
{
    use HasFactory;
    protected $table = "peliculas";

    //Tener la llave primaria de la tabla
    protected $primaryKey = "id";
    public $timestamps = true;
    protected $fillable = [
        'nombre',
        'sinopsis',
        'director',
        'anio_publicacion',
        'urlImagen',
        'duracion',
        'id_user',
        'id_genero',
        'eliminado'
    ];

    //Ocultar la informacion del valor
    protected $hidden = [
        'eliminado'
    ];

    protected $cast = [
        'eliminado'=> 'boolean'
    ];

    // Relacion de la tabla de genero
    public function genero(){
        return $this->belongsTo(genero::class, 'id_genero');
    }
}
