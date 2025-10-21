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
        Schema::create('cuatrimestres', function (Blueprint $table) {
            $table->id();
            $table->string('no_cuatrimestre');
            $table->string('nombre_cuatrimestre');
            $table->unsignedBigInteger('mes_id');

            $table->timestamps();


            $table->foreign('mes_id')->references('id')->on('meses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuatrimestres');
    }
};
