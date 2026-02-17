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
        Schema::create('materias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->string('clave');
            $table->integer('creditos');
            $table->unsignedBigInteger('cuatrimestre_id');
            $table->unsignedBigInteger('licenciatura_id');
            $table->enum('calificable', ['si', 'no'])->default('si');
            $table->timestamps();


            $table->foreign('licenciatura_id')->references('id')->on('licenciaturas')->onDelete('cascade');
            $table->foreign('cuatrimestre_id')->references('id')->on('cuatrimestres')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materias');
    }
};
