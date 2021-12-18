<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ValidateUserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $req, Closure $next) //hay que sacar el usuario no por id, sino por apitoken 
    {
        //Comprobar los permisos
        if($req->user->position =='management'){ //para ver si es directivo o rrhh y te deje pasar al controller
            /*$req = DB::table('users')
                ->where('position', 'like', 'human_resources')
                ->where('position', 'like', 'employee')
                ->get();*/
            $response['msg'] = "Perfil de director validado";
            return $next($req);
        }else if($req->user->position =='human_resources'){
            /*$req = DB::table('users')
                ->where('position', 'like', 'employee')
                ->get();*/
            $response['msg'] = "Perfil de recursos humanos validado";
            return $next($req);
        }else{
             $response['msg'] = "No tienes permisos para realizar esta función";
            
        }
        return response()->json($response);


        /*if($req->user->position =='management' || $req->user->position =='human_resources'){  
            return $next($req);
        }else{
             $response['msg'] = "No tienes permisos para realizar esta función";
            
        }
        return response()->json($response);****/
    }
}
