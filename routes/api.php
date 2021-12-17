<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
 //   return $request->user();
//});

Route::middleware(['apitoken','permissions'])->prefix('users')->group(function(){
    Route::put('/registerUser',[UsersController::class,'registerUser']);
    Route::get('/listEmployee',[UsersController::class, 'listEmployee']);
});

Route::post('login',[UsersController::class,'login']);

Route::prefix('users')->group(function(){
    //Route::put('/registerUser',[UsersController::class,'registerUser']); //Utilizada únicamente al principio para tener perfiles predefinidos
    Route::post('/recoverPassword',[UsersController::class,'recoverPassword']);
});

Route::middleware('apitoken')->prefix('users')->group(function(){
    Route::get('/seeProfile',[UsersController::class, 'seeProfile']);
});

//listado empleados -> apitoken y permisos
//ver datos personales -> apitoken
//crear nuevo empleado -> apitoken y permisos