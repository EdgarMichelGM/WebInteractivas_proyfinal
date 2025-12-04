<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TutoriaController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->role === 'alumno') {
            $materias = $user->materiasInscritas()
                ->with('asesor:id,name')
                ->select('materias.id', 'materias.nombre', 'materias.clave', 'materias.asesor_id')
                ->orderBy('materias.nombre')
                ->get()
                ->map(function ($m) {
                    return [
                        'id'     => $m->id,
                        'nombre' => $m->nombre,
                        'clave'  => $m->clave,
                        'asesor' => $m->asesor?->name,
                    ];
                });

            return Inertia::render('Tutorias/Index', [
                'modo'     => 'alumno',
                'materias' => $materias,
            ]);
        }

        $query = Materia::withCount('alumnos')
            ->with('asesor:id,name')
            ->orderBy('nombre');

        // Si es asesor, solo sus materias
        if ($user->role === 'asesor') {
            $query->where('asesor_id', $user->id);
        }

        $materias = $query->get()->map(function ($m) {
            return [
                'id'      => $m->id,
                'nombre'  => $m->nombre,
                'clave'   => $m->clave,
                'asesor'  => $m->asesor?->name,
                'alumnos' => $m->alumnos_count,
            ];
        });

        return Inertia::render('Tutorias/Index', [
            'modo'     => 'docente',
            'materias' => $materias,
        ]);
    }
}
