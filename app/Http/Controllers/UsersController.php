<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\Notification;
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
        $email = $data->email;
       
        //Encontrar al usuario con ese email
        $user = User::where('email', '=', $data->email)->first();

        //Comprobar si existe el usuario
        if($user){
            if (Hash::check($data->password, $user->password)) { //Comprobar la contraseña
                //Si todo correcto generar el api token
                do{
                    $token = Hash::make($user->id.now());
                }while(User::where('api_token', $token)->first()); //encontrar a un usuario con ese apitoken

                $user->api_token = $token;
                $user->save();
                $response['msg'] = "Login correcto. Api token generado: ".$user->api_token; //Incluye el api token
            }else{
                //Login mal
                $response['status'] = 0;
                $response['msg'] = "La contraseña no es correcta";
            }
        }
        else{
            $response['status'] = 0;
            $response['msg'] = "Usuario no encontrado";
        }
        
        return response()->json($response);
    }

    public function recoverPassword(Request $req){
        
        //Obtener el email y validarlo como en el login
        $response = ["status" => 1, "msg" => ""];
        $data = $req->getContent();
        $data = json_decode($data);

        //Buscar el email
        $email = $req->email;

        //Encontrar al usuario con ese email
        $user = User::where('email', '=', $data->email)->first();

        //Comprobar si existe el usuario
        if($user){
           
            $user->api_token = null;

            //Generar nueva contraseña aleatoriamente (función para generar strings aleatorios)
            $password = "aAbBcCdDeEfFgGhHiIjJkKlLmMnNñÑoOpPqQrRsStTuUvVwWxXyYzZ0123456789";
            $passwordCharCount = strlen($password);
            $passwordLength = 8;
            $newPassword = "";

            for($i = 0; $i < $passwordLength; $i++){
                $newPassword .= $password[rand(0, $passwordCharCount -1)];
            }

            //Enviarla por email
            Mail::to($user->email)->send(new Notification($newPassword));
       
            //Guardamos al usuario con la nueva contraseña cifrada
            $user->password = Hash::make($newPassword);
            $user->save();
            $response['msg'] = "Nueva contraseña generada. Revisa tu correo";

        }
        else{
            $response['status'] = 0;
            $response['msg'] = "Usuario no encontrado";
        }
        
        return response()->json($response);
    }

    public function listEmployee(Request $req){

        $response = ["status" => 1, "msg" => ""];
        $data = $req->getContent();
    	$data = json_decode($data);

        try{
        	$user = DB::table('users')
                ->get();
            $respuesta['empleados'] = $user;
        	
        }catch(\Exception $e){
            $respuesta['status'] = 0;
            $respuesta['msg'] = "Se ha producido un error: ".$e->getMessage();
        }
        return response()->json($respuesta);
    }

    public function seeProfile(Request $req){

        $response = ['status' => 1, "msg" => ""];
        $data = $req->getContent();
        $data = json_decode($data);

        try{
            $user = User::where('api_token', $token)->first();
            $user->api_token = $token;
            $user->api_token = User::find($token);
            $response['datos'] = $user;
        }catch(\Exception $e){
            $response['status'] = 0;
            $response['msg'] = "Se ha producido un error: ".$e->getMessage();
        }
        return response()->json($response);
    }
}
