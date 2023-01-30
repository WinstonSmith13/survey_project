<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\SurveyQuestionAnswerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// The following comment block explains the purpose of the API routes.

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| This is where the API routes for the application are registered. These
| routes are loaded by the RouteServiceProvider and assigned to the "api" 
| middleware group. The goal is to make building your API easy!
|
*/

// The following headers avoid browser security related errors for APIs.
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');

// The following group of routes are protected by the "auth:sanctum" middleware.
Route::middleware('auth:sanctum')->group(function(){
    // This route returns the authenticated user.
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // This route logs out the authenticated user.
    Route::post('/logout', [AuthController::class, 'logout']);

    // The CRUD operations are specified using Route::resource
    Route::resource('/survey', SurveyController::class);

    // This is a protected route for viewing the dashboard.
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // This is a protected route for viewing the answers of a specific survey question.
    Route::get('/answer/{id}', [SurveyQuestionAnswerController::class, 'index']);
});

// The following route allows guests to view survey data by its slug.
Route::get('/survey-by-slug/{survey:slug}', [SurveyController::class, 'showForGuest']);

// This route allows for storing answers for a specific survey.
Route::post('/survey/{survey}/answer', [SurveyController::class, 'storeAnswer']);

// Authentication routes
// This route registers a new user.
Route::post('/register', [AuthController::class, 'register']);
// This route logs in an existing user.
Route::post('/login', [AuthController::class, 'login']);

?>