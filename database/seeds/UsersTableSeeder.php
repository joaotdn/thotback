<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        \App\User::create([
            'name' => 'Administrador',
            'email' => 'admin@thot.com',
            'password' => Hash::make('123mudar'),
            'role' => 'admin'
        ]);
    }
}
