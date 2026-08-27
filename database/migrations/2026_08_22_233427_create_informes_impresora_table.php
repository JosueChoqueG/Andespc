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
        Schema::create('informes_impresora', function (Blueprint $table) {
            $table->id();
            $table->foreignId('impresora_id')->constrained('impresoras')->onDelete('restrict')->onUpdate('cascade');

            // Datos generales del informe
            $table->date('fecha_informe');
            $table->string('tecnico_nombre')->default('Josue Choque Gomez');
            $table->enum('tipo_informe', ['Atasco de Papel', 'Mantenimiento', 'Falla', 'Revisión', 'Otro']);

            // Sección: Descripción del problema
            $table->text('descripcion_problema')->nullable()->comment('Descripción detallada del problema reportado');

            // Sección: Diagnóstico
            $table->text('diagnostico')->nullable()->comment('Diagnóstico técnico realizado');

            // Sección: Acciones realizadas
            $table->text('acciones_realizadas')->nullable()->comment('Acciones correctivas o preventivas aplicadas');

            // Sección: Contador / copias
            $table->unsignedInteger('contador_copias')->nullable()->comment('Lectura del contador de copias al momento del informe');

            // Sección: Evidencias (rutas de imágenes)
            $table->string('imagen_01_path')->nullable()->comment('Ruta imagen evidencia 01');
            $table->string('imagen_01_caption')->nullable();
            $table->string('imagen_02_path')->nullable()->comment('Ruta imagen evidencia 02 (estado impresora)');
            $table->string('imagen_02_caption')->nullable();

            // Sección: Recomendaciones
            $table->text('recomendaciones')->nullable();

            // Estado del equipo al momento del informe
            $table->enum('estado_equipo', ['OPTIMO', 'BUENO', 'REGULAR', 'DEFICIENTE'])->default('REGULAR');

            // ¿Está dentro de garantía?
            $table->boolean('en_garantia')->default(false);

            // Observaciones generales
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informes_impresora');
    }
};
