<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SurveyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    //All the CRUD are specified using the route::resource
    Route::resource('/survey', SurveyController::class);

    //as a protected route. With the index method.
    Route::get('/dashboard', [DashboardController::class, 'index']);
});

//on specifie bien l'action pour avoir les données pour les guests
//on passe le slug - on specifie que l'on veut survey by slug.
Route::get('/survey-by-slug/{survey:slug}', [SurveyController::class, 'showForGuest']);

Route::post('/survey/{survey}/answer', [SurveyController::class, 'storeAnswer']);

//pour des raisons de sécurités toujours utiliser la méthode post pour l'inscription.
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

