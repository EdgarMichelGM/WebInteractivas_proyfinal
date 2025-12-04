<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MateriaController extends Controller
{
    /**
     * Listado para admin/asesor
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Materia::withCount('alumnos')
            ->with('asesor:id,name')
            ->orderBy('nombre');

        // Si es asesor, solo sus materias
        if ($user && $user->role === 'asesor') {
            $query->where('asesor_id', $user->id);
        }

        $materias = $query->get()->map(function ($m) {
            return [
                'id'       => $m->id,
                'nombre'   => $m->nombre,
                'clave'    => $m->clave,
                'asesor'   => $m->asesor?->name,
                'asesor_id'=> $m->asesor_id,
                'alumnos'  => $m->alumnos_count,
            ];
        });

        return Inertia::render('Materias/Index', [
            'materias' => $materias,
        ]);
    }

    /**
     * Catálogo de cursos para alumno (vista Courses/Index)
     */
    public function courses(Request $request)
    {
        $user = $request->user();

        $materias = Materia::with('asesor')
            ->orderBy('nombre')
            ->get()
            ->map(function ($m) use ($user) {
                $inscrito = false;

                if ($user && $user->role === 'alumno') {
                    $inscrito = $m->alumnos()
                        ->where('user_id', $user->id)
                        ->exists();
                }

                return [
                    'id'       => $m->id,
                    'nombre'   => $m->nombre,
                    'clave'    => $m->clave,
                    'asesor'   => $m->asesor?->name,

                    'nivel'    => 'Básico',
                    'duracion' => '4 h de contenido',
                    'alumnos'  => 0,
                    'rating'   => 4.8,

                    'inscrito' => $inscrito,
                ];
            });

        return Inertia::render('Courses/Index', [
            'materias' => $materias,
        ]);
    }

    /**
     * Mostrar formulario de creación (si lo usas)
     */
    public function create()
    {
        return Inertia::render('Materias/Create');
    }

    /**
     * Guardar nueva materia
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'     => 'required|string|max:255',
            'clave'      => 'required|string|max:50|unique:materias,clave',
            'asesor_id'  => 'nullable|exists:users,id',
            'descripcion'=> 'nullable|string',
        ]);

        Materia::create($data);

        return redirect()->route('materias.index')
            ->with('success', 'Materia creada correctamente.');
    }

    /**
     * Editar materia
     */
    public function edit(Materia $materia)
    {
        return Inertia::render('Materias/Edit', [
            'materia' => [
                'id'          => $materia->id,
                'nombre'      => $materia->nombre,
                'clave'       => $materia->clave,
                'asesor_id'   => $materia->asesor_id,
                'descripcion' => $materia->descripcion,
            ],
        ]);
    }

    /**
     * Actualizar materia
     */
    public function update(Request $request, Materia $materia)
    {
        $data = $request->validate([
            'nombre'     => 'required|string|max:255',
            'clave'      => 'required|string|max:50|unique:materias,clave,' . $materia->id,
            'asesor_id'  => 'nullable|exists:users,id',
            'descripcion'=> 'nullable|string',
        ]);

        $materia->update($data);

        return redirect()->route('materias.index')
            ->with('success', 'Materia actualizada correctamente.');
    }

    /**
     * Eliminar materia
     */
    public function destroy(Materia $materia)
    {
        $materia->delete();

        return redirect()->route('materias.index')
            ->with('success', 'Materia eliminada correctamente.');
    }

    /**
     * Inscribir alumno en una materia (ruta materias.enroll)
     */
    public function enroll(Request $request, Materia $materia)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'alumno') {
            abort(403, 'Solo los alumnos pueden inscribirse en materias.');
        }

        // Unique en la tabla pivot
        $materia->alumnos()->syncWithoutDetaching([$user->id]);

        return back()->with('success', 'Te has inscrito a la materia.');
    }
}
