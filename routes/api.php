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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/route',function(){})->middleware('permissions');

/*Route::middleware(['jwt','apitoken'])->prefix('users')->
    group(function (){
        Route::post('/register',[UsersController::class, 'register'])->withoutMiddleware('apitoken');
        Route::post('/login',[UsersController::class, 'login'])->withoutMiddleware('apitoken');
        Route::post('/key',[UsersController::class, 'recoverKey'])->withoutMiddleware('apitoken');
        Route::post('/logout',[UsersController::class, 'logout']);
        Route::post('/update',[UsersController::class, 'update']);
    });
*/
Route::prefix('users')->group(function(){
    Route::put('/registerUser',[UsersController::class,'registerUser']);
    Route::post('/desactivar_usuario/{id}',[UsuariosController::class,'desactivar_usuario']);
    Route::post('/editar/{id}',[UsuariosController::class,'editar']);
    Route::get('/ver/{id}',[UsuariosController::class,'ver']);
    Route::put('/adquirirCursos/{id}/{id_curso}',[UsuariosController::class,'adquirirCursos']);
    Route::get('/verCursosAdquiridos/{id}',[UsuariosController::class,'verCursosAdquiridos']);
});
    