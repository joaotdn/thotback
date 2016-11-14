<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
//    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('guest', ['except' => 'logout']);
//    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email','password');

        try {
            if(! $token = JWTAuth::attempt($credentials)) {
                return $this->response->errorUnauthorized();
            }

        } catch (JWTException $e) {
            return $this->response->errorInternal();
        }

        return $this->response->array(compact('token'))->setStatusCode(200);
    }

    public function index()
    {
        return User::all();
    }

    public function getUser()
    {
        try {
            $user = JWTAuth::parseToken()->toUser();
            if(!$user)
                return $this->response->errorNotFound("Usuário não encontrado");

        } catch (TokenInvalidException $e) {
            return $this->response->error("Ocorreu algum erro interno");
        }

        return $this->response->array(compact('user'))->setStatusCode(200);
    }

    /**
     * Refresh token and send back to client
     */
    public function getToken()
    {
        $token = JWTAuth::getToken();

        if(! $token) {
            return $this->response->errorUnauthorized("Token inválido");
        }

        try {
            $refreshedToken = JWTAuth::refresh($token);
        } catch (JWTException $e) {
            return $this->response->error("Ocorreu algum erro interno");
        }

        return $this->response->array(compact('refreshedToken'));
    }

    public function destroy()
    {
        $user = JWTAuth::parseToken()->authenticate();

        if(! $user) {

        }

        $user->delete();
    }
}
