<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            /* =========================
             * 1) DATOS GENERALES
             * ========================= */


            $table->foreignId('pais_nacimiento')
                ->nullable()
                ->constrained('countries')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('estado_nacimiento')
                ->nullable()
                ->constrained('states')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('lugar_nacimiento')
                ->nullable()
                ->constrained('cities')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            /* =========================
             * 2) DATOS DE CONTACTO
             * ========================= */
            $table->string('calle', 150)->nullable();
            $table->string('num_exterior', 45)->nullable();
            $table->string('num_interior', 45)->nullable();
            $table->string('colonia', 100)->nullable();
            $table->string('codigo_postal', 10)->nullable();

            $table->string('municipio_residencia', 100)->nullable();

            $table->foreignId('estado_residencia_id')
                ->nullable()
                ->constrained('states')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('ciudad_residencia_id')
                ->nullable()
                ->constrained('cities')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('celular', 20)->nullable();
            $table->string('telefono_fijo', 20)->nullable();

            $table->string('correo_electronico', 150);
            $table->string('tutor', 150)->nullable();

            /* =========================
             * 3) DATOS ESCOLARES
             * ========================= */
            $table->string('bachillerato_procedente', 150)->nullable();

            $table->foreignId('licenciatura_id')
                ->nullable()
                ->constrained('licenciaturas')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('generacion_id')
                ->nullable()
                ->constrained('generaciones')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('cuatrimestre_id')
                ->nullable()
                ->constrained('cuatrimestres')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->string('foto')->nullable();
            $table->boolean('status')->default(true);

            $table->timestamps();

            // Reglas anti-duplicados
            $table->unique('CURP');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripciones');
    }
};
