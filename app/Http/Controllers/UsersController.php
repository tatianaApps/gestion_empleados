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
    	
        $validator = Validator::make(json_decode($req->getContent(), true), [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:App\Models\User,email|max:50|regex:/^([_a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix',
            'password' => 'required|regex:/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{6,}/',
            'position' => 'required|in:management,human_resources,employee',
            'salary' => 'required|numeric',
            'biography' => 'required|max:200'
        ]);

        if ($validator->fails()) {
            //Preparar la respuesta 
            $response['status'] = 0;
    		$response['msg'] = $validator->errors();
            return response()->json($response);
        }else {
            $data = $req->getContent();
            //Validar el json
            $data = json_decode($data);

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
    }

    public function login(Request $req){
        
        $response = ["status" => 1, "msg" => ""];
    	$data = $req->getContent();
        $data = json_decode($data);

        //Buscar el email
        $email = $req->email;
        $password = $req->password;

        //Validar
        /*$validator = Validator::make(json_decode($req->getContent(), true), [
            'email' => 'required|email|unique:App\Models\User,email|max:50|regex:/^([_a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix'
        ]);

        if ($validator->fails()) {
            $response['status'] = 0;
    		$response['msg'] = $validator->errors();
            return response()->json($response);
        }*/

        //Encontrar al usuario con ese email
        $user = User::where('email', $email)->first();

        //Pasar la validación ??

        //Comprobar la contraseña
        if (Hash::check($req->password, $user->password)) {
            //Todo correcto

            //Generar el api token
            do{
                $token = Hash::make($user->id.now());
            }while(User::where('api_token', $token)->first()); //encontrar a un usuario con ese apitoken

            $user->api_token = $token;
            $user->save();

            $response['msg'] = "Login correcto ".$user->api_token;
            return response()->json($response); //Incluye el api token
        }else{
            //Login mal
            $response['msg'] = "La contraseña no es correcta";
            return response()->json($response);
        }
    }

    public function recoverPassword(Request $req){
        
        //Obtener el email y validarlo como en el login
        $response = ["status" => 1, "msg" => ""];
        $data = $req->getContent();
        $data = json_decode($data);

        //Buscar el email
        $email = $data->email;

        //Validar
        $validator = Validator::make(json_decode($req->getContent(), true), [
            'email' => 'required|email|unique:App\Models\User,email|max:50|regex:/^([_a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix'
        ]);

        if ($validator->fails()) {
            $response['status'] = 0;
    		$response['msg'] = $validator->errors();
            return response()->json($response);
        }else {
            //Encontrar el usuario con ese email
            $user = User::where('email', $email)->first();
        }

            //Pasar la validación
         
        //Si encontramos al usuario
        $user->api_token = null;

        //Generar nueva contraseña aleatoriamente (función para generar strings aleatorios)
        $password = 

        $user->password = Hash::make($password); //guardamos la nueva

        //Enviarla por email

        //Temporal: devolver la nueva contraseña en la respuesta, se devuelve la de arriba, password = aleatorio
    }

}
