<?php

use Illuminate\Http\Request;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:api');

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {

    $api->get('users/{user_id}/roles/{role_name}','App\Http\Controllers\HomeController@attachUserRole');

    $api->get('users/{user_id}/roles','App\Http\Controllers\HomeController@getUserRole');

    $api->post('role/permission/add','App\Http\Controllers\HomeController@attachPermission');

    $api->get('role/{role_name}/permissions','App\Http\Controllers\HomeController@getPermissions');

    $api->post('authenticate', 'App\Http\Controllers\Auth\LoginController@authenticate');

});

$api->version('v1',['middleware' => 'api.auth'], function ($api) {

    /**
     * Usuarios
     */
    $api->get('users','App\Http\Controllers\Auth\LoginController@index');

    $api->get('user','App\Http\Controllers\Auth\LoginController@getUser');

	$api->get('user/{id}','App\Http\Controllers\Auth\TeacherController@show');

    $api->get('user/{id}/bancas','App\Http\Controllers\Auth\TeacherController@getProjetosProfessor');

    $api->get('user/{id}/alocado','App\Http\Controllers\Auth\TeacherController@verificaProfessorAlocado');

	$api->put('user/update/{id}','App\Http\Controllers\Auth\TeacherController@update');

	$api->post('user/{id}/bancas/enviar','App\Http\Controllers\Auth\TeacherController@enviaDatasDisponiveis');

    $api->get('token','App\Http\Controllers\Auth\LoginController@getToken');

    $api->post('delete','App\Http\Controllers\Auth\LoginController@destroy');

    $api->get('teachers','App\Http\Controllers\Auth\TeacherController@index');

    $api->post('novo/professor','App\Http\Controllers\Auth\TeacherController@store');

    /**
     * Bancas
     */
    $api->get('projetos','App\Http\Controllers\Auth\ProjetoController@index');

    $api->put('projeto/update/{projeto_id}','App\Http\Controllers\Auth\ProjetoController@update');

    $api->post('novo/projeto','App\Http\Controllers\Auth\ProjetoController@store');

    $api->get('projetos/{projeto_id}','App\Http\Controllers\Auth\ProjetoController@show');


    /**
     * cursos
     */
    $api->post('novo/curso','App\Http\Controllers\Auth\CursoController@store');

    $api->get('cursos','App\Http\Controllers\Auth\CursoController@index');

    $api->get('delete/curso/{curso_id}','App\Http\Controllers\Auth\CursoController@destroy');

});
