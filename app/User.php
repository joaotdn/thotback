<?php

namespace App;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable, EntrustUserTrait, CanResetPassword;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'name', 'password', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getFillable() {
        return $this->fillable;
    }

    public function teachers()
    {
        return $this->morphMany('\App\Teacher', 'teacherable');
    }

    public function projetos()
    {
        return $this->hasMany('App\Projeto');
    }

    public function bancas()
    {
        return $this->belongsTo('App\ProfessorBancas');
    }
}
