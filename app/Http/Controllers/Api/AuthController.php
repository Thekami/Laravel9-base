<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\ErrorLog;
use App\Models\User;
use Auth;
use App\Traits\ApiResponseTrait;

class AuthController extends Controller
{
    use ApiResponseTrait;
    
    protected $env;

    public function register(RegisterRequest $request){
        
        $this->env = config('app.env');

        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'username' => $request->sername,
                'password' => \Hash::make($request->password)
            ]);
        
            return $this->responseCreated($user);

        } catch (\Exception $e) {

            $ErrorLog = new ErrorLog();
            $ErrorLog->saveErrorLog(Auth::id(), "AuthController", 'Register', $e);
            
            return $this->env == 'local' 
                ? $this->responseError($e->getMessage()) 
                : $this->responseError();
        }
    }
}
