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
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->string('hora');
            $table->unsignedBigInteger('dia_id');
            $table->unsignedBigInteger('cuatrimestre_id');
            $table->unsignedBigInteger('licenciatura_id');
            $table->unsignedBigInteger('generacion_id');
            $table->unsignedBigInteger('asignacion_materia_id');



            $table->foreign('dia_id')->references('id')->on('dias')->onDelete('cascade');
            $table->foreign('cuatrimestre_id')->references('id')->on('cuatrimestres')->onDelete('cascade');
            $table->foreign('licenciatura_id')->references('id')->on('licenciaturas')->onDelete('cascade');
            $table->foreign('generacion_id')->references('id')->on('generaciones')->onDelete('cascade');
            $table->foreign('asignacion_materia_id')->references('id')->on('asignacion_materias')->onDelete('cascade');



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
