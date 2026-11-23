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
        Schema::table('responsables', function (Blueprint $table) {
            $table->string('cargo', 150)->nullable()->after('nombre_responsable');
            $table->foreignId('oficina_id')->nullable()->after('cargo')->constrained('oficinas')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('responsables', function (Blueprint $table) {
            $table->dropForeign(['oficina_id']);
            $table->dropColumn(['cargo', 'oficina_id']);
        });
    }
};
