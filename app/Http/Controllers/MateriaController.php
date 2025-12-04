<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\User;
use Inertia\Inertia;

class MateriaController extends Controller
{
    public function index()
    {
        $materias = Materia::with('asesor:id,name')
            ->select('id', 'nombre', 'clave', 'asesor_id')
            ->orderBy('nombre')
            ->get()
            ->map(function ($m) {
                return [
                    'id' => $m->id,
                    'nombre' => $m->nombre,
                    'clave' => $m->clave,
                    'asesor' => $m->asesor?->name,
                ];
            });

        return Inertia::render('Materias/Index', [
            'materias' => $materias,
        ]);
    }

    public function create()
    {
        $asesores = User::where('role', 'asesor')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return Inertia::render('Materias/Create', [
            'asesores' => $asesores,
        ]);
    }

    public function edit($id)
    {
        $materia = Materia::with('asesor:id,name')->findOrFail($id);
        $asesores = User::where('role', 'asesor')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return Inertia::render('Materias/Edit', [
            'materia' => $materia,
            'asesores' => $asesores,
        ]);
    }
}
