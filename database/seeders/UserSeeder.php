<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@mraprende.com'],
            [
                'name' => 'Admin',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ]
        );

        // Asesores
        $asesor1 = User::updateOrCreate(
            ['email' => 'luis.asesor@mraprende.com'],
            [
                'name' => 'Luis Pérez',
                'role' => 'asesor',
                'password' => Hash::make('password'),
            ]
        );

        $asesor2 = User::updateOrCreate(
            ['email' => 'ana.asesor@mraprende.com'],
            [
                'name' => 'Ana López',
                'role' => 'asesor',
                'password' => Hash::make('password'),
            ]
        );

        // Alumnos
        User::updateOrCreate(
            ['email' => 'alumno1@mraprende.com'],
            [
                'name' => 'Alumno Uno',
                'role' => 'alumno',
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'alumno2@mraprende.com'],
            [
                'name' => 'Alumno Dos',
                'role' => 'alumno',
                'password' => Hash::make('password'),
            ]
        );
    }
}
