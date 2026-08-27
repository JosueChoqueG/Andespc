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
        Schema::table('informes_impresora', function (Blueprint $table) {
            $table->text('incidencias')->nullable()->after('tipo_informe')
                  ->comment('Texto libre de la incidencia reportada');
            $table->json('procedimientos')->nullable()->after('incidencias')
                  ->comment('Lista de pasos/procedimientos realizados (JSON array)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('informes_impresora', function (Blueprint $table) {
            $table->dropColumn(['incidencias', 'procedimientos']);
        });
    }
};
