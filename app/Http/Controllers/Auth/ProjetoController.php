<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Projeto;
use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Validator;

class ProjetoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Projeto::all();
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
            'nome' => 'required|unique:projetos',
            'aluno' => 'required|unique:projetos',
            'orientador_id' => 'required|integer',
            'area_primaria' => 'required',
            'area_secundaria' => 'required',
            'data' => 'required|date_format:d/m/Y',
            'hora' => 'required|unique:projetos|date_format:H:i',
            'sala' => 'required'
        ]);

        /**
         * Dados não validados
         */
        if ($validator->fails()) {
            $message = [
                'msg' => $validator->errors()->all(),
                'redirect' => false
            ];
            return response()->json($message, 201);
        }

        $input = Input::all();
        $orientador = User::find($input['orientador_id']); // orientador

        /**
         * Cadastre se o objeto tiver orientador
         */
        if($orientador) {

            $projeto = new Projeto();

            $projeto->nome = $input['nome'];
            $projeto->aluno = $input['aluno'];
            $projeto->orientador_id = $input['orientador_id'];
            $projeto->curso_id = $input['curso_id'];
            $projeto->area_primaria = $input['area_primaria'];
            $projeto->area_secundaria = $input['area_secundaria'];
            $projeto->data = Carbon::createFromFormat('d/m/Y', $input['data']);
            $projeto->hora = Carbon::createFromFormat('H:i', $input['hora']);
            $projeto->sala = $input['sala'];

            if($projeto->save()) {
                $message = [
                    'msg' => 'Projeto adicionado com sucesso',
                    'redirect' => true,
                    'projeto' => $projeto
                ];

                return response()->json($message, 201);
            }

        }

        return response()->json(['msg' => 'Ocorreu algum erro. Tente novamente.', 'redirect' => false], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $projeto = Projeto::find($id);
        return $projeto;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $projeto = Projeto::find($id);

        if(!$projeto) {
            $response = [
                'msg' => 'Projeto não encontrado',
                'class' => 'alert alert-dismissible alert-danger'
            ];
            return response()->json($response, 404);
        }

        $fields = Input::all();
        $fields['data'] = Carbon::createFromFormat('d/m/Y', $fields['data']);
        $fields['hora'] = Carbon::createFromFormat('H:i', $fields['hora']);

        try {
            if($projeto->update($fields)) {

                $response = [
                    'msg' => 'Projeto salvo com sucesso',
                    'class' => 'alert alert-dismissible alert-success',
                    'projeto' => $projeto
                ];

                return response()->json($response, 202);
            }
        } catch (\Exception $e) {
            $response = [
                'msg' => 'Não foi possível salvar o projeto',
                'class' => 'alert alert-dismissible alert-danger',
                'error' => $e
            ];

            return response()->json($response, 404);
        }

        $response = [
            'msg' => 'Não foi possível salvar o projeto',
            'class' => 'alert alert-dismissible alert-danger'
        ];

        return response()->json($response, 404);
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
