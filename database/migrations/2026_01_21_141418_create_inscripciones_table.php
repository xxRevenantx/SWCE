<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alumno_id');
            $table->unsignedBigInteger('licenciatura_id');
            $table->unsignedBigInteger('generacion_id');
            $table->unsignedBigInteger('cuatrimestre_id');
            $table->boolean('status')->default(true);
            $table->date('fecha_inscripcion');

            $table->foreign('alumno_id')->references('id')->on('alumnos')->onDelete('cascade');
            $table->foreign('licenciatura_id')->references('id')->on('licenciaturas')->onDelete('cascade');
            $table->foreign('generacion_id')->references('id')->on('generaciones')->onDelete('cascade');
            $table->foreign('cuatrimestre_id')->references('id')->on('cuatrimestres')->onDelete('cascade');


            $table->timestamps();

            $table->unique(
                ['alumno_id', 'licenciatura_id', 'generacion_id', 'cuatrimestre_id'],
                'inscripciones_unique_alumno_lic_gener_cuat'
            );


        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripciones');
    }
};
