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
    public function handle(Request $request, Closure $next)
    {
        $response = ["status" => 1, "msg" => ""];
    	$data = $req->getContent();
        $data = json_decode($data);

        //Buscar al usuario
        $apitoken = $req->api_token; //pasar en Postman en params, no Json

        //Pasar user
        $user = User::where('api_token', $apitoken)->first(); 

        if(!$user){
            //si no hay usuario
            //Error
            $response['msg'] = "El usuario no existe.";
            return response()->json($response);
        }else{
            $request->user = $user;
            return $next($request);
        }
    }
}
