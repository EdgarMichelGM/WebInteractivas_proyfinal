<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('online_classes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('materia_id')
                ->constrained('materias')
                ->onDelete('cascade');

            $table->foreignId('started_by_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->string('titulo')->nullable();
            $table->string('link')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('online_classes');
    }
};
