<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class VerifyApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $req, Closure $next)
    {
        if(isset($req->api_token)){
            //Buscar al usuario
            $apitoken = $req->api_token; //pasar en Postman en params, no Json
            
            //Pasar usuario
            if($user = User::where('api_token', $apitoken)->first()){
                $user = User::where('api_token', $apitoken)->first();
                $response['msg'] = "Token correcto";
                $req->user = $user;
                return $next($req);
            }else{
                 //Error
                die("Token incorrecto");
            }
        }else{
            $response['msg'] = "Token no introducido";
        }
        
        return response()->json($response);
    }
}
