<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JawabanSoalController;
use App\Http\Controllers\KontribusiExploreController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\SurveyDesainController;
use App\Http\Controllers\SoalTypeController;
use App\Http\Controllers\SurveyKriteriaController;
use App\Http\Controllers\SurveySoalController;
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

// Route::controller(UserController::class)->group(function(){
//     Route::post('register', 'register');
//     Route::post('login', 'login');
// });

// // Route::post('/login', [AuthController::class, 'login']);
// Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Route::middleware('auth:sanctum')->get('/login', function (Request $request) {
//     return $request->user();
// });

// Route::post('/login', [AuthController::class, 'login'])->middleware('web');
// Route::post('/logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum']);
Route::middleware('web')->post('/login', [AuthController::class, 'login']);
Route::middleware('web')->post('/logout', [AuthController::class, 'logout']);

Route::get('users', [UserController::class, 'index']);
Route::post('users', [UserController::class, 'store']);
Route::get('users/{id}', [UserController::class, 'show']);
Route::post('users/edit/{id}', [UserController::class, 'update']);
Route::delete('users/delete/{id}', [UserController::class, 'destroy']);
Route::middleware('auth:sanctum')->get('/user', [UserController::class, 'showCurrentUser']);

Route::get('jawaban-soal', [JawabanSoalController::class, 'index']);
Route::post('jawaban-soal', [JawabanSoalController::class, 'store']);
Route::get('jawaban-soal/{id}', [JawabanSoalController::class, 'show']);
Route::post('jawaban-soal/edit/{id}', [JawabanSoalController::class, 'update']); 
Route::delete('jawaban-soal/delete/{id}', [JawabanSoalController::class, 'destroy']);  

Route::get('kontribusi-explore', [KontribusiExploreController::class, 'index']);
Route::post('kontribusi-explore', [KontribusiExploreController::class, 'store']);
Route::get('kontribusi-explore/{id}', [KontribusiExploreController::class, 'show']);
Route::post('kontribusi-explore/edit/{id}', [KontribusiExploreController::class, 'update']); 
Route::delete('kontribusi-explore/delete/{id}', [KontribusiExploreController::class, 'destroy']);  

Route::get('soal-type', [SoalTypeController::class, 'index']);
Route::post('soal-type', [SoalTypeController::class, 'store']);
Route::get('soal-type/{id}', [SoalTypeController::class, 'show']);
Route::post('soal-type/edit/{id}', [SoalTypeController::class, 'update']); 
Route::delete('soal-type/delete/{id}', [SoalTypeController::class, 'destroy']);  

Route::get('survey', [SurveyController::class, 'index']);
Route::post('survey', [SurveyController::class, 'store']);
Route::get('survey/{id}', [SurveyController::class, 'show']);
Route::post('survey/edit/{id}', [SurveyController::class, 'update']); 
Route::delete('survey/delete/{id}', [SurveyController::class, 'destroy']);  

Route::get('survey-desain', [SurveyDesainController::class, 'index']);
Route::post('survey-desain', [SurveyDesainController::class, 'store']);
Route::get('survey-desain/{id}', [SurveyDesainController::class, 'show']);
Route::post('survey-desain/edit/{id}', [SurveyDesainController::class, 'update']); 
Route::delete('survey-desain/delete/{id}', [SurveyDesainController::class, 'destroy']);  

Route::get('survey-kriteria', [SurveyKriteriaController::class, 'index']);
Route::post('survey-kriteria', [SurveyKriteriaController::class, 'store']);
Route::get('survey-kriteria/{id}', [SurveyKriteriaController::class, 'show']);
Route::post('survey-kriteria/edit/{id}', [SurveyKriteriaController::class, 'update']); 
Route::delete('survey-kriteria/delete/{id}', [SurveyKriteriaController::class, 'destroy']);  

Route::get('survey-soal', [SurveySoalController::class, 'index']);
Route::post('survey-soal', [SurveySoalController::class, 'store']);
Route::get('survey-soal/{id}', [SurveySoalController::class, 'show']);
Route::post('survey-soal/edit/{id}', [SurveySoalController::class, 'update']); 
Route::delete('survey-soal/delete/{id}', [SurveySoalController::class, 'destroy']);  