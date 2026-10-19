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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('password');
            $table->string('nombre')->nullable()->after('name');
            $table->string('apellido')->nullable()->after('nombre');
            $table->string('numero')->nullable()->after('apellido');
            $table->date('fecha_nacimiento')->nullable()->after('numero');
        });

        // El login es por usuario (name), asi que el correo deja de ser obligatorio.
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable()->change();
        });

        // El nombre de usuario no se puede repetir.
        Schema::table('users', function (Blueprint $table) {
            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['name']);
            $table->dropColumn(['is_admin', 'nombre', 'apellido', 'numero', 'fecha_nacimiento']);
        });
    }
};
