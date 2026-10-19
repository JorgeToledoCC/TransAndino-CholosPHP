<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departure_id')
                ->constrained('departures')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('nombre_pasajero');
            $table->string('ci_pasajero');
            $table->unsignedInteger('numero_asiento');
            $table->enum('estado', ['vendido', 'anulado'])->default('vendido');
            $table->timestamps();

            $table->index('departure_id');
        });

        // Indice unico PARCIAL: evita vender el mismo asiento dos veces en la
        // misma salida, pero libera el asiento cuando el pasaje es anulado.
        // Valido tanto en PostgreSQL como en SQLite.
        DB::statement(
            "CREATE UNIQUE INDEX tickets_departure_asiento_vendido
             ON tickets (departure_id, numero_asiento)
             WHERE estado = 'vendido'"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
