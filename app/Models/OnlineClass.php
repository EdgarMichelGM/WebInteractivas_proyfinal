<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OnlineClass extends Model
{
    protected $fillable = [
        'materia_id',
        'started_by_id',
        'titulo',
        'link',
        'is_active',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    public function materia()
    {
        return $this->belongsTo(Materia::class);
    }

    public function docente()
    {
        return $this->belongsTo(User::class, 'started_by_id');
    }
}
