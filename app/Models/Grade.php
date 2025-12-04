<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'materia_id',
        'alumno_id',
        'created_by_id',
        'valor',
        'oportunidad',
        'observaciones',
    ];

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }

    public function alumno()
    {
        return $this->belongsTo(User::class, 'alumno_id');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
