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
        Schema::create('datos_escolares', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alumno_id')->unique();
            $table->string('matricula')->unique();
            $table->string('folio')->unique()->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();


            $table->foreign('alumno_id')->references('id')->on('alumnos')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_escolares');
    }
};
