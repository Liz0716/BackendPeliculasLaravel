<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peliculas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('sinopsis');
            $table->string('director');
            $table->year('anio_publicacion');
            $table->string('urlImagen');
            $table->string('duracion');
            $table->foreignId('id_user')->references('id')->on('users');
            $table->foreignId('id_genero')->references('id')->on('generos');
            $table->boolean("eliminado")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peliculas');
    }
};
