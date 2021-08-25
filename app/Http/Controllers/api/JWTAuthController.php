<?php

namespace App\Http\Controllers\api;


use App\User;
use Throwable;
use App\Mail\SendMail;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreUserPut;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\StoreRegisterPost;
use App\Http\Requests\RegisterAuthRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Mail\SendMailreset;
use App\Mail\SendMailVerify;
use Tymon\JWTAuth\Exceptions\JWTException;


class JWTAuthController extends ApiResponseController
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

            $userinsert = DB::select('select users.id from users where email = ?', [$request['email']]);
            $data['confirmation_code'] = Str::random(6);
            $data['name'] = $request['name'];
            $data['email'] = $request['email'];

            if ($userinsert == null) {

                $user = new  User();
                $user->name = $request['name'];
                $user->email = $request['email'];
                $user->password = bcrypt($request['password']);
                $user->rol_id = 2;
                $user->confirmation_code = $data['confirmation_code'];
                $user->save();

                // Send confirmation code

                $result = $this->sendEmailVerify($data);
            } else {
                return $this->successResponse(['message' => 'El usuario ' . $request->email . ' ya esta insertado']);
            }
        } else {
            return $this->successResponse(['message' => 'Error al validar']);
        }
    }

    public function sendEmailVerify($data)
    {

        $title = 'Se ha registrado en ABRACADABRA!!!! Gracias por elegirnos';
        $customer_details = [
            'name' => $data['name'],
            'email' => $data['email'],
            'confirmation_code' => $data['confirmation_code']
        ];
        $sendmail = Mail::to($customer_details['email'])
            ->send(new SendMailVerify($title, $customer_details));
        if (empty($sendmail)) {

            return $this->successResponse(['message' => 'Mensaje Enviado Correctamente.']);
        } else {
            return $this->successResponse(['message' => 'Error al enviar el mensaje.']);
        }
    }


    public function verify($code)
    {
        $user = User::where('confirmation_code', $code)->first();
        if (!$user) {
            //redireccionar al register o mostrar una notificacion de que tiene que activar su cuenta
            return $this->successResponse(['message' => 'el codigo de confirmacion es incorrecto ']);
        }
        $user->confirmed = true;
        $user->confirmation_code = null;
        $user->active = 'active';
        $user->save();

        //ver q devolver aqui para redireccionar a la vista del login
        return $this->successResponse(['message' => 'El usuario ' . $user->email . ' Ha confirmado correctamente su correo']);
    }

    public function login(Request $request)
    {
        $id = DB::select('select users.id from users where email = ?', [$request['email']]);
        $user = User::find($id[0]->id);
        $v_user = User::where('email', '=', $request['email']);
        if ($v_user != null) {
            if ($user->active == 'active') {

                $credentials = $request->only(['email', 'password']);
                $payloadable = $user->getJWTCustomClaims();
                $jwt_token = null;
                if (!$jwt_token = Auth::attempt($credentials)) {
                    return  response()->json([
                        'status' => 'invalid_credentials',
                        'message' => 'Correo o contraseña no válidos.',
                    ], 401);
                }

                $jwt_token = Auth::fromUser($user, $payloadable);
                $user = Auth::authenticate($request->token);

                return  response()->json([
                    'status' => 'Se ha logueado correctamente',
                    'token' => $jwt_token,
                    'data' => $user,
                ]);
            }

            return  response()->json([
                'status'=>'active',
                'message' => 'El usuario ' . $user->name . ' Debe activar su cuenta en Abracadabra, revise su cuenta de correo',
            ]);
        }
    }

    public  function  getAuthUser(Request  $request)
    {
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

    public  function  logout(Request  $request)
    {

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
            'token' => $token_new
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
            'expires_in' => Auth::factory()->getTTL() * 480
        ]);
    }


    //Verificacion de TOKEN!!!!

    public function validateToken()
    {

        //obtener token request tipo GET
        $headers = getallheaders();
        $tokenArray = $headers['Authorization'];
        $tokenX = explode(' ', $tokenArray);
        $token = $tokenX[1];
        //buscar manera de decodificar este token obtenido y verificar rol e id user

        //obtener token request tipo POST
        //$token = $request->bearerToken();

    }


    // devuelve todos los users insertados en el sistema

    public function index()
    {
        $users = User::all();
        return $this->successResponse(['users' => $users, 'message' => 'User retrieved successfully.']);
    }


    public function update(Request $request, User $user)
    {
        $v_user = new StoreUserPut();
        $validator = $request->validate($v_user->rules());

        if ($validator) {
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->rol_id = $request['rol_id'];
            $user->active = $request['active'];
            $user->save();

            return $this->successResponse(['user' => $user, 'message' =>  'User updated successfully.']);
        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return $this->successResponse(['message' => 'User deleted successfully.']);
    }

    //Restablecer contraseña
    public function resetPassword(Request $request){

        $v_user = User::where('email', '=', $request['email']);
        if ($v_user != null) {

            $userResetPassword = DB::select('select users.* from users where email = ?', [$request['email']]);
            $data['code_reset_password'] = Str::random(6);
            $data['name'] = $userResetPassword['name'];
            $data['email'] = $request['email'];
            $this->sendEmailResetPassword($data);
            $user = User::find($userResetPassword[0]->id);
            $user->code_reset_password = $data['code_reset_password'];
            $user->save();
        }
        else{

            return $this->successResponse(['message' => 'Usted no se encuentra registrado en Abracadabra.']);
        }
    }

    //funcion para actualizar password del usuario recibe(email, password, code)
    public function updatePassword(Request $request,User $user){

        $user = User::where('code_reset_password', $request['code'])->first();
        if (!$user) {
            //redireccionar al register o mostrar una notificacion de que tiene que activar su cuenta
            return $this->successResponse(['message' => 'el codigo de confirmacion es incorrecto ']);
        }
        $v_user = new UpdatePasswordRequest();
        $validator = $request->validate($v_user->rules());
        if ($validator) {
            $user->password = $request['password'];
            $user->code_reset_password = null;
            $user->save();

            return $this->successResponse(['user' => $user, 'message' =>  'User updated successfully.']);
        }
        return response()->json([
            'message' => 'Error al validar'
        ], 201);
    }

    public function sendEmailResetPassword($data)
    {
        $title = 'Restablecer Contraseña Abracadabra!!!';
        $customer_details = [
            'name' => $data['name'],
            'email' => $data['email'],
            'code_reset_password' => $data['code_reset_password']
        ];
        $sendmail = Mail::to($customer_details['email'])
            ->send(new SendMailreset($title, $customer_details));
        if (empty($sendmail)) {

            return $this->successResponse(['message' => 'Mensaje Enviado Correctamente.']);
        } else {
            return $this->successResponse(['message' => 'Error al enviar el mensaje.']);
        }
    }
}