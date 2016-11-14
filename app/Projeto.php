<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Projeto extends Model
{
    protected $fillable = [
        'nome', 'hora', 'aluno', 'curso_id', 'orientador_id', 'area_secundaria',
        'area_primaria', 'sala'
    ];

    public function curso()
    {
        return $this->belongsTo('App\Curso');
    }

    public function orientador()
    {
        return $this->belongsTo('App\User');
    }
}
