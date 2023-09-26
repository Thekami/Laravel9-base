<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    CONST SUCCESS_MSG = "Correcto";
    CONST ERROR_MSG = "Sucedió un error inesperado";
    public function register(RegisterRequest $request){

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'username' =>$request->username,
                'password' => \Hash::make($request->password)
            ]);
        
            return response([
                'success' => true,
                'data' => $request->all()
            ], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response([
                'success' => true,
                "message" => self::ERROR_MSG,
                "error" => $e->getMessage()
            ]);
        }
    }
}
