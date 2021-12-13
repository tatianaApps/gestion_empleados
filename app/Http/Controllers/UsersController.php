<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UsersController extends Controller
{
    /*public function register(){
        return view ('users.register',['title' => 'Registrar empleado', 'idBody' => ""]);
        $password = $req->password;
        $valid = true;
    }*/

    public function registerUser(Request $req){

        $response = ["status" => 1, "msg" => ""];
    	$data = $req->getContent();

        //Validar el json
        $data = json_decode($data);

        $validator = Validator::make(json_decode($req->getContent(), true), [
            'name' => 'required|max:50',
            'email' => 'required|unique:users|max:255|regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'password' => 'required|min:6|regex:/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/',
            'position' => 'required|max:20',
            'salary' => 'required|max:50|numeric|between:0,999999999.99',
            'biography' => 'required|max:500'
        ]);

        if ($validator->fails()) {
            //Preparar la respuesta
            $response = ['msg'] = "El  ya existe, pruebe con otro";
            
                }else {
                $user->save();
                $response['msg'] = "Usuario guardado con id ".$user->id;
                }    
            }catch(\Exception $e){
            $respuesta['status'] = 0;
            $respuesta['msg'] = "Se ha producido un error: ".$e->getMessage();
            }

         return response()->json($respuesta);
        }
            ];
        }
        
        //Generar usuario
        $user = new User();

        $user->name = $data->name;
        $user->email = $data->email;
        $user->password = $data->password;
        $user->position = $data->position;
        $user->salary = $data->salary;
        $user->biography = $data->biography;
        
        /*$user->email = $validator['email'];
        $user->password = Hash::make($validator['password']);
        $user->position = $validator['position'];
        $user->salary = $validator['salary'];
        $user->biography = $validator['biography'];*/

        try{
            if (User::where('email', '=', $data->email)->first()) { //first comprueba la primera coincidencia
                $response['msg'] = "El email ya existe, pruebe con otro";
            }else {
                $user->save();
                $response['msg'] = "Usuario guardado con id ".$user->id;
            } 
    	}catch(\Exception $e){
    		$response['status'] = 0;
    		$response['msg'] = "Se ha producido un error: ".$e->getMessage();
    	}
    	return response()->json($response);
    }

   

}
