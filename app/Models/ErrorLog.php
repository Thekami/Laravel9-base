<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Log;

class ErrorLog extends Model
{
    use HasFactory;

    protected $table = 'error_logs';
    
    protected $fillable = ['user_id', 'controller', 'method', 'message', 'json'];

    /**
     * Save an error log
     * @return void
     */
    public function saveErrorLog($user_id, $controller, $method, $error){

        try {
            ErrorLog::create([
                "user_id"    => $user_id,
                "controller" => $controller,
                "method"     => $method,
                "message"    => $error->getMessage(),
                "json"       => json_encode($error)
            ]);
        } catch (\Exception $e) {
            Log::error("Falló al insertar error_log", [
                "user_id"    => $user_id,
                "controller" => $controller,
                "method"     => $method,
                "message"    => $error->getMessage(),
                "json"       => json_decode(json_encode($error), 1)
            ]);
        }

    }
}
