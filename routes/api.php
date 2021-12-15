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

Route::middleware(['apitoken'])->prefix('users')->group(function(){
    Route::put('/registerUser',[UsersController::class,'registerUser'])->withoutMiddleware('apitoken');
    //Route::post('recoverPassword',[UsersController::class,'recoverPassword'])->withoutMiddleware('apitoken');
});

Route::post('/login',[UsersController::class,'login']);

Route::middleware('apitoken',)->get('/protected',[UsersController::class, 'protected']); //solo protegida para usuario normal autenticado
Route::middleware('apitoken', 'permissions')->get('/registerUser',[UsersController::class, 'registerUser']);   


//listado empleados -> apitoken y permisos
//ver datos personales -> apitoken
//crear nuevo empleado -> apitoken y permisos