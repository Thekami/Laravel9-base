<?php

// app/Traits/ApiResponseTrait.php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponseTrait
{

    protected $successMsg = "Acción realizada correctamente";
    protected $errorMsg = "Sucedió un error inesperado, intentelo mas tarde o pongase en contacto con el administrador del sistema";
    protected $notFoundMsg = "Recuerso no encontrado";

    protected function responseCreated($data = []){
        return response([
            'success' => true,
            'data' => $data,
        ], Response::HTTP_CREATED);
    }

    protected function responseOk($data = []){
        return response([
            'success' => true,
            'data' => $data,
        ], Response::HTTP_OK);
    }

    protected function responseNotFound($message = null){
        return response([
            'success' => false,
            'message' => is_null($message) ? $this->notFoundMsg : $message,
            'data' => [],
        ], Response::HTTP_NOT_FOUND);
    }

    protected function responseError($error = '', $message = null, $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return response([
            'success' => false,
            'message' => is_null($message) ? $this->errorMsg : $message,
            'error' => $error,
        ], $statusCode);
    }

    protected function responseSuccess($data = [], $statusCode = Response::HTTP_OK)
    {
        return response([
            'success' => true,
            'data' => $data,
        ], $statusCode);
    }
}
