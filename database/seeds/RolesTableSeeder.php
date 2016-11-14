<?php

use App\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        //Orientadores
        $orientador = new Role();
        $orientador->name = 'orientador';
        $orientador->display_name = 'Orientador';
        $orientador->description = 'Usuário que irá avaliar as bancas';
        $orientador->save();

        //Administradores
        $admin = new Role();
        $admin->name = 'admin';
        $admin->display_name = 'Administrador';
        $admin->description = 'Usuário que irá administrar o sistema';
        $admin->save();

        //Alunos
        $aluno = new Role();
        $aluno->name = 'aluno';
        $aluno->display_name = 'Aluno';
        $aluno->description = 'Usuário que irá ser avaliado por orientadores';
        $aluno->save();
    }
}
