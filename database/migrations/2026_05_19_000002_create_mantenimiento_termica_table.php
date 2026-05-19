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
        Schema::create('mantenimiento_termica', function (Blueprint $table) {
            $table->id();
            $table->foreignId('termica_id')->constrained('termicas')->onDelete('restrict')->onUpdate('cascade');
            $table->date('fecha_mantenimiento');
            $table->enum('tipo_mantenimiento', ['Preventivo', 'Correctivo']);
            $table->text('descripcion_mantenimiento')->comment('Trabajos realizados, separados por salto de línea');
            $table->text('observacion_mantenimiento')->nullable();
            $table->date('fecha_fallas')->nullable();
            $table->text('fallas_detectadas')->nullable();
            $table->text('fallas_solucion')->nullable();
            $table->text('observacion_general')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mantenimiento_termica');
    }
};
