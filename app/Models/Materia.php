<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    protected $fillable = [
        'nombre',
        'clave',
        'asesor_id',
        'descripcion',
    ];

    public function asesor()
    {
        return $this->belongsTo(User::class, 'asesor_id');
    }
}
