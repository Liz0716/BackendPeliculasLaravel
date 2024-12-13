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
            $table->string("nombre", 100);
            $table->string("director", 100);
            $table->year("anio_publicacion", 100);
            $table->unsignedInteger("id_genero")->nullable();
            $table->unsignedInteger("id_user")->nullable();
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
