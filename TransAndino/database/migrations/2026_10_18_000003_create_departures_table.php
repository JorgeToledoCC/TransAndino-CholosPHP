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
        Schema::create('departures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('route_id')
                ->constrained('routes')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignId('bus_id')
                ->constrained('buses')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('chofer');
            $table->date('fecha');
            $table->time('hora');
            $table->decimal('precio_final', 8, 2);
            $table->timestamps();

            // PostgreSQL no indexa automaticamente las llaves foraneas.
            $table->index('route_id');
            $table->index('bus_id');
            $table->index('fecha');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departures');
    }
};
