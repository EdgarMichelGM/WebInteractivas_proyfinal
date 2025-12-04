<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Materia;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Mail\GradeNotificationMail;
use Illuminate\Support\Facades\Mail;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Alumno: solo ve sus calificaciones
        if ($user->role === 'alumno') {
            $grades = Grade::with('materia')
                ->where('alumno_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($g) {
                    $valor = $g->valor;

                    return [
                        'id'       => $g->id,
                        'materia'  => $g->materia?->nombre,
                        'clave'    => $g->materia?->clave,
                        'promedio' => $valor,
                        'estado'   => $valor >= 70 ? 'aprobada' : 'reprobada',
                    ];
                });

            return Inertia::render('Grades/Index', [
                'modo'           => 'alumno',
                'calificaciones' => $grades,
            ]);
        }

        // Docente/Admin: ve calificaciones y puede crear
        $query = Grade::with(['materia', 'alumno'])
            ->orderBy('created_at', 'desc');

        if ($user->role === 'asesor') {
            $query->whereHas('materia', function ($q) use ($user) {
                $q->where('asesor_id', $user->id);
            });
        }

        $grades = $query->get()->map(function ($g) {
            $valor = $g->valor;

            return [
                'id'       => $g->id,
                'alumno'   => $g->alumno?->name,
                'materia'  => $g->materia?->nombre,
                'clave'    => $g->materia?->clave,
                'promedio' => $valor,
                'estado'   => $valor >= 70 ? 'aprobada' : 'reprobada',
            ];
        });

        // opciones para el formulario
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

        $alumnosOptions = User::where('role', 'alumno')
            ->orderBy('name')
            ->select('id', 'name', 'email')
            ->get()
            ->map(function ($u) {
                return [
                    'id'    => $u->id,
                    'label' => "{$u->name} ({$u->email})",
                ];
            });

        return Inertia::render('Grades/Index', [
            'modo'            => 'docente',
            'calificaciones'  => $grades,
            'materiasOptions' => $materiasOptions,
            'alumnosOptions'  => $alumnosOptions,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (!in_array($user->role, ['asesor', 'admin'])) {
            abort(403, 'Solo docentes o administradores pueden registrar calificaciones.');
        }

        $data = $request->validate([
            'materia_id'    => 'required|exists:materias,id',
            'alumno_id'     => 'required|exists:users,id',
            'valor'         => 'required|integer|min:0|max:100',
            'oportunidad'   => 'nullable|string|max:100',
            'observaciones' => 'nullable|string',
        ]);

        if ($user->role === 'asesor') {
            Materia::where('id', $data['materia_id'])
                ->where('asesor_id', $user->id)
                ->firstOrFail();
        }

        Grade::updateOrCreate(
            [
                'materia_id' => $data['materia_id'],
                'alumno_id'  => $data['alumno_id'],
            ],
            [
                'valor'         => $data['valor'],
                'oportunidad'   => $data['oportunidad'] ?? null,
                'observaciones' => $data['observaciones'] ?? null,
                'created_by_id' => $user->id,
            ]
        );

        return back()->with('success', 'Calificación guardada correctamente.');
    }

    public function sendEmail(Request $request, Grade $grade)
    {
        $user = $request->user();

        if (!in_array($user->role, ['asesor', 'admin'])) {
            abort(403);
        }

        if (!$grade->alumno || !$grade->alumno->email) {
            return back()->with('error', 'El alumno no tiene correo configurado.');
        }

        // Si es asesor, valida que la materia sea suya
        if ($user->role === 'asesor') {
            if (!$grade->materia || $grade->materia->asesor_id !== $user->id) {
                abort(403);
            }
        }

        Mail::to($grade->alumno->email)
            ->send(new GradeNotificationMail($grade));

        return back()->with('success', 'Calificación enviada por correo al alumno.');
    }

}
