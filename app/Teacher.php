<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [];

    public $timestamps = false;

    public function teacherable()
    {
        return $this->morphTo();
    }
}
