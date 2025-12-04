@component('mail::message')
# Hola {{ $alumno->name }}

Se ha registrado o actualizado una calificación en la materia **{{ $materia->nombre }} ({{ $materia->clave }})**.

- Calificación: **{{ $grade->valor }}**
- Estado: **{{ $grade->valor >= 70 ? 'Aprobada' : 'Reprobada' }}**

@if($grade->oportunidad)
- Oportunidad: {{ $grade->oportunidad }}
@endif

@if($grade->observaciones)
- Observaciones: {{ $grade->observaciones }}
@endif

@component('mail::button', ['url' => url('/')])
Ir a la plataforma
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
