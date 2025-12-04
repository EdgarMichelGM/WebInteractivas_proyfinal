<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();

            $table->foreignId('materia_id')
                ->constrained('materias')
                ->onDelete('cascade');

            // alumno al que pertenece la calificación
            $table->foreignId('alumno_id')
                ->constrained('users')
                ->onDelete('cascade');

            // quién la registró
            $table->foreignId('created_by_id')
                ->constrained('users')
                ->onDelete('cascade');

            // calificación numérica
            $table->unsignedTinyInteger('valor');

            // opcional: ordinario, extraordinario, etc.
            $table->string('oportunidad')->nullable();

            // opcional: comentarios
            $table->text('observaciones')->nullable();

            $table->timestamps();

            // una calificación final por materia/alumno
            $table->unique(['materia_id', 'alumno_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
