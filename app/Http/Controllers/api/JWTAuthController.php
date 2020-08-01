<?php

namespace App\Http\Controllers\api;


use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreRegisterPost;
use App\Http\Requests\RegisterAuthRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
// use Illuminate\Support\Facades\Validator;


class JWTAuthController extends Controller
{

    public  $loginAfterSignUp = true;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     */

    public function register(Request $request)
    {

        $v_user = new StoreRegisterPost();
        $validator = $request->validate($v_user->rules());

        if ($validator) {
            $user = new  User();
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->password = bcrypt($request['password']);
            $user->rol_id = 1;
            $user->save();

            if ($this->loginAfterSignUp) {
                return  $this->login($request);
            }

            return  response()->json([
                'status' => 'the user ir register correctli',
                'data' => $user
            ], 200);
        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);

    }


    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        $jwt_token = null;
        if (!$jwt_token = Auth::attempt($credentials)) {
            return  response()->json([
                'status' => 'invalid_credentials',
                'message' => 'Correo o contraseña no válidos.',
            ], 401);
        }

        $user = Auth::authenticate($request->token);
        return  response()->json([
            'status' => 'Se ha logueado correctamente',
            'token' => $jwt_token,
            'data' => $user,
        ]);
    }


    public  function  getAuthUser(Request  $request) {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = Auth::authenticate($request->token);
        return  response()->json(['user' => $user]);
    }

      /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public  function  logout(Request  $request) {

        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            Auth::invalidate($request->token);
            return  response()->json([
                'status' => 'ok',
                'message' => 'Cierre de sesión exitoso.'
            ]);
        } catch (JWTException  $exception) {
            return  response()->json([
                'status' => 'unknown_error',
                'message' => 'Al usuario no se le pudo cerrar la sesión.'
            ], 500);
        }
    }

      /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
       $token_new = $this->createNewToken(Auth::refresh());
        return response()->json([
            'status' => 'ok',
            'message' => 'Refresh exitoso',
            'token'=> $token_new
        ]);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }



}
