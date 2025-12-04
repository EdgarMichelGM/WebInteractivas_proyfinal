<?php

namespace Database\Seeders;

use App\Models\Materia;
use App\Models\User;
use Illuminate\Database\Seeder;

class MateriaSeeder extends Seeder
{
    public function run(): void
    {
        $luis = User::where('email', 'luis.asesor@mraprende.com')->first();
        $ana = User::where('email', 'ana.asesor@mraprende.com')->first();

        if (!$luis || !$ana) {
            return;
        }

        Materia::updateOrCreate(
            ['clave' => 'MAT101'],
            [
                'nombre' => 'Cálculo diferencial',
                'asesor_id' => $luis->id,
                'descripcion' => 'Límites, derivadas y aplicaciones básicas.',
            ]
        );

        Materia::updateOrCreate(
            ['clave' => 'INF201'],
            [
                'nombre' => 'Programación I',
                'asesor_id' => $ana->id,
                'descripcion' => 'Algoritmos, pseudocódigo y fundamentos de programación.',
            ]
        );
    }
}
