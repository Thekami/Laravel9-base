<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\ErrorLog;
use App\Models\User;
use Auth;
use App\Traits\ApiResponseTrait;
use Exception;

class AuthController extends Controller
{
    use ApiResponseTrait;
    
    public $env;
    protected $controller = "AuthController";

    public function register(RegisterRequest $request){
        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'username' => $request->username,
                'password' => \Hash::make($request->password)
            ]);
        
            return $this->responseCreated($user);

        } catch (Exception $e) {
            return $this->catchError(Auth::id(), $e, $this->controller, 'register');
        }
    }

    public function login(LoginRequest $request){
        
        try {
            $credentials = ['password' => $request->password];

            // Check if user field contains an email or username
            $field = filter_var($request->user, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            // Make a credentials array
            $credentials = [
                $field => $request->user,
                'password' => $request->password,
            ];

            // Validate credentials
            if(Auth::attempt($credentials)){

                $user = auth()->user();

                return $this->responseOk([
                    'user' => $user,
                    'access_token' => $user->createToken('auth_token')->plainTextToken,
                    'token_type' => 'Bearer'
                ]);
            }
            else{
                return $this->responseUnautorized('Usuario o contraseña incorrectos');
            }
        } catch (Exception $e) {
            return $this->catchError(Auth::id(), $e, $this->controller, 'login');
        }
    }

    protected function catchError($user_id, $error, $controller, $method){

        $this->env = config('app.env');
        
        // Send error to error_logs table
        $ErrorLog = new ErrorLog();
        $ErrorLog->saveErrorLog($user_id, $controller, $method, $error);
        
        // Validate if send error to end user or not
        return $this->env == 'local' 
            ? $this->responseError($error->getMessage()) 
            : $this->responseError();
    }
}
