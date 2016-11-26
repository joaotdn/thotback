<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfessorBancas extends Model
{
    public $timestamps = false;
    protected $fillable = [  'professor_id', 'datas_disponiveis' ];
    protected $casts = [
        'datas_disponiveis' => 'array'
    ];

    public function professores()
    {
        return $this->hasMany('App\User');
    }
}
