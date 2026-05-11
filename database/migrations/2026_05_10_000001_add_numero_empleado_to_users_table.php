<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'numero_empleado')) {
                $table->string('numero_empleado', 30)->nullable()->unique()->after('username');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'numero_empleado')) {
                $table->dropUnique(['numero_empleado']);
                $table->dropColumn('numero_empleado');
            }
        });
    }
};
