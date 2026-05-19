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
        Schema::create('termicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('oficina_id')->constrained('oficinas')->onDelete('restrict')->onUpdate('cascade');
            $table->string('tipo_termica', 50)->default('Térmica POS');
            $table->string('marca_termica', 50)->default('Epson');
            $table->string('modelo_termica', 50);
            $table->date('fecha_adquisicion')->nullable();
            $table->string('serie_termica', 50)->unique('idx_serie_unica');
            $table->foreignId('responsable_id')->nullable()->constrained('responsables')->onDelete('set null')->onUpdate('cascade');
            $table->enum('tipo_conexion', ['USB', 'ETHERNET', 'SERIAL', 'WI-FI', 'BLUETOOTH'])->default('USB');
            $table->string('nombre_host', 50)->nullable();
            $table->string('direccion_ip', 45)->nullable();
            $table->enum('estado_termica', ['OPTIMO', 'BUENO', 'REGULAR', 'DEFICIENTE', 'DE BAJA'])->default('OPTIMO');
            $table->string('velocidad_impresion', 15)->nullable();
            $table->string('modelo_consumible', 50)->nullable();
            $table->string('tipo_consumible', 50)->nullable();
            $table->unsignedInteger('cantidad_impresion')->default(0)->comment('Metros impresos o número de cortes acumulados');
            $table->unsignedInteger('capacidad_impresion')->nullable()->comment('Vida útil teórica en Km');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('termicas');
    }
};
