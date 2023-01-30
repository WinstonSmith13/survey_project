<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SurveyController;

use App\Http\Controllers\SurveyQuestionAnswerController;
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

/* Avoid errors related to browser security related to APIs */
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    // All the CRUD are specified using the route::resource
    Route::resource('/survey', SurveyController::class);

    // Add a protected route. With the index method
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Add a protected route. With the index method 
    Route::get('/answer/{id}', [SurveyQuestionAnswerController::class, 'index']);
});

// Specify the action to have the data for the guests
// I pass the slug as a parameter - I specify that I want survey by slug.
Route::get('/survey-by-slug/{survey:slug}', [SurveyController::class, 'showForGuest']);


Route::post('/survey/{survey}/answer', [SurveyController::class, 'storeAnswer']);

// Authentification routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

