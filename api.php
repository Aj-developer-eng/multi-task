<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\apicontroller;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/lp_login',[App\Http\Controllers\apicontroller::class,'lead_login']);
Route::post('lp_login_submit',[App\Http\Controllers\apicontroller::class,'lead_login_submit']);

//for register

//to update ip
Route::post('/updateip/{id}',[apicontroller::class,'update_ip1']);
//to get travel record
Route::post('/travel',[apicontroller::class,'update_travel']);
Route::get('/lead_passenger_location',[App\Http\Controllers\apicontroller::class,'lead_passenger_location1']);
Route::post('/ip_save',[App\Http\Controllers\apicontroller::class,'ip_location']);


Route::middleware('auth:sanctum')->group( function () {
Route::post('register_method',[apicontroller::class,'register_process']);
Route::get('/lead_logout',[App\Http\Controllers\apicontroller::class,'lead_logout']);
Route::get('/lead_passenger_location',[App\Http\Controllers\apicontroller::class,'lead_passenger_location']);
});

Route::get('/dashboard',[App\Http\Controllers\apicontroller::class,'indexapi']);
   
