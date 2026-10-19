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
        // Tabla pivote de la relacion muchos-a-muchos entre buses y rutas:
        // un bus puede cubrir varias rutas y una ruta puede ser cubierta por varios buses.
        Schema::create('bus_route', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')
                ->constrained('buses')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignId('route_id')
                ->constrained('routes')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            // Un bus no puede repetirse en la misma ruta.
            $table->unique(['bus_id', 'route_id']);
            $table->index('route_id');
            $table->index('bus_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_route');
    }
};
