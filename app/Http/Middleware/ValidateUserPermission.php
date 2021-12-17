<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;

class ValidateUserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $req, Closure $next) //hay que sacar el usuario no por id, sino por apitoken ??
    {
        //Comprobar los permisos
        if($req->user->position =='management' || $req->user->position =='human_resources'){
            return $next($req);
            $response['msg'] = "Perfil validado";
        }else{
             $response['msg'] = "No tienes permisos para realizar esta función";
            
        }
        return response()->json($response);
    }
}
