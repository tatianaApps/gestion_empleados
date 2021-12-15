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
    public function handle(Request $request, Closure $next) //hay que sacar el usuario no por id, sino por apitoken ??
    {
        //Comprobar los permisos
        if($request->user->position == 'management' || $request->user->position == 'human_resources')
            return $next($request);
        else{
            //Error
        }
        
    }
}
