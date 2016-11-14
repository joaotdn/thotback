<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {

        $cadastrarProfessor = new Permission();
        $cadastrarProfessor->name = 'cadastrar-professor';
        $cadastrarProfessor->display_name = 'Cadastrar professor';
        $cadastrarProfessor->description = 'Cadastrar um novo professor no sistema';
        $cadastrarProfessor->save();

        $cadastrarBanca = new Permission();
        $cadastrarBanca->name = 'cadastrar-banca';
        $cadastrarBanca->display_name = 'Cadastrar banca';
        $cadastrarBanca->description = 'Cadastrar uma nova banca com projeto';
        $cadastrarBanca->save();

    }
}
