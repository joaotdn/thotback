<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\ProfessorBancas;
use App\ProfessorDatas;
use App\Projeto;
use App\Teacher;
use App\User;
use Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::with('teachers')->where('name','!=','Administrador')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:users',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6',
            'area_primaria' => 'required',
            'area_secundaria' => 'required',
            'role' => 'required|in:professor,professor-moderador'
        ]);

        if ($validator->fails()) {
            $message = [
                'msg' => 'O professor já foi cadastrado',
            ];

            return response()->json($message, 201);
        }

        $input = Input::all();

        $user = new User();

        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->password = bcrypt($input['password']);
        $user->role = $input['role'];


        if($user->save()) {
            $user = User::find($user->id);

            $teacher = new Teacher();

            $teacher->area_primaria = $input['area_primaria'];
            $teacher->area_secundaria = $input['area_secundaria'];

            $user->teachers()->save($teacher);

            $message = [
                'msg' => 'Professor adicionado com sucesso',
                'professor' => $user
            ];

            return response()->json($message, 201);
        }

        $message = [
            'msg' => 'Prfessor adicionado com sucesso',
        ];

        return response()->json($message, 401);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
	    $usuario = User::with('teachers')->where('id',$id)->firstOrFail();

	    $usuario->view_usuario = [
		    'href' => 'api/v1/usuario/',
		    'method' => 'GET'
	    ];

	    $response = [
		    'msg' => '',
		    'usuario' => $usuario,
		    'senha' => $usuario->password
	    ];

	    return response()->json($response, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::with('teachers')->find($id);

        if(!$user) {
            $response = [
                'msg' => 'Usuário não encontrado',
                'class' => 'alert alert-dismissible alert-danger'
            ];
            return response()->json($response, 404);
        }

        $fields = Input::all();

        if($user->update($fields)) {

            Teacher::where('teacherable_id',$id)->update([
                'area_primaria' => $fields['area_primaria'],
                'area_secundaria' => $fields['area_secundaria']
            ]);

            $response = [
                'msg' => 'Usuário salvo com sucesso',
                'class' => 'alert alert-dismissible alert-success',
                'usuario' => $user
            ];

            return response()->json($response, 201);

        }

        $response = [
            'msg' => 'Não foi possível salvar o usuário',
            'class' => 'alert alert-dismissible alert-danger'
        ];

        return response()->json($response, 404);
    }

    /**
     * Lista os projetos com areas de interesse semelhantes
     * ao do professor pelo id dele
     * @param $user_id id do professor
     */
    public function getProjetosProfessor($user_id)
    {
        // $usuario = User::find($user_id);
        $professor = Teacher::where('teacherable_id',$user_id)->firstOrFail();

        $area1 = $professor->area_primaria;
        $area2 = $professor->area_secundaria;

        // lista projetos em que pelo menos um examinador está nulo
        $projetos = Projeto::where('examinador_1',null)
                             ->orWhere('examinador_2',null)
                             ->get();

        $projetosAreas = array();

        foreach ($projetos as $projeto) {
            $areas = array(
                $projeto->area_primaria,
                $projeto->area_secundaria
            );

            if (in_array($area1, $areas) || in_array($area2, $areas)) {
                $projetosAreas[] = $projeto;
            }
        }

        return response()->json($projetosAreas, 201);
    }

    public function enviaDatasDisponiveis(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'professor_id' => 'required|unique:professor_datas',
            'datas_disponiveis' => 'required|array'
        ]);

        if ($validator->fails()) {
            $message = [
                'msg' => $validator->errors()->all(),
            ];

            return response()->json($message, 201);
        }

        $professor = User::findOrFail($id);
        $inputs = Input::all();

        if($professor) {
            $datas_disponiveis = new ProfessorDatas($inputs);
            if($datas_disponiveis->save()) {
                $message = [
                    'msg' => 'Datas enviadas com sucesso',
                    'professor' => $professor
                ];

                return response()->json($message, 201);
            }
        }

        return $inputs;
    }

    /**
     * Verifica se o professor ja submeteu
     * as datas disponiveis
     * @param $id
     * @return string
     */
    public function verificaProfessorAlocado($id)
    {
        $professor_banca = ProfessorBancas::where('professor_id',$id)->first();

        if( $professor_banca ) {
            $message = [
                'msg' => 'checked'
            ];

            return response()->json($message, 201);
        }

        $message = [
            'msg' => 'unchecked'
        ];

        return response()->json($message, 201);
    }

    public function listaProfessorBanca($professor_id) {

        $bancas = ProfessorBancas::where('professor_id',$professor_id)->get();
        return $bancas;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
