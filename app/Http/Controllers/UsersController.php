<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersController extends Controller
{
    public function registerUser(Request $req){

        $response = ["status" => 1, "msg" => ""];
    	$data = $req->getContent();

        //Validar el json
        $data = json_decode($data);

        $validator = Validator::make(json_decode($req->getContent(), true), [
            'name' => ['required', 'max:50'],
            'email' => ['required', 'email', 'unique:users', 'max:255', 'regex:/^([_a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix'],
            'password' => ['required', 'min:6', 'regex:/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])/'],
            'position' => ['required', 'max:20'],
            'salary' => ['required', 'max:50', 'numeric', 'between:0,999999999.99'],
            'biography' => ['required', 'max:500'],
        ]);

        if ($validator->fails()) {
            //Preparar la respuesta 
            $response['msg'] = "Los datos no son correctos, pruebe de nuevo";
            return response()->json($response);
        }
        
        //Generar usuario
        $user = new User();

        $user->name = $data->name;
        $user->email = $data->email;
        $user->password = Hash::make($data->password);
        $user->position = $data->position;
        $user->salary = $data->salary;
        $user->biography = $data->biography;
        
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

    public function login(Request $req){
        
        $response = ["status" => 1, "msg" => ""];
    	$data = $req->getContent();

        //Validar el json
        $data = json_decode($data);

        //Buscar el email
        $email = $req->email;

        //Validar

        //Encontrar al usuario con ese email

        $user = User::where('email', $email)->first();

        //Pasar la validación

        //Comprobar la contraseña
        if (Hash::check($req->password, $user->password)) {
            //Todo correcto

            //Generar el api token
            do{
                $token = Hash::make($user->id.now());
            }while(User::where('api_token', $token)->first()); //encontrar a un usuario con ese apitoken

            $user->api_token = $token;
            $user->save();

            $response['msg'] = "Login correcto  ".$user->api_token;
            return response()->json($response); //Incluye el api token
        }else{
            //Login mal
            $response['msg'] = "La contraseña no es correcta";
            return response()->json($response);
        }
    }

}
