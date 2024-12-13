<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class genero extends Model
{
    use HasFactory;
    protected $table = "generos";

    //identificamos la llave primaria
    protected $primaryKey = "id";
    public $timestamps = true;
    protected $fillable = [
        'nombre',
        'eliminado'
    ];

    //Ocultar la informacion del valor
    protected $hidden = [
        'eliminado'
    ];



}
