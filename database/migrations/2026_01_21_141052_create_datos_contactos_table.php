<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('datos_contactos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained()->onDelete('cascade');
            $table->string('calle', 255);
            $table->string('numero_exterior', 10)->nullable();
            $table->string('numero_interior', 10)->nullable();
            $table->string('colonia', 255);
            $table->string('municipio', 255);
            $table->string('codigo_postal', 10);
            $table->string('celular', 20);
            $table->string('telefono', 20)->nullable();
            $table->string('bachillerato_procedente', 255);

            $table->unsignedBigInteger('ciudad_id')->nullable();
            $table->unsignedBigInteger('estado_id')->nullable();
            $table->unsignedBigInteger('pais_id')->nullable();

            $table->foreign('ciudad_id')->references('id')->on('cities')->nullOnDelete();
            $table->foreign('estado_id')->references('id')->on('states')->nullOnDelete();
            $table->foreign('pais_id')->references('id')->on('countries')->nullOnDelete();


            $table->timestamps();


            $table->unique('alumno_id');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_contactos');
    }
};
