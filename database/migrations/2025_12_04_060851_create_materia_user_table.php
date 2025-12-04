<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('materia_user', function (Blueprint $table) {
            $table->id();

            $table->foreignId('materia_id')
                ->constrained('materias')
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->timestamps();

            $table->unique(['materia_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('materia_user');
    }
};
