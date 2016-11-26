<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Http\Requests;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Curso::all();
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
            'nome' => 'required|unique:cursos'
        ]);

        /**
         * O curso já existe
         */
        if ($validator->fails()) {
            $curso = Curso::where('nome','=',$request['nome'])->first();

            $message = [
                'msg' => 'Este curso já foi cadastrado',
                'curso' => $curso
            ];
            return response()->json($message, 401);
        }

        /**
         * Parâmetros do usuário
         */
        $input = Input::all();
        $curso = new Curso();
        if( $input['nome'] ) {
            $curso->nome = $input['nome'];
        }

        /**
         * Cadastrar o curso
         */
        if($curso->save()) {
            $message = [
                'msg' => 'Curso adicionado com sucesso',
                'curso' => $curso
            ];

            return response()->json($message, 201);
        }

        $response = [
            'msg' => 'Um erro ocorreu. Tente novamente.'
        ];

        return response()->json($response, 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $curso = Curso::where('id',$id)->firstOrFail();

        $message = [
            'msg' => 'Curso encontrado',
            'curso' => $curso->nome,
            'curso_id' => $curso->id
        ];

        return response()->json($message, 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $curso = Curso::findOrFail($id);

        if(! $curso->delete()) {
            return response()->json(['msg' => 'Não foi possível deletar'], 404);
        }

        $message = [
            'msg' => 'Curso removido com sucesso',
            'curso' => $curso
        ];

        return response()->json($message, 201);
    }
}
