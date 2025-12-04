<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\OnlineClass;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OnlineClassController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'alumno') {
            $materiaIds = $user->materiasInscritas()
                ->pluck('materias.id');

            $clases = OnlineClass::with(['materia', 'docente'])
                ->whereIn('materia_id', $materiaIds)
                ->where('is_active', true)
                ->orderByDesc('started_at')
                ->get()
                ->map(function ($c) {
                    return [
                        'id'        => $c->id,
                        'materia'   => $c->materia?->nombre,
                        'clave'     => $c->materia?->clave,
                        'titulo'    => $c->titulo ?? 'Clase en línea',
                        'docente'   => $c->docente?->name,
                        'started_at'=> optional($c->started_at)->format('d/m/Y H:i'),
                        'link'      => $c->link,
                    ];
                });

            return Inertia::render('Agenda/Index', [
                'modo'   => 'alumno',
                'clases' => $clases,
            ]);
        }

        $query = OnlineClass::with(['materia', 'docente'])
            ->orderByDesc('started_at');

        if ($user->role === 'asesor') {
            $query->whereHas('materia', function ($q) use ($user) {
                $q->where('asesor_id', $user->id);
            });
        }

        $clases = $query->get()->map(function ($c) {
            return [
                'id'         => $c->id,
                'materia'    => $c->materia?->nombre,
                'clave'      => $c->materia?->clave,
                'titulo'     => $c->titulo ?? 'Clase en línea',
                'docente'    => $c->docente?->name,
                'is_active'  => $c->is_active,
                'started_at' => optional($c->started_at)->format('d/m/Y H:i'),
                'ended_at'   => optional($c->ended_at)->format('d/m/Y H:i'),
            ];
        });

        $materiasQuery = Materia::orderBy('nombre');

        if ($user->role === 'asesor') {
            $materiasQuery->where('asesor_id', $user->id);
        }

        $materiasOptions = $materiasQuery
            ->select('id', 'nombre', 'clave')
            ->get()
            ->map(function ($m) {
                return [
                    'id'    => $m->id,
                    'label' => "{$m->clave} - {$m->nombre}",
                ];
            });

        return Inertia::render('Agenda/Index', [
            'modo'            => 'docente',
            'clases'          => $clases,
            'materiasOptions' => $materiasOptions,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (!in_array($user->role, ['asesor', 'admin'])) {
            abort(403);
        }

        $data = $request->validate([
            'materia_id' => 'required|exists:materias,id',
            'titulo'     => 'nullable|string|max:255',
        ]);

        if ($user->role === 'asesor') {
            Materia::where('id', $data['materia_id'])
                ->where('asesor_id', $user->id)
                ->firstOrFail();
        }

        OnlineClass::create([
            'materia_id'    => $data['materia_id'],
            'started_by_id' => $user->id,
            'titulo'        => $data['titulo'] ?: 'Clase en línea',
            'is_active'     => true,
            'started_at'    => now(),
            'link'          => null,
        ]);

        return back()->with('success', 'Clase iniciada.');
    }

    public function close(Request $request, OnlineClass $onlineClass)
    {
        $user = $request->user();

        if (!in_array($user->role, ['asesor', 'admin'])) {
            abort(403);
        }

        if ($user->role === 'asesor') {
            if (!$onlineClass->materia || $onlineClass->materia->asesor_id !== $user->id) {
                abort(403);
            }
        }

        $onlineClass->update([
            'is_active' => false,
            'ended_at'  => now(),
        ]);

        return back()->with('success', 'Clase cerrada.');
    }
}
